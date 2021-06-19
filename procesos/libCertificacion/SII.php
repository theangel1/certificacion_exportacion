<?php
function ConexionAutomaticaSII($model){        
       
        try
		{
            require_once("xmlseclibs/XmlseclibsAdapter.php");            		            
			 $options = ['stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
			])];
            $soapClient = new SoapClient('https://maullin.sii.cl/DTEWS/CrSeed.jws?WSDL', $options);
            $_msg="RESPUESTA DE LA CONEXION" ;
            $result = $soapClient->getSeed();
			echo '<br>'.$_msg;
        }
		catch(SoapFault $exception)
		{
            $_msg.="ERROR SOAP FAULT";
            $_msg.=$exception->getTraceAsString();
            $_msg.=$exception->getMessage();
			echo '<br>'.$_msg;
        }

        $seed = new DOMDocument;
        $seed->loadXML($result);
        $semilla = $seed->getElementsByTagName("SEMILLA")->item(0)->nodeValue;
        $estado = $seed->getElementsByTagName("ESTADO")->item(0)->nodeValue;        
        
        
        if($estado == "00")
		{
        $xmlr = simplexml_load_string($result);
        $seed_file="semillas/" . substr($model["RutEmisor"],0,-2)."/seed_".$model["SetDTE_ID"] . "_".rand(10000,20000).".xml";
        $xmlr->saveXML($seed_file);           
        $body = "<getToken><item><Semilla>$semilla</Semilla></item></getToken>";
        $dom = new DOMDocument;
        $dom->formatOutput = FALSE;
        $dom->preserveWhiteSpace = TRUE;

        $dom->loadXML($body);
        $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();        
        $pfx = file_get_contents("../certificados/".substr($model["RutEmisor"],0,-2)."/".$model["certificado"]);	                
		openssl_pkcs12_read($pfx, $key,$model["clave"]);
        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
        $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($dom);
        $dom->save($seed_file);
        
        $optionsDev = ['stream_context' => stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ])];
		$TokenClient = new SoapClient('https://maullin.sii.cl/DTEWS/GetTokenFromSeed.jws?WSDL', $optionsDev);
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
           echo "<br>Error con conexion SII, Error numero : " . $estado;

        }
}


function enviarAlSii($model)
{
	
    $tokenSII = ConexionAutomaticaSII($model);
    $TRACKID=0;

    /* @var $model EnviosDtes */        
    $pRutEnvia   = substr ($model["RutEnvia"],0, -2);
    $pDigEnvia   = substr ($model["RutEnvia"], -1);
    $pRutEmpresa  =  substr ($model["RutEmisor"],0, -2);
    $pDigEmpresa  = substr ($model["RutEmisor"], -1);  
	$pAmbiente = $model["ambiente"];

    $file = "xml_envios/" . $pRutEmpresa."/".$model["SetDTE_ID"] . ".xml";
    $data = array('rutSender'=>$pRutEnvia,'dvSender'=>$pDigEnvia,'rutCompany'=>$pRutEmpresa,'dvCompany'=>$pDigEmpresa,'archivo'=>$model["SetDTE_ID"] . ".xml");
            $agent = "Mozilla/4.0 (compatible; PROG 1.0; Windows NT 5.0; YComp 5.0.2.4)";
    $boundary = '--7d23e2a11301c4';
    $cuerpo = multipart_build_query($data, $boundary, $file);
    $bodyLength = strlen($cuerpo);  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_URL, "https://maullin.sii.cl/cgi_dte/UPL/DTEUpload");
    curl_setopt($ch, CURLOPT_PORT , 443);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $cuerpo);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array("POST /cgi_dte/UPL/DTEUpload HTTP/1.0", "Expect:", "accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg,application/vnd.ms-powerpoint, application/ms-excel,application/msword, */*", "Referer: http://dev.adichilespa.cl", "Accept-Language: es-cl", "Content-Type:multipart/form-data: boundary=7d23e2a11301c4", "Accept-Encoding: gzip, deflate", "User-Agent: $agent", "Host: maullin.sii.cl", "Content-Length: $bodyLength", "Connection: keep-alive", "Cache-Control: no-cache", "Cookie: TOKEN=$tokenSII")); 

    $resposeText = curl_exec($ch);
    $resposeInfo = curl_getinfo($ch);
	error_log($resposeText,0);		
    curl_close ($ch);
    $estadoUpload = getTextBetweenTagsENV($resposeText, 'STATUS');
    $rspUpload = "";

    if($estadoUpload == 0){
        $rspUpload = "Upload OK";
        $TRACKID = getTextBetweenTagsENV($resposeText, 'TRACKID');
        echo $rspUpload . "<br> TRACKID " . $TRACKID;
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


    /* @var $model EnviosDtes */        
    $pRutEnvia   = substr ($model["RutEnvia"],0, -2);
    $pDigEnvia   = substr ($model["RutEnvia"], -1);
    $pRutEmpresa  =  substr ($model["RutEmisor"],0, -2);
    $pDigEmpresa  = substr ($model["RutEmisor"], -1);  

    $file = "xml_libros/" . $pRutEmpresa."/".$model["tipoLibro"]."/".$model["SetDTE_ID"] . ".xml";
    $data = array('rutSender'=>$pRutEnvia,'dvSender'=>$pDigEnvia,'rutCompany'=>$pRutEmpresa,'dvCompany'=>$pDigEmpresa,'archivo'=>$model["SetDTE_ID"] . ".xml");
            $agent = "Mozilla/4.0 (compatible; PROG 1.0; Windows NT 5.0; YComp 5.0.2.4)";
    $boundary = '--7d23e2a11301c4';
    $cuerpo = multipart_build_query($data, $boundary, $file);
    $bodyLength = strlen($cuerpo);  

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_URL, "https://maullin.sii.cl/cgi_dte/UPL/DTEUpload");
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


    curl_setopt($ch, CURLOPT_HTTPHEADER, array("POST /cgi_dte/UPL/DTEUpload HTTP/1.0", "Expect:", "accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg,application/vnd.ms-powerpoint, application/ms-excel,application/msword, */*", "Referer: http://dev.adichilespa.cl", "Accept-Language: es-cl", "Content-Type:multipart/form-data: boundary=7d23e2a11301c4", "Accept-Encoding: gzip, deflate", "User-Agent: $agent", "Host: maullin.sii.cl", "Content-Length: $bodyLength", "Connection: keep-alive", "Cache-Control: no-cache", "Cookie: TOKEN=$tokenSII")); 
    //echo $cuerpo;

    $resposeText = curl_exec($ch);
    $resposeInfo = curl_getinfo($ch);
	print_r($resposeText);
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


function buildSign($toSign, $privkey) 
{ //para generar el timbre
    echo "\tFirmando:" .$toSign;
    $signature = null;
    $priv_key = $privkey;
    //$pub_key = openssl_get_publickey($publickey);
    $pkeyid = openssl_get_privatekey($priv_key);
    openssl_sign($toSign, $signature, $priv_key, OPENSSL_ALGO_SHA1);
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
    
    $dom->save("xml_respuestas/" . substr($model["RutEmisor"],0,-2)."/R_".$model[3].".xml");
 }
 