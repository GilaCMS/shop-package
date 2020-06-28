<?php View::css("shop/shop.css")?>
<div class="shop-list">
<div class="wrapper sidebar" style="grid-area:sidebar">
  <?php View::widgetArea('sidebar')?>
</div>
<div class="products-list" style="grid-area:productlist">
<?=View::script("core/lazyImgLoad.js")?>

<?php

foreach ($products as $p) {
  $href = Gila::make_url('shop','product',['id'=>$p['id'],'slug'=>$slug]);
  ?>
  <div class="product">
    <div class="product-body">
      <a href="<?=$href?>" class="thumb">
        <img data-src="<?=View::thumb_sm($p['image'])?>" class="lazy img-responsive" alt="Image">
      </a>
      <div class="product-title"><?=$p['title']?></div>
      <div class="product-price"><?=$p['price']?> <?=gila::option('shop.currency')?></div>
      <?=(@$p['old_price']>$p['price'])?'<del>'.$p['old_price'].'</del>':''?>
    </div>
    <div class="product-footer"><a class="g-btn" href="<?=$href?>"><?=__('Details')?></a></div>
  </div>
<?php } ?>
  </div>
  <!-- Pagination -->
  <div class="" style="margin:20px 0;text-align:center;grid-area:pagin">
    <ul class="g-nav g-pagination pagination">
      <?php
      for($pl=1;$pl<$totalpages+1;$pl++) {
        $active = "";
        if($pl==$page) $active="active";
        ?>
      <li class="<?=$active?>">
        <?php
        if($category) {
          $cat = $category.'/'.Slugify::text($category_name);
        } else $cat = '/products';
        ?>
        <a href="<?=Gila::url('shop').$cat?>?page=<?=$pl?>"><?=$pl?></a>
      </li>
      <?php } ?>
    </ul>
  </div>
</div>
