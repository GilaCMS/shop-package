<?php

gila::controller('shop','shop/controllers/shopController','shopController');
gila::addLang('shop/lang/');
gila::amenu(['shop'=>[__('Shop'),"#",'icon'=>'shopping-cart']]);
gila::amenu_child('shop',[__('Products'),"admin/content/shop_product",'icon'=>'codepen']);
gila::amenu_child('shop',[__('Orders'),"admin/content/shop_order",'icon'=>'shopping-cart']);
gila::amenu_child('shop',[__('Categories'),"admin/content/shop_category",'icon'=>'bars']);
gila::amenu_child('shop',[__('Shipping Methods'),"admin/content/shipping_method",'icon'=>'truck']);

gila::onController('shop',function(){
    view::stylesheet('lib/bootstrap/bootstrap.min.css');
    view::stylesheet('lib/font-awesome/css/font-awesome.min.css');
});

gila::widgets(['category-list'=>'shop/widgets/category-list']);

gila::$privilege['shop_op']="Operator of products and shop orders.";

gila::content('shop_product','shop/tables/shop_product.php');
gila::content('shop_sku','shop/tables/shop_sku.php');
gila::content('shop_category','shop/tables/shop_category.php');
gila::content('shop_order','shop/tables/shop_order.php');
gila::content('shop_orderitem','shop/tables/shop_orderitem.php');
gila::content('shipping_method','shop/tables/shipping_method.php');



//global $db;
$pages = shop\models\shop::getAllCategories();
$productcategoryOptions = "";
foreach ($pages as $p) {
    $productcategoryOptions .= "<option value=\"{$p['id']}\">{$p['title']}</option>";
}

menuItemTypes::addItemType ('productcategory', [
    "data"=>[
        "type"=>"productcategory",
        "id"=>1
    ],
    "template"=>"<select class=\"g-input\" v-model=\"model.id\">$productcategoryOptions</select>",
    "response"=>function($mi){
        global $db;
        $ql="SELECT id,title FROM shop_category WHERE id=?;";
        $res = $db->query($ql,@$mi['id']);
        while($r=mysqli_fetch_array($res)){
            $url = gila::url("shop").$r[0].'/'.$r[1];
            $name = $r[1];
            return[$url,$name];
        }
        return false;
    }
]);

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