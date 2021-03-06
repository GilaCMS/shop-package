<?php

$table = [
  'name'=> 'shop_sku',
  'title'=> 'Stock Keeping Unit',
  'pagination'=> 30,
  'tools'=>['add'],
  'commands'=>['edit','delete'],
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
    'product_id'=> ['list'=>'false','edit'=>'false','qtype'=>'INT(11) NOT NULL DEFAULT 0'],
    //'price'=> ['title'=>'Price','qtype'=>'VARCHAR(20) DEFAULT ""'],
    'stock'=> [
      'default'=> 1,'title'=> 'Stock','qtype'=>'INT(4) DEFAULT 1'
    ]
  ]
];
