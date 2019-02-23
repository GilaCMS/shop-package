<?php
namespace shop\models;

class shop
{
  static private $shipping_method;
  static private $payment_method;
  static private $cart_total;
  static private $attributes, $meta_attr;

  static function shipping_methods() {
    global $db;
    if(!isset(self::$shipping_method)) {
      self::$shipping_method = $db->get("SELECT id,img,description,cost,freeafter FROM shipping_method ORDER BY pos;");
    }
    return self::$shipping_method;
  }

  static function payment_methods() {
    global $db;
    if(!isset(self::$payment_method)) {
      self::$payment_method = $db->get("SELECT id,img,description,cost,cost_f FROM payment_method ORDER BY pos;");
    }
    return self::$payment_method;
  }

  static function cartItems() {
    global $db;
    $cart = \session::key('cart');
    $items=[];
    if(is_array($cart)) foreach ($cart as $k=>$cqty) if(is_numeric($cqty)) {
      $ql = "SELECT a.id as id,a.image as image,title,
        (CASE WHEN new_price=\"\" THEN a.price ELSE new_price END) as price
        FROM shop_product a,shop_sku b WHERE a.id=b.product_id AND b.id=?";
      $res = $db->query($ql, $k);
      $items[$k] = mysqli_fetch_array($res);

      if($v = $db->value("SELECT metavalue FROM shop_skumeta WHERE sku_id=? AND metakey IN({self::$meta_attr});",[$k])) {
        $items[$k]['title'] .= ' ('.$v.') ';   
      }
      $items[$k]['qty'] = $cqty;
    }
    return $items;
  }

  static function cartTotal() {
    if(isset(self::$cart_total)) return self::$cart_total;
    $cart = \session::key('cart');
    self::$cart_total = 0;
    if($cart==null) return 0;
    if(is_array($cart)) foreach ($cart as $key => $value) {
      self::$cart_total += $value;
    }
    return self::$cart_total;
  }

  static function cartUpdate() {
    $cart = \session::key('cart');
    $_product = \router::post('_product');

    $added = \router::get('add');
    $qty = \router::get('qty')?:1;
    if ($added!=null) {
      if(is_array($cart)) {
        $cart[$added] = $qty;
        if ($cart[$added] < 1) unset($cart[$added]);
      } else $cart = [];
    }

    $remove = \router::get('remove');
    if ($remove!=null) {
      if(is_array($cart)) {
        unset($cart[$remove]);
      } else $cart = [];
    }

    if ($_product != null) {
      foreach($_product as $id=>$qty) {
        $id=(int)$id;
        $qty=(int)$qty;
        $cart[$id] = $qty;
        if ($cart[$id] < 1) unset($cart[$id]);
      }
    }

    self::$cart_total = 0;
    foreach($cart as $key=>$qty) self::$cart_total += $qty;

    \session::key('cart',$cart);
    if($cart == []) \session::unsetKey('cart');
  }

  static function getOrderById($id)
  {
    global $db;
    $p = $db->query("SELECT id,`user_id`,`add_receiver`,`add_address`,`add_reference`,`add_shipping_method`,`add_pc`,`add_city`,`add_phone`,`add_email` FROM shop_order WHERE id=?;",[$id]);
    return mysqli_fetch_array($p);
  }

  static function placeOrder($args,$cart)
  {
    global $db;
    $user_id = \session::user_id();
      $data = [
      $user_id,$args['receiver'],$args['address'],$args['reference'],$args['shipping_method'],
      $args['pc'],$args['city'],$args['phone'],$args['email'], \session::key('shop_ref')
    ];
    $query = "INSERT INTO `shop_order`(`user_id`,`add_receiver`,`add_address`,`add_reference`,`add_shipping_method`,`add_pc`,`add_city`,`add_phone`,`add_email`,`ref`)
    VALUES(?,?,?,?,?,?,?,?,?,?);";

    $db->query($query,$data);

    if($error = $db->error())  {
      echo $error;
      exit;
    }

    $cart_id = $db->insert_id;

    foreach ($cart as $k=>$qty) {
      $res = $db->query("SELECT id FROM shop_sku WHERE id=?",[$k]);

      if($product = mysqli_fetch_array($res)) {
        $db->query("INSERT INTO shop_orderitem(`product_id`,`description`,`order_id`,`qty`,`cost`)
          SELECT a.id,
          CONCAT(title,'-',(CASE WHEN a.upc!=\"\" THEN a.upc ELSE \"\" END)),
          '{$cart_id}',
          {$qty},
          (CASE WHEN new_price=\"\" THEN a.price ELSE new_price END)
          FROM shop_product a, shop_sku b WHERE b.id=? AND a.id=b.product_id;",[$k]);
        // CONCAT(title,'-',(CASE WHEN b.upc!=\"\" THEN b.upc WHEN a.upc!=\"\" THEN a.upc ELSE \"\" END)),
        
        if($error = $db->error()) echo $error;
        
        $cart_item_id = $db->insert_id;

        $attr = $db->getList("SELECT metavalue FROM shop_skumeta WHERE sku_id=? AND metakey IN({self::$meta_attr});",[$k]);
        if(count($attr)>0) {
          $cattr = "'".implode("'-'",$attr)."'";
          echo $cattr;
          $db->query("UPDATE shop_orderitem SET `description`=CONCAT(`description`,'-',$cattr) WHERE id=$cart_item_id;");
        }

        $db->query("UPDATE shop_sku SET stock = stock - {$qty} WHERE id=?",[$k]);
      } else {
        echo "ERROR";
        //error
      }
    }


    \session::unsetKey('cart');
    return $cart_id;
  }

  static function getProductMeta($id,$meta) {
    return product::getMeta($id,$meta);
  }

  static function getProductById($id) {
    return product::getById($id);
  }

  static function getProducts($args) {
    return product::get($args);
  }

  static function getCategoryTree($parent = 0) {
    global $db;
    $list = $db->get('SELECT * FROM shop_category WHERE parent_id=?;',[$parent]);
    if($list != []) {
      $children = $db->get('SELECT * FROM shop_category WHERE parent_id=?;',[$parent]);
      if($children != []) $list['children'] = $children;
    }
    return $list;
  }

  static function getAllCategories() {
    global $db;
    $list = $db->get('SELECT * FROM shop_category;');
    return $list;
  }

  static function attributes() {
    global $db;
    if(isset(self::$attributes)) return self::$attributes;
    $attributes = $db->gen("SELECT id,label FROM shop_attribute;");
    $meta_attr = [];
    foreach($attributes as $attr) {
      $meta_attr = "'attr{$attr[0]}'";
      self::$attributes['attr'.$attr[0]] = $attr;
    }
    self::$meta_attr = implode(',',$meta_attr);
    return self::$attributes;
  }

}
