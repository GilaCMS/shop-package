<?php

$table = [
    'name'=> 'shipping_method',
    'title'=> 'Shipping Methods',
    'pagination'=> 30,
    'tools'=>['add','csv'],
    'commands'=>['edit','delete'],
    'bulk_actions'=> true,
    'id'=>'id',
    'lang'=> 'shop/lang/',
    'fields'=> [
        'id'=> ['edit'=>false,'qtype'=>'INT NOT NULL AUTO_INCREMENT'],
        'img'=> ['type'=>'media','qtype'=>'VARCHAR(120)'],
        'description'=> ['qtype'=>'VARCHAR(200)'],
        'cost'=> ['type'=>'number','qtype'=>'DOUBLE DEFAULT 0'],
        'freeafter'=> ['type'=>'number','qtype'=>'DOUBLE DEFAULT 0'],
        'pos'=> ['type'=>'number','qtype'=>'INT NOT NULL DEFAULT 0']
    ]
];
