<?php
require_once("libCertificacion/xmlseclibs/XmlseclibsAdapter.php");
require_once("libCertificacion/SII.php");
require_once("../config/Conexion.php");
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);
session_start();
date_default_timezone_set('America/Santiago');
#Declara variables

$rutEmpresa = $_SESSION['rut'];
$fechaResolucion = $_SESSION['fechaResol'];
$etapaCertificacion = "C";
$certificadoNombre = $_SESSION['certificado'];
$certificadoClave = $_SESSION['clave'];
$rutRLegal = $_SESSION['rut_rl'];
$idContribuyente = $_SESSION['contribuyente'];
$conn = dbCertificacion();

	#declaro variables
	$carpeta=substr($rutEmpresa,0,-2);
	$cantDTE=0;
	$pRutEmpresa=substr($rutEmpresa,0,-2);
	$pRutEnvia= substr($rutRLegal,0,-2);
	$ambiente = "maullin";
	
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
					else 
					{
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
	
	$doc = new DOMDocument("1.0", "ISO-8859-1");
	$doc->formatOutput = FALSE;
	$doc->preserveWhiteSpace = TRUE;

	$fragment = $doc->createDocumentFragment();            

	$timezone = new DateTimeZone('America/Santiago'); 
	$date = new DateTime('', $timezone);
	$fechaTimbre = $date->format('Y-m-d\TH:i:s'); 
	$fechaArchivo= $date->format('YmdHis'); 
	$subtotalDTE = "";
	$SubTotDTE = array();
	$caratulaXML = "<Caratula version=\"1.0\">\n<RutEmisor>" . $rutEmpresa . "</RutEmisor>\n<RutEnvia>" . $rutRLegal . "</RutEnvia>\n";    
	$caratulaXML .="<RutReceptor>60803000-K</RutReceptor>\n<FchResol>" . $fechaResolucion . "</FchResol>\n<NroResol>0</NroResol>\n";
	$caratulaXML .="<TmstFirmaEnv>" . $fechaTimbre . "</TmstFirmaEnv>\n";
	$fuente="xml_emitidos/$carpeta/";	
	$Documento = new DOMDocument();
	$Documento->formatOutput = FALSE;
	$Documento->preserveWhiteSpace = TRUE;
	$archivo = scandir($fuente);
	$portipo = explode(",","33,34,61,52,56,110,112,111");

		foreach($portipo as $tipodoc){
			foreach ($archivo as $file) {
				if ($file != '.' && $file != '..') {
					$Documento->load($fuente.$file);
					$tipoDTEin=$Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
			
					if($tipoDTEin == $tipodoc){
						$tipoDTEin=$Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
						if($etapaCertificacion=="C"){
							$IDDTE = "SET_Certificacion_".$fechaTimbre;
						}else if($etapaCertificacion=="S"){
							$IDDTE = "ENV_SETSIMULACION_".$fechaTimbre;
						}
						//if($tipoDTEin==$tipoDTE){
							 $SubTotDTE[] = array('TpoDTE'=>$tipoDTEin);
							 echo "<br>Subiendo archivo $file\n";
							 $archDTE[]=$file;
							 $cantDTE++;
						//}
					}
				}
			}
		}

	echo "<br>cantidad de documentos a procesar ".$cantDTE;
	if($cantDTE>0)
	{
		$SubTotDTE = array_count_values(array_column($SubTotDTE, 'TpoDTE'));
		foreach($SubTotDTE AS $tipo => $cantidad){
		  $subtotalDTE .= "<SubTotDTE>\n<TpoDTE>" . $tipo . "</TpoDTE>\n<NroDTE>" . $cantidad . "</NroDTE>\n</SubTotDTE>\n";
		}

		$caratulaXML .= $subtotalDTE . "</Caratula>\n";
		$EnvioDTE = "<EnvioDTE version=\"1.0\" xmlns=\"http://www.sii.cl/SiiDte\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sii.cl/SiiDte EnvioDTE_v10.xsd\">\n";
		$EnvioDTE .= "<SetDTE ID=\"env_certificacion_" . $fechaArchivo. "\">\n" . $caratulaXML . "</SetDTE>\n</EnvioDTE>";

		$fragment->appendXML($EnvioDTE);

		$doc->appendChild($fragment);

		$SetDTE = $doc->getElementsByTagName("SetDTE")->item(0);
		$Documento = new DOMDocument();

		for($det=0;$det<=$cantDTE-1;$det++)
		{
			$Documento->formatOutput = FALSE;
			$Documento->preserveWhiteSpace = TRUE;

			$Documento->load("xml_emitidos/".$carpeta."/".$archDTE[$det]); //Direccion xml de cada dte (esto es en un loop)

			$NodoDTE = $Documento->getElementsByTagName("DTE")->item(0);                
			$importar = $doc->importNode($NodoDTE, true);
			$SetDTE->appendChild($importar);             
		}    

		$DTE = $doc->getElementsByTagName('DTE');
		foreach($DTE as $DT){
			$DT->removeAttributeNS('http://www.w3.org/2000/09/xmldsig#','default');
		}    

		$xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
		$pfx = file_get_contents("../certificados/".substr($rutEmpresa,0,-2)."/".$certificadoNombre);	
		openssl_pkcs12_read($pfx, $key, $certificadoClave);
		$xmlTool->setPrivateKey($key["pkey"]);
		$xmlTool->setpublickey($key["cert"]);
		$xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
		$xmlTool->sign($doc, "ENVIO");            
		if(!is_writable ("xml_envios/$carpeta")){
				echo "error de permisos\n";
				exit;
		}
		$doc->save("xml_envios/".$carpeta."/".$IDDTE.".xml");
		echo "\ngrabando archivo xml_envios/$carpeta/$IDDTE.xml\n";
		/*libxml_use_internal_errors(true);

		$xmlv = new DOMDocument(); 
		$xmlv->load("procesos/xml_envios/".$carpeta."/".$IDDTE.".xml"); 

		if (!$xmlv->schemaValidate('procesos/validaciones/EnvioDTE_v10.xsd')) {
			echo "\t\t**ERROR**: DOMDocument::schemaValidate() Generated Errors!</b>";
			escribeLog("\t\t**ERROR**: DOMDocument::schemaValidate() Generated Errors!</b>");
			libxml_display_errors();
			exit;
		}*/
	
		$model["RutEnvia"]=$rutRLegal;
		$model["RutEmisor"]=$rutEmpresa;
		$model["SetDTE_ID"]=$IDDTE;
		$model["carpeta"]=$carpeta;
		$model["ambiente"]=$ambiente;
		$model["certificado"] = $certificadoNombre;
		$model["clave"] = $certificadoClave;
	
		$ret=0;
		$intentos=1;
        
		while(intval($ret)==0)
		{
			 if($intentos<=5){	 				
				$resultado = enviarAlSii($model);
				$aRes=explode("|",$resultado);
				$ret=$aRes[0];
				$intentos++;
				echo '<br>'.$intentos. "+intentos";
				sleep(5);
			}
			else			
				$ret=7;			
		}

		if($aRes[1]==0 and intval($aRes[0])>0)
		{		            
			echo "\n\tEnvio realizado Nº Track ".$aRes[0]." Modificacion trackID Netdte: ".$pRutEmpresa;
			$sql="insert into bitacora(trackid,sis_contribuyente_id) values(".$aRes[0].",'$idContribuyente')";
			if($conn->query($sql))
			{
				echo 'TrackID grabado en la base de datos';
			}
			else
			{
				echo 'no pude grabar en la bd';
			}
		}
		else		
			echo "**EOP**|".$aRes[1]."|No se pudo realizar el envio\n\t\t\tEstado:".$aRes[1]."\n\t\t\tRazon:".$aRes[2];
    
    
		if(intval($aRes[1])==0)
		{
			// and $aRes[0]!=""
			for($a=0;$a<=count($archDTE)-1;$a++)
			{
				echo "moviendo archivo xml_emitidos/$carpeta/".$archDTE[$a]." a xml_procesados/$carpeta/".$archDTE[$a]."\n";
				copy("xml_emitidos/$carpeta/".$archDTE[$a],"xml_procesados/$carpeta/".$archDTE[$a]);
				unlink("xml_emitidos/$carpeta/".$archDTE[$a]);
			}
			echo '<br><a href="https://certificaciones.netdte.cl/vistas/index.php">Back to the future</a>';
		}
		else
		{
			exit("Se produjo un error al procesar el sobre\n\t".$resultado);
		}
	}



