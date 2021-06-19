<?php
require_once("../procesos/libreriaSII/xmlseclibs/XmlseclibsAdapter.php");
require_once("../procesos/libreriaSII/SII.php");
date_default_timezone_set('America/Santiago');

session_start();
$conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
$rangos = explode(",",$_POST["folios"]);
$respuesta ="<table class='table table-striped table-bordered table-hover ui-datatable'>";

foreach($rangos as $folio)
{    
    if(strpos($folio,"-")>0)
	{
        $dteRango = explode("-",$folio);
		
        
        for($i=$dteRango[0];$i<=$dteRango[1];$i++)
		{
            $sql="select dte_receptor_rut,dte_fecha_emision,dte_monto_total from sis_bitacora where sis_contribuyente_id=".$_SESSION["contribuyente"]." and "
            . "dte_tipo=".$_POST["tipoDte"]." and dte_folio=$i";
    
            $query = $conn->query($sql);
            $data=$query->fetch_assoc();

            $model["RutEmisor"]     = $_SESSION["rut"];
            $model["RutConsultante"]      = $_SESSION["rut_rl"];
            $model["RutCompania"]     = $_SESSION["rut"];
            $model["Rutreceptor"]     = $data["dte_receptor_rut"];
            $model["TipoDte"]   = $_POST["tipoDte"];
            $model["FolioDte"]       = $i;
            $model["FechaEmisionDte"]      = date("dmY",$data["dte_fecha_emision"]);
            $model["MontoDte"] = $data["dte_monto_total"];
			$model["contribuyente"] = $_SESSION["contribuyente"];
            $model["clave"] = $_SESSION['clave'];
            $model["certificado"] = $_SESSION['certificado'];                     
            $respuesta.= QueryEstDte($model);
        }
    }
	else
	{
        $sql="select dte_receptor_rut,dte_fecha_emision,dte_monto_total from sis_bitacora where sis_contribuyente_id=".$_SESSION["contribuyente"]." and "
            . "dte_tipo=".$_POST["tipoDte"]." and dte_folio=$folio";
    
        $query = $conn->query($sql);
        $data=$query->fetch_assoc();
    
        $model["RutEmisor"]     = $_SESSION["rut"];
        $model["RutConsultante"]      = $_SESSION["rut_rl"];
        $model["RutCompania"]     = $_SESSION["rut"];
        $model["Rutreceptor"]     = $data["dte_receptor_rut"];
        $model["TipoDte"]   = $_POST["tipoDte"];
        $model["FolioDte"]       = $folio;
        $model["FechaEmisionDte"]      = date("dmY",$data["dte_fecha_emision"]);
        $model["MontoDte"] = $data["dte_monto_total"];
        $model["contribuyente"] = $_SESSION["contribuyente"];
         $model["clave"] = $_SESSION['clave'];
            $model["certificado"] = $_SESSION['certificado'];     
        $respuesta.= QueryEstDte($model);
    }
}
echo $respuesta."</table>";
