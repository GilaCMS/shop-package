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
      self::$payment_method = $db->get("SELECT id,img,description,cost,cost_f,gateway FROM payment_method ORDER BY pos;");
    }
    return self::$payment_method;
  }

  static function cartItems() {
    global $db;
    $cart = self::getCart();
    $items=[];
    if(is_array($cart)) foreach ($cart as $k=>$cqty) if(is_numeric($cqty)) {
      $ql = "SELECT a.id as id,a.image as image,title,
        (CASE WHEN new_price=\"\" THEN a.price ELSE new_price END) as price
        FROM shop_product a,shop_sku b WHERE a.id=b.product_id AND b.id=?";
      $res = $db->query($ql, $k);
      $items[$k] = mysqli_fetch_array($res);

      if(!isset(self::$meta_attr)) {
        $attributes=self::attributes();
      }
      $q = "SELECT metavalue FROM shop_skumeta WHERE sku_id=? AND metakey IN(".self::$meta_attr.");";
      $attr = $db->getList($q,[$k]);
      if(count($attr)>0) {
        $cattr = " ".implode("-",$attr);
        $items[$k]['title'] .= $cattr;
      }
      $items[$k]['qty'] = $cqty;
    }
    return $items;
  }

  static function cartTotal() {
    if(isset(self::$cart_total)) return self::$cart_total;
    $cart = self::getCart();
    self::$cart_total = 0;
    if($cart==null) return 0;
    if(is_array($cart)) foreach ($cart as $key => $value) {
      self::$cart_total += $value;
    }
    return self::$cart_total;
  }

  static function cartUpdate() {
    global $db;
    $cartId = \Session::key('cartId');
    $_product = \Router::post('_product');

    if($cartId==null) {
        $db->query("INSERT INTO shop_cart(`user_id`) VALUES(?);",[\Session::user_id()]);
        $cartId = $db->insert_id;
        \Session::key('cartId',$cartId);
    }

    $added = \Router::get('add');
    $qty = \Router::get('qty')?:1;
    if ($added!=null) {
      if(!is_null($cartId)) {
        if($res = $db->get("SELECT * FROM shop_cartitem WHERE sku_id=? AND cart_id=?",[$added, $cartId])) {
            $db->query("UPDATE shop_cartitem SET qty=? WHERE sku_id=? AND cart_id=?",[$qty,$added, $cartId]);
        } else {
            $db->query("INSERT INTO shop_cartitem(qty,sku_id,cart_id) VALUES(?,?,?);",[$qty,$added, $cartId]);
        }
      }
    }

    $remove = \Router::get('remove');
    if ($remove!=null) {
      if(!is_null($cartId)) {
        $db->query("DELETE FROM shop_cartitem WHERE sku_id=? AND cart_id=?",[$remove, $cartId]);
      }
    }

    if ($_product != null) {
      foreach($_product as $id=>$qty) {
        $id=(int)$id;
        $qty=(int)$qty;
        if ($qty < 1) {
            $db->query("DELETE FROM shop_cartitem WHERE sku_id=? AND cart_id=?",[$id, $cartId]);
        } else {
            if($res = $db->get("SELECT * FROM shop_cartitem WHERE sku_id=? AND cart_id=?",[$id, $cartId])) {
                $db->query("UPDATE shop_cartitem SET qty=? WHERE sku_id=? AND cart_id=?",[$qty,$id, $cartId]);
            } else {
                $db->query("INSERT INTO shop_cartitem(qty,sku_id,cart_id) VALUES(?,?,?);",[$qty,$id, $cartId]);
            }
        }
      }
    }

    self::$cart_total = 0;
    $cart = self::getCart();
    foreach($cart as $key=>$qty) self::$cart_total += $qty;
  }

  static function updateCartItem($cartId, $productId, $qty)
  {
    global $db;
    $cartId = \Session::key('cartId');
    if ($qty < 1) {
        $db->query("DELETE FROM shop_cartitem WHERE sku_id=? AND cart_id=?", [$productId, $cartId]);
    } else {
        if($res = $db->get("SELECT * FROM shop_cartitem WHERE sku_id=? AND cart_id=?", [$productId, $cartId])) {
            $db->query("UPDATE shop_cartitem SET qty=? WHERE sku_id=? AND cart_id=?", [$qty, $productId, $cartId]);
        } else {
            $db->query("INSERT INTO shop_cartitem(qty,sku_id,cart_id) VALUES(?,?,?);", [$qty, $productId, $cartId]);
        }
    }
  }

  static function getCart() {
      global $db;
      $cartId = \Session::key('cartId');
      $list = $db->get("SELECT sku_id,qty FROM shop_cartitem WHERE cart_id=?;", $cartId);
      $cart = [];
      foreach($list as $r) $cart[$r[0]] = $r[1];
      if ($cart==[]) return null;
      return $cart;
  }

  static function getOrderById($id)
  {
    global $db;
    $p = $db->query("SELECT id,`user_id`,`add_receiver`,`add_address`,`add_reference`,`add_shipping_method`,`add_pc`,`add_city`,`add_phone`,`add_email` FROM shop_order WHERE id=?;",[$id]);
    return mysqli_fetch_array($p);
  }

  static function placeOrder($args, $cartId)
  {
    global $db;
    $cart = self::getCart();
    $user_id = \Session::user_id();
      $data = [
      $user_id,$args['receiver'],$args['address'],$args['reference'],$args['shipping_method'],
      $args['pc'],$args['city'],$args['phone'],$args['email'], \Session::key('shop_ref')
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
          CONCAT(title,(CASE WHEN a.upc!=\"\" THEN CONCAT('-',a.upc) ELSE \"\" END)),
          '{$cart_id}',
          {$qty},
          (CASE WHEN new_price=\"\" THEN a.price ELSE new_price END)
          FROM shop_product a, shop_sku b WHERE b.id=? AND a.id=b.product_id;",[$k]);

        if($error = $db->error()) echo $error;
        $cart_item_id = $db->insert_id;

        if(!isset(self::$meta_attr)) {
          $attributes=self::attributes();
        }
        $q = "SELECT metavalue FROM shop_skumeta WHERE sku_id=? AND metakey IN(".self::$meta_attr.");";
        $attr = $db->getList($q, [$k]);
        if(count($attr)>0) {
          $cattr = implode('-',$attr);
          $db->query("UPDATE shop_orderitem SET `description`=CONCAT(`description`,' ','$cattr') WHERE id=$cart_item_id;");
        }

        $db->query("UPDATE shop_sku SET stock = stock - {$qty} WHERE id=?",[$k]);
      } else {
        echo "ERROR";
        //error
      }
    }

    \Session::unsetKey('cartId');
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
    $list = $db->get('SELECT * FROM shop_category WHERE parent_id=? AND active=1;',[$parent]);
    if($list != []) {
      $children = $db->get('SELECT * FROM shop_category WHERE parent_id=? AND active=1;',[$parent]);
      if($children != []) $list['children'] = $children;
    }
    return $list;
  }

  static function getAllCategories() {
    global $db;
    $list = $db->get('SELECT * FROM shop_category WHERE active=1;');
    return $list;
  }

  static function attributes() {
    global $db;
    if(isset(self::$attributes)) return self::$attributes;
    $attributes = $db->gen("SELECT id,label FROM shop_attribute;");
    $meta_attr = [];
    foreach($attributes as $attr) {
      $meta_attr[] = "'attr{$attr[0]}'";
      self::$attributes['attr'.$attr[0]] = $attr;
    }
    self::$meta_attr = implode(',',$meta_attr);
    return self::$attributes;
  }

}
