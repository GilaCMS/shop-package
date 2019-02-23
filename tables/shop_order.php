<?php

return [
    'name'=> 'shop_order',
    'title'=> 'Orders',
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
        'add_receiver'=> ['title'=>'_add_receiver_name','qtype'=>'VARCHAR(240)'],
        'user_id'=> [
            'list'=>false,
            'title'=>'User',
            'options'=>[0=>'-'],
            'qoptions'=> 'SELECT id, username FROM user;',
            'qtype'=>'INT(4) NOT NULL DEFAULT 0'
        ],
        'status'=> [
            'input-type'=>'select',
            'options'=> ['new'=>'New','paid'=>'Paid','process'=>'Process','delivered'=>'Delivered','canceled'=>'Canceled'],
            'qtype'=>'VARCHAR(20)'
        ],
        'add_city'=> ['title'=>'City','qtype'=>'VARCHAR(80)'],
        'add_shipping_method'=> ['title'=>'Shipping Method','input-type'=>'select','qoptions'=>'SELECT id,description FROM shipping_method','qtype'=>'INT(1) NOT NULL DEFAULT 0'],
        'add_address'=> ['list'=> false, 'title'=>'Address','qtype'=>'VARCHAR(240)'],
        'add_reference'=> ['list'=> false, 'title'=>'Reference','qtype'=>'VARCHAR(240)'],
        'add_pc'=> ['list'=> false, 'title'=>'CP','qtype'=>'VARCHAR(20)'],
        'add_phone'=> ['list'=> false, 'title'=>'Phone Number','qtype'=>'VARCHAR(20)'],
        'add_email'=> ['list'=> false, 'title'=>'Email','qtype'=>'VARCHAR(120)'],
        'total'=>[
            'title'=>'Total',
            'type'=>'number',
            'edit'=>false,'create'=>false,
            'qcolumn'=>'(SELECT SUM(cost*qty) FROM shop_orderitem soi WHERE soi.order_id=shop_order.id GROUP BY soi.order_id)',
        ],
        'commands'=>[
            'title'=>'',
            'edit'=>false,'create'=>false,
            'qcolumn'=>"''",'eval'=>"dv='<a href=\"shop/view_order/'+rv.id+'\"><i class=\"fa fa-shopping-cart\"></i></a>';"
        ],
        'ref'=>['qtype'=>'varchar(20)',],
        //'paymentmethod_id'=> [],
        //'paymentcode'=> [],
        //'paid'=> ['type'=>'date'],
    ],
    'children'=>[
        'shop_orderitem'=>[
            'parent_id'=>'order_id',
            'list'=>['id','image','product_id','description','qty','cost']
        ]
    ]
];
