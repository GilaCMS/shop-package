<?php

Gila::controller('shop','shop/controllers/shopController');
Gila::addLang('shop/lang/');
Gila::amenu(['shop'=>[__('Shop'),"#",'icon'=>'shopping-cart']]);
Gila::amenu_child('shop',[__('Products'),"admin/content/shop_product",'icon'=>'codepen']);
Gila::amenu_child('shop',[__('Orders'),"admin/content/shop_order",'icon'=>'shopping-cart']);
Gila::amenu_child('shop',[__('Categories'),"admin/content/shop_category",'icon'=>'bars']);
Gila::amenu_child('shop',[__('Attributes'),"admin/content/shop_attribute",'icon'=>'cogs']);
Gila::amenu_child('shop',[__('Shipping Methods'),"admin/content/shipping_method",'icon'=>'truck']);
Gila::amenu_child('shop',[__('Payment Methods'),"admin/content/payment_method",'icon'=>'dollar']);
Gila::amenu_child('shop',[__('Online Carts'),"admin/content/shop_cart",'icon'=>'shopping-cart']);

Gila::onController('shop',function(){
    View::stylesheet('lib/bootstrap/bootstrap.min.css');
    View::stylesheet('lib/font-awesome/css/font-awesome.min.css');
});

Gila::widgets(['shop-category-list'=>'shop/widgets/shop-category-list']);
Gila::widgets(['category-list'=>'shop/widgets/category-list']);

Gila::$privilege['shop_op']="Operator of products and shop orders.";

Gila::content('shop_product','shop/tables/shop_product.php');
Gila::content('shop_sku','shop/tables/shop_sku.php');
Gila::content('shop_attribute','shop/tables/shop_attribute.php');
Gila::content('shop_attr_option','shop/tables/shop_attr_option.php');
Gila::content('shop_category','shop/tables/shop_category.php');
Gila::content('shop_order','shop/tables/shop_order.php');
Gila::content('shop_orderitem','shop/tables/shop_orderitem.php');
Gila::content('shop_cart','shop/tables/shop_cart.php');
Gila::content('shop_cartitem','shop/tables/shop_cartitem.php');
Gila::content('shipping_method','shop/tables/shipping_method.php');
Gila::content('payment_method','shop/tables/payment_method.php');


Gila::addList ('menuItemType', ['productcategory', [
  "data"=>[
    "type"=>"productcategory",
    "id"=>1
  ],
  "template"=>function(){
    $pages = shop\models\shop::getAllCategories();
    $productcategoryOptions = "";
    foreach ($pages as $p) {
      $productcategoryOptions .= "<option value=\"{$p['id']}\">{$p['title']}</option>";
    }
    return "<select class=\"g-input\" v-model=\"model.id\">$productcategoryOptions</select>";
  },
  "response"=>function($mi){
    global $db;
    $ql="SELECT id,title FROM shop_category WHERE id=?;";
    $res = $db->query($ql,@$mi['id']);
    while($r=mysqli_fetch_array($res)){
      $url = Gila::url("shop/").$r[0].'/'.$r[1];
      $name = $r[1];
      return[$url,$name];
    }
    return false;
  }
]]);

gForm::addInputType("productcategory",function($name,$field,$ov) {
  global $db;
  $html = '<select class="g-input" name="'.$name.'">';
  $res=$db->get('SELECT id,title FROM shop_category;');
  $html .= '<option value=""'.(''==$ov?' selected':'').'>'.'[All]'.'</option>';
  foreach($res as $r) {
    $html .= '<option value="'.$r[0].'"'.($r[0]==$ov?' selected':'').'>'.$r[1].'</option>';
  }
  return $html . '</select>';
});

Gila::contentInit('shop_sku', function(&$table) {
  global $db;
  foreach(shop\models\shop::attributes() as $attr) {
    $options = [];
    $opres = $db->gen("SELECT optionkey,optionvalue FROM shop_attr_option WHERE attribute_id={$attr[0]};");
    foreach($opres as $op) {
      $options[$op[0]] = $op[1];
    }
    $table['fields']['attr'.$attr[0]] = [
      'title'=>$attr[1],
      'type'=>'meta',
      "mt"=>['shop_skumeta', 'sku_id', 'metavalue'],
      'metatype'=>['metakey', 'attr'.$attr[0]],
      'input-type'=>'select',
      'options'=>$options
    ];
  }
});

Gila::contentInit('shop_product', function(&$table) {
  foreach(shop\models\shop::attributes() as $attr) {
    $table['children']['shop_sku']['list'][] = 'attr'.$attr[0];
  }
});

if(isset($_GET['ref'])) session::define(['shop_ref'=>$_GET['ref']]);

Router::action('cm', 'setDiscount', function($table) {
  global $db;
  $ids = explode(',', $_GET['id']);
  $discount = (int)$_POST['discount'];
  if($table != 'shop_product') return;
  if(is_nan($discount)) return;
  if($discount>1) $discount = $discount/100;
  $product = new Table($table);
  if(!$product->can('update')) {
    @http_response_code(403);
    return;
  }

  foreach($ids as $id) {
    $q = "UPDATE $table SET new_price=price-price*? WHERE {$product->id()}=?;";
    $res = $db->query($q, [$discount, $id]);
  }
  echo '{"success":true,"message":"Discount set"}';
});
