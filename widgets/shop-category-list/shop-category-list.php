<style>
.widget-category-list ul{
  list-style: none;
  display: block;
  margin: auto;
  font: 18px Arial;
  line-height: 1.2;
  padding:0;
}
.widget-category-list ul li a{
  width:100%;
  margin:0;
}
.widget-category-list ul li{
  padding: 8px;
  border: 1px solid #ccc;
  background: #efefef
}
.widget-category-list ul li:hover{
  background: #d8d8d8
}

</style>
<ul>
<?php
global $db,$g;

if(isset($g->category)) {
  $parent_id = $db->value("SELECT parent_id FROM shop_category WHERE id = '{$g->category}';");
  if($g->category!=0) echo '<li><a href="'.gila::url('shop/products').'"><i class="fa fa-chevron-left"></i> '.__('_all_categories').'</a></li>';
  if($parent_id) {
    $catname = $db->value("SELECT title FROM shop_category WHERE id = '{$parent_id}';");
    echo '<li><a href="'.gila::make_url('shop/','',[
      'category'=>$parent_id,
      'slug'=>Slugify::text($catname)
      ]).'"><i class="fa fa-chevron-left"></i> '.$catname.'</a></li>';
  }

  $catname = $db->value("SELECT title FROM shop_category WHERE id = '{$g->category}';");
  if($catname) echo '<li><b>'.$catname.'</b></li>';

  $list = $db->get("SELECT id,title FROM shop_category WHERE parent_id = '{$g->category}';");
  if(is_array($list)) {
    echo '<ul>';
    foreach($list as $c) {
      echo '<li> <a href="'.Gila::make_url('shop','',[
        'category'=>$c[0],
        'slug'=>Slugify::text($c[1])
        ]).'">'.$c[1].' <i class="fa fa-chevron-right"></i></a>';
    }
    echo '</ul>';
  }
}
?>
</ul>
