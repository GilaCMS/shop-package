<?php

$table = [
    'name'=> 'shop_orderitem',
    'title'=> 'OrderItems',
    'lang'=> 'shop/lang/',
    'id'=>'id',
    'fields'=> [
        'id'=> ['edit'=>false,'list'=>false,'qtype'=>'INT NOT NULL AUTO_INCREMENT'],
        'order_id'=> ['edit'=>false,'list'=>false,'qtype'=>'INT(4)'],
        'image'=> [
            'edit'=>false,'title'=>'',
            'qcolumn'=>'(SELECT image FROM shop_product sp WHERE product_id=sp.id)',
            'eval'=>'dv="<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"',
            'display'=>'"<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"'
        ],
        'product_id'=> [
            'title'=>'Product',
            'eval'=>'dv="<a target=\"_blank\" href=\"shop/product/"+cv+"\">"+cv+"</a>"',
            //'qcolumn'=>"(SELECT CONCAT(id,' ',title) from shop_product where id=product_id)"
        ],
        'description'=> ['qtype'=>'VARCHAR(120)'],
        'qty'=> ['title'=>'Units','qtype'=>'DOUBLE NOT NULL DEFAULT 0'],
        'cost'=> ['title'=>'Price','qtype'=>'DOUBLE NOT NULL DEFAULT 0']
    ]
];
