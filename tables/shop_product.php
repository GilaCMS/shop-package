<?php

$table = [
    'name'=> 'shop_product',
    'title'=> 'Products',
    'pagination'=> 30,
    'tools'=>['add','csv'],
    'commands'=>['edit','clone','delete'],
    'bulk_actions'=> true,
    'search-box'=> true,
    'id'=>'id',
    'permissions'=>[
        'create'=>['admin','op_shop'],
        'read'=>['admin','op_shop'],
        'update'=>['admin','op_shop'],
        'delete'=>['admin','op_shop']
    ],
    'fields'=> [
        'id'=> ['edit'=>false],
        'image'=> [
            'type'=>'media',
            'eval'=>'dv="<img style=\"width:100px\" src=\""+cv+"\">"'
        ],
        'image2'=> ['type'=>'media', 'list'=> false],
        'image3'=> ['type'=>'media', 'list'=> false],
        'image4'=> ['type'=>'media', 'list'=> false],
        'title'=> [],
        'description'=> ['list'=> false],
        'price'=> [],
        'mlid'=> [],
        'category_id'=> [
            'title'=>'Main Category',
            'qoptions'=>'SELECT id,title FROM shop_category;'
        ],
        'categories'=> [
            'type'=> 'meta','edit'=>false,
            'title'=> 'Categories',
            'qoptions'=>'SELECT id,title FROM shop_category;',
            //"jt"=>['shop_product_category', 'product_id', 'category_id'],
            //"ot"=>['shop_category','id','title'],
            "mt"=>['shop_productmeta', 'product_id', 'metavalue'],
            'metatype'=>['metakey', 'category']
        ],
        'upc'=> ['list'=> false],
        'stock'=> [
            'default'=> 1
        ]
    ]/*,
    'oncreate'=> function(&$row) {
        if ($row['mlid'] != '') {
            $row['mlid'] = str_replace('-','',$row['mlid']);
            ini_set("allow_url_fopen", 1);
            $json = file_get_contents('https://api.mercadolibre.com/items/'.$row['mlid']);
            $obj = json_decode($json,true);

            $row['title'] = $obj['title'];
            $row['price'] = $obj['price'];
            $slugify = new Cocur\Slugify\Slugify();
            $slug = $slugify->slugify($obj['title']);

            foreach ($obj['pictures'] as $k=>$p) {
                file_put_contents("assets/products/$slug$k.jpg", file_get_contents($p['url']));
            }
        }

    }*/
];
