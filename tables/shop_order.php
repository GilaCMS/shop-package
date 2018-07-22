<?php

$table = [
    'name'=> 'shop_order',
    'title'=> 'Orders',
    'pagination'=> 30,
    'tools'=>['add','csv'],
    'commands'=>['edit','delete'],
    'bulk_actions'=> true,
    'search-box'=> true,
    'id'=>'id',
    'fields'=> [
        'id'=> [
            'edit'=>false,
            'qtype'=>'INT(11) NOT NULL AUTO_INCREMENT'
        ],
        'datetime'=> ['edit'=>false,'qtype'=>'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'],
        'add_receiver'=> ['title'=>'Client Name','qtype'=>'VARCHAR(240)'],
        'user_id'=> [
            'options'=>[0=>'-'],
            'qoptions'=> 'SELECT id, username FROM user;',
            'qtype'=>'INT(4) NOT NULL DEFAULT 0'
        ],
        'status'=> [
            'options'=> ['new'=>'New','paid'=>'Paid','process'=>'Process','delivered'=>'Delivered','canceled'=>'Canceled'],
            'qtype'=>'VARCHAR(20)'
        ],
        'add_city'=> ['title'=>'City','qtype'=>'VARCHAR(80)'],
        'add_shipping_method'=> ['title'=>'Shipping Method','qtype'=>'INT(1) NOT NULL DEFAULT 0'],
        'add_address'=> ['list'=> false, 'title'=>'Address','qtype'=>'VARCHAR(240)'],
        'add_reference'=> ['list'=> false, 'title'=>'Reference','qtype'=>'VARCHAR(240)'],
        'add_pc'=> ['list'=> false, 'title'=>'CP','qtype'=>'VARCHAR(20)'],
        'add_phone'=> ['list'=> false, 'title'=>'Phone Number','qtype'=>'VARCHAR(20)'],
        'add_email'=> ['list'=> false, 'title'=>'Email','qtype'=>'VARCHAR(120)'],
        //'paymentmethod_id'=> [],
        //'paymentcode'=> [],
        //'paid'=> ['type'=>'date'],
    ],
    'children'=>[
        'shop_orderitem'=>[]
    ]
];
