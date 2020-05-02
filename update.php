<?php

global $db;

$table = new gTable('src/shop/tables/shipping_method.php');
$table->update();
$table = new gTable('src/shop/tables/payment_method.php');
$table->update();
$table = new gTable('src/shop/tables/shop_category.php');
$table->update();
$table = new gTable('src/shop/tables/shop_product.php');
$table->update();
$table = new gTable('src/shop/tables/shop_order.php');
$table->update();
$table = new gTable('src/shop/tables/shop_orderitem.php');
$table->update();
$table = new gTable('src/shop/tables/shop_cart.php');
$table->update();
$table = new gTable('src/shop/tables/shop_cartitem.php');
$table->update();
$table = new gTable('src/shop/tables/shop_sku.php');
$table->update();
$table = new gTable('src/shop/tables/shop_attribute.php');
$table->update();
$table = new gTable('src/shop/tables/shop_attr_option.php');
$table->update();

$db->query('CREATE TABLE IF NOT EXISTS `shop_productmeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `metakey` varchar(80) DEFAULT NULL,
  `metavalue` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB');

$db->query('CREATE TABLE IF NOT EXISTS `shop_skumeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku_id` int(11) NOT NULL DEFAULT 0,
  `metakey` varchar(80) DEFAULT NULL,
  `metavalue` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB');

$db->query('ALTER TABLE `shop_sku` ADD INDEX `product_id` (`product_id` ASC);');
$db->query('ALTER TABLE `shop_productmeta` ADD INDEX `product_id` (`product_id` ASC);');
$db->query('ALTER TABLE `shop_skumeta` ADD INDEX `sku_id` (`sku_id` ASC);');
