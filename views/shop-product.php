<?php
global $db;
$slugify = new Cocur\Slugify\Slugify();
$slug = 'assets/products/'.$slugify->slugify($p['title']);

?>

<style>
#imgZoom{
    margin:auto;
    max-height: 600px;
}
#overlay{
  width:300px;
  height:300px;
  position:absolute;
  display:none;
  background-image:url('<?=$p['image']?>');
  background-repeat:no-repeat;
  z-index:99999;
}
.thumbs>img{
    border:2px solid rgba(1,1,1,0.1);
}
.thumbs>img.tmb-selected{
    border:2px solid steelblue;
    max-height: 160px;
}
.product-view {
    display:grid;
    grid-gap:20px;
    background:white;
    grid-template-columns: 1fr;
}
.product-img {
    display:grid;
    grid-gap:20px;
    grid-template-columns: 1fr;
}
.product-img .thumbs img{
    max-width: 60px;
}

#overlay {
    visibility: hidden;
}

@media (min-width:600px) {
    .product-img {
        grid-template-columns: 80px 1fr;
    }
    .product-img .thumbs img{
        max-width: 100px;
    }
}
@media (min-width:900px) {
    .product-view {
        grid-template-columns: 6fr 4fr;
    }
    .product-img {
        grid-template-columns: 100px 1fr;
    }
    #overlay {
        visibility: visible;
    }
}

</style>

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
        <h2>$ <?=$p['price']?><h2>
        <form action="shop/cart" method="get">
            <input name="add" value="<?=$p['id']?>" type="hidden"/>
            <input name="qty" class="g-input" value="1" type="number" size="2" style="width:70px"/>
            <button href="" class="g-btn" style="background:#ff4545"><i class="fa fa-shopping-cart"></i> <?=__('add_to_cart')?></button>
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
