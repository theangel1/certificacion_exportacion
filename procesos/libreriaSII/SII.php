<?php
/*+++++++++++++++++++
 *Script que realiza de forma automatica el envio de DTE y Libros 
 * al SII.
 * Parte de las librerias de FacturaCloudWS
 * Version 2.0
 * REV 2
 *  
 */
function ConexionAutomaticaSII($model){        
    $debug=0;
        try{
            require_once("xmlseclibs/XmlseclibsAdapter.php");
            
            //$url = "https://palena.sii.cl/DTEWS/CrSeed.jws?WSDL";
            //$soapClient = new SoapClient($url);
			$options = ['stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ])];
			
			$soapClient = new SoapClient('https://palena.sii.cl/DTEWS/CrSeed.jws?WSDL', $options);
            $_msg="<B>RESPUESTA DE LA CONEXION</B><br>" ;
            $result = $soapClient->getSeed();
        }catch(SoapFault $exception){
            $_msg.="ERROR <br/>";
            $_msg.=$exception->getTraceAsString();
            $_msg.=$exception->getMessage();
            return $exception->getMessage();
        }
        #error_log("Conexion al SII from libreriasSII/sii.php",0);
        #error_log($result,0);
        #error_log($_msg,0);
        $seed = new DOMDocument;
        $seed->loadXML($result);
        $semilla = $seed->getElementsByTagName("SEMILLA")->item(0)->nodeValue;
        $estado = $seed->getElementsByTagName("ESTADO")->item(0)->nodeValue;
        
        
        if($estado == "00"){
        $xmlr = simplexml_load_string($result);
        $seed_file="/home/netdte/www/procesos/semillas/seed" . rand(1000,20000000).".xml";
        $xmlr->saveXML($seed_file);           
        $body = "<getToken><item><Semilla>$semilla</Semilla></item></getToken>";
        $dom = new DOMDocument;
        $dom->formatOutput = FALSE;
        $dom->preserveWhiteSpace = TRUE;

        $dom->loadXML($body);
        $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
        //$pfx = file_get_contents(dirname(__FILE__) . "/procesos/certificado/".$archivoConfig["generales"]["certificado"]);
        
         $pfx = file_get_contents("/home/netdte/www/certificados/".substr($model["RutEmisor"],0,-2)."/".$_SESSION["certificado"]);
	
        openssl_pkcs12_read($pfx, $key, $_SESSION["clave"]);
        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
        $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($dom);
        $dom->save($seed_file);
        
        try{
           // $TokenClient = new SoapClient('https://palena.sii.cl/DTEWS/GetTokenFromSeed.jws?WSDL');
		   	$optionsDev = ['stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ])];
			$TokenClient = new SoapClient('https://palena.sii.cl/DTEWS/GetTokenFromSeed.jws?WSDL', $optionsDev);
            $rs = $TokenClient->getToken($dom->saveXML());
        }catch(SoapFault $exception){
            return $exception->getMessage();
        }

        
        
       /******* FIRMAR SEMILLA *******/;        
        
        /***** OBTENER EL TOKEN *****/
       	$formato = str_replace( 'SII:', '', $rs );
        //print_r($formato);
	   $xml = simplexml_load_string($formato);
        $tokenSII = $xml->RESP_BODY->TOKEN;
        /***** OBTENER EL TOKEN *****/  
        unlink($seed_file);
        #error_log("Token SII:".$tokenSII,0);
        return $tokenSII;
                
        }else{
            echo "Error con conexion SII, Error numero : " . $estado;

        }
}



function enviarAEC($model){
    $tockenSII = ConexionAutomaticaSII($model);
    
    if($tockenSII!="Service Temporarily Unavailable"){
        $pAmbiente="palena";
        $debug=0;
        $pArchivoAEC="../documentos/cesiones/".substr($model["RutEmisor"],0,-2)."/".$model["SetDTE_ID"].".xml";
        error_log("Enviado documento de cesion " .$pArchivoAEC,0);
        $file=$model["SetDTE_ID"].".xml";
        $rutSender = substr($model["RutEnvia"],0,-2);
        $dvSender = substr($model["RutEnvia"],-1);
        if($rutSender==""){
            return "0|Rut Envio vacio||";
        }

        $data = array('emailNotif'=>$model["pemailNotif"],'rutSender'=>$rutSender,'dvSender'=>$dvSender,'rutCompany'=>substr($model["RutEmisor"],0,-2),'dvCompany'=>substr($model["RutEmisor"],-1),'archivo'=>$model["SetDTE_ID"] . ".xml");
        $agent = "Mozilla/4.0 (compatible; PROG 1.0; Windows NT 5.0; YComp 5.0.2.4)";
        $boundary = '--7d23e2a11301c4';
        $cuerpo = multipart_build_query($data, $boundary, $file);
        $bodyLength = strlen($cuerpo);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_URL, "https://".$pAmbiente.".sii.cl/cgi_rtc/RTC/RTCAnotEnvio.cgi");
        curl_setopt($ch, CURLOPT_PORT , 443);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $cuerpo);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        //curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("POST /cgi_rtc/RTC/RTCAnotEnvio.cgi HTTP/1.0", "Expect:", "accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg,application/vnd.ms-powerpoint, application/ms-excel,application/msword, */*", "Referer: http://sisgenfe.sisgenchile.cl", "Accept-Language: es-cl", "Content-Type:multipart/form-data: boundary=7d23e2a11301c4", "Accept-Encoding: gzip, deflate", "User-Agent: $agent", "Host: ".$pAmbiente.".sii.cl", "Content-Length: $bodyLength", "Connection: keep-alive", "Cache-Control: no-cache", "Cookie: TOKEN=".$tockenSII)); 

        $resposeText = curl_exec($ch);
        $resposeInfo = curl_getinfo($ch);
        if($debug==1){
            echo $resposeText;
        }
        curl_close ($ch);
        $estadoUpload = getTextBetweenTagsENV($resposeText, 'STATUS');
        $rspUpload = "";

        if($estadoUpload == 0){
            $rspUpload = "Upload OK";
            $TRACKID = getTextBetweenTagsENV($resposeText, 'TRACKID');
            //echo $rspUpload . "<br /> TRACKID " . $TRACKID;
            //$model->trackid = $TRACKID;          
        }

        if($estadoUpload == 1){
            $rspUpload = "Rut usuario autenticado no tiene permiso para enviar en empresa Cedente";
        }

        if($estadoUpload == 2){
            $rspUpload = "Error en tamaño del archivo(muy grande o muy chico)";
        }

        if($estadoUpload == 4){
            $rspUpload = "Falta parametros de entrada";
        }

        if($estadoUpload == 5){
            $rspUpload = "Error de autenticación, TOKEN inválido, no existe o esta expirado";
        }

        if($estadoUpload == 6){
            $rspUpload = "Empresa no autorizada a enviar archivos";
        }

        if($estadoUpload == 9){
            $rspUpload = "Sistema Bloqueado";
        }

        if($estadoUpload == 10){
            $rspUpload = "Error Interno";
        }
        error_log("TrackID de cesion:...".$TRACKID,0);
        return $TRACKID."|".$estadoUpload."|".$rspUpload."|".$model["SetDTE_ID"];
    }else{
        return "|99|Sistema No Disponible|".$model["SetDTE_ID"];
    }
}

function buildSign($toSign, $privkey) { //para generar el timbre
    global $ficheroLog;
    error_log("\tFirmando:" .$toSign,0);
    $signature = null;
    $priv_key = $privkey;
    //$pub_key = openssl_get_publickey($publickey);
    $pkeyid = openssl_get_privatekey($priv_key);

    openssl_sign($toSign, $signature, $priv_key, OPENSSL_ALGO_SHA1);

    /*
    $ok = openssl_verify($toSign, $signature, $pub_key);

    if ($ok == 1) {
     //   echo "VALIDA";
    } elseif ($ok == 0) {
     //   echo "NO VALIDA";
    } else {
        echo "error: ".openssl_error_string();
    }
    */
    openssl_free_key($pkeyid);
    $base64 = base64_encode($signature);
    return $base64;
}


 function getTextBetweenTagsENV($string, $tagname)
 {
    $pattern = "/<$tagname>(.*?)<\/$tagname>/";
    preg_match($pattern, $string, $matches);
    return $matches[1];
 } 
 
 function multipart_build_query($fields, $boundary, $file = ""){
  $retval = '';
  $ruta = "../documentos/cesiones/".substr($_SESSION["rut"],0,-2)."/$file";

  foreach($fields as $key => $value){
      if($key=="archivo"){
       $retval .= "$boundary\r\nContent-Disposition: form-data; name=\"$key\"; filename=\"$ruta\"\r\n"; 
       $retval .= "Content-Type: text/xml\r\n\r\n";
       $dom4 = new DOMDocument;
       $dom4->formatOutput = False;
       $dom4->preserveWhiteSpace = True;
       $dom4->loadXML(file_get_contents($ruta));
       $retval .= $dom4->saveXML() . "\r\n\r\n";  
      }else{
       $retval .= "$boundary\r\nContent-Disposition: form-data; name=\"$key\"\r\n\r\n$value\r\n";  
      }

  }
  $retval .= "$boundary--\r\n";
  return $retval;
}

function creaRespuesta($model){
    $dom = new DOMDocument("1.0");
    $root = $dom->createElement("FACELEC:RESP_HDR");
        $dom->appendChild($root);
        $estado = $dom->createElement("ESTADO");
        $root->appendChild($estado);
        $valest = $dom->createTextNode($model[2]);
        $estado->appendChild($valest);
        
        $glosa = $dom->createElement("GLOSA_ESTADO");
        $root->appendChild($glosa);
        $valglosa = $dom->createTextNode($model[1]);
        $glosa->appendChild($valglosa);
        
        $track = $dom->createElement("TRACKID");
        $root->appendChild($track);
        $valtrack = $dom->createTextNode($model[0]);
        $track->appendChild($valtrack);
    
    $dom->save("../procesos/respuestas/R_" . substr($model["RutEmisor"],0,-2)."_A".$model["SetDTE_ID"].".xml");
 }

function QueryEstDte($model){
    $conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
    
    $tokenSII = ConexionAutomaticaSII($model);
    while ($tockenSII=="Service Temporarily Unavailable"){
        $tokenSII = ConexionAutomaticaSII($model);
    }
    $TRACKID=0;


    //$url = "https://palena.sii.cl/DTEWS/QueryEstDte.jws?WSDL";
	$options = ['stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ])];
    $soapClient = new SoapClient('https://palena.sii.cl/DTEWS/QueryEstDte.jws?WSDL', $options);
    
    /* @var $model EnviosDtes */        
    $pRutConsultante = substr($model["RutConsultante"],0,-2); $pDvConsultante   = substr($model["RutConsultante"],-1);
    $pRutCompania = substr($model["RutCompania"],0,-2); $pDvCompania = substr($model["RutCompania"],-1);
    $pRutreceptor = substr($model["Rutreceptor"],0,-2); $pDvReceptor = substr($model["Rutreceptor"],-1);
    $retorno=false;
    while(!$retorno){
        try{
            $result = $soapClient->getEstDte($pRutConsultante,$pDvConsultante,$pRutCompania,$pDvCompania,$pRutreceptor,$pDvReceptor,$model["TipoDte"],$model["FolioDte"],$model["FechaEmisionDte"],$model["MontoDte"],$tokenSII);
            $retorno=true;
        }catch(SoapFault $exception){
            if($exception->getMessage()=="Service Temporarily Unavailable"){
                sleep(2);
                $retorno=false;
            }else{
                echo $exception->getMessage();
                $retorno=false;
            }
        }
    }
    $consulta = new DOMDocument;
    $consulta->loadXML($result);
    //print_r($result);
    
    $estado = $consulta->getElementsByTagName("ESTADO")->item(0)->nodeValue;
    $glosa = $consulta->getElementsByTagName("GLOSA")->item(0)->nodeValue;
    $glosaEstado = $consulta->getElementsByTagName("GLOSA_ESTADO")->item(0)->nodeValue;
    $glosaError= $consulta->getElementsByTagName("GLOSA_ERR")->item(0)->nodeValue;
    switch($model["TipoDte"]){
        case 33:
            $tipoDocumento="Factura Electr&oacute;nica";
            break;
        case 34:
            $tipoDocumento="Factura Exenta Electr&oacute;nica";
            break;
        case 61:
            $tipoDocumento="Nota de Cr&eacute;dito Electr&oacute;nica";
            break;
        case 52:
            $tipoDocumento="Gu&iacute;a de Despacho Electr&oacute;nica";
            break;
        case 56:
            $tipoDocumento="Nota de D&eacute;bito Electr&oacute;nica";
            break;
    }
    $html="<tr><td>$tipoDocumento</td><td>".$model["FolioDte"]."</td><td>".$glosaEstado."</td><td>".substr($glosaError,0,30)."</td></tr>";
    if(trim(substr($glosaError,0,30))=="Documento Recibido por el SII"){
        $sql="update sis_bitacora set dte_estado_sii='DTE ACEPTADO',dte_estado_detalle='DTE ACEPTADO' where sis_contribuyente_id=".$model["contribuyente"]." "
                . "and dte_tipo=".$model["TipoDte"]." and dte_folio=".$model["FolioDte"];
        $query = $conn->query($sql);
    }
    return $html;
}

function QueryEstUp($model){
    
    $tokenSII = ConexionAutomaticaSII($model);
    while ($tockenSII=="Service Temporarily Unavailable"){
        $tokenSII = ConexionAutomaticaSII($model);
    }
    $TRACKID=0;


    //$url = "https://palena.sii.cl/DTEWS/QueryEstUp.jws?WSDL";
    
	$options = ['stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ])];
			$soapClient = new SoapClient('https://palena.sii.cl/DTEWS/QueryEstUp.jws?WSDL', $options);
    
    /* @var $model EnviosDtes */        
    $pRutConsultante = substr($model["RutEmisor"],0,-2);
     $pDvConsultante   = substr($model["RutEmisor"],-1);
    //$retorno=false;
    //while(!$retorno){
        try{
            error_log("soapClient->getEstUp(".$pRutConsultante.",".$pDvConsultante.",".$model["trackid"].",".$tokenSII.")",0);
            
            $result = $soapClient->getEstUp($pRutConsultante,$pDvConsultante,$model["trackid"],$tokenSII);
            //$retorno=true;
        }catch(SoapFault $exception){
            if($exception->getMessage()=="Service Temporarily Unavailable"){
                sleep(2);
                $retorno="1|Service Temporarily Unavailable||";
            }else{
                
                $retorno="1|".$exception->getMessage()."||";
            }
        }
    //}
    $consulta = new DOMDocument;
    $consulta->loadXML($result);
    error_log($result,0);
    
    $estado = $consulta->getElementsByTagName("ESTADO")->item(0)->nodeValue;
    $glosa = $consulta->getElementsByTagName("GLOSA")->item(0)->nodeValue;
    switch($estado){
        case "RSC":
            $glosaError="DTE Rechazado por Error en Schema";
            break;
        case "SOK":
            $glosaError="Schema Validado";
            break;
        case "CRT":
            $glosaError="Carátula OK";
            break;
        case "RFR":
            $glosaError="DTE Rechazado por Error en Firma";
            break;
        case "FOK":
            $glosaError="Firma de Envió Validada";
            break;
        case "PDR":
            $glosaError="Envio en Proceso";
            break;
        case "RCT":
            $glosaError="DTE Rechazado por Error en Carátula";
            break;
        case "RCT":
            $glosaError="DTE Aceptado";
            break;
        default:
            return "1|".$glosa."||";
    }
    return "0||$estado|$glosaError";
}

function QueryEstCesion($model){
    
    $tokenSII = ConexionAutomaticaSII($model);
    while ($tockenSII=="Service Temporarily Unavailable"){
        $tokenSII = ConexionAutomaticaSII($model);
    }
    $TRACKID=0;
	$options = ['stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ])];

    //$url = "https://palena.sii.cl/DTEWS/services/wsRPETCConsulta?WSDL";
    $soapClient = new SoapClient('https://palena.sii.cl/DTEWS/services/wsRPETCConsulta?WSDL', $options);
    
    /* @var $model EnviosDtes */        
    $pRutConsultante = substr($model["RutEmisor"],0,-2); 
    $pDvConsultante   = substr($model["RutEmisor"],-1);
    //$retorno=false;
    //while(!$retorno){
        try{
            error_log("soapClient->getEstCesion(".$tokenSII.",".$pRutConsultante.",".$pDvConsultante.",".$model["tipodoc"].",".$model["foliodoc"].",)",0);
            
            $result = $soapClient->getEstCesion($tokenSII,$pRutConsultante,$pDvConsultante,$model["tipodoc"],$model["foliodoc"]);
            //$retorno=true;
        }catch(SoapFault $exception){
            if($exception->getMessage()=="Service Temporarily Unavailable"){    
                sleep(2);
                $retorno="1|Service Temporarily Unavailable||";
            }else{
                
                $retorno="1|".$exception->getMessage()."||";
            }
        }
    //}
    $consulta = new DOMDocument;
    $consulta->loadXML($result);
    //error_log($result,0);
    
    $estado = $consulta->getElementsByTagName("ESTADO")->item(0)->nodeValue;
    $glosa = $consulta->getElementsByTagName("GLOSA")->item(0)->nodeValue;
    return "$glosa";
}
function wsDTECorreo($model){
    
    $tokenSII = ConexionAutomaticaSII($model);
    while ($tockenSII=="Service Temporarily Unavailable"){
        $tokenSII = ConexionAutomaticaSII($model);
    }
    $TRACKID=0;


    //$url = "https://palena.sii.cl/DTEWS/wsDTECorreo.jws?WSDL";
    //$soapClient = new SoapClient($url);
	//flagship
	$options = ['stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ])];
			
			$soapClient = new SoapClient('"https://palena.sii.cl/DTEWS/wsDTECorreo.jws?WSDL', $options);
    
    /* @var $model EnviosDtes */        
    $pRutEmpresa = substr($model["RutEmisor"],0,-2); $pDvEmpresa   = substr($model["RutEmisor"],-1);
    $pTrackId = $model["TrackId"];
    $retorno=false;
    while(!$retorno){
        try{
            $result = $soapClient->reenvioCorreo($pRutEmpresa,$pDvEmpresa,$pTrackId,$tokenSII);
            $retorno=true;
        }catch(SoapFault $exception){
            if($exception->getMessage()=="Service Temporarily Unavailable"){
                sleep(2);
                $retorno=false;
            }else{
                echo $exception->getMessage();
                $retorno=false;
            }
        }
    }
    $consulta = new DOMDocument;
    $consulta->loadXML($result);
    //print_r($result);
    
    $estado = $consulta->getElementsByTagName("ESTADO")->item(0)->nodeValue;
    $glosa = $consulta->getElementsByTagName("GLOSA")->item(0)->nodeValue;
    
       
    return $estado."|".$glosa;
}