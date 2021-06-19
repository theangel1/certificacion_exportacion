<?php
//Asi es, la clase deberia ir con Mayuscula al principio ....
session_start();
$conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
ini_set('memory_limit', '512m');
ini_set('max_execution_time','1800');
ini_set('set_time_limit' , '3600');

error_reporting(E_ALL);
ini_set('error_reporting', E_ERROR);
ini_set('display_errors',1);

eval("\$ret=tabla".$_REQUEST["func"]."(".$_REQUEST["par"].");");
echo $ret;


function tablaRespuesta(){
    global $conn;
    $sql="select sis_dte_tipo,sis_dte_emisor_rut,sis_dte_folio,sis_dte_emisor_id,ref_respuesta_id,sis_dte_tipo,mid(`sis_dte_nombre_file`,44) as sis_dte_nombre_file,"
        ."sis_dte_emisor_razon,sis_dte_fecha_emision,sis_dte_emisor_razon,sis_dte_monto from sis_dte where sis_contribuyente_id=".$_SESSION["contribuyente"]
        ." and year(sis_dte_fecha_emision)>2015 order by sis_dte_fecha_recepcion desc";
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	}
    $query = $conn->query($sql);
    $data='{"data":[';
    while ($dte= $query->fetch_assoc()){
        switch ($dte["sis_dte_tipo"]){
            case 33:
                $documento="Factura Electr&oacute;nica";
                break;
            case 34:
                $documento="Factura Exenta Electr&oacute;nica";
                break;
            case 61:
                $documento="Nota de Cr&eacute;dito Electr&oacute;nica";
                break;
            case 56:
                $documento="Nota de D&eacute;dito Electr&oacute;nica";
                break;
            case 52:
                $documento="Gu&iacute;a de Despacho Electr&oacute;nica";
                break;
        }
        $arc="/home/netdte/www/documentos/recepcionados/".$dte["sis_dte_nombre_file"];
  
        $ul="<ul class=dropdown-menu role=menu>"
            . "<li><a data-toggle=modal href=#acuseModal>Acuse Recibo</a></li>";
        		
		if(file_exists($arc)){			
            $ul .= "<li><a data-tipo='COMPRA' data-file='".$dte["sis_dte_nombre_file"]."' data-folio='".$dte["sis_dte_folio"]."' id='btn-pdf'>Ver PDF</a></li>"
            . "<li><a target='xmlView' href='../documentos/recepcionados/".$dte["sis_dte_nombre_file"]."'>Ver XML</a></li>"
            . "</ul>";
        }
        
        switch ($dte["ref_respuesta_id"]){
            case 1:
                $select="Aceptado Ley 19.983";
                break;
            case 2:
                $select="Aceptado";
                break;
            case 3:
                $select="Aceptado con reparo";
                break;
            case 4:
                $select="Rechazado";
                break;
            case 99:
                $select="Recibida";
                break;
        }

        $data2.='["'.date("Ymd",  strtotime($dte["sis_dte_fecha_emision"])).'","'.$dte["sis_dte_emisor_razon"].'","'.$documento.'","'.$dte["sis_dte_folio"].'","'
                .date("d-m-Y",  strtotime($dte["sis_dte_fecha_emision"])).'","'.$dte["sis_dte_monto"].'","'.$dte["sis_dte_emisor_rut"].'","'.$dte["sis_dte_tipo"].'","'.$select.'",'
                .'"<div class=\"btn-group\"><button class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">Opciones<span class=caret></span></button>'.$ul.'</div>","'.$input.'"],';
    }
    $data.=substr($data2,0,-1)."]}";
    return $data;
}

function tablaEmitidos(){
    global $conn;
    session_start();
    $carpeta=substr($_SESSION["rut"],0,-2);
    
    
    $sql="select dte_tipo,dte_folio,dte_fecha_envio,dte_fecha_emision,dte_receptor_rut,"
            . "dte_receptor_direccion,dte_receptor_razon,dte_monto_total,max(dte_trackid) as dte_trackid , "
            . "dte_estado_sii,dte_estado_detalle,sis_cedida from sis_bitacora use index (CONTRIBUYENTE) "
            . "where sis_contribuyente_id=".$_SESSION["contribuyente"]." "
            . "group by dte_tipo,dte_folio order by dte_tipo,dte_folio ";
    
    $sql="select a.dte_tipo,a.dte_folio,a.dte_fecha_envio,a.dte_fecha_emision,a.dte_receptor_rut,
            a.dte_receptor_direccion,a.dte_receptor_razon,a.dte_monto_total,a.dte_trackid, 
            a.dte_estado_sii,a.dte_estado_detalle,a.sis_cedida,a.dte_archivo_xml,dte_estado_comercial from sis_bitacora a use index(CONTRIBUYENTE) 
            inner join(select max(dte_trackid) as dte_trackid from sis_bitacora use index(CONTRIBUYENTE)  where sis_contribuyente_id=".$_SESSION["contribuyente"]." group by dte_folio) max on a.dte_trackid=max.dte_trackid
            where sis_contribuyente_id=".$_SESSION["contribuyente"]." group by dte_tipo,dte_folio order by dte_tipo,dte_folio ";
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
	$query = $conn->query($sql);

    
    $data='{"data":[ ';
    while ($dte=$query->fetch_assoc()){
        if(empty($dte["dte_archivo_xml"])){
            $arc="../documentos/emitidos/".$carpeta."/dte/T".$dte["dte_tipo"]."_F".$dte["dte_folio"].".xml";
            $aPdf="T".$dte["dte_tipo"]."_F".$dte["dte_folio"].".xml";
        }else{
            $arc="../documentos/emitidos/".$carpeta."/dte/".$dte["dte_archivo_xml"];
            $aPdf=$dte["dte_archivo_xml"];
        }
       
        if(file_exists($arc)){
            if($dte["sis_cedida"]==0){
                $ul="<ul class=dropdown-menu role=menu>"
                . "<li><a data-tipo='VENTA' data-file='".$aPdf."' data-folio='".$dte["dte_folio"]."' id='btn-pdf-venta'>Ver PDF</a></li>"
				//. "<li><a target='pdfView' href='viewPdf.php?tipo=VENTA&f=".$aPdf."'>Ver PDF</a></li>"
                . "<li><a data-toggle=modal href='#trazaModal'>Ver Traza</a></li>"
                . "<li><a data-monto='".$dte["dte_monto_total"]."' data-toggle=modal href='#cesionModal'>Ceder Documento</a></li>"
                . "<li><a data-toggle=modal href='#reenvioModal'>Verificaci&oacute;n SII</a></li>"
                . "<li><button type='button' class='btn btn-info' id='btn-down-xml' data-carpeta='".$carpeta."' data-tipo='".$dte["dte_tipo"]."' data-folio='".$dte["dte_archivo_xml"]."'>Descargar XML</button></li>"
                . "</ul>";
            }else{
                $ul="<ul class=dropdown-menu role=menu>"
                . "<li><a data-tipo='VENTA' data-file='".$aPdf."' data-folio='".$dte["dte_folio"]."' id='btn-pdf-venta'>Ver PDF</a></li>"
                . "<li><a data-toggle=modal href='#trazaModal'>Ver Traza</a></li>"
                . "<li><a data-toggle=modal href='#reenvioModal'>Verificaci&oacute;n SII</a></li>"
                . "<li><button type='button' class='btn btn-info' id='btn-down-xml' data-carpeta='".$carpeta."' data-tipo='".$dte["dte_tipo"]."' data-folio='".$dte["dte_archivo_xml"]."'>Descargar XML</button></li>";
                if(file_exists($arc)){
                    $ul .= "<li><a target='xmlView' href='../documentos/recepcionados/".$dte["sis_dte_nombre_file"]."'>Ver XML</a></li>";
                }
                $ul .="</ul>";
            }
            switch ($dte["dte_tipo"]){
                case 33:
                    $documento="Fact. Electr&oacute;nica";
                    break;
                case 34:
                    $documento="Fact. Ex. Electr&oacute;nica";
                    break;
                case 43:
                    $documento="Liq. Fact. Electr&oacute;nica";
                    break;
                case 61:
                    $documento="Nota Cr&eacute;d. Elect.";
                    break;
                case 56:
                    $documento="Nota de D&eacute;d. Elect.";
                    break;
                case 52:
                    $documento="Gu&iacute;a Desp. Elect.";
                    break;
            }
            if($dte["dte_receptor_rut"]==""){
                $DTE = new DOMDocument();
                $DTE->formatOutput = FALSE;
                $DTE->preserveWhiteSpace = TRUE;
                $DTE->encoding = "ISO-8859-1";
                #error_log($arc);
                error_log("------------",0);
                $DTE->load($arc);

                $Rut= $DTE->getElementsByTagName("RUTRecep")->item(0)->nodeValue;
                $Direccion = $DTE->getElementsByTagName("DirRecep")->item(0)->nodeValue;
                $Cliente = $DTE->getElementsByTagName("RznSocRecep")->item(0)->nodeValue;
                $Monto= $DTE->getElementsByTagName("MntTotal")->item(0)->nodeValue;
                $Estado=$dte["dte_estado_sii"];
                $RutReceptor=$DTE->getElementsByTagName("RUTRecep")->item(0)->nodeValue;
                $FecEmsion=$DTE->getElementsByTagName("FchEmis")->item(0)->nodeValue;
                $sql="update sis_bitacora set dte_fecha_emision='".$FecEmsion."',"
                        . "dte_receptor_rut='".$Rut."',dte_receptor_razon='".$Cliente."',"
                        . "dte_receptor_direccion='".$Direccion."',dte_monto_total='".$Monto."'"
                        . " where sis_contribuyente_id=".$_SESSION["contribuyente"]
                        . " and dte_tipo=".$dte["dte_tipo"]." and dte_folio=".$dte["dte_folio"];
                $query2 = $conn->query($sql);
            }else{
                $Rut= $dte["dte_receptor_rut"];
                $Direccion = htmlentities(utf8_encode($dte["dte_receptor_direccion"]));
                $Cliente = htmlentities(utf8_encode($dte["dte_receptor_razon"]));
                $Monto= $dte["dte_monto_total"];
                $Estado=$dte["dte_estado_sii"];
                $RutReceptor=$dte["dte_receptor_rut"];
                $FecEmsion=$dte["dte_fecha_emision"];
            }
    //        $data.='["'.$dte["sis_dte_emisor_razon"].'","'.$documento.'","'.$dte["sis_dte_folio"].'","'
    //                .date("d-m-Y",  strtotime($dte["sis_dte_fecha_emision"])).'","'.$dte["sis_dte_monto"].'",'
    //                .'"'.$select.'","'.$input.'"],';
            $estadoSii=str_replace(array("\"","'"),array("&quot",""),substr($dte["dte_estado_detalle"],0,45));
            if(strlen($estadoSii)>41){
                $estadoSii.='...';
            }
            $estadoCliente=substr($dte["dte_estado_comercial"],0,30);
//            $data.='["'.$dte["dte_tipo"].'","'.$Rut.'","'.$Direccion.'","'.$Cliente.'","'.$documento.'","'.$dte["dte_folio"].'","'
//                    .date("d-m-Y",  strtotime($FecEmsion)).'","$'.number_format($Monto).'.-","'.$dte["dte_trackid"].''
//                    . '","'.$dte["dte_estado_sii"].'","'.$estadoSii.'","'.$estadoCliente.'","<div class=\"btn-group\"><button class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">Opciones<span class=caret></span></button>'.$ul.'</div>","'.$RutReceptor.'"],';
            $data.='["'.$dte["dte_tipo"].'","'.$Rut.'","'.$Direccion.'","'.$Cliente.'","'.$documento.'","'.$dte["dte_folio"].'","'
                    .date("d-m-Y",  strtotime($FecEmsion)).'","'.$dte["dte_trackid"].''
                    . '","'.$dte["dte_estado_sii"].'","'.$estadoCliente.'","<div class=\"btn-group\"><button class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">Opciones<span class=caret></span></button>'.$ul.'</div>","'.$RutReceptor.'","'.$dte["dte_monto_total"].'"],';
        }else{
            if($dte["sis_cedida"]==0){
                $ul="<ul class=dropdown-menu role=menu>"
				//flag
                //. "<li><a target='pdfView' href='viewPdf.php?tipo=VENTA&f=".$aPdf."'>Ver PDF</a></li>"
				. "<li><a data-tipo='VENTA' data-file='".$aPdf."' data-folio='".$dte["dte_folio"]."' id='btn-pdf-venta'>Ver PDF</a></li>"
                . "<li><a data-toggle=modal href='#trazaModal'>Ver Traza</a></li>"
                . "<li><a data-toggle=modal href='#cesionModal'>Ceder Documento</a></li>"
                . "<li><a data-toggle=modal href='#reenvioModal'>Verificaci&oacute;n SII</a></li>"
                . "<li><button type='button' class='btn btn-info' id='btn-down-xml' data-carpeta='".$carpeta."' data-tipo='".$dte["dte_tipo"]."' data-folio='".$dte["dte_archivo_xml"]."'>Descargar XML</button></li>"
                . "</ul>";
            }else{
                $ul="<ul class=dropdown-menu role=menu>"
                . "<li><a data-toggle=modal href=#reenvioModal>Reenvio Correo</a></li>";
                if(file_exists($arc)){
                    $ul .= "<li><a target='xmlView' href='../documentos/recepcionados/".$dte["sis_dte_nombre_file"]."'>Ver XML</a></li>"
                    . "</ul>";
                }
            }
            switch ($dte["dte_tipo"]){
                case 33:
                    $documento="Fact. Electr&oacute;nica";
                    break;
                case 34:
                    $documento="Fact. Ex. Electr&oacute;nica";
                    break;
                case 61:
                    $documento="Nota Cr&eacute;d. Elect.";
                    break;
                case 56:
                    $documento="Nota de D&eacute;d. Elect.";
                    break;
                case 52:
                    $documento="Gu&iacute;a Desp. Elect.";
                    break;
            }
            
            $Rut= $dte["dte_receptor_rut"];
            $Direccion = htmlentities(utf8_encode($dte["dte_receptor_direccion"]));
            $Cliente = htmlentities(utf8_encode($dte["dte_receptor_razon"]));
            $Monto= doubleval($dte["dte_monto_total"]);
            $Estado=$dte["dte_estado_sii"];
            $RutReceptor=$dte["dte_receptor_rut"];
            $FecEmsion=$dte["dte_fecha_emision"];
            $estadoSii=str_replace(array("\"","'"),array("&quot",""),substr($dte["dte_estado_detalle"],0,45));
            if(strlen($estadoSii)>41){
                $estadoSii.='...';
            }
            $estadoCliente=substr($dte["dte_estado_comercial"],0,30);
//            $data.='["'.$dte["dte_tipo"].'","'.$Rut.'","'.$Direccion.'","'.$Cliente.'","'.$documento.'","'.$dte["dte_folio"].'","'
//            .date("d-m-Y",  strtotime($FecEmsion)).'","$'.number_format($Monto).'.-","'.$dte["dte_trackid"].''
//            . '","'.$dte["dte_estado_sii"].'","'.$estadoSii.'","'.$estadoCliente.'","<div class=\"btn-group\"><button class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">Opciones<span class=caret></span></button>'.$ul.'</div>","'.$RutReceptor.'"],';
            $data.='["'.$dte["dte_tipo"].'","'.$Rut.'","'.$Direccion.'","'.$Cliente.'","'.$documento.'","'.$dte["dte_folio"].'","'
                    .date("d-m-Y",  strtotime($FecEmsion)).'","'.$dte["dte_trackid"].''
                    . '","'.$dte["dte_estado_sii"].'","'.$estadoCliente.'","<div class=\"btn-group\"><button class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">Opciones<span class=caret></span></button>'.$ul.'</div>","'.$RutReceptor.'","'.$dte["dte_monto_total"].'"],';
        }
    }
    $data=substr($data,0,-1)."]}";
    return $data;
}

function tablaTraza($tipoDoc,$nDoc){
    global $conn;
    session_start();

    $sql="select * from sis_bitacora where sis_contribuyente_id=".$_SESSION["contribuyente"]." and dte_tipo=".$tipoDoc." and "
            . "dte_folio=".$nDoc;
    $query = $conn->query($sql);

    
    $data='{"data":[';
    while ($dte=$query->fetch_assoc()){
        $data.='["'.$dte["dte_trackid"].'","'.date("d-m-Y",  strtotime($dte["dte_fecha_envio"])).'","'.$dte["dte_estado_sii"].'","'.$dte["dte_estado_detalle"].'"],';
    }
    $data=substr($data,0,-1)."]}";
    return $data;
}

function tablaLibroVenta(){
    global $conn;
    session_start();
    $carpeta=substr($_SESSION["rut"],0,-2);
    
    
    $sql="select * from libro_venta where sis_contribuyente_id=".$_SESSION["contribuyente"]." order by libro_venta_ano,libro_venta_mes";
    $query = $conn->query($sql);

    
    $data='{"data":[';
    while ($dte=$query->fetch_assoc()){
        $data.='["'.$dte["libro_venta_ano"].'-'.$dte["libro_venta_mes"].'","'.$dte["libro_venta_trackid"].'","'.$dte["libro_venta_estado"].'"],';
    }
    $data=substr($data,0,-1)."]}";
    return $data;
}

function tablaLibroCompra(){
    global $conn;
    session_start();
    $carpeta=substr($_SESSION["rut"],0,-2);
    
    
    $sql="select * from libro_compra where sis_contribuyente_id=".$_SESSION["contribuyente"]." order by libro_compra_ano,libro_compra_mes";
    $query = $conn->query($sql);
    //echo $sql;
    
    $data='{"data":[';
    while ($dte=$query->fetch_assoc()){
        $data.='["'.$dte["libro_compra_ano"].'-'.$dte["libro_compra_mes"].'","'.$dte["libro_compra_trackid"].'","'.$dte["libro_compra_estado"].'"],';
    }
    $data=substr($data,0,-1)."]}";
    return $data;
}

function tablaCesiones(){
    global $conn;
    session_start();
    $carpeta=substr($_SESSION["rut"],0,-2);
    
    
    $sql="select * from sis_dte_cesion where sis_contribuyente_id=".$_SESSION["contribuyente"]." order by sis_dte_ces_id ";
    
    $query = $conn->query($sql);
    $data='{"data":[';
    while ($dte=$query->fetch_assoc()){
        if($dte["estado_sii"]==0){
            $ul="<ul class=dropdown-menu role=menu>"
            . "<li><a data-toggle=modal href=#cesionModal>Receder Documento</a></li>"
            . "<li><button id='btnBorraCesion' class='btn btn-link' data-idf='".$dte["sis_dte_ces_id"]."'>Eliminar Cesi&oacute;n</button></li>"
            . "</ul>";
        }else{
            $ul="";
        }
        switch ($dte["sis_dte_tipo"]){
            case 33:
                $documento="Factura Electr&oacute;nica";
                break;
            case 34:
                $documento="Factura Exenta Electr&oacute;nica";
                break;
            case 61:
                $documento="Nota de Cr&eacute;dito Electr&oacute;nica";
                break;
            case 56:
                $documento="Nota de D&eacute;dito Electr&oacute;nica";
                break;
            case 52:
                $documento="Gu&iacute;a de Despacho Electr&oacute;nica";
                break;
        }
            $data.='["'.$dte["sis_dte_tipo"].'","'.$dte["sis_dte_ces_rut"].'","'.$dte["sis_dte_ces_direccion"].'","'.$dte["sis_dte_ces_mail"].'","'.$dte["sis_dte_ces_monto"].'","'.$documento.'","'.$dte["sis_dte_folio"].'","'
                .date("d-m-Y",  strtotime($dte["sis_dte_ces_fecha"])).'","'.date("d-m-Y",  strtotime($dte["sis_dte_ces_vancimiento"])).'","'.$dte["sis_dte_ces_nombre"].'","'.$dte["trackid"].'","'.$dte["estado_sii_glosa"].'","<div class=\"btn-group\"><button class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\">Opciones<span class=caret></span></button>'.$ul.'</div>","'.$RutReceptor.'"],';
    }
    $data=substr($data,0,-1)."]}";
    return $data;
}