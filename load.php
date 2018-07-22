<?php

gila::controller('shop','shop/controllers/shopController','shopController');

gila::amenu(['shop'=>['Shop',"#",'icon'=>'shopping-cart']]);
gila::amenu_child('shop',['Products',"admin/content/shop_product",'icon'=>'codepen']);
gila::amenu_child('shop',['Orders',"admin/content/shop_order",'icon'=>'shopping-cart']);
gila::amenu_child('shop',['Categories',"admin/content/shop_category",'icon'=>'bars']);
gila::amenu_child('shop',['Shipping Methods',"admin/content/shipping_method",'icon'=>'truck']);
gila::amenu_child('shop',['Order Items',"admin/content/shop_orderitem",'icon'=>'codepen']);
//gila::amenu_child('shop',['Products',"shop/edit_products",'icon'=>'codepen']);
//gila::amenu_child('shop',['Categories',"shop/edit_categories",'icon'=>'bars']);
//gila::amenu_child('shop',['Orders',"shop/edit_orders",'icon'=>'shopping-cart']);
//gila::amenu_child('shop',['Order Items',"shop/edit_orderitems",'icon'=>'codepen']);
//gila::amenu_child('shop',['FB Marketplace',"shop/fbmarketplace",'icon'=>'facebook']);

gila::onController('shop',function(){
    view::stylesheet('lib/bootstrap/bootstrap.min.css');
    view::stylesheet('lib/font-awesome/css/font-awesome.min.css');
});

gila::widgets(['category-list'=>'shop/widgets/category-list']);

gila::$privilege['shop_operator']="Operator of products and shop orders.";

gila::content('shop_product','shop/tables/shop_product.php');
gila::content('shop_category','shop/tables/shop_category.php');
gila::content('shop_order','shop/tables/shop_order.php');
gila::content('shop_orderitem','shop/tables/shop_orderitem.php');
gila::content('shipping_method','shop/tables/shipping_method.php');
