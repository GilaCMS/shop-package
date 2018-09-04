<?php
global $db;
$slugify = new Cocur\Slugify\Slugify();
$slug = 'assets/products/'.$slugify->slugify($p['title']);
?>

<?php view::css("src/shop/assets/shop.css");?>
<style>
#overlay{
  background-image:url('<?=$p['image']?>');
}
</style>

<span>
    <a href="<?=gila::make_url('shop')?>"><?=__('Home')?></a>
<?php if(($sess_c = session::key('category')) && in_array($sess_c,$categories)) {
    $cat_name = session::key('category_name'); ?>
    \ <a href="<?=gila::make_url('shop','',['category'=>$sess_c,'slug'=>$slugify->slugify($cat_name)])?>"><?=$cat_name?></a>
<?php } ?>
    \ <strong><?=$p['title']?></strong>
</span>

<div class="product-view wrapper">

    <div class="product-img" style="text-align:center;height:100%">
        <div class="thumbs">
            <img src='<?=$p['image']?>' class="tmb-selected" onclick="selectImg(this)"/>
            <?php if($p['image2']){?><img src='<?=$p['image2']?>' onclick="selectImg(this)"/><?php }?>
            <?php if($p['image3']){?><img src='<?=$p['image3']?>' onclick="selectImg(this)"/><?php }?>
            <?php if($p['image4']){?><img src='<?=$p['image4']?>' onclick="selectImg(this)"/><?php }?>
        </div>
        <img id="imgZoom" src="<?=$p['image']?>" onmousemove="zoomIn(event)" onmouseout="zoomOut()" />
    </div>

    <div class="">
        <div id="overlay"></div>
        <h2 style="color:#004c98"><?=$p['title']?><h2>
        <?=(@$p['old_price']>$p['price'])?'<del style="font-size:80%;color:grey">'.$p['old_price'].' '.gila::option('shop.currency').'</del> &nbsp;-'.floor(100-100*$p['price']/$p['old_price']).'%':''?>
        <h2><?=$p['price']?>&nbsp;<?=gila::option('shop.currency')?><h2>
        <form action="shop/cart" method="get">
            <input name="add" value="<?=$p['id']?>" type="hidden"/>
            <input name="qty" class="g-input" value="1" type="number" min="0" max="<?=$p['stock']?>" size="2" style="width:70px"/>
            <button href="" class="g-btn" style="background:#ff4545"><?=__('add_to_cart')?></button>
        <form>
        <hr><p><?=($p['description']?:'')?></p>
    </div>

</div>
<div class="col-md-12">
    <?=view::widget_area('product.after')?>
</div>

<script>
function selectImg(img) {
    document.getElementsByClassName("tmb-selected")[0].classList.remove("tmb-selected");
    img.classList.add("tmb-selected");
    document.getElementById("imgZoom").src=img.src;
    document.getElementById("overlay").style.backgroundImage='url('+img.src+')';
}

function zoomIn(event) {
  var element = document.getElementById("overlay");
  element.style.display = "inline-block";
  var img = document.getElementById("imgZoom");
  var f = img.naturalWidth/img.offsetWidth;
  var posX = event.offsetX ? (event.offsetX) : event.pageX - img.offsetLeft;
  var posY = event.offsetY ? (event.offsetY) : event.pageY - img.offsetTop;
  element.style.backgroundPosition=(-posX*f+150)+"px "+(-posY*f+150)+"px";

}

function zoomOut() {
  var element = document.getElementById("overlay");
  element.style.display = "none";
}
</script>
