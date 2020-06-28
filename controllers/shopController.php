<?php

use core\models\Page;
use shop\models\shop as shop;

class shopController extends Controller
{
  private $cartId;
  private $addlist;

  function __construct()
  {
    $this->addlist = ['receiver','address','reference','shipping_method','pc','city','phone','email'];

    if($_SERVER['REQUEST_METHOD'] === 'POST') if(isset($_POST['submit_address'])) {
      foreach($this->addlist as $d) Session::key('delivery_'.$d, $_POST['add_'.$d] );
    }

    Gila::addLang('shop/lang/');
    Session::define(['cartId'=>null]);
    $this->cartId = Session::key('cartId');
    View::set('cart_items',shop::cartTotal());
  }

  function indexAction ()
  {
    if ($r = Page::getBySlug(Router::get('page',1))) {
      View::set('title',$r['title']);
      View::set('text',$r['page']);
      View::render('page.php');
    } else $this->listAction();
  }

  function admin_payment_methodAction ()
  {
    View::renderAdmin('page.php');
  }

  function productsAction () {
    $this->listAction();
  }

  function listAction ()
  {
    global $db;
    $c = Router::get('category',1);
    if($c && (int)$c==0) {
      http_response_code(404);
      View::render('404.php');
      return;
    }
    if(Session::userId()===0) {
      //Router::cache(72000,[shop::cartTotal()],[Gila::mt('shop_product')]);
    }

    $search = Router::get('search');
    $page = Router::get('page');
    $offers = Router::get('offers');
    if($page==null) $page=1;

    View::set('category_name', Gila::config('title').' - '.Gila::config('slogan'));

    $c = explode('-',$c)[0];
    View::set('category',$c);
    if($c != null) {
      $category_name = $db->value("SELECT title FROM shop_category WHERE id=?;",$c);
      View::set('category_name', $category_name);
      View::set('page_title', $category_name);
      Session::key('category',$c);
      Session::key('category_name',$category_name);
    }

    $products = shop::getProducts(['c'=>$c,'page'=>$page,'search'=>$search,'offers'=>$offers]);

    View::set('search',$search);
    View::set('page',$page);
    View::set('products',$products[0]);
    View::set('totalpages',$products[1]);
    View::render('shop-list.php','shop');
  }


  function productAction ()
  {
    $product_id = Router::get('product_id',1);
    $product_id = explode('-',$product_id);
    $id = $product_id[0];
    Gila::canonical('shop/product/'.$id);
    
    $p = shop::getProductById($id);
    $categories = shop::getProductMeta($id,'category');
    $slug = Slugify::text($p['title']);
    View::set('p',$p);
    View::set('sku_id',@$product_id[1]?:'');
    View::set('categories',$categories);
    View::set('page_title',$p['title']);
    View::meta('og:title',$p['title']);
    View::meta('og:type','product.item');
    View::meta('product:retailer_item_id',$id);
    View::meta('product:price:amount',$p['price']);
    View::meta('product:price:currency',Gila::option('shop.currency','EUR'));
    View::meta('fb:app_id',Gila::config('base'));
    View::meta('og:url',Gila::config('base').Gila::url('shop/product/'.$id));
    View::meta('og:description',strip_tags($p['description']));
    if($p['image']!=null) {
      View::meta('og:image',Gila::config('base').View::thumb($p['image'],'',600));
      View::meta('twiiter:image',Gila::config('base').$p['image']);
      View::meta('twitter:card','summary');
    }
    View::render('shop-product.php','shop');
  }

  function view_orderAction ($id)
  {
    if(Gila::hasPrivilege('admin')==false) return;
    View::set('o', shop::getOrderById($id));
    View::renderAdmin('admin/view_shop_order.php','shop');
  }

  function cartAction ()
  {
    global $db;
    shop::cartUpdate();
    View::set('cart_items', shop::cartTotal());
    View::set('product', shop::cartItems());
    View::render('shop-cart.php','shop');
  }

  function addressAction ()
  {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      echo "<meta http-equiv=\"refresh\" content=\"0,url=".Gila::url("/shop/checkout")."\" />";
      return;
    }

    foreach($this->addlist as $d) View::set('add_'.$d, Session::key('delivery_'.$d) );
    View::set('shipping_methods', shop::shipping_methods());
    View::set('product', shop::cartItems());
    View::render('shop-address.php','shop');
  }

  function checkoutAction ()
  {
    global $db,$g;
    if($this->cartId==null) if(Session::key('cart_pay_url') != null) {
      $g->cart_pay_url = Session::key('cart_pay_url');
      View::render('shop-checkout-pay-url.php','shop');
      return;
    }else {
      echo "<meta http-equiv=\"refresh\" content=\"0,url=".Gila::config('base')."\" />";
      return;
    }

    View::set('product', shop::cartItems());

    foreach($this->addlist as $d) View::set('add_'.$d, Session::key('delivery_'.$d) );

    View::set('shipping_methods', shop::shipping_methods());
    View::set('payment_methods', shop::payment_methods());
    View::render('shop-checkout.php','shop');
  }

  function placedorderAction ()
  {
    global $db,$g;

    if($this->cartId==null) {
      echo "<meta http-equiv=\"refresh\" content=\"0,url=".Gila::config('base')."\" />";
      return;
    }

    $data=[];

    foreach($this->addlist as $d) {
      $data[$d] = Session::key('delivery_'.$d);
    }
    $data['payment_method'] = @$_POST['payment_method']?:0;
 
    $order_id = Event::get('shop::placeOrder', null, ['data'=>$data, 'cartId'=>$order_id]);
    if($order_id == null) {
      $order_id = shop::placeOrder($data, $this->cartId);
    }

    if(Event::fire('shop::pay',$order_id)) return;

    $gateway = shop::payment_methods()[$data['payment_method']]['gateway'] ?? null;
    if($gateway) {
      Gila::getList('shop_pay_gateway')[$gateway]($order_id);
      return;
    }

    View::set('order_id',$order_id);
    View::render('shop-placedorder.php', 'shop');
  }

}
