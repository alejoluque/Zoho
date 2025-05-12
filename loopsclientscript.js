var data = ZDK.Page.getSubform('Quoted_Items').getValues();

data.forEach(function(item, index) {
    var idProductos = item.Product_Name.id;
    var producto = ZDK.Apps.CRM.Products.fetchById(idProductos);
    var categoria = producto.Tipo_de_producto;

    if (categoria != "Otros") { 
        var cell_obj = ZDK.Page.getSubform('Quoted_Items').getRow(index).getCell('List_Price');
        cell_obj.setReadOnly(true);
    }
    
    log("Producto : " + idProductos);
});
