<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ERROR);
ini_set("display_errors",1);
//require 'inc/html2pdf/html2pdf.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

//echo "<pre>";
//print_r($_GET);
session_start();

$conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
$XMLDTE = new DOMDocument();
$XMLDTE->formatOutput = FALSE;
$XMLDTE->preserveWhiteSpace = TRUE;
$XMLDTE->encoding = "ISO-8859-1";
$content = "<page>";
$unidad=$_SESSION["unidad"];
if($_POST["tipo"]=="COMPRA"){
    $rutaDte="../documentos/recepcionados/".$_POST["f"];
}else{
    $rutaDte="../procesos/xml_emitidos/".substr($_SESSION["rut"],0,-2)."/".$_POST["f"];
}

if($XMLDTE->load($rutaDte)){
    $DTE=$XMLDTE->getElementsByTagName("DTE");
    foreach($DTE as $Documento){
        if($Documento->getElementsByTagName("Folio")->item(0)->nodeValue==$_POST["ndoc"]){
            if($Documento->getElementsByTagName("FchVenc")->item(0)->nodeValue!=""){
                $fechaVenc=date("d-m-Y", strtotime($Documento->getElementsByTagName("FchVenc")->item(0)->nodeValue));
            }else{
                $fechaVenc="";
            }

            $TED = $Documento->getElementsByTagName("TED")->item(0);
            //$timbre= trim(str_replace(">  <","><",str_replace(">   <","><",str_replace(">    <","><",str_replace("\n","",str_replace("\t","",utf8_decode($XMLDTE->saveXML($TED))))))));
            $timbre= trim(str_replace("> <","><",str_replace(">  <","><",str_replace(">   <","><",str_replace(">    <","><",str_replace("\n","",str_replace("\t","",utf8_decode($XMLDTE->saveXML($TED)))))))));
			$timbre = str_replace("\"","'",$timbre);
            $timbre = str_replace(array("<",">"),array("&lt;","&gt;"),$timbre);
            if($_REQUEST["debug"]==1){
                echo $Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
            }
            switch(intval($Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue)){
                case 33:
                    $TipoDocumento="FACTURA ELECTRONICA";
                    break;
                case 34:
                    $TipoDocumento="FACTURA NO AFECTA <br>O<br> EXENTA ELECTRONICA";
                    break;
                case 43:
                    $TipoDocumento="LIQUIDACION<br>FACTURA ELECTRONICA";
                    break;
                case 61:
                    $TipoDocumento="NOTA DE CREDITO<br>ELECTRONICA";
                    break;
                case 52:
                    $TipoDocumento="GUIA DE DESPACHO<br>ELECTRONICA";
                    break;
                default:
                    $TipoDocumento=$Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
            }

            switch(intval($Documento->getElementsByTagName("TipoDespacho")->item(0)->nodeValue)){
                case 1:
                    $TipoDespacho="Efectivo";
                    break;
                case 2:
                    $TipoDespacho="Efectivo";
                    break;
                case 3:
                    $TipoDespacho="Efectivo";
                    break;
            }

            $content.=$watermark.'<table style="margin-right: 2px;margin-left: 2px;" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td colspan=2 style="vertical-align:top;width: 60%;">'.$ribbon.' 
                            <span style="font-face:Arial;font-weight:bold;font-size:11pt;color:#0f7e9a">'.strtoupper($Documento->getElementsByTagName("RznSoc")->item(0)->nodeValue).'</span><br>
                            <span style="font-face:Arial;font-size:10pt;font-weight:bold">Giro:'.strtoupper(substr($Documento->getElementsByTagName("GiroEmis")->item(0)->nodeValue,0,49)).'<br>'.strtoupper(substr($Documento->getElementsByTagName("DirOrigen")->item(0)->nodeValue,0,30)).'&nbsp;-&nbsp;'.strtoupper($Documento->getElementsByTagName("CmnaOrigen")->item(0)->nodeValue).'
                            </span>
                        </td>
                        <td style="font-face:Arial;font-size:10pt;font-weight:bold;text-align: center; border: 3px solid red;">
                            <p style="color:red">R.U.T.:'.rut_format($Documento->getElementsByTagName("RUTEmisor")->item(0)->nodeValue).'</p>
                            <p style="color:red">'.strtoupper($TipoDocumento).'</p>
                            <p style="color:red">Nº&nbsp;'.str_pad($Documento->getElementsByTagName("Folio")->item(0)->nodeValue, 9,"0",STR_PAD_LEFT).'</p>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td style="font-face:Arial;font-size:10pt;font-weight:bold;text-align:center">S.I.I. - '.$unidad.'</td>
                    </tr>
                    <tr>
                        <td colspan=3>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan=3>
                            <table width=100% border=0 cellspacing=0 cellpadding=0 style="font-face:helvetica;border:1px solid #0f7e9a">
                                <tbody>
                                    <tr style="font-size:9pt;">
                                        <td style="border-left:1px solid #000;border-top:1px solid #000;width: 10%;">Se&ntilde;or(es):</td>
                                        <td style="border-top:1px solid #000;width: 50%;">'.strtoupper($Documento->getElementsByTagName("RznSocRecep")->item(0)->nodeValue).'</td>
                                        <td style="border-top:1px solid #000;width: 10%;">Fecha:</td>
                                        <td style="border-top:1px solid #000;border-right:1px solid #000">'.date("d-m-Y",  strtotime($Documento->getElementsByTagName("FchEmis")->item(0)->nodeValue)).'</td>
                                    </tr>
                                    <tr style="font-size:9pt;">
                                        <td style="border-left:1px solid #000">R.U.T.:</td>
                                        <td>'.$Documento->getElementsByTagName("RUTRecep")->item(0)->nodeValue.'</td>
                                        <td>Telefono:</td>
                                        <td style="border-right:1px solid #000;"></td>
                                    </tr>
                                    <tr style="font-size:9pt;">
                                        <td style="border-left:1px solid #000">Giro:</td>
                                        <td style="border-right:1px solid #000;" colspan="3">'.strtoupper($Documento->getElementsByTagName("GiroRecep")->item(0)->nodeValue).'</td>
                                    </tr>
                                    <tr style="font-size:9pt;">
                                        <td style="border-left:1px solid #000">Direccion:</td>
                                        <td style="border-right:1px solid #000;" colspan="3">'.strtoupper($Documento->getElementsByTagName("DirRecep")->item(0)->nodeValue).'</td>
                                    </tr>
                                    <tr style="font-size:9pt;">
                                        <td style="border-left:1px solid #000">Comuna:</td>
                                        <td>'.strtoupper($Documento->getElementsByTagName("CmnaRecep")->item(0)->nodeValue).'</td>
                                        <td>Ciudad:</td>
                                        <td style="border-right:1px solid #000;">'.strtoupper($Documento->getElementsByTagName("CiudadRecep")->item(0)->nodeValue).'</td>
                                    </tr>
                                    <tr style="font-size:9pt;">
                                        <td style="border-left:1px solid #000">Fecha Vencimiento:</td>
                                        <td>'.$fechaVenc.'</td>';
                                    if($TipoDespacho!=""){
                                        $content.='<td>Tipo de Despacho</td><td style="border-right:1px solid #000"></td>';
                                    }else{
                                        $content.='<td>o</td><td style="border-right:1px solid #000"></td>';
                                    }
                                    $content.='</tr>
                                    <tr>
                                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000" colspan="4">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>';
                    //Referencias
                    $content.='<tr><td colspan="3"><table style="width: 100%;font-size:8pt;" border="0" cellspacing="0" cellpadding="2">';
                    $Referencias = $Documento->getElementsByTagName("Referencia");
                    $content.='<tr>';
                    $content.='<td>Referencias</td>';
                    $content.='</tr>';           
                    foreach($Referencias as $Referencia) {
                        switch($Referencia->getElementsByTagName("TpoDocRef")->item(0)->nodeValue){
                            case "33":
                                $TipoDocumento="FACTURA ELECTRONICA";
                                break;
                            case "34":
                                $TipoDocumento="FACTURA EXENTA ELECTRONICA";
                                break;
                            case "43":
                                $TipoDocumento="LIQUIDACION DE FACTURA ELECTRONICA";
                                break;
                            case "61":
                                $TipoDocumento="NOTA DE CREDITO ELECTRONICA";
                                break;
                            case "56":
                                $TipoDocumento="NOTA DE DEBITO ELECTRONICA";
                                break;
                            case "52":
                                $TipoDocumento="GUIA DE DESPACHO ELECTRONICA";
                                break;
                            case "801":
                                $TipoDocumento="ORDEN DE COMPRA";
                                break;
                            case "802":
                                $TipoDocumento="NOTA DE PEDIDO";
                                break;
                            default:
                                $TipoDocumento=$Referencia->getElementsByTagName("TpoDocRef")->item(0)->nodeValue;
                        }
                        $content.='<tr>';
                        $content.='<td>'.$TipoDocumento.' Folio '.$Referencia->getElementsByTagName("FolioRef")->item(0)->nodeValue.' del '.$Referencia->getElementsByTagName("FchRef")->item(0)->nodeValue.'</td>';
                        $content.='</tr>';
                    }
                    $content.='</table></td></tr>
                        <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table style="width: 100%;" border="0" cellspacing="0" cellpadding="2">
                                <thead>
                                    <tr bgcolor="#0f7e9a">
                                        <th style="width: 5%; text-align: center;border-right:1px solid #000;border-left:1px solid #000;border-top:1px solid #000;">Cod.</th>
                                        <th style="width: 60%; text-align: center;border-right:1px solid #000;border-top:1px solid #000;">Descripci&oacute;n</th>
                                        <th style="width: 5%;text-align: center;border-right:1px solid #000;border-top:1px solid #000;">Cant.</th>
                                        <th style="width: 5%;text-align: center;border-right:1px solid #000;border-top:1px solid #000;">Und.</th>
                                        <th style="width: 8%;text-align: center;border-right:1px solid #000;border-top:1px solid #000;">Precio</th>
                                        <th style="width: 8%; text-align: center;border-right:1px solid #000;border-top:1px solid #000;">% Desc.</th>
                                        <th style="width: 8%; text-align: center;border-top:1px solid #000;border-right:1px solid #000;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                $Detalles = $Documento->getElementsByTagName("Detalle");

                                foreach($Detalles as $Detalle) { 
                                    if(trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue)!=""){
                                        if(trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue)!=trim($Detalle->getElementsByTagName("NmbItem")->item(0)->nodeValue)){
                                            $DescItem = "<br>".trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue);
                                        }
                                    }else{
                                        $DescItem ="";
                                    }
                                    $content.='<tr style="font-face:Helvetica;font-size:7pt">
                                        <td style="border-left:1px solid #000;border-right:1px solid #000;"></td>
                                        <td style="text-align:left;border-right:1px solid #000;">'.substr($Detalle->getElementsByTagName("NmbItem")->item(0)->nodeValue,0,60).substr($DescItem,0,72).'</td>
                                        <td style="text-align:center;border-right:1px solid #000;" valign="bottom">'.number_format($Detalle->getElementsByTagName("QtyItem")->item(0)->nodeValue,1,",",".").'</td>
                                        <td style="text-align:center;border-right:1px solid #000;" valign="bottom">'.$Detalle->getElementsByTagName("UnmdItem")->item(0)->nodeValue.'</td>
                                        <td style="text-align:right;border-right:1px solid #000;" valign="bottom">'.number_format($Detalle->getElementsByTagName("PrcItem")->item(0)->nodeValue,0,',','.').'</td>
                                        <td style="text-align:right;border-right:1px solid #000;" valign="bottom">'.$Detalle->getElementsByTagName("DescuentoPct")->item(0)->nodeValue.'</td>
                                        <td style="border-right:1px solid #000;" align=right valign="bottom">'.number_format($Detalle->getElementsByTagName("MontoItem")->item(0)->nodeValue,0,',','.').'</td></tr>';
                                    $lin++;
                                }
                                $hasta=20 - $lin;
                                for($l=0;$l<=$hasta;$l++){
                                    $content.='<tr><td style="border-left:1px solid #000;border-right:1px solid #000;">&nbsp;</td>';
                                    $content.='<td style="border-right:1px solid #000;">&nbsp;</td>';
                                    $content.='<td style="border-right:1px solid #000;">&nbsp;</td>';
                                    $content.='<td style="border-right:1px solid #000;">&nbsp;</td>';
                                    $content.='<td style="border-right:1px solid #000;">&nbsp;</td>';
                                    $content.='<td style="border-right:1px solid #000;">&nbsp;</td>';
                                    $content.='<td style="border-right:1px solid #000;">&nbsp;</td></tr>';
                                }
                                $content.='<tr><td style="border-bottom:1px solid #000;border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td>';
                                $content.='<td style="border-bottom:1px solid #000;border-right:1px solid #000">&nbsp;</td>';
                                $content.='<td style="border-bottom:1px solid #000;border-right:1px solid #000">&nbsp;</td>';
                                $content.='<td style="border-bottom:1px solid #000;border-right:1px solid #000">&nbsp;</td>';
                                $content.='<td style="border-bottom:1px solid #000;border-right:1px solid #000">&nbsp;</td>';
                                $content.='<td style="border-bottom:1px solid #000;border-right:1px solid #000">&nbsp;</td>';
                                $content.='<td style="border-bottom:1px solid #000;border-right:1px solid #000">&nbsp;</td></tr>';
                                //Agrego el descuento global si existe
    //                            if($cot["dte_descuento_monto"]>0){
    //                                $descuento = $descuento+$cot["dte_descuento_monto"];
    //                            }
    //                            $html .='
    //                                <tr>
    //                                    <td  rowspan=4 colspan="3">
    //                                    <table widtH=80% border=0 cellpading=0 cellspacing=0>
    //                                        <thead>
    //                                            <tr>
    //                                                <th style="vertical-aling:top">Referencias</th>
    //                                            </tr>
    //                                        </thead>
    //                                        <tbody>';
    //                            $slqRef="select concat(if(a.dte_referencia_codigo=9999,'SET',a.dte_referencia_razon),' - ',c.tipo_documento_glosa,' Nº ',if(a.dte_referencia_codigo=9999,a.dte_referencia_razon,a.dte_referencia_numero),' del ') as referencia_glosa,b.dte_fec_emision "
    //                                    . "from dte_referencia a,dte b,ref_tipo_documentos c "
    //                                    . "where a.dte_numero=".$documento." and a.sis_contribuyente_id=$contribuyente"
    //                                    . " and a.dte_numero =b.dte_numero "
    //                                    . "and a.ref_referencia_id=c.tipo_documento_codigo "
    //                                    . "order by a.dte_numero,a.dte_referencia_linea";
    //                            $result4 = $conn->query($slqRef);
    //                 
    //                            while($ref = $result4->fetch_assoc()){
    //                                $content.='<tr><td style="vertical-aling:top">'.$ref["referencia_glosa"].' '. date("Y-m-d",strtotime($ref["dte_fec_emision"])).'</td></tr>';
    //                            }
    //                                $content.='</tbody>
    //                                    </table>
    //                                    </td>
    //                                </tr>
                                switch(intval($Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue)){
                                    case 33:
                                        $content.='<tr style="font-size:7pt;">
                                        <td colspan=5></td>
                                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;">Neto</td>
                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($Documento->getElementsByTagName("MntNeto")->item(0)->nodeValue,0,".",".").'</td>
                                        </tr>
                                        <tr style="font-size:7pt;">
                                            <td colspan=5></td>
                                            <td style="border-left:1px solid #000;border-bottom:1px solid #000;">Desc()</td>
                                            <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.$MontoDescuento.'</td>
                                        </tr>
                                        <tr style="font-size:7pt;">
                                            <td colspan=5></td>
                                            <td nowrap style="border-left:1px solid #000;border-bottom:1px solid #000">I.V.A.(19%)</td>
                                            <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($Documento->getElementsByTagName("IVA")->item(0)->nodeValue,0,".",".").'</td>
                                        </tr>';
                                        if(null!=$Documento->getElementsByTagName("ImptoReten")){
                                                $OtImp = $Documento->getElementsByTagName("ImptoReten");
                                                foreach($OtImp as $impuesto) {
                                                    $sql="select impuesto_glosa from ref_impuestos where impuesto_id=".$impuesto->getElementsByTagName("TipoImp")->item(0)->nodeValue;
                                                    $obj = $conn->query($sql);
                                                    $data = $obj->fetch_assoc();

                                                    $content.='<tr style="font-size:7pt;">
                                                        <td colspan=5></td>
                                                        <td nowrap style="border-left:1px solid #000;border-bottom:1px solid #000">'.$data["impuesto_glosa"].'('.$impuesto->getElementsByTagName("TasaImp")->item(0)->nodeValue.')</td>
                                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($impuesto->getElementsByTagName("MontoImp")->item(0)->nodeValue,0,".",".").'</td>
                                                    </tr>';
                                                }
                                            }
                                        break;
                                    case 34:
                                        $content.='<tr style="font-size:7pt;">
                                        <td colspan=5></td>
                                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;">Exento</td>
                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($Documento->getElementsByTagName("MntExe")->item(0)->nodeValue,0,".",".").'</td>
                                    </tr>
                                    <tr style="font-size:7pt;">
                                        <td colspan=5></td>
                                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;">Desc()</td>
                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.$MontoDescuento.'</td>
                                    </tr>';
                                        break;
                                    case 43:
                                        $content.='<tr style="font-size:7pt;">
                                        <td colspan=5></td>
                                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;">Neto</td>
                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($Documento->getElementsByTagName("MntNeto")->item(0)->nodeValue,0,".",".").'</td>
                                        </tr>
                                        <tr style="font-size:7pt;">
                                            <td colspan=5></td>
                                            <td style="border-left:1px solid #000;border-bottom:1px solid #000;">Desc()</td>
                                            <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.$MontoDescuento.'</td>
                                        </tr>
                                        <tr style="font-size:7pt;">
                                            <td colspan=5></td>
                                            <td nowrap style="border-left:1px solid #000;border-bottom:1px solid #000">I.V.A.(19%)</td>
                                            <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($Documento->getElementsByTagName("IVA")->item(0)->nodeValue,0,".",".").'</td>
                                        </tr>';
                                        $l=1;
                                        if(null!=$Documento->getElementsByTagName("Comisiones")){
                                            $Comisiones = $Documento->getElementsByTagName("Comisiones");
                                            foreach($Comisiones as $comision) {
                                                if($l==1){
                                                    $content.='<tr style="font-size:7pt;">
                                                        <td colspan=5></td>
                                                        <td nowrap style="border-left:1px solid #000;border-bottom:1px solid #000">Neto Com.</td>
                                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($comision->getElementsByTagName("ValComNeto")->item(0)->nodeValue,0,".",".").'</td>
                                                    </tr>';
                                                    if(intval($comision->getElementsByTagName("ValComExe")->item(0)->nodeValue)>0){
                                                        $content.='<tr style="font-size:7pt;">
                                                        <td colspan=5></td>
                                                        <td nowrap style="border-left:1px solid #000;border-bottom:1px solid #000">Iva Com.</td>
                                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($comision->getElementsByTagName("ValComExe")->item(0)->nodeValue,0,".",".").'</td>
                                                        </tr>';
                                                    }
                                                    $content.='<tr style="font-size:7pt;">
                                                        <td colspan=5></td>
                                                        <td nowrap style="border-left:1px solid #000;border-bottom:1px solid #000">Iva Com.</td>
                                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($comision->getElementsByTagName("ValComIVA")->item(0)->nodeValue,0,".",".").'</td>
                                                    </tr>';
                                                    $l++;
                                                }
                                            }
                                        }
                                        break;
                                    case 61:
                                        $content.='<tr style="font-size:7pt;">
                                        <td colspan=5></td>
                                        <td style="border-left:1px solid #000;border-bottom:1px solid #000;">Neto</td>
                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($Documento->getElementsByTagName("MntNeto")->item(0)->nodeValue,0,".",".").'</td>
                                        </tr>
                                        <tr style="font-size:7pt;">
                                            <td colspan=5></td>
                                            <td style="border-left:1px solid #000;border-bottom:1px solid #000;">Desc()</td>
                                            <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.$MontoDescuento.'</td>
                                        </tr>
                                        <tr style="font-size:7pt;">
                                            <td colspan=5></td>
                                            <td nowrap style="border-left:1px solid #000;border-bottom:1px solid #000">I.V.A.(19%)</td>
                                            <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($Documento->getElementsByTagName("IVA")->item(0)->nodeValue,0,".",".").'</td>
                                        </tr>';
                                            if(null!=$Documento->getElementsByTagName("ImptoReten")){
                                                $OtImp = $Documento->getElementsByTagName("ImptoReten");
                                                foreach($OtImp as $impuesto) {
                                                    $sql="select impuesto_glosa from ref_impuestos where impuesto_id=".$impuesto->getElementsByTagName("TipoImp")->item(0)->nodeValue;
                                                    $obj = $conn->query($sql);
                                                    $data = $obj->fetch_assoc();

                                                    $content.='<tr style="font-size:7pt;">
                                                        <td colspan=5></td>
                                                        <td nowrap style="border-left:1px solid #000;border-bottom:1px solid #000">'.$data["impuesto_glosa"].'&nbsp;('.$impuesto->getElementsByTagName("TasaImp")->item(0)->nodeValue.'%)</td>
                                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($impuesto->getElementsByTagName("MontoImp")->item(0)->nodeValue,0,".",".").'</td>
                                                    </tr>';
                                                }
                                            }
                                        break;
                                    default:
                                        $TipoDocumento=$Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
                                }
                                    $content.='<tr style="font-size:7pt;">
                                        <td colspan=5></td>
                                        <td style="border-bottom:1px solid #000;border-left:1px solid #000;">Total</td>
                                        <td style="border-bottom:1px solid #000;border-right:1px solid #000;" align="right">'.number_format($Documento->getElementsByTagName("MntTotal")->item(0)->nodeValue,0,".",".").'</td>
                                    </tr>
                                    </tbody>
                            </table>
                        </td>
                    </tr>    
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="font-size:9pt;">SON:'.numtoletras($Documento->getElementsByTagName("MntTotal")->item(0)->nodeValue).'</td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>';

                    $content.='<tr style="font-size:9pt;">
                        <td colspan="3" align="left"> 
                            <table width="90%" border="0" cellspacing=0 cellpading=0>
                                <tr>
                                    <td style="border-left:1px solid #000;border-top:1px solid #000" colspan="2">Nombre:</td>
                                    <td style="border-top:1px solid #000;border-right:1px solid #000;">R.U.T.:</td>
                                </tr>
                                <tr>
                                    <td style="border-left:1px solid #000">Fecha:</td>
                                    <td>Recinto:</td>
                                    <td style="border-right:1px solid #000;">Firma:</td>
                                </tr>
                                <tr>
                                    <td style="border-left:1px solid #000;border-bottom:1px solid #000;border-right:1px solid #000" colspan="3">El acuse de recibo que se declara en este acto, de acuerdo a lo dispuesto en la letra b)
                                        del Art. 4&deg;, y la letra c) del Art. 5&deg; de la Ley 19.983, acredita que la entrega de mercader&iacute;as
                                        o servicio (s) prestado (s) ha (n) sido recibido (s).</td>
                                </tr>
                            </table>


                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td>                           
                           <span style="margin-left:37px;font-face:Arial;font-size:7pt"><barcode dimension="2D" type="PDF417" value="'.$timbre.'" style="width:60mm; height:30mm; font-size: 4mm"></barcode></span><br>
						   <span style="margin-left:100px;font-face:Arial;font-size:7pt">Timbre Electr&oacute;nico SII</span><br>
                           <span style="margin-left:45px;font-face:Arial;font-size:7pt">Res. '.$Documento->getElementsByTagName("NroResol")->item(0)->nodeValue.' de '.date("Y", strtotime($Documento->getElementsByTagName("FchResol")->item(0)->nodeValue)).'. Verifique documento: www.sii.cl</span><br>
                        </td>
                    </tr>
                </tbody>
            </table>'; 
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
    
function numtoletras($xcifra){ 
    $xarray = array(0 => "Cero",1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA", 100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS");

    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)){
	if ($xpos_punto == 0)		{
            $xcifra = "0".$xcifra;
            $xpos_punto = strpos($xcifra, ".");
	}
	$xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
	$xdecimales = substr($xcifra."00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }
 
    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for($xz = 0; $xz < 3; $xz++){
	$xaux = substr($XAUX, $xz * 6, 6);
	$xi = 0; $xlimite = 6; // inicializo el contador de centenas xi y establezco el l&#65533;mite a 6 d&#65533;gitos en la parte entera
	$xexit = true; // bandera para controlar el ciclo del While	
	while ($xexit)
		{
		if ($xi == $xlimite) // si ya lleg&#65533; al l&#65533;mite máximo de enteros
			{
			break; // termina el ciclo
			}
 
		$x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
		$xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres d&#65533;gitos)
		for ($xy = 1; $xy < 4; $xy++) // ciclo para revisar centenas, decenas y unidades, en ese orden
			{
			switch ($xy) 
				{
				case 1: // checa las centenas
					if (substr($xaux, 0, 3) < 100) // si el grupo de tres d&#65533;gitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
						{
						}
					else
						{
						$xseek = $xarray[substr($xaux, 0, 3)]; // busco si la centena es n&#65533;mero redondo (100, 200, 300, 400, etc..)
						if ($xseek)
							{
							$xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Mill&#65533;n, Millones, Mil o nada)
							if (substr($xaux, 0, 3) == 100) 
								$xcadena = " ".$xcadena." CIEN ".$xsub;
							else
								$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
							$xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
							}
						else // entra aqu&#65533; si la centena no fue numero redondo (101, 253, 120, 980, etc.)
							{
							$xseek = $xarray[substr($xaux, 0, 1) * 100]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
							$xcadena = " ".$xcadena." ".$xseek;
							} // ENDIF ($xseek)
						} // ENDIF (substr($xaux, 0, 3) < 100)
					break;
				case 2: // checa las decenas (con la misma l&#65533;gica que las centenas)
					if (substr($xaux, 1, 2) < 10)
						{
						}
					else
						{
						$xseek = $xarray[substr($xaux, 1, 2)];
						if ($xseek)
							{
							$xsub = subfijo($xaux);
							if (substr($xaux, 1, 2) == 20)
								$xcadena = " ".$xcadena." VEINTE ".$xsub;
							else
								$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
							$xy = 3;
							}
						else
							{
							$xseek = $xarray[substr($xaux, 1, 1) * 10];
							if (substr($xaux, 1, 1) * 10 == 20)
								$xcadena = " ".$xcadena." ".$xseek;
							else	
								$xcadena = " ".$xcadena." ".$xseek." Y ";
							} // ENDIF ($xseek)
						} // ENDIF (substr($xaux, 1, 2) < 10)
					break;
				case 3: // checa las unidades
					if (substr($xaux, 2, 1) < 1) // si la unidad es cero, ya no hace nada
						{
						}
					else
						{
						$xseek = $xarray[substr($xaux, 2, 1)]; // obtengo directamente el valor de la unidad (del uno al nueve)
						$xsub = subfijo($xaux);
						$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
						} // ENDIF (substr($xaux, 2, 1) < 1)
					break;
				} // END SWITCH
			} // END FOR
			$xi = $xi + 3;
		} // ENDDO
 
		if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
			$xcadena.= " DE";
 
		if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
			$xcadena.= " DE";
 
		// ----------- esta l&#65533;nea la puedes cambiar de acuerdo a tus necesidades o a tu pa&#65533;s -------
		if (trim($xaux) != "")
			{
			switch ($xz)
				{
				case 0:
					if (trim(substr($XAUX, $xz * 6, 6)) == "1")
						$xcadena.= "UN BILLON ";
					else
						$xcadena.= " BILLONES ";
					break;
				case 1:
					if (trim(substr($XAUX, $xz * 6, 6)) == "1")
						$xcadena.= "UN MILLON ";
					else
						$xcadena.= " MILLONES ";
					break;
				case 2:
					if ($xcifra < 1 )
						{
						$xcadena = "CERO PESOS";
						}
					if ($xcifra >= 1 && $xcifra < 2)
						{
						$xcadena = "UN PESO";
						}
					if ($xcifra >= 2)
						{
						$xcadena.= " PESOS"; // 
						}
					break;
				} // endswitch ($xz)
			} // ENDIF (trim($xaux) != "")
		// ------------------      en este caso, para M&#65533;xico se usa esta leyenda     ----------------
		$xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
		$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
		$xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
		$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
		$xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
		$xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
		$xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
	} // ENDFOR	($xz)
	return trim($xcadena);
} // END FUNCTION
 
 
function subfijo($xx){ // esta funci&#65533;n regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
    //	
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
    //
    return $xsub;
}

function rut_format( $rut ) {
    return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
}

