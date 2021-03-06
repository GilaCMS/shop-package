<?php

return [
  'name'=> 'shop_orderitem',
  'title'=> 'OrderItems',
  'lang'=> 'shop/lang/',
  'id'=>'id',
  'tools'=>['add'],
  'commands'=>['edit','delete'],
  'permissions'=>[
    'create'=>['admin','shop_op'],
    'read'=>['admin','shop_op'],
    'update'=>['admin'],
    'delete'=>['admin']
  ],
  'list'=>['id','image','product_id','description','qty','cost'],
  'fields'=> [
    'id'=> ['edit'=>false,'qtype'=>'INT NOT NULL AUTO_INCREMENT'],
    'order_id'=> ['edit'=>false,'list'=>false,'qtype'=>'INT(4)'],
    'image'=> [
      'edit'=>false,
      'qcolumn'=>'(SELECT image FROM shop_product sp WHERE product_id=sp.id)',
      'eval'=>'dv="<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"',
      'display'=>'"<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"'
    ],
    'product_id'=> [
      'title'=>'Product','qtype'=>'INT NOT NULL DEFAULT 0','type'=>'key','table'=>'shop_product',
      'eval'=>'dv="<a target=\"_blank\" href=\"shop/product/"+cv+"\">"+cv+"</a>"',
      //'qcolumn'=>"(SELECT CONCAT(id,' ',title) from shop_product where id=product_id)"
    ],
    'description'=> ['title'=>'Description','qtype'=>'VARCHAR(120)'],
    'qty'=> ['title'=>'Units','qtype'=>'DOUBLE NOT NULL DEFAULT 0'],
    'cost'=> ['title'=>'Price','qtype'=>'DOUBLE NOT NULL DEFAULT 0']
  ]
];
