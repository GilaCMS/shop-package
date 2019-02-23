<style>
label {margin-top:15px}
.delivery-options>label{
  width:100%;
  position: relative;
}
.delivery-options>label>div{
  padding: 10px;
  margin:3px;
  width:80%;
  vertical-align:middle;
  font-weight: normal;
  padding-left: 30px;
}
.delivery-options  img{
  max-height:50px;
  width:auto;
}
.delivery-options input[type=radio]{
  position:absolute;
  left:15px;
  top:30px;
  display:none;
}
.delivery-options input[type=radio]+div{
  border: 2px solid #d8d8d8;
  border-radius: 6px;
}
.delivery-options input[type=radio]:checked+div{
  border: 2px solid #4ff545;

}
.g-label,.g-input{width:90%;display:inline-block;margin: 1px 0}

</style>

<div class="col-md-12">
<div class="col-sm-10 col-sm-offset-1 col-sm-12 wrapper" style="background:white">
  <h2><?=__('your_data')?></h2>
<form id="addressSelect" name="addressSelect" method="post" action="<?=gila::make_url("shop","checkout")?>" class="g-card wrapper">
  <div class="row">
    <div class="col-md-6 gm-6">
      <label class="g-label"><?=__('_add_receiver_name')?>: </label><input class="form-control g-input" value="<?=$add_receiver?>" name="add_receiver" type="text" required>
    </div>
    <div class="col-md-6 gm-6">
      <label class="g-label"><?=__('_add_tel')?>: </label><input class="form-control g-input" value="<?=$add_phone?>" name="add_phone" maxlength="20" type="tel" required>
    </div>
    <div class="col-md-6 gm-6">
      <label class="g-label"><?=__('Email')?>: </label><input class="form-control g-input" value="<?=$add_email?>" name="add_email" maxlength="120" type="email" required>
    </div>
    <div class="col-md-6 gm-6">
      <label class="g-label"><?=__('_add_address')?>: </label><input class="form-control g-input" value="<?=$add_address?>" name="add_address" maxlength="200" type="text" required>
    </div>
    <div class="col-md-6 gm-6">
      <label class="g-label"><?=__('_add_reference')?>: </label><input class="form-control g-input" value="<?=$add_reference?>" name="add_reference" placeholder="<?=__('_add_reference_placeholder')?>" maxlength="200" type="text">
    </div>
    <div class="col-md-6 gm-6">
      <label class="g-label"><?=__('_add_pc')?>: </label><input class="form-control g-input" value="<?=$add_pc?>" name="add_pc" maxlength="5" type="text" required>
    </div>
    <div class="col-md-6 gm-6">
      <label class="g-label"><?=__('City')?>: </label><br><input class="form-control g-input" value="<?=$add_city?>" name="add_city" maxlength="80" type="text" required>
    </div>

    <div class="col-md-6 gm-6">
      <label class="g-label"><?=__('Shipping Method')?>:</label>
      <select class="form-control g-input" name="add_shipping_method" required>
      <?php
      $total = 0;
      foreach ($product as $kid=>$p) {
        $total += $p['qty']*$p['price'];
      }
        foreach($c->shipping_methods as $key=>$dt) {
          if($add_shipping_method == $key) $selected="selected"; else $selected="";
          echo "<option value=\"{$dt['id']}\" $selected>";
          echo $dt['description'];
          $cost = $dt['cost'];
          
          if($cost > 0) {
            if($dt['freeafter']==0 || $dt['freeafter']>$total)
              echo ' <strong>+'.$dt['cost'].' '.gila::option('shop.currency').'</strong>';
            else
              echo " <strong>".__('Free')."</strong>";
          }
          echo "</option>";
        }
      ?>
      </select>
    </div>

  </div>
  <input name="submit_address" type="hidden">
  <div class="gm-12" style="text-align:right">
    <br><input class="g-btn btn-primary" id="checkout_btn" type="submit" value="<?=__('continue_to_checkout')?>">
  </div>
</form>
</div>
</div>
