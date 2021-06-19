<?php
require_once("../procesos/libreriaSII/xmlseclibs/XmlseclibsAdapter.php");
require_once("../procesos/libreriaSII/SII.php");
date_default_timezone_set('America/Santiago');


session_start();
$conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
$rangos = explode(",",$_POST["folios"]);
$respuesta ="<table class='table table-striped table-bordered table-hover ui-datatable'>";
foreach($rangos as $folio){
    
    if(strpos($folio,"-")>0){
        $dteRango = explode("-",$folio);
        
    //    for($i=$dteRango[0];$i<=$dteRango[1];$i++){
         
    //    }
    }else{
        $model["contribuyente"] = $_SESSION["contribuyente"];
            $model["clave"] = $_SESSION['clave'];
            $model["certificado"] = $_SESSION['certificado'];  
         $model["RutEmisor"] = $_SESSION["rut"];
            $model["RutConsultante"]      = $_SESSION["rut_rl"];
            $model["tipodoc"]   = 33;//$_SESSION["rut_rl"];
            $model["foliodoc"]  = $folio;
            $respuesta.= QueryEstCesion($model);
    }
}
    
echo $respuesta."</table>";