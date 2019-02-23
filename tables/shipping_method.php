<?php

$table = [
  'name'=> 'shipping_method',
  'title'=> 'Shipping Methods',
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
    'id'=> ['edit'=>false,'qtype'=>'INT NOT NULL AUTO_INCREMENT'],
    'img'=> ['title'=>'Image','type'=>'media','qtype'=>'VARCHAR(120)'],
    'description'=> ['title'=>'Description','qtype'=>'VARCHAR(200)'],
    'cost'=> ['title'=>'Cost','type'=>'number','qtype'=>'DOUBLE DEFAULT 0'],
    'freeafter'=> ['title'=>'Free After','type'=>'number','qtype'=>'DOUBLE DEFAULT 0'],
    'pos'=> ['type'=>'number','qtype'=>'INT NOT NULL DEFAULT 0']
  ]
];
