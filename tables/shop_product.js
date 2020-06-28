
gtableTool.discount = {
  fa: "plus", label: g.tr("Price")+" %",
  fn: function(table) {
    let _this = table
    x = prompt("Set discount", 0);
    if(x===null) return;

    g.loader()
    g.post('cm/setDiscount/'+table.name+'?id='+table.selected_rows.join(),{discount:x}, function(data){
      data = JSON.parse(data)
      g.loader(false)
      g.alert(data.message, data.success?'success':'error')
      _this.load_page();
      _this.selected_rows = []
    })
  }
}
