<?php
//Asi es, la clase deberia ir con Mayuscula al principio ....
session_start();
$conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
//exit("\$ret=".$_REQUEST["func"]."(".$_REQUEST["par"].");");
eval("\$ret=".$_REQUEST["func"]."(".$_REQUEST["par"].");");
echo $ret;

function BorraCesion($id)
{
    global $conn;
    $sql="delete from sis_dte_cesion where sis_dte_ces_id=".$id;
    $query=$conn->query($sql);
    $ar = $conn->affected_rows;
    if($ar>0)
        $respuesta ='[{"ERROR":"0","MENSAJE":"La cesión fue eliminada con éxito"}]';
    else
        $respuesta ='[{"ERROR":"1","MENSAJE":"La cesión no pudo ser eliminada, intente mas tarde.<br>Si el problema persiste comuniquese con soporte.<br><br>COD:CNE00029"}]';    

    return $respuesta;

}