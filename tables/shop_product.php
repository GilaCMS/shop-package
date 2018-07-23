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
            'eval'=>'dv="<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"'
        ],
        'image2'=> ['type'=>'media', 'list'=> false],
        'image3'=> ['type'=>'media', 'list'=> false],
        'image4'=> ['type'=>'media', 'list'=> false],
        'title'=> [],
        'description'=> ['list'=> false,'qtype'=>'TEXT'],
        'price'=> [],
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
