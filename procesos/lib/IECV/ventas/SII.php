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
        try{
            require_once("xmlseclibs/XmlseclibsAdapter.php");
            $fuente_cfg="procesos/config/".substr($model["RutEmisor"],0,-2)."/config.ini";
            $archivoConfig = parse_ini_file($fuente_cfg,true);
		
            $url = "http://localhost:8080/CrSeed.jws?wsdl";
            $soapClient = new SoapClient($url);
            $_msg="<B>RESPUESTA DE LA CONEXION</B><br>" ;
            $result = $soapClient->getSeed();
            if($archivoConfig["opcionales"]["debug"]==1){
                echo $_msg;
            }
        }catch(SoapFault $exception){
            $_msg.="ERROR <br/>";
            $_msg.=$exception->getTraceAsString();
            $_msg.=$exception->getMessage();
            echo $_msg;
            die();
        }

        $seed = new DOMDocument;
        $seed->loadXML($result);
        $semilla = $seed->getElementsByTagName("SEMILLA")->item(0)->nodeValue;
        $estado = $seed->getElementsByTagName("ESTADO")->item(0)->nodeValue;
        
        
        if($estado == "00"){
        $xmlr = simplexml_load_string($result);
        $seed_file="procesos/semillas/" . substr($model["RutEmisor"],0,-2)."/seed_".$model["SetDTE_ID"] . "_".rand(10000,20000).".xml";
        $xmlr->saveXML($seed_file);           
        $body = "<getToken><item><Semilla>$semilla</Semilla></item></getToken>";
        $dom = new DOMDocument;
        $dom->formatOutput = FALSE;
        $dom->preserveWhiteSpace = TRUE;

        $dom->loadXML($body);
        $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
        //$pfx = file_get_contents(dirname(__FILE__) . "/procesos/certificado/".$archivoConfig["generales"]["certificado"]);
         $pfx = file_get_contents("procesos/certificado/".substr($model["RutEmisor"],0,-2)."/".$archivoConfig["generales"]["certificado"]);
	
        openssl_pkcs12_read($pfx, $key, $archivoConfig["generales"]["clavefirma"]);
        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
        $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($dom);
        $dom->save($seed_file);
        
        $TokenClient = new SoapClient('http://localhost:8080/GetTokenFromSeed.jws?WSDL');
        $rs = $TokenClient->getToken($dom->saveXML());

        
        
       /******* FIRMAR SEMILLA *******/;        
        
        /***** OBTENER EL TOKEN *****/
       	$formato = str_replace( 'SII:', '', $rs );
        //print_r($formato);
	$xml = simplexml_load_string($formato);
        $tokenSII = $xml->RESP_BODY->TOKEN;
        /***** OBTENER EL TOKEN *****/  
        unlink($seed_file);
        return $tokenSII;
                
        }else{
            echo "Error con conexion SII, Error numero : " . $estado;

        }
}



function enviarAEC($model){
    $tockenSII = ConexionAutomaticaSII($model);
    $fuente_cfg="procesos/config/".substr($model["RutEmisor"],0,-2)."/config.ini";
    $archivoConfig = parse_ini_file($fuente_cfg,true);
    $debug=$archivoConfig["opcionales"]["debug"];
    
     $pemailNotif="";
     $rutCompany="";
     $dvCompany="";
     $pArchivoAEC="procesos/xml_cesion/" . $pRutEmpresa."/".$model["SetAEC_ID"] . ".xml";
     
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
    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("POST /cgi_rtc/RTC/RTCAnotEnvio.cgi HTTP/1.0", "Expect:", "accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg,application/vnd.ms-powerpoint, application/ms-excel,application/msword, */*", "Referer: http://sisgenfe.sisgenchile.cl", "Accept-Language: es-cl", "Content-Type:multipart/form-data: boundary=7d23e2a11301c4", "Accept-Encoding: gzip, deflate", "User-Agent: $agent", "Host: ".$pAmbiente.".sii.cl", "Content-Length: $bodyLength", "Connection: keep-alive", "Cache-Control: no-cache", "Cookie: TOKEN=$tokenSII")); 
    
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
    return $TRACKID."|".$estadoUpload."|".$rspUpload."|".$model["SetDTE_ID"];

}

function enviarAlSii($model){
    $tokenSII = ConexionAutomaticaSII($model);
    $TRACKID=0;
    $fuente_cfg="procesos/config/".substr($model["RutEmisor"],0,-2)."/config.ini";
    $archivoConfig = parse_ini_file($fuente_cfg,true);
    $debug=$archivoConfig["opcionales"]["debug"];

    /* @var $model EnviosDtes */        
    $pRutEnvia   = substr ($model["RutEnvia"],0, -2);
    $pDigEnvia   = substr ($model["RutEnvia"], -1);
    $pRutEmpresa  =  substr ($model["RutEmisor"],0, -2);
    $pDigEmpresa  = substr ($model["RutEmisor"], -1);  
    $pAmbiente = $model["ambiente"];
	
    $file = "procesos/xml_envios/" . $pRutEmpresa."/".$model["SetDTE_ID"] . ".xml";
    $data = array('rutSender'=>$pRutEnvia,'dvSender'=>$pDigEnvia,'rutCompany'=>$pRutEmpresa,'dvCompany'=>$pDigEmpresa,'archivo'=>$model["SetDTE_ID"] . ".xml");
    $agent = "Mozilla/4.0 (compatible; PROG 1.0; Windows NT 5.0; YComp 5.0.2.4)";
    $boundary = '--7d23e2a11301c4';
    $cuerpo = multipart_build_query($data, $boundary, $file);
    $bodyLength = strlen($cuerpo);  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_URL, "https://".$pAmbiente.".sii.cl/cgi_dte/UPL/DTEUpload");
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
    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
    //curl_setopt($ch, CURLOPT_ENCODING,'identity'); //se en el log aparece basura descomentar

    curl_setopt($ch, CURLOPT_HTTPHEADER, array("POST /cgi_dte/UPL/DTEUpload HTTP/1.0", "Expect:", "accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg,application/vnd.ms-powerpoint, application/ms-excel,application/msword, */*", "Referer: http://sisgenfe.sisgenchile.com", "Accept-Language: es-cl", "Content-Type:multipart/form-data: boundary=7d23e2a11301c4", "Accept-Encoding: gzip, deflate", "User-Agent: $agent", "Host: ".$pAmbiente.".sii.cl", "Content-Length: $bodyLength", "Connection: keep-alive", "Cache-Control: no-cache", "Cookie: TOKEN=$tokenSII")); 

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
        $rspUpload = "El Sender no tiene permiso para enviar";
    }

    if($estadoUpload == 2){
        $rspUpload = "Error en tamaño del archivo(muy grande o muy chico)";
    }

    if($estadoUpload == 3){
        $rspUpload = "Archivo cortado(tamano <> al parametro size";
    }

    if($estadoUpload == 5){
        $rspUpload = "No esta autenticado";
    }

    if($estadoUpload == 6){
        $rspUpload = "Empresa no autorizada a enviar archivos";
    }

    if($estadoUpload == 7){
        $rspUpload = "Esquema Invalido";
    }

    if($estadoUpload == 8){
        $rspUpload = "Firma del Documento";
    }

    if($estadoUpload == 9){
        $rspUpload = "Sistema Bloqueado";
    }

    return $TRACKID."|".$estadoUpload."|".$rspUpload."|".$model["SetDTE_ID"];

}

function enviarIECVAlSii($model){
    $tokenSII = ConexionAutomaticaSII($model);
    $TRACKID=0;
    $fuente_cfg="procesos/config/".substr($model["RutEmisor"],0,-2)."/config.ini";
    $archivoConfig = parse_ini_file($fuente_cfg,true);
    $debug=$archivoConfig["opcionales"]["debug"];

    /* @var $model EnviosDtes */        
    $pRutEnvia   = substr ($model["RutEnvia"],0, -2);
    $pDigEnvia   = substr ($model["RutEnvia"], -1);
    $pRutEmpresa  =  substr ($model["RutEmisor"],0, -2);
    $pDigEmpresa  = substr ($model["RutEmisor"], -1);  
    $pAmbiente = $model["ambiente"];
	
    $file = "procesos/xml_libros/" . $pRutEmpresa."/".$model["tipoLibro"]."/".$model["SetDTE_ID"] . ".xml";
    $data = array('rutSender'=>$pRutEnvia,'dvSender'=>$pDigEnvia,'rutCompany'=>$pRutEmpresa,'dvCompany'=>$pDigEmpresa,'archivo'=>$model["SetDTE_ID"] . ".xml");
            $agent = "Mozilla/4.0 (compatible; PROG 1.0; Windows NT 5.0; YComp 5.0.2.4)";
    $boundary = '--7d23e2a11301c4';
    $cuerpo = multipart_build_query($data, $boundary, $file);
    $bodyLength = strlen($cuerpo);  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_URL, "https://".$pAmbiente.".sii.cl/cgi_dte/UPL/DTEUpload");
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
    curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
    //curl_setopt($ch, CURLOPT_ENCODING,'identity'); //se en el log aparece basura descomentar

    curl_setopt($ch, CURLOPT_HTTPHEADER, array("POST /cgi_dte/UPL/DTEUpload HTTP/1.0", "Expect:", "accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg,application/vnd.ms-powerpoint, application/ms-excel,application/msword, */*", "Referer: http://sisgenfe.sisgenchile.com", "Accept-Language: es-cl", "Content-Type:multipart/form-data: boundary=7d23e2a11301c4", "Accept-Encoding: gzip, deflate", "User-Agent: $agent", "Host: ".$pAmbiente.".sii.cl", "Content-Length: $bodyLength", "Connection: keep-alive", "Cache-Control: no-cache", "Cookie: TOKEN=$tokenSII")); 
    //echo $cuerpo;

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
        $rspUpload = "El Sender no tiene permiso para enviar";
    }

    if($estadoUpload == 2){
        $rspUpload = "Error en tamano del archivo(muy grande o muy chico)";
    }

    if($estadoUpload == 3){
        $rspUpload = "Archivo cortado(tamano <> al parametro size";
    }

    if($estadoUpload == 5){
        $rspUpload = "No esta autenticado";
    }

    if($estadoUpload == 6){
        $rspUpload = "Empresa no autorizada a enviar archivos";
    }

    if($estadoUpload == 7){
        $rspUpload = "Esquema Invalido";
    }

    if($estadoUpload == 8){
        $rspUpload = "Firma del Documento";
    }

    if($estadoUpload == 9){
        $rspUpload = "Sistema Bloqueado";
    }
    return $TRACKID."|".$estadoUpload."|".$rspUpload."|".$model["SetDTE_ID"];

}        


function buildSign($toSign, $privkey) { //para generar el timbre
    global $ficheroLog;
    escribelog("\tFirmando:" .$toSign,$ficheroLog);
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
  $ruta = realpath($file);

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
    
    $dom->save("procesos/respuestas/" . substr($model["RutEmisor"],0,-2)."/R_".$model[3].".xml");
 }
 

?>
