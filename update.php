<?php

global $db;

$table = new gTable('src/shop/tables/shipping_method.php');
$table->update();
$table = new gTable('src/shop/tables/shop_category.php');
$table->update();
$table = new gTable('src/shop/tables/shop_product.php');
$table->update();
$table = new gTable('src/shop/tables/shop_order.php');
$table->update();
$table = new gTable('src/shop/tables/shop_orderitem.php');
$table->update();

$db->query('CREATE TABLE IF NOT EXISTS `shop_productmeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `metakey` varchar(80) DEFAULT NULL,
  `metavalue` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB');

$db->query('CREATE TABLE IF NOT EXISTS `shop_skumeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku_id` int(11) DEFAULT NULL,
  `metakey` varchar(80) DEFAULT NULL,
  `metavalue` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB');

/*


$db->query('CREATE TABLE IF NOT EXISTS `shop_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) DEFAULT NULL,
  `description` text,
  `category_id` int(11),
  `price` double DEFAULT 0,
  `mlid` varchar(45) DEFAULT NULL,
  `image` varchar(120) DEFAULT NULL,
  `image2` varchar(120) DEFAULT NULL,
  `image3` varchar(120) DEFAULT NULL,
  `image4` varchar(120) DEFAULT NULL,
  `upc` varchar(30) DEFAULT NULL,
  `stock` int(11) DEFAULT 1,
  `publish` int(1) DEFAULT 1,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB');


$db->query('CREATE TABLE IF NOT EXISTS `shop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) DEFAULT NULL,
  `description` text,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB');

$db->query('CREATE TABLE IF NOT EXISTS `shop_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `status` varchar(30) DEFAULT "new",
  `deliverymethod_id` int(11) NOT NULL DEFAULT 1,
  `deliveryaddress_id` int(11) DEFAULT 0,
  `add_receiver` varchar(200) DEFAULT NULL,
  `add_address` varchar(200) DEFAULT NULL,
  `add_reference` varchar(200) DEFAULT NULL,
  `add_pc` varchar(5) DEFAULT NULL,
  `add_city` varchar(80) DEFAULT NULL,
  `add_phone` varchar(20) DEFAULT NULL,
  `add_email` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB');

$db->query('ALTER TABLE shop_order ADD `add_delivery_type` varchar(20) DEFAULT NULL');


$db->query('CREATE TABLE IF NOT EXISTS `shop_orderitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `cost` double DEFAULT 0,
  `qty` double DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB');
*/
