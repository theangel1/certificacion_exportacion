<?php
session_start();
$conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");

$sql="update sis_contribuyente set sis_contribuyente_razon='".$_POST["razon"]."',sis_contribuyente_fantasia='".$_POST["fantasia"]."',"
        . "sis_contribuyente_giro='".$_POST["giro"]."',sis_contribuyente_direccion='".$_POST["direccion"]."',"
        . "sis_contribuyente_telefono".$_POST["fono"]."',sis_contribuyente_email='".$_POST["email"].","
        . "sis_contribuyente_representante='".$_POST["representante"]."',sis_contribuyente_rutrl='".$_POST["rut"]."' "
        . "where sis_contribuyente_id=".$_SESSION["contribuyente"];
$conn->query($sql);
if($conn->affected_rows>0){
    $respuesta ='[{"ERROR":"0","RAZON":"Los datos se actualizaron correctamente"}]';
}else{
    $respuesta ='[{"ERROR":"1","RAZON":"Ocurrio un error al tratar de actualizar los datos "}]';
}
echo $respuesta;