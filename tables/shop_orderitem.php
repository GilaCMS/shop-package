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
        'id'=> ['edit'=>false],
        'order_id'=> ['edit'=>false],
        'product_id'=> [
            'title'=>'Product',
            'qcolumn'=>"(SELECT CONCAT(id,' ',title) from shop_product where id=product_id)"
        ],
        'qty'=> [],
        'cost'=> []
    ]
];
