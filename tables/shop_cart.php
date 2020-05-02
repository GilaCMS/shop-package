<?php

return [
  'name'=> 'shop_cart',
  'title'=> 'Carts',
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
    'delete'=>['admin']
  ],
  'fields'=> [
    'id'=> [
      'edit'=>false,
      'qtype'=>'INT(11) NOT NULL AUTO_INCREMENT'
    ],
    'datetime'=> ['edit'=>false,'qtype'=>'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
    'user_id'=> [
      'list'=>false,
      'title'=>'User',
      'options'=>[0=>'-'],
      'qoptions'=> 'SELECT id, username FROM user;',
      'qtype'=>'INT(4) NOT NULL DEFAULT 0'
    ],
  ],
  'children'=>[
    'shop_cartitem'=>[
      'parent_id'=>'cart_id',
      'list'=>['id','image','sku_id','qty']
    ]
  ]
];
