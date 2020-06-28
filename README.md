# shop-package
A package to built a simple eshop

### Events
**placeOrder** Creates order when client have finished the order. The cart session has to be closed here.


### Global Lists
**shop_pay_gateway** Renders an alternative page for payment type. It can be selected in a payment method.
```
Gila::addList('pay-on', function($orderId){
  View::renderFile('pay-on.php', 'pay-on');
});
```
