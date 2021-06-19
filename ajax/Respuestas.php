<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/procesos/libreriaSII/xmlseclibs/XmlseclibsAdapter.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/procesos/libreriaSII/SII.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/procesos/libreriaSII/IC.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/procesos/libreriaSII/IC/ObjectAndXML.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/ws/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

date_default_timezone_set('America/Santiago');

$conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
$msgError="";

eval("\$ret=procesa".$_POST["func"]."(".$_POST["par"].");");
echo $ret;

function procesaBuscaRegistro($pRut){
    global $conn;
    
    $sql="select sis_dte_ces_rut,sis_dte_ces_nombre,sis_dte_ces_direcion, "
            . "sis_dte_ces_mail "
            . "FROM sis_dte_cesion where sis_dte_ces_rut='".$pRut."' and "
            . "sis_contribuyente_id=".$_SESSION["contribuyente"]." group by "
            . "sis_dte_ces_rut";
 
    $query=$conn->query($sql);
    $num = $conn->affected_rows;
    if($num>0){
        $data = $query->fetch_assoc();
        $respuesta ='[{"ERROR":"0","RAZON":"'.$data["sis_dte_ces_nombre"].'",'
                . '"DIRECCION":"'.$data["sis_dte_ces_direcion"].'",'
                . '"EMAIL":"'.$data["sis_dte_ces_mail"].'"}]';
    }else{
        $respuesta ='[{"ERROR":"0","RAZON":""}]';
    }
    return $respuesta;
}

function procesaRespuesta($pRut,$pFolio,$pTipo,$pTipoResp,$pMsgRechazo)
{
    global $conn;
    $retRegistro="";
    $aTipoResp = explode("-",$pTipoResp);
    $sql="update sis_dte set sis_dte_sobre=1,ref_respuesta_id='".$pTipoResp[0]."',sis_dte_glosa_estado='$pMsgRechazo' "
            . "where sis_contribuyente_id=".$_SESSION["contribuyente"]
            . " and sis_dte_tipo=$pTipo and sis_dte_folio=$pFolio and sis_dte_emisor_id=".substr($pRut,0,-2);
    
    $query=$conn->query($sql);
    $num = $conn->affected_rows;
    if($num>0){
        $retRespuesta = procRespuesta($pRut,$pFolio,$pTipo,$aTipoResp[0],$pMsgRechazo);
        if($pTipo=="33" or $pTipo=="34" or $pTipo=="43"){
            $retRegistro = porcRegistroRoA($pRut,$pFolio,$pTipo,$aTipoResp[1]);
        }
        $respuesta ='[{"ERROR":"0","MENSAJE":"'.$retRespuesta.'<br>Registro en SII:'.$retRegistro.'"}]';
    }else{
        if($pTipo=="33" or $pTipo=="34" or $pTipo=="43"){
            $retRegistro = porcRegistroRoA($pRut,$pFolio,$pTipo,$aTipoResp[1]);
            $respuesta ='[{"ERROR":"0","MENSAJE":"Se envió correo a proveedor con el acuse de recibo o rechazo<br>Registro en SII:'.$retRegistro.'"}]';
        }else{
            $respuesta ='[{"ERROR":"0","MENSAJE":"Se envió correo a proveedor con el acuse de recibo o rechazo."}]';
        }
    }
    return $respuesta;
}

function procesaCesion($pTipo,$pFolio,$pRut,$pNombre,$pDireccion,$pMail,$pMonto,$pFecha,$pFechaEmi,$pRutReceptor,$pReceptor){
    global $conn,$msgError;
    //$msgError="Se realizo con exito el proceso de cesion del documento $pFolio";
        
    $pMonto=str_replace(array("$",",",".-"),"",$pMonto);
    $pFecha=substr($pFecha,6,4)."-".substr($pFecha,3,2)."-".substr($pFecha,0,2);
    
    
    
    if(procAec($pTipo,$pFolio,$pRut,$pNombre,$pDireccion,$pMail,$pMonto,$pFecha,$pFechaEmi,$pRutReceptor,$pReceptor)===true){
        $respuesta ='[{"ERROR":"0","MENSAJE":"'.$msgError.'"}]';
    }else{
        $respuesta ='[{"ERROR":"1","MENSAJE":"'.$msgError.'"}]';
    }
    
    return $respuesta;
}

function procAec($pTipo,$pFolio,$pRut,$pNombre,$pDireccion,$pMail,$pMonto,$pFecha,$pFechaEmi,$pRutReceptor,$pReceptor){
    global $conn,$msgError;
    $retVal=true;
    $pFono="";
    $rut_emisor=$_SESSION['rut'];
    $rut_cesionario=  str_replace(".","",$pRut);
    $FchResol=$_SESSION["FECRESOL"];
    $NumResol=$_SESSION["NUMRESOL"];
    $timezone = new DateTimeZone('America/Santiago'); 
    $date = new DateTime('', $timezone);
    $TmstFirma = $date->format('Y-m-d\TH:i:s');
    $idDoc="R".substr($_SESSION["rut"],0,-2)."T".$pTipo."F".$pFolio."_AEC";
    $idDocCedido="T".$pTipo."F".$pFolio."_Cedido";
    $idDocCesion="T".$pTipo."F".$pFolio."_Cesion";

    /*PROCESO GENERADOR DEL DOCUMENTO*/
    $xml = fopen("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDoc.".xml","w+");
	
    $caratulaXML = "<Caratula version=\"1.0\">\n"
    . "<RutCedente>" . $rut_emisor . "</RutCedente>\n"
    . "<RutCesionario>" . $rut_cesionario . "</RutCesionario>\n"
    . "<NmbContacto>".$pNombre."</NmbContacto>\n"
    . "<FonoContacto>".$pFono."</FonoContacto>\n"
    . "<MailContacto>".$pMail."</MailContacto>\n"
    . "<TmstFirmaEnvio>" . $TmstFirma . "</TmstFirmaEnvio>\n"
    . "</Caratula>\n"
    . "<Cesiones>";
    //Generamos Nodos	
	$retVal = procDteCedido($idDocCedido,substr($rut_emisor,0,-2),$pTipo,$pFolio,$_SESSION["certificado"],$_SESSION["clave"]);
    if($retVal)
    {
        $retVal = procCesion($idDocCesion,substr($rut_emisor,0,-2),$pTipo,$pFolio,$rut_cesionario,$pNombre,$pDireccion,$pMail,$pMonto,$pFecha,$pFechaEmi,$pRutReceptor,$pReceptor,$_SESSION["certificado"],$_SESSION["clave"]);
    }
    else
    {
        return false;
    }
    //Leemos Nodos
	$dteCedido = fopen("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDocCedido.".xml","r");
	$dteCediodXml=fread($dteCedido,filesize("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDocCedido.".xml"));
	$caratulaXML .= str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n","",$dteCediodXml);
	$dteCesion =fopen("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDocCesion.".xml","r");
	$dteCesionXml=fread($dteCesion,filesize("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDocCesion.".xml"));
	$caratulaXML .= str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n","",$dteCesionXml);
	$caratulaXML .="</Cesiones>\n";
		
	$DocAec = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><AEC version=\"1.0\" xmlns=\"http://www.sii.cl/SiiDte\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sii.cl/SiiDte AEC_v10.xsd\">\n"
        . "<DocumentoAEC ID=\"" . $idDoc. "\">\n" . $caratulaXML . "</DocumentoAEC>\n</AEC>";
		
	fwrite($xml,$DocAec);
	fclose($xml);
	unlink("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDocCedido.".xml");
	unlink("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDocCesion.".xml");
	
	$DTE = new DOMDocument();
	$DTE->formatOutput = TRUE;
	$DTE->preserveWhiteSpace = TRUE;
	$DTE->encoding = "ISO-8859-1";
	$DTE->load("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDoc.".xml");//"procesos/xml_procesados/$pRutEmpresa/dte/".substr($array_cesion[0],0));
	
	$xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
        $pfx = file_get_contents("../certificados/".substr($rut_emisor,0,-2)."/".$_SESSION["certificado"]);
        //$pfx = file_get_contents(dirname(__FILE__) . "/certificados/".substr($pRut,0,-2)."/".$_SESSION["certificado"]);
        openssl_pkcs12_read($pfx, $key,$_SESSION["clave"] );
    
        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
	$xmlTool->sign($DTE, "AEC");
	
	$caratulaXMLFinal= $DTE->saveXml();
	
	$xmlFinal = fopen("../documentos/cesiones/".substr($rut_emisor,0,-2)."/".$idDoc.".xml","w");
	fwrite($xmlFinal,$caratulaXMLFinal);
	fclose($xmlFinal);
        if($retVal){
            //Proceso de Envío del archivo 
            $resultado=procesaEnvio($idDoc,$pTipo,$pFolio);
            $res = explode("|",$resultado);            
            if(intval($res[0])>0 and intval($res[1])==0){
                $sql="select max(sis_dte_ces_id)+1 as id from sis_dte_cesion where sis_contribuyente_id=".$_SESSION["contribuyente"];
                $query=$conn->query($sql);
                $data = $query->fetch_assoc();
                $pSesionId = $data["id"];
                if(intval($pSesionId)<=0){
                    $pSesionId=1;
                }
                $sql="insert into  sis_dte_cesion values(".$_SESSION["contribuyente"].",$pTipo,$pFolio,$pSesionId,'$rut_cesionario','$pNombre','$pDireccion','$pMail',$pMonto,'$pFecha',current_timestamp,'".$_SESSION['usuario']."','".$res[0]."','".$res[1]."','')";
    
                $query=$conn->query($sql);
                $num = $conn->affected_rows;

                if($num>0){
                    $msgError="Se realizo el envio del archivo de cesión, el SII enviará un correo electronico indicando el estado de la operación";
                    return true;
                }else{
                    $msgError="No se pudo grabar el resultado de la cesion-".$sql;
                    return false;
                }
            }else{
                $msgError="ERROR: Problema de comunicaci&oacute;n con los servidores del S.I.I.<br>Intente nuevamente y si el problema persiste comuníquese con soporte.".$sql;
                return false;
            }
        }
        return $retVal;
}

function procDteCedido($idDocCedido,$pRutEmpresa,$pTipo,$pFolio,$certificado,$clave){
    global $conn,$msgError;
    $timezone = new DateTimeZone('America/Santiago'); 
    $date = new DateTime('', $timezone);
    $TmstFirma = $date->format('Y-m-d\TH:i:s');
    $sql="SELECT dte_archivo_xml FROM sisgenchile_sisgenfe.sis_bitacora "
            . "where sis_contribuyente_id=".$_SESSION["contribuyente"]." and dte_tipo=$pTipo and dte_folio=$pFolio  "
            . "and (dte_estado_sii='DTE ACEPTADO' or dte_estado_sii='DTE Aceptado con Reparos Leves' or dte_estado_sii='DTE Aceptado con Reparos')";
    
    $query=$conn->query($sql);
    $data = $query->fetch_assoc();
    $arcOrigen = $data["dte_archivo_xml"];
    if(empty($arcOrigen)){
        //$FileDteXml="/home/visoft/www/sisgenfe/documentos/emitidos/$pRutEmpresa/dte/null.xml";
        $FileDteXml="/home/netdte/www/procesos/xml_emitidos/$pRutEmpresa/null.xml";
    }else{
        //$FileDteXml="/home/visoft/www/sisgenfe/documentos/emitidos/$pRutEmpresa/dte/".$arcOrigen;//T".$pTipo."_F".$pFolio.".xml";
        $FileDteXml="/home/netdte/www/procesos/xml_emitidos/$pRutEmpresa/".$arcOrigen;//T".$pTipo."_F".$pFolio.".xml";
    }
  
    
    if(!file_exists($FileDteXml)){
        $msgError= "No se pudo encontrar el documento de origen intente publicarlo nuevamente o comuniquese con soporte<br>$arcOrigen";
        error_log("No se pudo encontrar el documento de origen intente publicarlo nuevamente o comuniquese con soporte<br>$arcOrigen",0);
        return false;
    }
    
    if(!$dte = fopen($FileDteXml,"r")){
        $msgError= "No se pudo leer el documento de origen $arcOrigen";
        return false;
    }else{
        $dteXml=fread($dte,filesize($FileDteXml));
        if(!$xmlCedido = fopen("../documentos/cesiones/".$pRutEmpresa."/".$idDocCedido.".xml","w+")){
            $msgError = "No se pudo crear el xml del documento cedido";
            return false;
        }
        $DteCedido="<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<DTECedido version=\"1.0\">\n"
        . "<DocumentoDTECedido ID=\"".$idDocCedido."\">".str_replace("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n","",$dteXml);
        $DteCedido.="<TmstFirma>" . $TmstFirma . "</TmstFirma>\n</DocumentoDTECedido>\n</DTECedido>";
        fwrite($xmlCedido,$DteCedido);
        fclose($xmlCedido);

        $DTECEDIDO = new DOMDocument();
        $DTECEDIDO->formatOutput = FALSE;
        $DTECEDIDO->preserveWhiteSpace = TRUE;
        $DTECEDIDO->encoding = "ISO-8859-1";
        $DTECEDIDO->load("../documentos/cesiones/".$pRutEmpresa."/".$idDocCedido.".xml");//"procesos/xml_procesados/$pRutEmpresa/dte/".substr($array_cesion[0],0));
        $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
        error_log("Tratando de firmar con ../certificados/".$pRutEmpresa."/".$certificado,0);
        error_log("Y clave ".$clave,0);
        $pfx = file_get_contents("../certificados/".$pRutEmpresa."/".$certificado);
        openssl_pkcs12_read($pfx, $key,$clave);

        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
        $xmlTool->sign($DTECEDIDO, "DTECedido");
        $XMLFinal= $DTECEDIDO->saveXml();

        $xmlCedidoFinal = fopen("../documentos/cesiones/".$pRutEmpresa."/".$idDocCedido.".xml","w");
        if(!fwrite($xmlCedidoFinal,$XMLFinal)){
            $msgError="No se pudo grabar el xml del DTE Cedido (Line 176)";
            return false;
        }
        fclose($xmlCedidoFinal);
        return true;
    }
}

function procCesion($idDocCesion,$pRutEmpresa,$pTipo,$pFolio,$pRut,$pNombre,$pDireccion,$pMail,$pMonto,$pFecha,$pFechaEmi,$pRutReceptor,$pReceptor,$certificado,$clave){
    $timezone = new DateTimeZone('America/Santiago'); 
    $date = new DateTime('', $timezone);
    $TmstFirma = $date->format('Y-m-d\TH:i:s');
    $arcCesion = fopen("../documentos/cesiones/".$pRutEmpresa."/".$idDocCesion.".xml","w+");
	
	$XmlCesion= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<Cesion version=\"1.0\">\n"
	. "<DocumentoCesion ID=\"".$idDocCesion."\">\n"
	. "<SeqCesion>1</SeqCesion>\n"
	. "<IdDTE>\n"
	. "<TipoDTE>".$pTipo."</TipoDTE>\n"
	. "<RUTEmisor>".$_SESSION["rut"]."</RUTEmisor>\n"
	. "<RUTReceptor>".$pRutReceptor."</RUTReceptor>\n"
	. "<Folio>".$pFolio."</Folio>\n"
	. "<FchEmis>".date("Y-m-d",strtotime($pFechaEmi))."</FchEmis>\n"
	. "<MntTotal>".$pMonto."</MntTotal>\n"
	. "</IdDTE>\n"
	. "<Cedente>\n"
	. "<RUT>".$_SESSION["rut"]."</RUT>\n"
	. "<RazonSocial>".$_SESSION["razon"]."</RazonSocial>\n"
	. "<Direccion>".$_SESSION["direccion"]."</Direccion>\n"
        . "<eMail>".$_SESSION["email"]."</eMail>\n"
	. "<RUTAutorizado>\n"
        . "<RUT>".$_SESSION["rut_rl"]."</RUT>\n"
        . "<Nombre>".$_SESSION["representante"]."</Nombre>\n"
        . "</RUTAutorizado>\n";
	
	$XmlCesion.="<DeclaracionJurada>Se declara bajo juramento que ".$_SESSION["representante"].", RUT ".$_SESSION["rut_rl"]
	. " ha puesto a disposicion del cesionario ".$pNombre.", RUT ".$pRut.", el o los documentos donde"
	. " constan los recibos de las mercaderias entregadas o sevicios prestados, entregados por parte del deudor de la factura "
	. $pReceptor.", RUT ".$pRutReceptor.", de acuerdo a lo establecido en la Ley N 19.983</DeclaracionJurada>\n"
	. "</Cedente>\n"
	. "<Cesionario>\n"
	. "<RUT>".$pRut."</RUT>\n"
	. "<RazonSocial>".$pNombre."</RazonSocial>\n"
	. "<Direccion>".$pDireccion."</Direccion>\n"
	. "<eMail>".$pMail."</eMail>\n"
	. "</Cesionario>\n"
	. "<MontoCesion>".$pMonto."</MontoCesion>\n"
	. "<UltimoVencimiento>".$pFecha."</UltimoVencimiento>\n"
	. "<TmstCesion>".$TmstFirma."</TmstCesion>\n"
	. "</DocumentoCesion>\n"
	. "</Cesion>";
	
	fwrite($arcCesion,$XmlCesion);
	fclose($arcCesion);
	
	$DTECESION = new DOMDocument();
	$DTECESION->formatOutput = FALSE;
	$DTECESION->preserveWhiteSpace = TRUE;
	$DTECESION->encoding = "ISO-8859-1";
	$DTECESION->load("../documentos/cesiones/".$pRutEmpresa."/".$idDocCesion.".xml");//"procesos/xml_procesados/$pRutEmpresa/dte/".substr($array_cesion[0],0));
	$xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();

        $pfx = file_get_contents("../certificados/".$pRutEmpresa."/".$certificado);
        openssl_pkcs12_read($pfx, $key,$clave);
    
        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
	$xmlTool->sign($DTECESION, "Cesion");
	$XMLFinal= $DTECESION->saveXml();
	
	$xmlCesionFinal = fopen("../documentos/cesiones/".$pRutEmpresa."/".$idDocCesion.".xml","w");
	fwrite($xmlCesionFinal,$XMLFinal);
	fclose($xmlCesionFinal);
        return true;
}

function procesaEnvio($idDoc,$pTipo,$pFolio){
    global $msgError;
    
    $model["RutEnvia"]      = $_SESSION["rut_rl"];
    $model["RutEmisor"]     = $_SESSION["rut"];
    $model["SetDTE_ID"]     = $idDoc;
    $model["pemailNotif"]   = $_SESSION["email"];
    $model["dteTipo"]       = $pTipo;
    $model["dteFolio"]      = $pFolio;
    $model["clave"] = $_SESSION['clave'];
    $model["certificado"] = $_SESSION['certificado'];  
    error_log("Estoy en respuestas. procesa envio... Clave:". $model["clave"]." certificado: ". $model["certificado"]);
    $resultado = enviarAEC($model);
    return $resultado;
}

function procRespuesta($pRut,$pFolio,$pTipo,$pTipoResp,$pMsgRechazo){
    global $conn,$msgError;
    $retVal=true;
    $pFono="";
    $rut_emisor=$_SESSION['rut'];
    $FchResol=$_SESSION["FECRESOL"];
    $NumResol=$_SESSION["NUMRESOL"];
    $pRutEmpresa=substr($_SESSION['rut'],0,-2);
    $timezone = new DateTimeZone('America/Santiago'); 
    $date = new DateTime('', $timezone);
    $TmstFirma = $date->format('Y-m-d\TH:i:s');
    $idDoc="ACUSE_RM".substr($_SESSION["rut"],0,-2)."T".$pTipo."F".$pFolio;
    $idDocResp="ACUSE_RC".substr($_SESSION["rut"],0,-2)."T".$pTipo."F".$pFolio;
    $msgError="Se informo con exito el intercambio de informacion"; 
    
    $sql="select * from sis_dte where sis_contribuyente_id=".$_SESSION["contribuyente"]
            . " and sis_dte_tipo=".$pTipo." and sis_dte_folio=".$pFolio." and sis_dte_emisor_rut='".$pRut."'";
    
    $query = $conn->query($sql);
    $dte=$query->fetch_assoc();
    
    /*PROCESO GENERADOR DEL DOCUMENTO*/
    
    switch(intval($pTipoResp)){
        case 1:
            $recepcion = new IC();
            $Resultado = new SetRecibos();
            
            $Resultado->setCaratula();
                $Resultado->Caratula->setRutResponde($rut_emisor);
                $Resultado->Caratula->setRutRecibe($pRut);
                $Resultado->Caratula->setNmbContacto($_SESSION["Nombre"]);
                $Resultado->Caratula->setFonoContacto($_SESSION["telefono"]);
                $Resultado->Caratula->setMailContacto($_SESSION["email"]);
                $fechaR = $date->format('Y-m-d\TH:i:s');
                $Resultado->Caratula->setTmstFirmaEnv($fechaTimbre);
            $Resultado->setRecibo();
            $Recibo = new DocumentoRecibo();
                $Recibo->setTipoDoc($pTipo);
                $Recibo->setFolio($pFolio);
                $Recibo->setFchEmis($dte["sis_dte_fecha_emision"]);
                $Recibo->setRUTEmisor($pRut);
                $Recibo->setRUTRecep($rut_emisor);
                $Recibo->setMntTotal($dte["sis_dte_monto"]);
                $Recibo->setRecinto("Casa Matriz");
                $Recibo->setRutFirma($_SESSION["rut_rl"]);
                $Recibo->setDeclaracion("El acuse de recibo que se declara en este acto, de acuerdo a lo dispuesto en la letra b) del Art. 4, y la letra c) del Art. 5 de la Ley 19.983, acredita que la entrega de mercaderias o servicio(s) prestado(s) ha(n) sido recibido(s).");
                $Recibo->setTmstFirmaRecibo($fechaTimbre);
            $Resultado->Recibo->setDocumentoRecibo($Recibo);
            
            $obj = new ObjectAndXML($idDoc,$pRutEmpresa);
            $obj->setStartElement("EnvioRecibos");
            $obj->setId($idDoc);
            $recepcion->SetRecibos($Resultado);
            $recordsXML = $obj->objToXML($recepcion);
            $IC_TIMBRE = new DOMDocument();
            $IC_TIMBRE->formatOutput = FALSE;
            $IC_TIMBRE->preserveWhiteSpace = TRUE;
            $IC_TIMBRE->load("/home/netdte/public_html/procesos/xml_respuestas/".substr($rut_emisor,0,-2)."/".$idDoc.".xml",$pRut);
            $IC_TIMBRE->encoding = "ISO-8859-1";
    
            $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
    
            $pfx = file_get_contents("/home/netdte/public_html/certificados/".$pRutEmpresa."/".$_SESSION["certificado"]);
            openssl_pkcs12_read($pfx, $key,$_SESSION["clave"]);
    
            $xmlTool->setPrivateKey($key["pkey"]);
            $xmlTool->setpublickey($key["cert"]);
            $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
            $xmlTool->sign($IC_TIMBRE, "RM");
            $xmlTool->sign($IC_TIMBRE, "RME");
    
            $IC_TIMBRE->save("/home/netdte/public_html/procesos/xml_respuestas/".substr($rut_emisor,0,-2)."/".$idDoc.".xml");
            enviaMail("/home/netdte/public_html/procesos/xml_respuestas/".substr($rut_emisor,0,-2)."/".$idDoc.".xml",$pRut);
               
            break;
        case 2:
        case 3:
        case 4:
            $respuesta = new IC();
            $Resultado = new Resultado();

            $Resultado->setCaratula();
                $Resultado->Caratula->setRutResponde($rut_emisor);
                $Resultado->Caratula->setRutRecibe($pRut);
                $Resultado->Caratula->setIdRespuesta("00001");
                $Resultado->Caratula->setNroDetalles("1");
                $Resultado->Caratula->setFonoContacto($_SESSION["telefono"]);
                $Resultado->Caratula->setMailContacto($_SESSION["email"]);
                $fechaR = $TmstFirma;
                $Resultado->Caratula->setTmstFirmaResp($fechaR);
                $Resultado->setRecepcionEnvio();
            $ResultadoDTE = new ResultadoDTE();
                $ResultadoDTE->setTipoDTE($pTipo);
                $ResultadoDTE->setFolio($pFolio);
                $ResultadoDTE->setFchEmis($dte["sis_dte_fecha_emision"]);
                $ResultadoDTE->setRUTEmisor($pRut);
                $ResultadoDTE->setRUTRecep($rut_emisor);
                $ResultadoDTE->setMntTotal($dte["sis_dte_monto"]);
                $ResultadoDTE->setCodEnvio("1");
                if($pTipoResp=="2"){
                    $ResultadoDTE->setEstadoDTE("0");
                    $ResultadoDTE->setEstadoDTEGlosa("DTE Recibido OK");
                    //$ResultadoDTE->setCodRchDsc($array_valores[9]);
                }else if($pTipoResp=="3"){
                    $ResultadoDTE->setEstadoDTE("1");
                    $ResultadoDTE->setEstadoDTEGlosa("DTE Aceptado con Discrepancia - ".$pMsgRechazo);
                    //$ResultadoDTE->setCodRchDsc($array_valores[9]);
                }else if($pTipoResp=="4"){
                    $ResultadoDTE->setEstadoDTE("2");
                    $ResultadoDTE->setEstadoDTEGlosa("DTE Rechazado - ".$pMsgRechazo);
                    //$ResultadoDTE->setCodRchDsc($array_valores[9]);
                }
                $Resultado->setResultadoDTE($ResultadoDTE);
                $obj = new ObjectAndXML($idDocResp,$pRutEmpresa);
                $obj->setStartElement("RespuestaDTE");
                $obj->setId($idDocResp);
                $respuesta->setResultado($Resultado);
                //utf8_encode_deep($respuesta);
                $recordsXML = $obj->objToXML($respuesta);
            
                $IC_TIMBRE = new DOMDocument();
                $IC_TIMBRE->formatOutput = FALSE;
                $IC_TIMBRE->preserveWhiteSpace = TRUE;
                $IC_TIMBRE->load("/home/netdte/public_html/procesos/xml_respuestas/".substr($rut_emisor,0,-2)."/".$idDocResp.".xml");
                $IC_TIMBRE->encoding = "ISO-8859-1";
                $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
    
                $pfx = file_get_contents("/home/netdte/public_html/certificados/".$pRutEmpresa."/".$_SESSION["certificado"]);
                openssl_pkcs12_read($pfx, $key,$_SESSION["clave"]);
                $xmlTool->setPrivateKey($key["pkey"]);
                $xmlTool->setpublickey($key["cert"]);
                $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
                $xmlTool->sign($IC_TIMBRE, "RC");
                $IC_TIMBRE->save("/home/netdte/public_html/procesos/xml_respuestas/".substr($rut_emisor,0,-2)."/".$idDocResp.".xml");
                enviaMail("/home/netdte/public_html/procesos/xml_respuestas/".substr($rut_emisor,0,-2)."/".$idDocResp.".xml",$pRut);
            break;
    }
    return $msgError;
}

function porcRegistroRoA($pRut,$pFolioDte,$pTipoDoc,$pTipoResp){
    $url = "https://ws1.sii.cl/WSREGISTRORECLAMODTE/registroreclamodteservice?wsdl";
    $soapClient = new SoapClient($url);
    $pRutEmisor = substr($pRut,0,-2);
	$pDvEmisor = substr($pRut,-1);
    $model["certificado"]=$_SESSION["certificado"];
    $model["clavefirma"]=$_SESSION["clave"];
    $model["RutEmisor"]=$_SESSION["rut"];	
    $tokenSII = strval(ConexionAutomaticaSII($model));
    $soapClient->__setCookie("TOKEN",$tokenSII );
    try
    {
        error_log("Funcion : ingresarAceptacionReclamoDoc(".$pRutEmisor.",".$pDvEmisor.",".$pTipoDoc.",".$pFolioDte.",".$pTipoResp.");",0);
        $result = $soapClient->ingresarAceptacionReclamoDoc($pRutEmisor,$pDvEmisor,$pTipoDoc,$pFolioDte,$pTipoResp);
        $aResult = get_object_vars($result);
        error_log("Return:".$aResult["descResp"],0);
        $answer ='[{"ERROR":"0","MENSAJE":"'.$aResult["descResp"].'"}]';
    }
    catch(SoapFault $exception)
    {
        if($exception->getMessage()=="Service Temporarily Unavailable"){    
            return "Service Temporarily Unavailable";
        }
    }
    return $aResult["descResp"];
}

function escribeLog($texto){
    global $ficheroLog;
    file_put_contents($ficheroLog,"[".date("H:i:s")."]".$texto."\n",FILE_APPEND);
}

function utf8_encode_deep(&$input) {
    if (is_string($input)) {
        $input = utf8_encode($input);
    } else if (is_array($input)) {
        foreach ($input as &$value) {
            utf8_encode_deep($value);
        }

        unset($value);
    } else if (is_object($input)) {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var) {
            utf8_encode_deep($input->$var);
        }
    }
}

function enviaMail($dte,$pRut){
    global $conn;
    
    $sql="select correo_dte from sisgenchile_sisgenfe.sii_empresa_dte where empresa_dte_id='".substr($pRut,0,-2)."'";
    if($query=$conn->query($sql)){
        $dteQ=$query->fetch_assoc();
        $correo_cliente = $dteQ["correo_dte"];
        $mail = new PHPMailer;
        $mail->IsSMTP();
        $mail->Host = 'mail.sisgenchile.com';
       $mail->SMTPAuth = true;
       $mail->Username = "dte@sisgenchile.com";
       $mail->Password = "dtesisgen2017";
       $mail->SMTPSecure ='tls';
       $mail->Port=587; 

        $mail->SetFrom('dte@sisgenchile.com', 'Sisgen Chile - Facturacion Electronica');
        $mail->AddAddress($correo_cliente);
        
        $mail->Subject = "Intercambio de informacion";
        $mail->MsgHTML("Adjuntamos respuesta por el envio de DTE");
        $mail->AddAttachment($dte);

        if(!$mail->Send()) {
            escribeLog("Mailer Error: " . $mail->ErrorInfo,$ficheroLog);
            echo ("Mailer Error: " . $mail->ErrorInfo."\n");
        } else {
            if($DesdeDte){
                escribeLog("Se envío el dte al correo ".$mailCliente."\n Se adjunto el documento\n\t".$fuente_sobre,$ficheroLog);
            }else{
                escribeLog ("Message sent!".$body."\n\t".$fuente_sobre,$ficheroLog);
            }
        }
    }else{
        echo "**WARNING**|No se pudo rescatar el correo electrónico del cliente ";
        $correo_cliente = "";
    }
}