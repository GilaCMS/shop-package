<?php

$table = [
  'name'=> 'shop_category',
  'title'=> 'Categories',
  'pagination'=> 30,
  'tools'=>['add'],
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
    'active'=> ['qtype'=>'TINYINT DEFAULT 1', 'type'=>'checkbox'],
    'parent_id'=> [
      'input-type'=>'select',
      'options'=>[0=>'-'],
      'qoptions'=> 'SELECT id, title FROM shop_category;',
      'list'=>false,
      'qtype'=>'INT'
    ],
  ]
];
