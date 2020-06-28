<?php

$table = [
  'name'=> 'shop_product',
  'title'=> 'Products',
  'pagination'=> 30,
  'tools'=>['add'],
  'commands'=>['edit','clone'],
  'clone'=>['image','image2','title','price'],
  'bulk_actions'=> ['delete','discount'],
  'search_box'=> true,
  'search_boxes'=>['categories'],
  'lang'=> 'shop/lang/',
  'id'=>'id',
  'meta_table'=> ['shop_productmeta', 'product_id', 'metakey', 'metavalue'],
  'permissions'=>[
    'create'=>['admin','shop_op'],
    'read'=>['admin','shop_op'],
    'update'=>['admin','shop_op'],
    'delete'=>['admin','shop_op']
  ],
  'fields'=> [
    'id'=> ['title'=>'#','edit'=>false],
    'image'=> [
      'type'=>'media',
      'qtype'=>'VARCHAR(120)',
      'eval'=>'dv="<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"',
      'display'=>'"<img style=\"max-width:100px;max-height:120px;\" src=\""+cv+"\">"'
    ],
    'image2'=> ['type'=>'media', 'qtype'=>'VARCHAR(120)', 'list'=> false],
    'image3'=> ['type'=>'media', 'qtype'=>'VARCHAR(120)', 'list'=> false],
    'image4'=> ['type'=>'media', 'qtype'=>'VARCHAR(120)', 'list'=> false],
    //'gallery'=> [
    //  'type'=>'meta','input-type'=>'media-gallery', 'max'=>8, 'list'=> false,
    //  'metatype'=>'image', 'title'=>'Gallery'
    //],
    'categories'=> [
      'type'=> 'meta',
      'input_type'=> 'select2',
      'title'=> 'Categories',
      'qoptions'=>'SELECT id,title FROM shop_category;',
      'meta_key'=> 'category'
    ],
    'title'=> ['title'=>'Title','qtype'=>'VARCHAR(120)'],
    'price'=> ['title'=>'Price','qtype'=>'DOUBLE DEFAULT 0','type'=>'number'],
    'new_price'=> ['qtype'=>'VARCHAR(20) DEFAULT ""', 'list'=> false, 'create'=> false],
    'discount'=>[
      'edit'=>false,'type'=>'number',
      'qcolumn'=>'IF(price=0 OR new_price=price OR new_price="" OR new_price IS NULL, "", ROUND((price-new_price)/price,2))'
    ],
    'stock'=>[
      'type'=>'number','edit'=>false,
      'qcolumn'=>'(SELECT COALESCE(SUM(stock),0) FROM shop_sku WHERE product_id=shop_product.id)'
    ],
    'upc'=> ['list'=> false,'qtype'=>'VARCHAR(30) DEFAULT ""'],
    'description'=> [
      'title'=>'Description','list'=> false,'qtype'=>'TEXT','type'=>'textarea','input-type'=>'tinymce', 'allow-tags'=>true
    ]
  ],
  'children'=>[
    'shop_sku'=>[
      'parent_id'=>'product_id',
      'list'=>['id','stock']
    ]
  ]
];
