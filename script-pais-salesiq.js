/* Captura pais mediante SalesIQ*/
$zoho.salesiq.ready=function()
 {   
    $zoho.salesiq.visitor.getGeoDetails();
}

$zoho.salesiq.afterReady=function(visitorgeoinfo)
{

 var elementGeoPais= document.getElementById("geo_pais");
    if(elementGeoPais == null){}else{document.getElementById("geo_pais").value = visitorgeoinfo.Country;}
//   //console.log();

}
