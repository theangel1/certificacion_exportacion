<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/procesos/libreriaSII/xmlseclibs/XmlseclibsAdapter.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/procesos/libreriaSII/SII.php';
date_default_timezone_set('America/Santiago');
ini_set('error_reporting', E_ERROR);
ini_set('display_errors',1);

$conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
$model["RutConsultante"] = $_SESSION["rut_rl"];
$model["RutEmisor"]=$_SESSION["rut"];
$model["trackid"]=$_POST["trackid"];

$respuesta = explode("|",QueryEstUp($model));


if($respuesta[0]==0)
{
    $sql="update sis_bitacora set estado_sii='".$respuesta[3]."' where dte_track_id='".$_POST["trackid"]."'";
	error_log($sql, 3, "errAngel");
    $conn->query($sql);
    echo '[{"ERROR":"0","MENSAJE":"'.$respuesta[3].'"}]';
	
}
else
{
	error_log("en else", 3, "errAngel");
    echo '[{"ERROR":"1","MENSAJE":"'.$respuesta[1].'"}]';
}

