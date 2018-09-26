<?php

$table = [
    'name'=> 'shop_sku',
    'title'=> 'Stock Keeping Unit',
    'pagination'=> 30,
    'tools'=>['add'],
    'commands'=>['edit','delete'],
    'bulk_actions'=> true,
    'search-box'=> true,
    'lang'=> 'shop/lang/',
    'id'=>'id',
    'permissions'=>[
        'create'=>['admin','shop_op'],
        'read'=>['admin','shop_op'],
        'update'=>['admin','shop_op'],
        'delete'=>['admin','shop_op']
    ],
    'fields'=> [
        'id'=> ['title'=>'#','edit'=>false],
        'product_id'=> ['list'=>'false','edit'=>'false','qtype'=>'INT(11) NOT NULL'],
/*        'size'=> [
            'type'=>'meta',
            'options'=>[''=>'-','OneSize'=>'One Size','S'=>'S','S-M'=>'S-M','M'=>'M','M-L'=>'M-L','L'=>'L'],
            "mt"=>['shop_skumeta', 'sku_id', 'metavalue'],
            'metatype'=>['metakey', 'size']
        ],*/
        //'price'=> ['title'=>'Price','qtype'=>'VARCHAR(20) DEFAULT ""''],
        //'upc'=> ['qtype'=>'VARCHAR(30)'],
        'stock'=> [
            'default'=> 1,'title'=> 'Stock','qtype'=>'INT(4) DEFAULT 1'
        ]
    ]
];
