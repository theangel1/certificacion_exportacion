<?php
require_once '../modelos/Exportacion.php';
session_start();
$exportacion = new Exportacion();

$idContribuyente = $_SESSION['contribuyente'];
$folio = isset($_POST['folio'])? trim($_POST['folio']):"";
$tipoDoc = isset($_POST['tipoDoc']) ? trim($_POST['tipoDoc']):"";
$idexportacion = isset($_POST['idexportacion'])? trim($_POST['idexportacion']):"";


switch($_GET["op"])
{
    case 'mostrar':
        $rspta = $exportacion->mostrar($tipoDoc,$folio, $idContribuyente);
        echo json_encode($rspta);
    break;
    
    case 'mostrarDetalle':
        $rspta = $exportacion->loadDetalle($idexportacion);        
        echo json_encode($rspta);        
    break;
    
    case 'mostrarReferencia':
        $rspta = $exportacion->loadReferencia($idexportacion);
        echo json_encode($rspta);        
    break;

    case 'mostrarDscRec':
        $rspta = $exportacion->loadDscRcg($idexportacion);
        echo json_encode($rspta);        
    break;

}