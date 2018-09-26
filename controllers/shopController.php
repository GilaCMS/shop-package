<?php

use core\models\page as page;
use shop\models\shop as shop;

class shopController extends controller
{
    private $cart;
    private $addlist;

    function __construct()
    {
        if(session::user_id()==0) router::cache(300,[shop::cartTotal()]);
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
        if ($r = page::getBySlug(router::get('page',1))) {
            view::set('title',$r['title']);
            view::set('text',$r['page']);
            view::render('page.php');
        } else $this->listAction();
    }

    function admin_payment_methodAction ()
    {
        view::renderAdmin('page.php');
    }

    function listAction ()
    {
        global $db;
        $c = router::get('category',1);

        $search = router::get('search');
        $page = router::get('page');
        $offers = router::get('offers');
        if($page==null) $page=1;

        view::set('category_name', gila::config('title').' - '.gila::config('slogan'));

        $c = explode('-',$c)[0];
        view::set('category',$c);
        if($c != null) {
            $category_name = $db->value("SELECT title FROM shop_category WHERE id=?;",$c);
            view::set('category_name', $category_name);
            view::set('page_title', $category_name);
            session::key('category',$c);
            session::key('category_name',$category_name);
        }

        $products = shop::getProducts(['c'=>$c,'page'=>$page,'search'=>$search,'offers'=>$offers]);

        view::set('search',$search);
        view::set('page',$page);
        view::set('products',$products[0]);
        view::set('totalpages',$products[1]);
        view::render('shop-list.php','shop');
    }


    function productAction ()
    {
        $product_id = router::get('product_id',1);
        $product_id = explode('-',$product_id);
        $id = $product_id[0];
        
        $p = shop::getProductById($id);
        $categories = shop::getProductMeta($id,'category');
        view::set('p',$p);
        view::set('sku_id',@$product_id[1]?:'');
        view::set('categories',$categories);
        view::set('page_title',$p['title']);
        view::meta('og:title',$p['title']);
        view::meta('og:type','product.item');
        view::meta('product:retailer_item_id',$id);
        view::meta('product:price:amount',$p['price']);
        view::meta('product:price:currency',gila::option('shop.currency','EUR'));
        view::meta('fb:app_id',gila::config('base'));
        view::meta('og:url',gila::config('base').gila::url('shop/product/'.$id));
        view::meta('og:description',strip_tags($p['description']));
        if($p['image']!=null) {
            view::meta('og:image',gila::config('base').view::thumb($p['image'],'',600));
            view::meta('twiiter:image',gila::config('base').$p['image']);
            view::meta('twitter:card','summary');
        }
        view::render('shop-product.php','shop');
    }

    function view_orderAction ($id)
    {
        if(gila::hasPrivilege('admin')==false) return;
        view::set('o', shop::getOrderById($id));
        view::renderAdmin('admin/view_shop_order.php','shop');
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
        view::set('product',shop::cartItems());
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

        view::set('product',shop::cartItems());

        foreach($this->addlist as $d) view::set('add_'.$d, session::key('delivery_'.$d) );

        view::set('shipping_methods', shop::shipping_methods());
        view::set('payment_methods', shop::payment_methods());
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
        $data['payment_method'] = @$_POST['payment_method']?:0;

        $order_id = shop::placeOrder($data,$this->cart);

        if(event::fire('shop::pay',$order_id)) exit;

        view::set('order_id',$order_id);
        view::render('shop-placedorder.php','shop');
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
