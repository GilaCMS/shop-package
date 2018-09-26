<?php

$table = [
    'name'=> 'shop_attribute',
    'title'=> 'Attributes',
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
        'label'=> ['title'=>'Attribute','qtype'=>'VARCHAR(50)']
    ],
    'children'=>[
        'shop_attr_option'=>[
            'parent_id'=>'attribute_id',
            'list'=>['id','optionkey','optionvalue']
        ]
    ]
];
