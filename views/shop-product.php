<?php View::css("shop/shop.css");?>
<style>
#overlay{
  background-image:url('<?=$p['image']?>');
}
</style>

<span>
    <a href="<?=Gila::make_url('shop')?>"><?=__('Home')?></a>
<?php if(($sess_c = Session::key('category')) && in_array($sess_c,$categories)) {
    $cat_name = Session::key('category_name'); ?>
    \ <a href="<?=Gila::make_url('shop','',['category'=>$sess_c,'slug'=>Slugify::text($cat_name)])?>"><?=$cat_name?></a>
<?php } ?>
    \ <strong><?=$p['title']?></strong>
</span>

<div class="product-view wrapper">

  <div class="product-img" style="text-align:center;height:100%">
    <div class="thumbs">
      <img src='<?=View::thumb($p['image'],'',600)?>' class="tmb-selected" onclick="selectImg(this)"/>
      <?php if($p['image2']){?><img src='<?=View::thumb($p['image2'],'',600)?>' onclick="selectImg(this)"/><?php }?>
      <?php if($p['image3']){?><img src='<?=View::thumb($p['image3'],'',600)?>' onclick="selectImg(this)"/><?php }?>
      <?php if($p['image4']){?><img src='<?=View::thumb($p['image4'],'',600)?>' onclick="selectImg(this)"/><?php }?>
    </div>
    <img id="imgZoom" src="<?=View::thumb($p['image'],'',600)?>" onmousemove="zoomIn(event)" onmouseout="zoomOut()" />
  </div>

  <div>
    <div id="overlay"></div>
    <h2 class="product--title"><?=$p['title']?></h2>
    <label><?=$p['upc']?></label>
    <h3><?=(@$p['old_price']>$p['price'])?'<del style="color:grey">'.$p['old_price'].' '.Gila::option('shop.currency').'</del> &nbsp;-'.floor(100-100*$p['price']/$p['old_price']).'%':''?><h3>
    <h3 class="product--price"><?=$p['price']?>&nbsp;<?=Gila::option('shop.currency')?></h3>
    <form action="shop/cart" method="get">
      <?php
      if(count($p['sku'])==1) $sku_id = $p['sku'][0]['id'];
      if(sizeof($p['sku_attr'])>0) {
        $attr_label = [];
        foreach($p['sku_attr'] as $_attr) $attr_label[] = __(shop\models\shop::attributes()[$_attr]['label']);
        echo '<div class="product--attributes"><label>'.implode(' - ',$attr_label).'</label>&nbsp;';
        echo '<select name="add" class="g-input" onchange="change_sku(this)" required>';
        if(count($p['sku'])>1) {
          echo '<option value="">-- '.__('Select').' --</option>';
        } 
        foreach($p['sku'] as $sku) {
          echo '<option value="'.$sku['id'].'" '.($sku['id']==$sku_id?'selected':'').'>'.implode(' - ',$sku['attr']).'</option>';
        }
        echo '</select></div>';
      } else {
        echo '<input name="add" value="'.$p['sku'][0]['id'].'" type="hidden"/>';
      }
      if(count($p['sku'])==0) {
        echo '<br><span class="alert warning">'.__('Without Stock').'</span>';
      } else {
      ?>
      <div style="margin-bottom: 1.5em">
        <?=__("Quantity")?>: <input name="qty" class="g-input" value="1" type="number" min="1" max="<?=@$p['stock'][$sku_id]?:0?>" size="2" style="width:70px"/>
      </div>
      <button href="" class="g-btn fullwidth" style="background:#ff4545"><?=__('add_to_cart')?></button>
    <?php } ?>
    </form>
    <hr><div class="product--description"><?=($p['description']?:'')?></div>
  </div>

</div>
<div class="col-md-12">
  <?=View::widget_area('product.after')?>
</div>

<script>
function change_sku(d) {
  document.location = '<?=Gila::make_url('shop','product').$p['id']?>-'+d.value
}

function selectImg(img) {
  document.getElementsByClassName("tmb-selected")[0].classList.remove("tmb-selected");
  img.classList.add("tmb-selected");
  document.getElementById("imgZoom").src=img.src;
  document.getElementById("overlay").style.backgroundImage='url("'+img.src+'")';
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
