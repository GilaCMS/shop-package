<?php
namespace shop\models;

class product
{
  static function getById($id)
  {
    global $db;

    $p = $db->get("SELECT id,upc,image,image2,image3,image4,title,description,(CASE WHEN new_price=\"\" THEN price ELSE new_price END) as price,price as old_price FROM shop_product WHERE id=?;",[$id])[0];

    $p['sku_attr'] = $db->getList("SELECT metakey FROM shop_skumeta,shop_sku WHERE product_id=$id AND sku_id=shop_sku.id GROUP BY metakey");
    $p['sku'] = [];
    $skus = $db->get("SELECT id,stock FROM shop_sku WHERE product_id=? AND stock>0",[$id]);
    foreach($skus as $sku) {
      $_id = $sku[0];
      $_attr=[]; 
      foreach($p['sku_attr'] as $sku_attr) {
        if($_v = $db->value("SELECT metavalue FROM shop_skumeta WHERE metakey='$sku_attr' AND sku_id=$_id;")) {
          $_attr[]=$_v;
        } else $_attr[]='';
      }
      $p['sku'][] = [
        'id'=>$_id,
        'attr'=>$_attr
      ];
      $p['stock'][$_id] = $sku[1];
    }

    return $p;
  }

  static function getMeta($id)
  {
    global $db;
    return $db->getList("SELECT metavalue FROM shop_productmeta WHERE product_id=? AND metakey=?;",[$id,$meta]);

  }

  static function get($args,$ppp = 16)
  {
    global $db;
    $page = $args['page']?:1;
    $limit = "LIMIT ".(($page-1)*$ppp).",$ppp";
    $where = "WHERE title !='' AND (SELECT SUM(stock) FROM shop_sku WHERE product_id=shop_product.id GROUP BY product_id)>0";

    if ($args['c']>0) {
      $clist = $db->getList("SELECT id FROM shop_category WHERE parent_id = ?;",[$args['c']]);
      $clist[] = $args['c'];
      $clist = implode(',',$clist);
      $where = "WHERE title !='' AND (SELECT count(*) FROM shop_productmeta WHERE shop_productmeta.product_id=shop_product.id AND shop_productmeta.metavalue IN($clist))>0 AND (SELECT SUM(stock) FROM shop_sku WHERE product_id=shop_product.id GROUP BY product_id)>0";
    }

    if($args['search']) {
      $search = strip_tags($args['search']);
      $where .= " AND title like '%$search%' AND stock>0";
    }

    if(@$args['offers']==true) {
      $where .= " AND new_price!=\"\"";
    }
    
    $res = $db->value("SELECT COUNT(*) FROM shop_product $where;");
    $totalpages = (int)floor($res/$ppp);
    if($res%$ppp>0) $totalpages++;
    $ql = "SELECT shop_product.id,image,title,(CASE WHEN new_price=\"\" THEN price ELSE new_price END) as price,price as old_price, (SELECT SUM(stock) FROM shop_sku WHERE product_id=shop_product.id GROUP BY product_id) as stock FROM shop_product $where ORDER BY id DESC $limit;";
    return [$db->get($ql),$totalpages];
  }
}
