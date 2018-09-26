<?php

$table = [
    'name'=> 'shop_attr_option',
    'title'=> 'Options',
    'pagination'=> 30,
    'tools'=>['add'],
    'commands'=>['edit','delete'],
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
        'attribute_id'=> ['title'=>'Attribute','edit'=>false,'list'=>false,'qtype'=>'INT(4)'],
        'optionkey'=> ['title'=>'Key','qtype'=>'VARCHAR(20)'],
        'optionvalue'=> ['title'=>'Value','qtype'=>'VARCHAR(30)']
    ]
];
