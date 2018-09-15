<?php global $g; ?>
<style>
#checkout-content {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap:10px;
}
@media (min-width:800px) {
    #checkout-content {
        grid-template-columns: minmax(240px, 1fr) minmax(360px, 1fr);
    }
    #checkout-content > div:nth-child(3) {grid-column: span 2;}
}
@media (min-width:900px) {
    #checkout-content {
        grid-template-columns: minmax(240px, 3fr) minmax(240px, 4fr) minmax(160px, 2fr);
    }
    #checkout-content > div:nth-child(3) {grid-column: span 1;}
}
#checkout-content > div {
    border: 1px solid #ccc;
    padding: 10px;
    background: white;
    height: auto;
    line-height: 2;
    width:100%;
}
.cc-title {
    font-weight: bold;
    display: block;
    font-size: 1.2em;
    margin-bottom: 12px;
}
.cart-table td{padding:0 2px;}
label{font-weight:bold;}
</style>

<div id="checkout-content" class="">

<div class="gl-4"><span class="cc-title"><i class="fa fa-map-marker"></i> <?=__('your_data')?></span>

    <label><?=__('_add_receiver_name')?>:</label> <?=$add_receiver?><br>
    <label><?=__('_add_address')?>:</label> <?=$add_address?><br>
    <label><?=__('_add_reference')?>:</label> <?=$add_reference?><br>
    <label><?=__('_add_pc')?>:</label> <?=$add_pc?><br>
    <label><?=__('City')?>:</label> <?=$add_city?><br>
    <label><?=__('_add_tel')?>:</label> <?=$add_phone?><br>
    <label><?=__('Email')?>:</label> <?=$add_email?><br>
    <label><?=__('Shipping Method')?>:</label> <?=$g->shipping_methods[$add_shipping_method]['description']?><br>
    <br><a class="g-btn btn-warning" href="<?=gila::url("shop/address")?>"><?=__('review_data')?></a>
</form>
</div>

<div class="gl-4"><span class="cc-title"><i class="fa fa-shopping-cart"></i> <?=__('Cart')?></span>
    <table class="table cart-table" style="width:100%;font-size:90%;line-height:1">
        <tr>
            <th colspan=2><?=__('Product')?>
            <th><?=__('Total')?>
<?php
$slugify = new Cocur\Slugify\Slugify();
$total = 0;
$delivery_cost = $g->shipping_methods[$add_shipping_method]['cost'];

foreach ($product as $kid=>$p) {
    $imgsrc = view::thumb_sm($p['image']);
    //$addurl = gila::url("shop/cart")."?add={$p['id']}&qty=";
    $total += $p['qty']*$p['price'];
?>
        <tr>
            <td style="width:80px"><img src="<?=$imgsrc?>" style="max-height:120px;width:100%;" />
            <td><?=$p['qty']?> x <?=$p['title']?>
            <td><?=($p['qty']*$p['price'])?>&nbsp;<?=gila::option('shop.currency')?>

<?php } ?>
        <tr>
            <td style="text-align:right; font-weight:bold;" colspan="2"><?=__('Total')?>
            <td><?=$total?>&nbsp;<?=gila::option('shop.currency')?>
    </table>
    <a class="g-btn btn-warning" href="<?=gila::url("shop/cart")?>"><?=__('review_cart')?></a>
</div>

<div class="gm-4"><span class="cc-title"><i class="fa fa-money"></i> <?=__('Payment')?></span>
    <label><?=__('Products')?>:</label> <?=$total?>&nbsp;<?=gila::option('shop.currency')?><br>
    <label><?=__('shipping_cost')?>:</label> <?=$delivery_cost?>&nbsp;<?=gila::option('shop.currency')?><br>
    <label><?=__('total_to_pay')?>:</label> <?=($total+$delivery_cost)?>&nbsp;<?=gila::option('shop.currency')?><br>
    <a class="fullwidth g-btn btn-success" href="<?=gila::url("shop/placedorder")?>"><?=__('Checkout')?></a>
</div>

</div>
