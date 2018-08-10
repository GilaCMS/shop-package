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

        \session::key('cart',$cart);
        if($cart == []) \session::unsetKey('cart');
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
            } else {
                //error
            }
        }


        \session::unsetKey('cart');
        return $cart_id;
    }

    static function getProductMeta($id,$meta) {
        global $db;
        return $db->getList("SELECT metavalue FROM shop_productmeta WHERE product_id=? AND metakey=?;",[$id,$meta]);
    }

    static function getProductById($id) {
        global $db;
        $p = $db->query("SELECT id,image,image2,image3,image4,title,description,price,old_price FROM shop_product WHERE id=?;",[$id]);
        return mysqli_fetch_array($p);
    }

    static function getProducts($args) {
        global $db;
        $limit = "LIMIT ".(($args['page']-1)*16).",16";
        $where = "WHERE title !='' AND stock>0";

        if ($args['c']) {
            $clist = $db->getList("SELECT id FROM shop_category WHERE parent_id = ?;",[$args['c']]);
            $clist[] = $args['c'];
            $clist = implode(',',$clist);
            $where = "WHERE title !='' AND shop_productmeta.product_id=shop_product.id AND shop_productmeta.metavalue IN($clist) AND stock>0";
        }

        if($args['search']) {
            $search = strip_tags($args['search']);
            $where .= " AND title like '%$search%' AND stock>0";
        }

        $totalpages = (int)$db->value("SELECT COUNT(*)/16 FROM shop_product $where;")+1;
        $ql = "SELECT shop_product.id,image,title,price, old_price FROM shop_product,shop_productmeta $where GROUP BY id ORDER BY id DESC $limit;";
        return [$db->get($ql),$totalpages];
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
