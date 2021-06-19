<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ERROR);
ini_set("display_errors",1);
//require 'inc/html2pdf/html2pdf.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require '../config/Conexion.php';
use Spipu\Html2Pdf\Html2Pdf;

//echo "<pre>";
//print_r($_GET);
session_start();

$conn = dbCertificacion();
$XMLDTE = new DOMDocument();
$XMLDTE->formatOutput = FALSE;
$XMLDTE->preserveWhiteSpace = TRUE;
$XMLDTE->encoding = "ISO-8859-1";
$content = "<page>";

$unidad= "SANTIAGO CENTRO";
$numResol = "0";
$fechaResol = "01/05/2015";
//$unidad=$_SESSION["unidadsii"];
//$numResol = $_SESSION['numResol'];
//$fechaResol = $_SESSION['fechaResol'];
//$carpeta=$_SESSION["carpeta"];   
/*if($_POST["tipo"]=="PROCESADOS"){
    $rutaDte="../procesos/xml_procesados/".substr($_SESSION["rut"],0,-2)."/".$_POST["f"];
}else{*/
    $rutaDte="T39_F1.xml";
//}


if($XMLDTE->load($rutaDte)){
    $DTE=$XMLDTE->getElementsByTagName("DTE");
    foreach($DTE as $Documento){
        //if($Documento->getElementsByTagName("Folio")->item(0)->nodeValue==$_POST["ndoc"]){
        if($Documento->getElementsByTagName("Folio")->item(0)->nodeValue==1){
            $TED = $Documento->getElementsByTagName("TED")->item(0);
            
            if($_REQUEST["debug"]==1){
                echo $Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
            }
            switch(intval($Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue)){
                case 39:
                    $TipoDocumento="BOLETA ELECTRÓNICA";
                    break;
                case 41:
                    $TipoDocumento="BOLETA NO AFECTA O EXENTA ELECTRÓNICA";
                    break;
                default:
                    $TipoDocumento=$Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
            }
           /* $rutaTimbre = "../procesos/firmas/".$_SESSION["carpeta"]."/T".intval($Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue).
           "_F".$Documento->getElementsByTagName("Folio")->item(0)->nodeValue.".png";*/
            $rutaTimbre = "timbre.png";

            $Telefono = $Documento->getElementsByTagName("Telefono")->item(0)->nodeValue;
            

            $content.=$watermark.
            '<style media="screen" type="text/css">
body{font-family:Arial,Verdana,sans-serif;font-size:12px}hr{border:none;height:1px;background-color:#575757}#container{margin-top:60px;height:560px;width:280px;margin-left:225px;border:1px solid #000;padding-left:10px;padding-right:10px}.emisor{font:inherit;font-size:16px}#emisor{text-align:center;position:relative;font-weight:700;line-height:110%;font-size:12px;margin-top:10px;width:280px}#datosEmisor{font-size:8px;text-align:left;line-height:135%;margin-left:10px}#datosBoleta{position:relative;margin-top:15px;margin-left:30px;width:200px}#boleta{border-width:3px;border-style:solid;border-color:#d64431;padding:0 10px 4px;text-align:center;font-weight:700;line-height:30%;width:192px}#boleta p{margin:8px}#sii{text-align:center}#fecha{position:relative;margin-top:5px;width:260px;margin-left:10px}#tablaFecha td{font-size:10px;line-height:1%}#detalle{position:relative;margin-top:10px;margin-left:10px;width:260px}#headerDetalle{width:260px}#headerDetalle table{width:258px;border:1px solid #0f7e9a;border-collapse:collapse}#headerDetalle td{font-size:11px;line-height:80%;background-color:#0f7e9a;color:#fff}#tablaDetalle{position:relative;border-collapse:collapse;font-size:8px;text-align:center;table-layout:fixed;width:260px;text-align:left}#tablaDetalle table{border-collapse:collapse}#tablaDetalle td{padding:1px;border-left:1px solid #929292;border-right:1px solid #929292;word-wrap:break-word}#totales{position:relative;width:260px;margin-left:10px}#tablaTotales{width:260px}#tablaTotales td{font-weight:700;font-size:11px;line-height:93%}#pago{position:relative;width:260px;margin-left:10px}#tablaPago{width:280px}#tablaPago td{font-size:10px;line-height:5%}#timbre{position:relative;text-align:center;font-size:8px;font-weight:700;width:280px}.rojo{color:#d64431}.b{font-weight:700}.azul{color:#0f7e9a}
</style>
    <body>    
        <div id="container">
            <div id="datosBoleta">
                <div id="boleta" class="rojo">
                 <p><b>R.U.T.: '.rut_format($Documento->getElementsByTagName("RUTEmisor")->item(0)->nodeValue).'</b></p>
                    <p><b>'.strtoupper($TipoDocumento).'</b></p>
                    <p><b>Nº&nbsp;'.str_pad($Documento->getElementsByTagName("Folio")->item(0)->nodeValue, 9,"0",STR_PAD_LEFT).'</b></p>
                </div>
                <div id="sii" class="rojo"><b>S.I.I. - SANTIAGO CENTRO</b></div>
            </div>   
            <div id="emisor"> 
                <b class="azul">'.strtoupper($Documento->getElementsByTagName("RznSocEmisor")->item(0)->nodeValue).'</b><br>
                <span style="font-size: 10px">'.strtoupper(substr($Documento->getElementsByTagName("GiroEmisor")->item(0)->nodeValue,0,49)).'</span><br>
                <div id="datosEmisor">
                    Dirección: '.strtoupper(substr($Documento->getElementsByTagName("DirOrigen")->item(0)->nodeValue,0,30)).'&nbsp;,&nbsp;'
                 .strtoupper($Documento->getElementsByTagName("CmnaOrigen")->item(0)->nodeValue).',&nbsp;'              
                 .strtoupper($Documento->getElementsByTagName("CiudadOrigen")->item(0)->nodeValue).'<br>'
                 .'Teléfono: '.strtoupper($Documento->getElementsByTagName("Telefono")->item(0)->nodeValue).'<br>'
                 .'Corre Electrónico: '.strtoupper($Documento->getElementsByTagName("CorreoEmisor")->item(0)->nodeValue).'<br>
                </div>
            </div>
            <div id="fecha">
                <hr>
                <table id="tablaFecha">
                    <tr>
                        <td>Fecha</td><td>:</td>
                        <td style="padding-right: 5px; ">'.date("d-m-Y",  strtotime($Documento->getElementsByTagName("FchEmis")->item(0)->nodeValue)).'</td>                              
                        <td>Vendedor</td><td>:</td>
                        <td style="padding-left: 10px;" ></td>
                    </tr>
                </table>
            </div>
            <div id="detalle">
                <div id="headerDetalle"> 
                    <table id="tablaHeaderDetalle">
                        <tr>
                            <td style="width: 35%; text-align: center"><b>Descripción </b></td>
                            <td style="width: 21%; text-align: center"><b>Un. Med.</b></td>
                            <td style="width: 22%; text-align: center"><b>Cantidad</b></td>
                            <td style="width: 22%; text-align: center"><b>Precio</b></td>
                        </tr>
                    </table>
                </div>
                <table id="tablaDetalle">';
                    $Detalles = $Documento->getElementsByTagName("Detalle");
                    foreach($Detalles as $Detalle) 
                    { 
                        if(trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue)!="")
                        {
                            if(trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue)!=trim($Detalle->getElementsByTagName("NmbItem")->item(0)->nodeValue))
                            {
                                $DescItem = trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue);
                            }
                        }else
                        {
                            $DescItem ="";
                        }
                        $content.=
                        '
                    <tr>                            
                        <td style="width: 35%; text-align: left; padding-top: 4px;">'.substr($Detalle->getElementsByTagName("NmbItem")->item(0)->nodeValue,0,60).substr($DescItem,0,72).'</td>
                        <td style="width: 21%; text-align: center; padding-top: 4px;">'.$Detalle->getElementsByTagName("UnmdItem")->item(0)->nodeValue.'</td>
                        <td style="width: 22%; text-align: center; padding-top: 4px;">'.number_format($Detalle->getElementsByTagName("QtyItem")->item(0)->nodeValue,2,".",",").'</td>
                        <td style="width: 22%; text-align: right; padding-right: 2px; padding-top: 4px;">'.number_format($Detalle->getElementsByTagName("PrcItem")->item(0)->nodeValue,2,'.',',').'</td>
                    </tr>';
                    }
                    $content.='<tr><td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td></tr>';
             $content.='</table>
            </div>
              <div id="totales">
                <table id="tablaTotales">
                    <tr>
                        <td style="width: 120px;"><b>TOTAL EXENTO&nbsp;:&nbsp;$</b></td>
                        <td style="text-align: right; width: 120px;"><b>'.number_format($Documento->getElementsByTagName("MntExe")->item(0)->nodeValue,2,".",",").'</b></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;"><b>SUB TOTAL&nbsp;:&nbsp;$</b></td>
                        <td style="text-align: right; width: 120px;"><b>'.number_format($Documento->getElementsByTagName("MntTotal")->item(0)->nodeValue,2,".",",").'</b></td>
                    </tr>
                    <tr>
                        <td style="width: 120px;"><b>TOTAL&nbsp;:&nbsp;$</b></td>
                        <td style="text-align: right; width: 120px;"><b>'.number_format($Documento->getElementsByTagName("MntTotal")->item(0)->nodeValue,2,".",",").'</b></td>
                    </tr>
                </table>
                <hr>
            </div>
            <div id="pago">
                <table id="tablaPago">
                    <tr>
                        <td style="width: 80px;"><b>Paga con:</b></td>
                        <td style="width: 80px;"><b>Vuelto:</b></td>
                        <td style="width: 80px; text-align: right"><b>Hora:</b></td>
                    </tr>
                </table>
                <hr>
            </div>
             <br>
                <div id="timbre">                           
                        <img src="'.$rutaTimbre.'" width=60mm;/><br><br>
                        Timbre Electr&oacute;nico SII<br>
                        Res. '.$numResol.' de '.getDate(strtotime($fechaResol))["year"].'<br>
                       Verifique documento: www.sisgenchile.cl
                </div> 
            </div>
    </body>'; 
        }
    }
}else{
    $content.=$rutaDte;
}
$content.="</page>";

if(!isset($_REQUEST["debug"])){
    $html2pdf = new Html2Pdf('P','letter','es');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf');
}else{
    echo $content;   
}
    
function rut_format( $rut ) {
    return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
}