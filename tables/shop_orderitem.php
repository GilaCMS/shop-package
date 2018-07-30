<?php

$table = [
    'name'=> 'shop_orderitem',
    'title'=> 'OrderItems',
    'pagination'=> 30,
    'tools'=>['add','csv'],
    'commands'=>['edit','delete'],
    'bulk_actions'=> true,
    'search-box'=> true,
    'id'=>'id',
    'fields'=> [
        'id'=> ['edit'=>false,'qtype'=>'INT NOT NULL AUTO_INCREMENT'],
        'order_id'=> ['edit'=>false,'qtype'=>'INT(4)'],
        'product_id'=> [
            'title'=>'Product',
            'qcolumn'=>"(SELECT CONCAT(id,' ',title) from shop_product where id=product_id)"
        ],
        'description'=> ['qtype'=>'VARCHAR(120)'],
        'qty'=> ['qtype'=>'DOUBLE NOT NULL DEFAULT 0'],
        'cost'=> ['qtype'=>'DOUBLE NOT NULL DEFAULT 0']
    ]
];
