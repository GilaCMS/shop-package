<?php

$table = [
    'name'=> 'shop_category',
    'title'=> 'Categories',
    'pagination'=> 30,
    'tools'=>['add','csv'],
    'commands'=>['edit','delete'],
    'bulk_actions'=> true,
    'id'=>'id',
    'permissions'=>[
        'create'=>['admin','shop_op'],
        'read'=>['admin','shop_op'],
        'update'=>['admin','shop_op'],
        'delete'=>['admin','shop_op']
    ],
    'lang'=> 'shop/lang/',
    'fields'=> [
        'id'=> ['edit'=>false],
        'title'=> ['qtype'=>'VARCHAR(120)'],
        'description'=> ['qtype'=>'TEXT'],
        'parent_id'=> [
            'options'=>[0=>'-'],
            'qoptions'=> 'SELECT id, title FROM shop_category;',
            'list'=>false,
            'qtype'=>'INT'
        ],
    ]
];
