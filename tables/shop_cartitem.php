<?php

return [
  'name'=> 'shop_cartitem',
  'title'=> 'CartItems',
  'lang'=> 'shop/lang/',
  'id'=>'id',
  'commands'=>['edit','delete'],
  'permissions'=>[
    'create'=>['admin','shop_op'],
    'read'=>['admin','shop_op'],
    'update'=>['admin'],
    'delete'=>['admin']
  ],
  'list'=>['id','image','sku_id','qty'],
  'fields'=> [
    'id'=> ['edit'=>false,'qtype'=>'INT NOT NULL AUTO_INCREMENT'],
    'cart_id'=> ['edit'=>false,'list'=>false,'qtype'=>'INT(4)'],
    'image'=> [
      'edit'=>false, 'type'=>'media',
      'qcolumn'=>'(SELECT image FROM shop_product sp,shop_sku sk WHERE sku_id=sk.id AND sp.id=sk.product_id)',
      'eval'=>'dv="<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"',
      'display'=>'"<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"'
    ],
    'sku_id'=> [
      'title'=>'Sku id','qtype'=>'INT NOT NULL DEFAULT 0'
    ],
    'qty'=> ['title'=>'Units','qtype'=>'DOUBLE NOT NULL DEFAULT 0']
  ]
];
