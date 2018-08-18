<?php

$table = [
    'name'=> 'shop_product',
    'title'=> 'Products',
    'pagination'=> 30,
    'tools'=>['add','csv'],
    'commands'=>['edit','clone','delete'],
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
        'image'=> [
            'type'=>'media',
            'qtype'=>'VARCHAR(120)',
            'eval'=>'dv="<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"',
            'display'=>'"<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"'
        ],
        'image2'=> ['type'=>'media', 'qtype'=>'VARCHAR(120)', 'list'=> false],
        'image3'=> ['type'=>'media', 'qtype'=>'VARCHAR(120)', 'list'=> false],
        'image4'=> ['type'=>'media', 'qtype'=>'VARCHAR(120)', 'list'=> false],
        'title'=> ['title'=>'Title','qtype'=>'VARCHAR(120)'],
        'description'=> ['list'=> false,'qtype'=>'TEXT'],
        'price'=> ['title'=>'Price','qtype'=>'DOUBLE DEFAULT 0'],
        'old_price'=> ['qtype'=>'DOUBLE DEFAULT 0', 'list'=> false, 'create'=> false],
        'categories'=> [
            'type'=> 'meta',
            'title'=> 'Categories',
            'qoptions'=>'SELECT id,title FROM shop_category;',
            "mt"=>['shop_productmeta', 'product_id', 'metavalue'],
            'metatype'=>['metakey', 'category']
        ],
        'upc'=> ['list'=> false,'qtype'=>'VARCHAR(30) NOT NULL'],
        'stock'=> [
            'default'=> 1,'qtype'=>'INT(4) DEFAULT 0'
        ]
    ]
];
