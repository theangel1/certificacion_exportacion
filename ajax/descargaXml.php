<?php
$path = $_SERVER['DOCUMENT_ROOT'].'/procesos/xml_envios/'.$_POST["carpeta"].'/';
$filename = $_POST["folio"];
if(is_file($path.$filename))
{
    header('Content-Type: application/xml;');
    header('Content-Disposition: attachment; filename='.$filename.';');
    readfile($path.$filename);
}
else
{
    echo "Can't read the file ".$filename . " y el path: ".$path;
    echo "<br>Ur echo: ".$path.$filename;
    echo "<br>Ur echo del nombre: ".$filename;
    exit(500);
}