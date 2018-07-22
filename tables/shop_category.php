<?php

$table = [
    'name'=> 'shop_category',
    'title'=> 'Categories',
    'pagination'=> 30,
    'tools'=>['add','csv'],
    'commands'=>['edit','delete'],
    'bulk_actions'=> true,
    'id'=>'id',
    //'csv'=> ['id','name','email'],
    'fields'=> [
        'id'=> ['edit'=>false],
        'title'=> [],
        'description'=> [],
        'parent_id'=> [
            'options'=>[0=>'-'],
            'qoptions'=> 'SELECT id, title FROM shop_category;',
            'list'=>false
        ],
    ]
];
