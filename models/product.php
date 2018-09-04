<?php
namespace shop\models;

class product
{
    static function getById($id,$meta)
    {
        global $db;
        $p = $db->query("SELECT id,image,image2,image3,image4,title,description,price,old_price,stock FROM shop_product WHERE id=?;",[$id]);
        return mysqli_fetch_array($p);
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
        $where = "WHERE title !='' AND stock>0";

        if (@$args['c']>0) {
            $clist = $db->getList("SELECT id FROM shop_category WHERE parent_id = ?;",[$args['c']]);
            $clist[] = $args['c'];
            $clist = implode(',',$clist);
            $where = "WHERE title !='' AND shop_productmeta.product_id=shop_product.id AND shop_productmeta.metavalue IN($clist) AND stock>0";
        }

        if($args['search']) {
            $search = strip_tags($args['search']);
            $where .= " AND title like '%$search%' AND stock>0";
        }

        $totalpages = (int)$db->value("SELECT COUNT(*)/$ppp FROM shop_product $where;")+1;
        $ql = "SELECT shop_product.id,image,title,price, old_price, stock FROM shop_product,shop_productmeta $where GROUP BY id ORDER BY id DESC $limit;";
        return [$db->get($ql),$totalpages];
    }
}
