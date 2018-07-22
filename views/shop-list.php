<style>
.products-list{
    display: grid;
    grid-gap: 10px;
    column-gap: 10px;
    grid-template-columns: repeat(auto-fill, minmax(120px,1fr));
}

.pagination>li{display:inline-block}
.product>.panel {
    border: 1px solid #b1b1b1;
}
.product-body {
    height:100%;
    padding-bottom: 54px;
}
.product-price {
    font-weight: bold;
    color: #ff4545;
    font-size: 1.2em;
    padding:6px;
}
.product-title {
    padding:6px;
}
.product-footer {
    position:relative;
    bottom:0;
    padding:6px;
    margin-top:-56px;
    text-align: center;
}
.sidebar{
    display: none;
}
/* no grid css support*/
.products-list{
    margin:auto;
    width:100%;
}
.product {
    display: inline-block;
    border: 1px solid #ccc;
    box-shadow: 1px 1px 3px #ccc;
    max-width:260px;
	display: inline-block;
	vertical-align: top;
    background: white;
}
.product img{
    width:100%;
    max-height:220px;
    height: auto;
    margin:auto;
}

/*
@media (min-width:600px) {
    .products-list{
        column-count: 3;
        -webkit-column-count: 3;
        -moz-column-count: 3;
    }
}
@media (min-width:1100px) {
    .products-list{
        column-count: 4;
        -webkit-column-count: 4;
        -moz-column-count: 4;
    }
}
*/
.shop-list {
    display:grid;
    grid-template-columns: 20px 0px 1fr 20px;
    grid-template-areas:'. productlist productlist .''. pagin pagin.'
}
@media (min-width:540px) {
    .products-list{
        grid-template-columns: repeat(auto-fit, minmax(100px,220px));
    }
}
@media (min-width:800px) {
    .sidebar{
        display: inline-block;
    }
    .shop-list {
        grid-template-columns: 20px minmax(200px,260px) minmax(560px,1fr) 20px;
        grid-template-areas:'. sidebar productlist .''. pagin pagin.'
    }

}
</style>
  <div class="shop-list">
    <div class="wrapper sidebar" style="grid-area:sidebar">
        <?php view::widget_area('sidebar')?>
    </div>
    <div class="products-list" style="grid-area:productlist">
    <script src="src/core/assets/lazyImgLoad.js" async></script>
<?php
$slugify = new Cocur\Slugify\Slugify();

foreach ($products as $p) {
    $slug = $slugify->slugify($p['title']);
    $thumb = $p['image'].'_sm.jpg';
    $href=gila::make_url('shop','product',['id'=>$p['id'],'slug'=>$slug]);
    ?>
    <div class="product">
        <div class="product-body">
            <a href="<?=$href?>" class="thumb">
                <img data-src="<?=view::thumb_sm($p['image'],$thumb)?>" class="lazy img-responsive" alt="Image">
            </a>
            <div class="product-title"><?=$p['title']?></div>
            <div class="product-price">$ <?=$p['price']?></div>
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
