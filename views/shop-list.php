<?php view::css("src/shop/assets/shop.css");?>
<div class="shop-list">
<div class="wrapper sidebar" style="grid-area:sidebar">
    <?php view::widget_area('sidebar')?>
</div>
<div class="products-list" style="grid-area:productlist">
<script src="src/core/assets/lazyImgLoad.js" async></script>

<?php
$slugify = new Cocur\Slugify\Slugify();

foreach ($products as $p) {
    //$slug = $slugify->slugify($p['title']);
    $href=gila::make_url('shop','product',['id'=>$p['id'],'slug'=>$slug]);
    ?>
    <div class="product">
        <div class="product-body">
            <a href="<?=$href?>" class="thumb">
                <img data-src="<?=view::thumb_sm($p['image'])?>" class="lazy img-responsive" alt="Image">
            </a>
            <div class="product-title"><?=$p['title']?></div>
            <div class="product-price"><?=$p['price']?> <?=gila::option('shop.currency')?></div>
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
                    $slugify = new Cocur\Slugify\Slugify();
                    $cat = $category.'/'.$slugify->slugify($category_name);
                } else $cat = '';
                ?>
                <a href="<?=gila::url('shop').$cat?>?page=<?=$pl?>"><?=$pl?></a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
