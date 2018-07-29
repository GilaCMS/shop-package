<?php

use core\models\page as page;
use shop\models\shop as shop;

class shopController extends controller
{
    private $cart;
    private $addlist;

    function __construct()
    {
        router::cache(600,[shop::cartTotal()]);
        $this->addlist = ['receiver','address','reference','shipping_method','pc','city','phone','email'];

        if($_SERVER['REQUEST_METHOD'] === 'POST') if(isset($_POST['submit_address'])) {
            foreach($this->addlist as $d) session::key('delivery_'.$d, $_POST['add_'.$d] );
        }

        gila::addLang('shop/lang/');

        session::define(['cart'=>[]]);
        $this->cart = session::key('cart');
        view::set('cart_items',shop::cartTotal());
    }

    function indexAction ()
    {
        $this->listAction();
    }

    function listAction ()
    {
        global $db;
        $c = router::get('category',1);
        $search = router::get('search');
        $page = router::get('page');
        if($page==null) $page=1;

        view::set('category_name', gila::config('title').' - '.gila::config('slogan'));

        $c = explode('-',$c)[0];
        view::set('category',$c);
        if($c != null) {
            $category_name = $db->value("SELECT title FROM shop_category WHERE id=?;",$c);
            view::set('category_name', $category_name);
            view::set('page_title', $category_name);
        }

        $products = shop::getProducts(['c'=>$c,'page'=>$page,'search'=>$search]);

        view::set('search',$search);
        view::set('page',$page);
        view::set('products',$products[0]);
        view::set('totalpages',$products[1]);
        view::render('shop-list.php','shop');
    }


    function productAction ()
    {
        global $db;
        $id = router::get('product_id',1);
        $id = explode('-',$id)[0];
        $p = $db->query("SELECT * FROM shop_product WHERE id=?;",$id);
        $p = mysqli_fetch_array($p);
        view::set('p',$p);
        view::set('page_title',$p['title']);
        view::meta('og:title',$p['title']);
        view::meta('og:type','product.item');
        view::meta('product:retailer_item_id',$id);
        view::meta('product:price:amount',$p['price']);
        view::meta('product:price:currency',gila::option('shop.currency','EUR'));
        view::meta('fb:app_id',gila::config('base'));
        view::meta('og:url',gila::config('base').gila::url('shop/product/'.$id));
        view::meta('og:description',$p['description']);
        if($p['image']!=null) {
            view::meta('og:image',gila::config('base').$p['image']);
            view::meta('twiiter:image',gila::config('base').$p['image']);
            view::meta('twitter:card','summary');
        }
        view::render('shop-product.php','shop');
    }

    function edit_productsAction ()
    {
        if(gila::hasPrivilege('admin')==false) return;
        view::renderAdmin('admin/products.phtml','shop');
    }

    function edit_categoriesAction ()
    {
        if(gila::hasPrivilege('admin')==false) return;
        view::renderAdmin('admin/categories.phtml','shop');
    }

    function edit_ordersAction ()
    {
        if(gila::hasPrivilege('admin')==false) return;
        view::renderAdmin('admin/shop_orders.phtml','shop');
    }

    function edit_orderitemsAction ()
    {
        if(gila::hasPrivilege('admin')==false) return;
        view::renderAdmin('admin/shop_orderitems.phtml','shop');
    }

    function cartAction ()
    {
        global $db;
        shop::cartUpdate();
        view::set('cart_items',shop::cartTotal());
        view::set('product',shop::cartItems());
        view::render('shop-cart.php','shop');
    }

    function addressAction ()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<meta http-equiv=\"refresh\" content=\"0,url=".gila::url("/shop/checkout")."\" />";
            return;
        }

        foreach($this->addlist as $d) view::set('add_'.$d, session::key('delivery_'.$d) );
        view::set('shipping_methods', shop::shipping_methods());
        view::render('shop-address.php','shop');
    }

    function checkoutAction ()
    {
        global $db,$g;
        if($this->cart==null) if(session::key('cart_pay_url') != null) {
            $g->cart_pay_url = session::key('cart_pay_url');
            view::render('shop-checkout-pay-url.php','shop');
            return;
        }else {
            echo "<meta http-equiv=\"refresh\" content=\"0,url=".gila::config('base')."\" />";
            return;
        }

        $product=[];
        if(is_array($this->cart)) foreach ($this->cart as $k=>$pid) {
            $res = $db->query("SELECT * FROM shop_product WHERE id=?",$k);
            $product[$k] = mysqli_fetch_array($res);
            $product[$k]['qty'] = $pid;
        }
        view::set('product',$product);

        foreach($this->addlist as $d) view::set('add_'.$d, session::key('delivery_'.$d) );

        view::set('shipping_methods', shop::shipping_methods());
        view::render('shop-checkout.php','shop');
    }

    function placedorderAction ()
    {
        global $db,$g;

        if($this->cart==null) {
            echo "<meta http-equiv=\"refresh\" content=\"0,url=".gila::config('base')."\" />";
            return;
        }

        $data=[];

        foreach($this->addlist as $d) {
            $data[$d] = session::key('delivery_'.$d);
        }

        $order_id = shop::placeOrder($data);

        if(event::fire('shop::pay',$order_id)) exit;

        view::set('order_id',$order_id);
        view::render('shop-placedorder.php','shop');
    }

    function payAction_ ()
    {
        global $db,$g;

        if($this->cart==null) {
            echo "<meta http-equiv=\"refresh\" content=\"0,url=".gila::config('base')."\" />";
            return;
        }

        $data=[];

        foreach($this->addlist as $d) {
            $data[] = session::key('delivery_'.$d);
        }

        $db->query("INSERT INTO `shop_order`(`user_id`,`add_receiver`,`add_address`,`add_reference`,`add_shipping_method`,`add_pc`,`add_city`,`add_phone`,`add_email`)
        VALUES(0,?,?,?,?,?,?,?,?);",$data);

        $this->cart_id = $db->insert_id;
        $total_price = 0;

        $mp_data = [];
        $mp_data['items'] = [];
        foreach ($this->cart as $k=>$pid) {
            $res = $db->query("SELECT id,title,price FROM shop_product WHERE id=?",[$k]);

            if($product = mysqli_fetch_array($res)) {
                $db->query("INSERT INTO shop_orderitem(`product_id`,`order_id`,`qty`,`cost`)
                VALUES(?,?,?,?);",[$k, $this->cart_id, $pid, $product[2]]);
                /*$mp_data['items'][] = [
                    'title'=>$product[1],
                    'quantity'=>(int)$pid,
                    'currency_id' => "MXN",
                    'unit_price'=>$product[2]
                ];*/
                $total_price += (int)$pid * $product[2];
            } else {
                //error
            }
        }

        $shipping_method = session::key('delivery_shipping_method');
        $delivery_cost = shop::shipping_methods()[$shipping_method]['cost'];
        $total_price += $delivery_cost;

        $mp_data['items'][] = [
            'title'=> "Order #".$this->cart_id,
            'quantity'=> 1,
            'currency_id' => "MXN",
            'unit_price'=> $total_price
        ];

        session::unsetKey('cart');

        require_once __DIR__.'/../meli/mercadopago.php';
        $mp = new MP ('5093451076745206', 'Hl8vRgOYcPcH7f4xYjzw398llBNTk0vw');

        $response = $mp->create_preference($mp_data);
        //file_put_contents(__DIR__."/response.txt",json_encode($response));
        $this->cart_pay_url = $response['response']['init_point'];
        session::define(['cart_pay_url'=>$this->cart_pay_url]);
        echo "<meta http-equiv=\"refresh\" content=\"0,url=".$this->cart_pay_url."\">";
    }

    function pageAction ()
    {
        view::renderFile('shop-header.php');
        $id = router::get('id',1) ;
        if ($r = page::getByIdSlug($id)) {
            view::set('title',$r['title']);
            view::set('text',$r['page']);
            view::renderFile('page.php');
        } else view::renderFile('404.phtml');
        view::renderFile('shop-footer.php');

    }

    function proImagesAction(){
        global $db;
        $slugify = new Cocur\Slugify\Slugify();
        $res = $db->get("SELECT id,title FROM shop_product WHERE stock>0");
        foreach($res as $p){
            $slug = 'assets/products/'.$slugify->slugify($p[1]).'0.jpg';
            $db->query("UPDATE shop_product SET image=? WHERE id=?",[$slug,$p[0]]);
        }
        echo "ok";
    }



}
