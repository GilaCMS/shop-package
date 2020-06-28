<style>
.cart-table td {vertical-align: middle; padding:4px}
.cart-table th:nth-child(3),.cart-table th:nth-child(4),.cart-table th:nth-child(5),
.cart-table td:nth-child(3),.cart-table td:nth-child(4),.cart-table td:nth-child(5){
  display:flex;
}
.cart-table{border-right: 1px solid var(--main-border-color)}
.cart-table tr{border: 1px solid var(--main-border-color)}
@media (min-width:400px) {
  .cart-table th:nth-child(3),.cart-table th:nth-child(4),.cart-table th:nth-child(5),
  .cart-table td:nth-child(3),.cart-table td:nth-child(4),.cart-table td:nth-child(5){
    display:table-cell;
  }
}
.cart-table th:nth-child(5),.cart-table td:nth-child(5){border:auto;}
</style>

<div class="wrapper" style="background:white">
  <h2><?=__('Cart')?></h2>
  <form id="reviewCart" action="<?=Gila::url("shop/cart")?>" method="post">
  <table class="g-table bordered cart-table" >
    <tr>
      <th>
      <th class="fullwidth"><?=__("Product")?>
      <th><?=__("Price")?>
      <th><?=__("Units")?>
      <th><?=__("Total")?>
<?php
$total = 0;
$currency = Gila::option('shop.currency',' EUR');
foreach ($product as $kid=>$p) {
  $imgsrc = View::thumb_sm($p['image']);
  $total += $p['qty']*$p['price'];
  $tdprice = $p['price'].'&nbsp;'.$currency;
  $p['url'] = 'shop/product/'.$p['id'].'-'.$kid;
?>
    <tr>
      <td><a class="removebtn g-btn btn-white" href="<?=Gila::make_url("shop","cart")."?remove=".$kid?>"><i class="fa fa-remove"></i></a>
      <td style="max-width:100px;"><img src="<?=$imgsrc?>" style="max-height:120px;vertical-align:middle" />
        <span><a href="<?=$p['url']?>"><?=$p['title']?></a></span>
      <td><?=$tdprice?>
      <td>
        <span>
        <input name="_product[<?=$kid?>]" class="g-input" type="number" value="<?=$p['qty']?>" style="width:60px"></input>
        </span>
      <td><?=($p['qty']*$p['price'])?>&nbsp;<?=$currency?>

<?php } ?>
    <tr>
      <td style="text-align:right; font-weight:bold;" colspan="2"><?=__("Total")?>
      <td style="text-align:right;" colspan="3"><?=$total?>&nbsp;<?=$currency?>
  </table>
  <hr>
  <div style="text-align:right">
    <input type="submit" class="g-btn btn-white" type="button" value="<?=__("Update Cart")?>">
  </div>
  <div style="text-align:right">
    <br>
    <a class="g-btn btn-white" href="<?=Gila::url("shop")?>"><?=__("Continue Shopping")?></a>
    <a class="g-btn btn-primary" href="<?=Gila::url("shop/address")?>"><?=__("continue_to_checkout")?></a>
  </div>
</form>
</div>

<script>
  history.pushState({},'cart',"<?=Gila::url("shop/cart")?>");
</script>
