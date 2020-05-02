<link rel="stylesheet" type="text/css" href="lib/pnk/pnk.css?">
<style>.pnk-edit .update-div{width:48%}.g-input{min-width:240px}</style>

<div class="pnk-edit" style="display: block;" tid="pnk-t1">
  <h3 class="pnk-edit-title"><?=__('Order')?> #<?=$o['id']?></h3>
  <hr>
  <div class="update-div" col="add_receiver">
    <label class="g-label" for="add_receiver"><?=__('_add_receiver_name')?></label>
    <input disabled class="g-input" value="<?=$o['add_receiver']?>" type="text">
  </div>
  <div class="update-div" col="user_id">
    <label class="g-label" for="user_id"><?=__('User')?>#</label>
    <input disabled class="g-input" value="<?=$o['user_id']?>" >
  </div>
  <div class="update-div" col="status">
    <label class="g-label" for="status"><?=__('Status')?></label>
    <select disabled class="g-input pnk-select" tabindex="-1" aria-hidden="true">
      <option value="new" <?=($o['status']=='new'?'selected':'')?>>New</option>
      <option value="paid" <?=($o['status']=='paid'?'selected':'')?>>Paid</option>
      <option value="process" <?=($o['status']=='proccess'?'selected':'')?>>Process</option>
      <option value="delivered" <?=($o['status']=='delivered'?'selected':'')?>>Delivered</option>
      <option value="canceled" <?=($o['status']=='canceled'?'selected':'')?>>Canceled</option>
    </select>
  </div>
  <div class="update-div" col="add_city">
    <label class="g-label" for="add_city"><?=__('City')?></label>
    <input disabled class="g-input" value="<?=$o['add_city']?>" type="text">
  </div>
  <div class="update-div" col="add_shipping_method">
    <label class="g-label" for="add_shipping_method"><?=__('Shipping Method')?></label>
    <input disabled class="g-input" value="<?=$o['add_shipping_method']?>" type="text">
  </div>
  <div class="update-div" col="add_address">
    <label class="g-label" for="add_address"><?=__('_add_address')?></label>
    <input disabled class="g-input" value="<?=$o['add_address']?>" type="text">
  </div>
  <div class="update-div" col="add_reference">
    <label class="g-label" for="add_reference"><?=__('_add_reference')?></label>
    <input disabled class="g-input" value="<?=$o['add_reference']?>" type="text">
  </div>
  <div class="update-div" col="add_pc">
    <label class="g-label" for="add_pc"><?=__('_add_pc')?></label>
    <input disabled class="g-input" value="<?=$o['add_pc']?>" type="text">
  </div>
  <div class="update-div" col="add_phone">
    <label class="g-label" for="add_phone"><?=__('_add_tel')?></label>
    <input disabled class="g-input" value="<?=$o['add_phone']?>" type="text">
  </div>
  <div class="update-div" col="add_email">
    <label class="g-label" for="add_email">Email</label>
    <input disabled class="g-input" value="<?=$o['add_email']?>" type="text">
  </div>
</div>

<div class='pnk-table' pnk-src='src/shop/tables/shop_orderitem' pnk-table="shop_orderitem" id='tshoporderitem' filters="order_id=<?=$o['id']?>"></div>

<?=view::script('lib\jquery\jquery-3.3.1.min.js')?>
<?=view::script('lib\pnk\pnk-1.4.js')?>
<script>
pnk_populate_tables(document);
</script>
