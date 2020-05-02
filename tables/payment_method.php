<?php

$table = [
  'name'=> 'payment_method',
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
    'description'=> ['title'=>'Desription','qtype'=>'VARCHAR(200)'],
    'cost'=> ['title'=>'Cost','type'=>'number','qtype'=>'DOUBLE DEFAULT 0'],
    'cost_f'=> ['title'=>'Cost Factor','type'=>'number','qtype'=>'DOUBLE DEFAULT 0'],
    'gateway'=> [
      'title'=>'Process',
      'type'=>'select',
      'qtype'=>'VARCHAR(30)',
      'options'=> [''=>'None']
    ],
    'pos'=> ['type'=>'number','qtype'=>'INT NOT NULL DEFAULT 0']
  ]
];
