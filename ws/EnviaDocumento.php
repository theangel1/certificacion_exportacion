<?php
ini_set('error_reporting', 1);
ini_set('display_errors',1);
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
date_default_timezone_set('America/Santiago');

function enviaDocumento($archivo,$pRutEmpresa,$pRutEnvia,$pFecResol,$pNumResol,$pCertificado,$pClaveFirma,$correo_cliente,$debug=0)
{
    error_log("Envio al Sii From NetDte",0);
    $carpeta=substr($pRutEmpresa,0,-2);
    #error_log("Carpeta Entrada:".$carpeta,0);    
    $conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager", "--d5!RWN[LIm", "sisgenchile_sisgenfe");
    if (! function_exists('array_column')){
        function array_column(array $input, $columnKey, $indexKey = null) {
            $array = array();
            foreach ($input as $value) {
                if ( ! isset($value[$columnKey])) {
                    trigger_error("Key \"$columnKey\" does not exist in array");
                    return false;
                }
                if (is_null($indexKey)) {
                    $array[] = $value[$columnKey];
                }
                else {
                    if ( ! isset($value[$indexKey])) {
                        trigger_error("Key \"$indexKey\" does not exist in array");
                        return false;
                    }
                    if ( ! is_scalar($value[$indexKey])) {
                        trigger_error("Key \"$indexKey\" does not contain scalar value");
                        return false;
                    }
                    $array[$value[$indexKey]] = $value[$columnKey];
                }
            }
            return $array;
        }
    }

    $cantDTE=0;
    $doc = new DOMDocument("1.0", "ISO-8859-1");
    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    $fragment = $doc->createDocumentFragment();            

    $timezone = new DateTimeZone('America/Santiago'); 
    $date = new DateTime('', $timezone);
    $fechaTimbre = $date->format('Y-m-d\TH:i:s'); 
    $fechaArchivo= $date->format('YmdHis'); 
    $subtotalDTE = "";$SubTotDTE = array();
    $fuente="/home/netdte/www/procesos/xml_emitidos/".$carpeta."/";
    //$carpeta=str_replace("%0D","",$carpeta);

    $Documento = new DOMDocument();
    $Documento->formatOutput = FALSE;
    $Documento->preserveWhiteSpace = TRUE;
    //$archivo = scandir($fuente);
    #error_log("\tArchivos Incluidos:",0);
    $tipoDTEin= intval(substr($archivo,1,2));
	#error_log("\t\t$archivo",0);
    $Documento->load($fuente.$archivo);

	#star

    $caratulaXML = "<Caratula version=\"1.0\">\n<RutEmisor>" . $pRutEmpresa . "</RutEmisor>\n<RutEnvia>" . $pRutEnvia . "</RutEnvia>\n";    
    #comento esta parte porq viene con el rut del SII
    $caratulaXML .="<RutReceptor>60803000-K</RutReceptor>\n<FchResol>" . $pFecResol . "</FchResol>\n<NroResol>" .$pNumResol. "</NroResol>\n";
	#$caratulaXML .="<RutReceptor>".$rutRecibe."</RutReceptor>\n<FchResol>" . $pFecResol . "</FchResol>\n<NroResol>" .$pNumResol. "</NroResol>\n";
    $caratulaXML .="<TmstFirmaEnv>" . $fechaTimbre . "</TmstFirmaEnv>\n";	


    $tipoDTEin=$Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
    $folioDTEin=$Documento->getElementsByTagName("Folio")->item(0)->nodeValue;
    $rutRecibe=$Documento->getElementsByTagName("RUTRecep")->item(0)->nodeValue;

    $IDDTE = "T".$tipoDTEin."_F".$folioDTEin;

    $SubTotDTE[] = array('TpoDTE'=>$tipoDTEin);
	#error_log("\tSubiendo archivo $archivo",0);
    $archDTE["archivo"][]=$archivo;
    $archDTE["tipo"][]=$tipoDTEin;
    $archDTE["folio"][]=$folioDTEin;
    $archDTE["receptor"][]=$rutRecibe;
    $cantDTE++;


    if($cantDTE>0)
    {
        $SubTotDTE = array_count_values(array_column($SubTotDTE, 'TpoDTE'));
        foreach($SubTotDTE AS $tipo => $cantidad){
          $subtotalDTE .= "<SubTotDTE>\n<TpoDTE>" . $tipo . "</TpoDTE>\n<NroDTE>" . $cantidad . "</NroDTE>\n</SubTotDTE>\n";
      }

      $caratulaXML .= $subtotalDTE . "</Caratula>\n";
      $EnvioDTE = "<EnvioDTE version=\"1.0\" xmlns=\"http://www.sii.cl/SiiDte\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sii.cl/SiiDte EnvioDTE_v10.xsd\">\n";
      $EnvioDTE .= "<SetDTE ID=\"env_" . $fechaArchivo. "\">\n" . $caratulaXML . "</SetDTE>\n</EnvioDTE>";

      $fragment->appendXML($EnvioDTE);

      $doc->appendChild($fragment);

      $SetDTE = $doc->getElementsByTagName("SetDTE")->item(0);
      $Documento = new DOMDocument();

      for($det=0;$det<=$cantDTE-1;$det++)
      {
        $Documento->formatOutput = FALSE;
        $Documento->preserveWhiteSpace = TRUE;

            $Documento->load("/home/netdte/www/procesos/xml_emitidos/".$carpeta."/".$archDTE["archivo"][$det]); //Direccion xml de cada dte (esto es en un loop)

            $NodoDTE = $Documento->getElementsByTagName("DTE")->item(0);                
            $importar = $doc->importNode($NodoDTE, true);
            $SetDTE->appendChild($importar);             
        }    

        $DTE = $doc->getElementsByTagName('DTE');
        foreach($DTE as $DT){
            $DT->removeAttributeNS('http://www.w3.org/2000/09/xmldsig#','default');
        }    

        $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
        $pfx = file_get_contents("/home/netdte/www/certificados/".$carpeta."/".$pCertificado);
        openssl_pkcs12_read($pfx, $key, $pClaveFirma);

        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
        $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($doc, "ENVIO");            
        if(!is_writable ("/home/netdte/www/procesos/xml_envios/$carpeta")){
            error_log("**EOP**|10|Error de permisos en la carpeta de envios contacte a soporte",0);
            exit;
        }

        $doc->save("/home/netdte/www/procesos/xml_envios/".$carpeta."/".$archivo);
        #error_log("\tGrabando archivo xml_envios/$carpeta/".$archivo,0);

        $model["RutEnvia"]=$pRutEnvia;
        $model["RutEmisor"]=$pRutEmpresa;
        $model["SetDTE_ID"]=$IDDTE;
        $model["carpeta"]=$carpeta;
        $model["ambiente"]="palena";
        $model["tipoDTE"]=$tipoDTEin;
        $model["folioDTE"]=$folioDTEin;
        $model["RutReceptor"]=$rutRecibe;
        $model["certificado"]=$pCertificado;
        $model["clave"]=$pClaveFirma;
        $model["archivo"]=$archivo;
        
        $ret=0;
        $intentos=1;		
        while(intval($ret)==0){
            if($intentos<=5){			
                $resultado = enviarAlSii($model);		
                $aRes=explode("|",$resultado);
                $ret=$aRes[0];
                $intentos++;
                sleep(5);
            }else{
                $ret=7;
            }
        }                

        if($aRes[1]==0 and intval($aRes[0])>0){
            error_log($aRes[0]."|".$aRes[2],0);
            error_log("\n\tEnvio realizado Nº Track ".$aRes[0]." Modificacion trackID Netdte: ".$pRutEmpresa,0);
            
            #ojo aca con el verifica estado
            if(verificaEstado($_POST["id"],$_POST["tipoDte"],$_POST["folioDocumento"],$archivo))
            {
                $sql1="insert into sis_bitacora (sis_contribuyente_id,dte_tipo,dte_folio,dte_fecha_envio,"
                . "dte_fecha_emision,dte_receptor_rut,dte_receptor_razon,dte_receptor_direccion,"
                . "dte_monto_total,sis_contribuyente_rut,dte_trackid,dte_archivo_xml)"
                . " values (".$_POST["id"].",".$_POST["tipoDte"].",".$_POST["folioDocumento"].",current_timestamp,'"
                . $_POST["fec_emision"]."','".$_POST["rut_receptor"]."','".$_POST["razon_social_recep"]."','".$_POST["direccion_receptor"]."',"
                . "'".number_format($_POST["total"],0,".","")."','".$_POST["rut_emisor"]."','".$aRes[0]."','".$archivo."')";
                if($conn->query($sql1)){
                    error_log("**EOP**|Documento publicado correctamente ".date("H:i:s")."\n",0);
                }else{
                    error_log("**EOP**|Documento publicado correctamente pero no Grabado en COM\n".$sql1."\n\t".$conn->error."\n",0);
                }

                $cuerpo="Se ha enviado correctamente el sobre con los DTE generados:<br>"
                ."Cantidad de documentos procesados <strong>".$cantDTE."</strong><br>"
                ."Numero de Envío:".$aRes[0]."<br>"
                ."Archivos procesados:<ul>";
                $cuerpo.="</ul>";
            }
        }else{
            error_log("**EOP**|".$aRes[1]."|No se pudo realizar el envio\n\t\t\tEstado:".$aRes[1]."\n\t\t\tRazon:".$aRes[2],0);
            $cuerpo="Despu&eacute;s 5 intentos no se pudo enviar el sobre. La respuesta del SII fue:<br>"
            ."Estado :<strong>".$aRes[1]."</strong><br>"
            ."Razon :<strong>".$aRes[2]."</strong>";
            return false;
        }
        
        
        if($aRes[1]==0 and intval($aRes[0])>0)
        {
         enviaIntercambio($archivo,$pRutEmpresa,$pRutEnvia,$pFecResol,$pNumResol,$pCertificado,$pClaveFirma,$correo_cliente,$debug);
         return true;
     }
     else
     {
         return false;
     }
 }
}


function enviaIntercambio($archivo,$pRutEmpresa,$pRutEnvia,$pFecResol,$pNumResol,$pCertificado,$pClaveFirma,$correo_cliente,$debug=0){
    error_log("*Realizando Envio de Intercambio a Cliente",0);
    error_log("*Archivo:".$archivo,0);
    error_log("*Rut Empresa:".$pRutEmpresa,0);
    error_log("*Rut Envia:".$pRutEnvia,0);
    $carpeta=substr($pRutEmpresa,0,-2);    
    #error_log("Carpeta Entrada:".$carpeta,0);
    $conn = new mysqli("sisgenchile.com","sisgenchile_dbmanager", "--d5!RWN[LIm", "sisgenchile_intradb");
    
    if (! function_exists('array_column')) {
        function array_column(array $input, $columnKey, $indexKey = null) {
            $array = array();
            foreach ($input as $value) {
                if ( ! isset($value[$columnKey])) {
                    trigger_error("Key \"$columnKey\" does not exist in array");
                    return false;
                }
                if (is_null($indexKey)) {
                    $array[] = $value[$columnKey];
                }
                else {
                    if ( ! isset($value[$indexKey])) {
                        trigger_error("Key \"$indexKey\" does not exist in array");
                        return false;
                    }
                    if ( ! is_scalar($value[$indexKey])) {
                        trigger_error("Key \"$indexKey\" does not contain scalar value");
                        return false;
                    }
                    $array[$value[$indexKey]] = $value[$columnKey];
                }
            }
            return $array;
        }
    }

    $cantDTE=0;
    $doc = new DOMDocument("1.0", "ISO-8859-1");
    $doc->formatOutput = FALSE;
    $doc->preserveWhiteSpace = TRUE;
    $fragment = $doc->createDocumentFragment();            

    $timezone = new DateTimeZone('America/Santiago'); 
    $date = new DateTime('', $timezone);
    $fechaTimbre = $date->format('Y-m-d\TH:i:s'); 
    $fechaArchivo= $date->format('YmdHis'); 
    $subtotalDTE = "";$SubTotDTE = array();
    $fuente="/home/netdte/www/procesos/xml_emitidos/".$carpeta."/";
    //$carpeta=str_replace("%0D","",$carpeta);

    $Documento = new DOMDocument();
    $Documento->formatOutput = FALSE;
    $Documento->preserveWhiteSpace = TRUE;
    //$archivo = scandir($fuente);
    $tipoDTEin= intval(substr($archivo,1,2));
    $Documento->load($fuente.$archivo);
    $tipoDTEin=$Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
    $folioDTEin=$Documento->getElementsByTagName("Folio")->item(0)->nodeValue;
    $rutRecibe=$Documento->getElementsByTagName("RUTRecep")->item(0)->nodeValue;
    $newRazon = $Documento->getElementsByTagName("RznSoc")->item(0)->nodeValue;
    error_log("*Rut Recibe:".$rutRecibe,0);

	#another star

    $caratulaXML = "<Caratula version=\"1.0\">\n<RutEmisor>" . $pRutEmpresa . "</RutEmisor>\n<RutEnvia>" . $pRutEnvia . "</RutEnvia>\n";    
    $caratulaXML .="<RutReceptor>".$rutRecibe."</RutReceptor>\n<FchResol>" . $pFecResol . "</FchResol>\n<NroResol>" .$pNumResol. "</NroResol>\n";
    $caratulaXML .="<TmstFirmaEnv>" . $fechaTimbre . "</TmstFirmaEnv>\n";

    $IDDTE = "T".$tipoDTEin."_F".$folioDTEin;

    $SubTotDTE[] = array('TpoDTE'=>$tipoDTEin);
    $archDTE["archivo"][]=$archivo;
    $archDTE["tipo"][]=$tipoDTEin;
    $archDTE["folio"][]=$folioDTEin;
    $archDTE["receptor"][]=$rutRecibe;
    $cantDTE++;


    if($cantDTE>0)
    {
        $SubTotDTE = array_count_values(array_column($SubTotDTE, 'TpoDTE'));
        foreach($SubTotDTE AS $tipo => $cantidad)
        {
          $subtotalDTE .= "<SubTotDTE>\n<TpoDTE>" . $tipo . "</TpoDTE>\n<NroDTE>" . $cantidad . "</NroDTE>\n</SubTotDTE>\n";
        }

      $caratulaXML .= $subtotalDTE . "</Caratula>\n";
      $EnvioDTE = "<EnvioDTE version=\"1.0\" xmlns=\"http://www.sii.cl/SiiDte\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sii.cl/SiiDte EnvioDTE_v10.xsd\">\n";
      $EnvioDTE .= "<SetDTE ID=\"env_" . $fechaArchivo. "\">\n" . $caratulaXML . "</SetDTE>\n</EnvioDTE>";

      $fragment->appendXML($EnvioDTE);

      $doc->appendChild($fragment);

      $SetDTE = $doc->getElementsByTagName("SetDTE")->item(0);
      $Documento = new DOMDocument();

      for($det=0;$det<=$cantDTE-1;$det++){
        $Documento->formatOutput = FALSE;
        $Documento->preserveWhiteSpace = TRUE;

            $Documento->load("/home/netdte/www/procesos/xml_emitidos/".$carpeta."/".$archDTE["archivo"][$det]); //Direccion xml de cada dte (esto es en un loop)

            $NodoDTE = $Documento->getElementsByTagName("DTE")->item(0);                
            $importar = $doc->importNode($NodoDTE, true);
            $SetDTE->appendChild($importar);             
        }    

        $DTE = $doc->getElementsByTagName('DTE');
        foreach($DTE as $DT){
            $DT->removeAttributeNS('http://www.w3.org/2000/09/xmldsig#','default');
        }    

        $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
        $pfx = file_get_contents("/home/netdte/www/certificados/".$carpeta."/".$pCertificado);
        openssl_pkcs12_read($pfx, $key, $pClaveFirma);

        $xmlTool->setPrivateKey($key["pkey"]);
        $xmlTool->setpublickey($key["cert"]);
        $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($doc, "ENVIO");            
        if(!is_writable ("/home/netdte/www/procesos/xml_intercambio/$carpeta")){
            error_log("**EOP**|10|Error de permisos en la carpeta de envios contacte a soporte",0);
            exit;
        }

        $doc->save("/home/netdte/www/procesos/xml_intercambio/".$carpeta."/T".$tipoDTEin."_F".$folioDTEin.".xml");
        //$doc->save("/home/sisgenchile/www/sisgenfe/procesos/xml_procesados/".$carpeta."/T".$tipoDTEin."_F".$folioDTEin.".xml");        


        error_log("tracking Email... ".$_POST["email_recep"]."| Provengo de NETDTE",0);
        $htmlEnviaDocumento="<p>Estimado Cliente de $newRazon: </p>Junto con saludar adjuntamos 
        Documento electr&oacute;nico XML.</p><p>Saludos cordiales</p> <p>Proveedores de Facturaci&oacute;n Electr&oacute;nica 
        <strong>Sisgen Chile Limitada</strong>.</p>
        <p><a href='https://www.sisgenchile.com'>Visitar Sitio Web Sisgen Chile Limitada</a></p>
        <p><strong>Este es un Correo Autom&aacute;tico, por favor no responder.</strong></p>";
        
        for($a=0;$a<=$cantDTE-1;$a++)
        {
            //$sql="select correo_dte from sisgenchile_sisgenfe.sii_empresa_dte where empresa_dte_id='".substr($archDTE["receptor"][$a],0,-2)."'";
            $sql="select mail_intercambio from sisgenchile_sisgenfe.sisgen_intercambio where rut='".$archDTE["receptor"][$a]."'";			
            
            #Si viene vacio de dteacierto y es distinto a mauco, entro al if.
            if(empty($_POST["email_recep"]) and intval($_POST["id"])!==512 and intval($_POST["id"])!==15)
            {
                if($query=$conn->query($sql))
                {
                    $dteQ=$query->fetch_assoc();
                    $correo_cliente = $dteQ["mail_intercambio"];
                }
                else 
                {
                    error_log("**WARNING**|No se pudo rescatar el correo electrónico del cliente en BD SISGEN-CHILE.com ".$sql,0);						
                }
				#si aun asi no tiene email, no haré nada.
                if(!empty($correo_cliente))
                {
					#$correo_cliente = "void@sisgenchileile.cl";
					$sobre="T".$tipoDTEin."_F".$folioDTEin.".xml"; //$IDDTE.".xml";
					$subject="T".$archDTE["tipo"][$a]."_F".$archDTE["folio"][$a];
					enviaMail($subject, $htmlEnviaDocumento,$carpeta,$sobre,$archDTE["archivo"][$a],$correo_cliente,$rutRecibe,true);						
					$devSql="insert into sisgenchile_sisgenfe.dev_email values(0,'" .date("Y-m-d h:i:s"). "','" .$pRutEmpresa."','" .$rutRecibe. "','" .$correo_cliente."')";										
					$query=$conn->query($devSql);						
					#error_log("Sending Email to $correo_cliente from DataBase",0);
				}
				else
				{
					error_log("Can't send email.",0);
				}
					
			}
			#Pregunto si viene algo de dteacierto y no es mauco... Significa que usare el correo que viene de dteacierto.
			if(!empty($_POST["email_recep"]) && intval($_POST["id"])!==512 && intval($_POST["id"])!==15)
			{
				$_POST["email_recep"] = $correo_cliente;
				$sobre="T".$tipoDTEin."_F".$folioDTEin.".xml"; //$IDDTE.".xml";
				$subject="T".$archDTE["tipo"][$a]."_F".$archDTE["folio"][$a];
				enviaMail($subject, $htmlEnviaDocumento,$carpeta,$sobre,$archDTE["archivo"][$a],$correo_cliente,$rutRecibe,true);
				$devSql="insert into sisgenchile_sisgenfe.dev_email values(0,'" .date("Y-m-d h:i:s"). "','" .$pRutEmpresa."','" .$rutRecibe. "','" .$correo_cliente."')";
				$query=$conn->query($devSql);					
				error_log("Sending Email to $correo_cliente NETDTE/ ********SEGUNDO IF DTE ACIERTO******",0);
			}
				
				#Arreglo especial para Mauco
				
			if(intval($_POST["id"])==512 || intval($_POST["id"])==15)
			{
				if($query=$conn->query($sql))
				{
					$dteQ=$query->fetch_assoc();
					$correo_cliente = $dteQ["mail_intercambio"];
				}
				else 
				{
					error_log("**WARNING**|No se pudo rescatar el correo electrónico del cliente en Mauco. ".$sql,0);						
				}
				if(empty($correo_cliente))
				{
					$correo_cliente = "void@sisgenchile.cl";
				}
				if(!empty($correo_cliente))
				{						
					$sobre="T".$tipoDTEin."_F".$folioDTEin.".xml"; //$IDDTE.".xml";
					$subject="T".$archDTE["tipo"][$a]."_F".$archDTE["folio"][$a];
					enviaGmail($subject, $htmlEnviaDocumento,$carpeta,$sobre,$archDTE["archivo"][$a],$correo_cliente,$rutRecibe,true);
					$devSql="insert into sisgenchile_sisgenfe.dev_email values(0,'" .date("Y-m-d h:i:s"). "','" .$pRutEmpresa."','" .$rutRecibe. "','" .$correo_cliente."')";
					$query=$conn->query($devSql);						
					error_log("Sending Email to $correo_cliente from MAUCO",0);
				}
			    else
				{
					error_log("Can't send email from Mauco.",0);
				}
					
			}
				
        }


        }
    }

    function enviaMail($asunto,$body,$carpeta,$sobre='',$dte='',$mailCliente='',$rutCliente='',$DesdeDte=false)
    {
       $fuente_sobre="/home/netdte/www/procesos/xml_intercambio/$carpeta/$sobre";
       $xml = new DOMDocument; 
       $xml->load($fuente_sobre);     
       $p=explode("_",$dte);
       $p1=str_replace("F","",substr($p[1],0,-4));    

       $mail = new PHPMailer;	
       $mail->SMTPDebug = 1;
       $mail->IsSMTP();	
       $mail->Host = 'mail.sisgenchile.com';
       $mail->SMTPAuth = true;
       $mail->Username = "dte@sisgenchile.com";
       $mail->Password = "dtesisgen2017";
       $mail->SMTPSecure ='tls';
       $mail->Port=587;	
       $mail->setFrom('dte@sisgenchile.com', 'Facturacion Electronica Sisgen Chile');

       if($DesdeDte)
       {
        $mail->AddAddress($mailCliente);
    }    
    $mail->Subject = $asunto;
    $mail->msgHTML($body);
    if($DesdeDte)
    {
        $mail->AddAttachment($fuente_sobre);        
    }
    if(!$mail->send())
    {
        error_log("Mailer Error: " . $mail->ErrorInfo, 0);
    }
    else 
    {
        if($DesdeDte)
        {
            error_log("Se envió el DTE al correo with new Mailer Class ".$mailCliente."\n Se adjunto el documento\n\t".$fuente_sobre, 0);
        }

    }



}

###function con gmail
function enviaGmail($asunto,$body,$carpeta,$sobre='',$dte='',$mailCliente='',$rutCliente='',$DesdeDte=false)
{       
    $fuente_sobre="/home/netdte/www/procesos/xml_intercambio/$carpeta/$sobre";
    $xml = new DOMDocument; 
    $xml->load($fuente_sobre);     
    $p=explode("_",$dte);
    $p1=str_replace("F","",substr($p[1],0,-4));
    

    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->SMTPDebug = 1;
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Port=587;
    $mail->SMTPSecure ='tls';
    $mail->SMTPAuth = true;
    $mail->Username = "dte@netdte.cl";
    $mail->Password = "sisgenchile2018";
    $mail->setFrom('dte@netdte.com', 'Facturacion Sisgen FE');
    
    if($DesdeDte)
    {
        $mail->AddAddress($mailCliente);
    }
    
    $mail->Subject = $asunto;
    $mail->msgHTML($body);
    if($DesdeDte)
    {
        $mail->AddAttachment($fuente_sobre);        
    }
    if(!$mail->send())
    {
        error_log("Mailer Error: " . $mail->ErrorInfo,0);
    }
    else 
    {
        if($DesdeDte)
        {
            error_log("Se envió el DTE al correo with new Mailer GMAIL Class ".$mailCliente."\n Se adjunto el documento\n\t".$fuente_sobre,0);
        }		
    }
}



function verificaEstado($contribuyente,$tipo,$folio,$archivo){
    #error_log("[BITAcORA] Entrando a Verificar Bitacora del Documento para Angel",0);
    $conn = new mysqli("sisgenchile.com", "sisgenchile_dbmanager","--d5!RWN[LIm", "sisgenchile_sisgenfe");
    $sql="select dte_estado_sii,dte_archivo_xml from sis_bitacora where sis_contribuyente_id=$contribuyente and dte_tipo=$tipo and dte_folio=$folio";
    #error_log("[BITACORA] $sql",0);
    $query=$conn->query($sql);
    $rec = $query->fetch_assoc();
    $rowcount=$query->num_rows;
    error_log("\n\t[BITACORA]Verificando bitacora\n\t[BITACORA]Contribuyente:$contribuyente\n\t[BITACORA]Tipo:$tipo\n\t[BITACORA]Folio:$folio\n\t[BITACORA]Filas:$rowcount\n\tEstado:".$rec["dte_estado_sii"]."\n\tVacio[BITACORA]:".empty($rec["dte_estado_sii"]),0);
    if($rowcount==0)
    {
        return true;
    }
    else if($rec["dte_estado_sii"]=="DTE ACEPTADO")
    {
        if(empty($rec["dte_archivo_xml"]))
        {
            $sql="update sis_bitacora set dte_archivo_xml='$archivo' where sis_contribuyente_id=$contribuyente and dte_tipo=$tipo and dte_folio=$folio";
            $conn->query($sql);
        }
        return false;
    }
    else if($rec["dte_estado_sii"]=="DTE Aceptado con Reparos")
    {
        if(empty($rec["dte_archivo_xml"]))
        {
            $sql="update sis_bitacora set dte_archivo_xml='$archivo' where sis_contribuyente_id=$contribuyente and dte_tipo=$tipo and dte_folio=$folio";
            $conn->query($sql);
        }
        return false;
    }
    else if(empty($rec["dte_estado_sii"]))
    {
       return false;
   }
   else
   {
    return true;
}
}
