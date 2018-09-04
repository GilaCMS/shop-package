<?php
namespace shop\models;

class shop
{
    static private $shipping_method;
    static private $cart_total;

    static function shipping_methods() {
        global $db;
        if(!isset(self::$shipping_method)) {
            self::$shipping_method = $db->get("SELECT img,description,cost,freeafter FROM shipping_method ORDER BY pos;");
        }
        return self::$shipping_method;
    }

    static function cartItems() {
        global $db;
        $cart = \session::key('cart');
        $items=[];
        if(is_array($cart)) foreach ($cart as $k=>$pid) if(is_numeric($pid)){
            $res = $db->query("SELECT * FROM shop_product WHERE id=?",$k);
            $items[$k] = mysqli_fetch_array($res);
            $items[$k]['qty'] = $pid;
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
            $args['pc'],$args['city'],$args['phone'],$args['email']
        ];
        $query = "INSERT INTO `shop_order`(`user_id`,`add_receiver`,`add_address`,`add_reference`,`add_shipping_method`,`add_pc`,`add_city`,`add_phone`,`add_email`)
        VALUES(?,?,?,?,?,?,?,?,?);";

        $db->query($query,$data);

        if($error = $db->error())  {
            echo $error;
            exit;
        }

        $cart_id = $db->insert_id;

        foreach ($cart as $k=>$qty) {
            $res = $db->query("SELECT id,title,price FROM shop_product WHERE id=?",[$k]);

            if($product = mysqli_fetch_array($res)) {
                $db->query("INSERT INTO shop_orderitem(`product_id`,`description`,`order_id`,`qty`,`cost`)
                SELECT id,title,'{$cart_id}',{$qty},price FROM shop_product WHERE id=?;",[$k]);
                $db->query("UPDATE shop_product SET stock = stock - {$qty} WHERE id=?",[$k]);
            } else {
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
        return product::getById($id,$meta);
    }

    static function getProducts($args) {
        return product::get($args);
    }

    static function getCategoryTree($parent = 0) {
        global $db;
        $list = $db->get('SELECT * FROM shop_category WHERE parent_id=? AND active=1',[$parent]);
        if($list != []) {
            $children = $db->get('SELECT * FROM shop_category WHERE parent_id=? AND active=1',[$parent]);
            if($children != []) $list['children'] = $children;
        }
        return $list;
    }

}
