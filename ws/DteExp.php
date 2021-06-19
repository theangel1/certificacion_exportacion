<?php
ob_start();
date_default_timezone_set('America/Santiago');
session_start();

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
require_once("../config/Conexion.php");
require_once("Metodos.php");
require_once("EnviaDocumento.php");
require_once("../procesos/lib/xmlseclibs/XmlseclibsAdapter.php");
require_once("../procesos/lib/ObjectAndXML.php");
require_once("../procesos/lib/SII.php");
require_once("../procesos/lib/EXPORTACIONES/DTE.php");

$conn = dbCertificacion();  
/*$sql="select sis_contribuyente_certificado,sis_contribuyente_clave,sis_contribuyente_rutrl,sis_contribuyente_fecresol,sis_contribuyente_numresol from sis_contribuyente where "
    . "sis_contribuyente_id=".$_POST["IdContribuyente"];
$query = $conn->query($sql);
$con= $query->fetch_assoc();  
  */
echo 'iniciando exportaciones' ;

//error_log("Iniciando NetDte-Exportaciones",3,"error_log-Exportaciones");    
#Declarando variables
$linea = 0;
$nroLinRef=0;  
$lineaDr=0;
$marcaFOLIO=0;
$timezone = new DateTimeZone('America/Santiago'); 
$date = new DateTime('', $timezone);
$fechaTimbre = $date->format('Y-m-d\TH:i:s');
$idContribuyente= $_POST['IdContribuyente'];
$certificadoNombre = $_SESSION['certificado'];
$certificadoClave = $_SESSION['clave'];
#variables de Identificacion de documento
$tipoDte = $_POST["TipoDTE"];
$folio = GetFolioActual($tipoDte,$idContribuyente);
$fechaEmision = $_POST["FchEmis"]; 
$indServicio = $_POST["IndServicio"];
$formaPagoExp = $_POST["FmaPagExp"];
#Variables de Emisor
$rutEmisor =$_POST['RutEmi'];
$razonSocial= $_POST['RznSocEmi'];
$acteco= $_POST['Acteco'];
$giro = $_POST['GiroEmi'];
$direccion = $_POST['DirEmi'];
$comuna =$_POST['ComunaEmi'];
$ciudad = $_POST['CiudadEmi'];
$telefono = $_POST["TelefonoEmi"] ;
$email = $_POST['EmailEmi'];
#Variables de receptor
$rutReceptor = '55555555-5';   
$razonReceptor = $_POST["RznSocRecep"];
$nacionalidad = $_POST["Nacionalidad"];
$giroReceptor = $_POST["GiroRecep"];
$direccionReceptor = $_POST["DirRecep"];
#aduana
$CodModVenta = $_POST["CodModVenta"];
$CodClauVenta = $_POST["CodClauVenta"];
$TotClauVenta = $_POST["TotClauVenta"];
$CodViaTransp = $_POST["CodViaTransp"];
$CodPtoEmbarque = $_POST["CodPtoEmbarque"];
$IdAdicPtoEmb = $_POST["IdAdicPtoEmb"];
$CodPtoDesemb = $_POST["CodPtoDesemb"];
$IdAdicPtoDesemb = $_POST["IdAdicPtoDesemb"];
$CodUnidMedTara = $_POST["CodUnidMedTara"];
$CodUnidPesoBruto = $_POST["CodUnidPesoBruto"];
$CodUnidPesoNeto = $_POST["CodUnidPesoNeto"];
$TotBultos = $_POST["TotBultos"];
$CodTpoBultos = $_POST["CodTpoBultos"];
$CantBultos = $_POST["CantBultos"];
$Marcas = $_POST["Marcas"];
$MntFlete = $_POST["MntFlete"];
$MntSeguro = $_POST["MntSeguro"];
$CodPaisRecep = $_POST["CodPaisRecep"];
$CodPaisDestin = $_POST["CodPaisDestin"];

$recargo = 0;
$recargoPct = 0;
$descuento = 0;
$descuentoPct = 0;
$valoresLineaDetalle = 0;

for($h = 0 ; $h <=count($_POST['DDLSelectTpoMov'])-1 ; $h++)
{
    
    if($_POST['DDLSelectTpoMov'][$h] =="R")
    {    
        switch($_POST['DDLSelectTpoValor'][$h])
        {
            case '%':
                $recargoPct = $recargoPct + $_POST['boxValorDR'][$h] /100 ;
            break;

            case '$':
                $recargo = $recargo + $_POST['boxValorDR'][$h];
            break;
        }

        
        #echo '<br>'.$_POST['boxValorDR'][$h];
    }
    else if($_POST['DDLSelectTpoMov'][$h]=="D")
    {
        switch ($_POST['DDLSelectTpoValor'][$h])
        {
            case '%':
                $descuentoPct =  $descuentoPct + $_POST['boxValorDR'][$h]/100;
            break;            

            case '$':
                $descuento =  $descuento + $_POST['boxValorDR'][$h];        
            break;
            
        }
        
       # echo '<br>'.$_POST['boxValorDR'][$h];
    } 
}

for( $g = 0; $g <= count($_POST['boxNmbItem'])-1; $g++)
{    
   $valoresLineaDetalle = $valoresLineaDetalle + round($_POST['boxMontoItem'][$g]); 
   #echo '<br>'.round($_POST['boxMontoItem'][$g]);   
}

#Seteando Totales
$tipo_moneda = $_POST["TpoMoneda"];
$monto_exento = $valoresLineaDetalle + $recargo +($valoresLineaDetalle*$recargoPct) -$descuento -($valoresLineaDetalle * $descuentoPct);
$monto_total = $monto_exento;
$cambioCLP = $_POST['TpoCambio'];
$montoExentoOtraMoneda = $cambioCLP * $monto_exento;
$montoTotalOtraMoneda = $montoExentoOtraMoneda;

#variables de aduana

    /*PROCESO GENERADOR DEL DOCUMENTO*/
    
    $DTE = new DTE();
    $Documento = new Exportaciones();
    $transportes = new Transporte();
    $aduanas = new Aduana();
    $otrasMonedas = new OtraMoneda();
    $totalesEncabezado = new Totales();

    $Documento->setEncabezado();
    
    #set Identificacion del documento
    $Documento->Encabezado->setIdDoc();
    $Documento->Encabezado->IdDoc->setTipoDTE($tipoDte);
    $Documento->Encabezado->IdDoc->setFolio($folio);
    $Documento->Encabezado->IdDoc->setFchEmis($fechaEmision);
    if($indServicio!="" && intval(trim($indServicio)>0))
        $Documento->Encabezado->IdDoc->setIndServicio($indServicio);

    if(trim($formaPagoExp!="") && intval(trim($formaPagoExp)>0))
        $Documento->Encabezado->IdDoc->setFmaPagExp($formaPagoExp);    
    
    #set emisor    
    $Documento->Encabezado->setEmisor();
    $Documento->Encabezado->Emisor->setRUTEmisor($rutEmisor);
    $Documento->Encabezado->Emisor->setRznSoc($razonSocial);
    $Documento->Encabezado->Emisor->setGiroEmis($giro);
    $Documento->Encabezado->Emisor->setActeco($acteco);
    $Documento->Encabezado->Emisor->setDirOrigen($direccion);
    $Documento->Encabezado->Emisor->setCmnaOrigen($comuna);
    $Documento->Encabezado->Emisor->setCiudadOrigen($ciudad);
    $Documento->Encabezado->Emisor->setCorreoEmisor($email);
    if(trim($telefono)!="")
        $Documento->Encabezado->Emisor->setTelefono($telefono);
    
     #set receptor
    $Documento->Encabezado->setReceptor();
    $Documento->Encabezado->Receptor->setRUTRecep($rutReceptor);   
    $Documento->Encabezado->Receptor->setRznSocRecep($razonReceptor);
    if(trim($nacionalidad)!="")
    {
        $Documento->Encabezado->Receptor->setExtranjero();
        $Documento->Encabezado->Receptor->Extranjero->setNacionalidad($nacionalidad);
    }
    $Documento->Encabezado->Receptor->setGiroRecep($giroReceptor);
    $Documento->Encabezado->Receptor->setDirRecep($direccionReceptor);      

   $variableAux = false;
    
    if(trim($CodModVenta)!="" && intval(trim($CodModVenta)>0))
        $aduanas->setCodModVenta($CodModVenta);    

    if(trim($CodClauVenta)!="" && intval(trim($CodClauVenta)>0))
    {
        $aduanas->setCodClauVenta($CodClauVenta);
        $variableAux = true;
    }

    if(trim($TotClauVenta)!="" && intval(trim($TotClauVenta)>0))
        $aduanas->setTotClauVenta($TotClauVenta);

    if(trim($CodViaTransp)!="" && intval(trim($CodViaTransp)>0))
    {
        $aduanas->setCodViaTransp($CodViaTransp);
        $variableAux = true;
    }

    if(trim($CodPtoEmbarque)!="" && intval(trim($CodPtoEmbarque)>0))
        $aduanas->setCodPtoEmbarque($CodPtoEmbarque);

    if(trim($IdAdicPtoEmb)!="" && intval(trim($IdAdicPtoEmb)>0))
        $aduanas->setIdAdicPtoEmb($IdAdicPtoEmb);

    if(trim($CodPtoDesemb)!="" && intval(trim($CodPtoDesemb)>0))
        $aduanas->setCodPtoDesemb($CodPtoDesemb);

    if(trim($IdAdicPtoDesemb)!="" && intval(trim($IdAdicPtoDesemb)>0))
        $aduanas->setIdAdicPtoDesemb($IdAdicPtoDesemb);

    if(trim($CodUnidMedTara)!="" && intval(trim($CodUnidMedTara)>0))
        $aduanas->setCodUnidMedTara($CodUnidMedTara);

    if(trim($CodUnidPesoBruto)!="" && intval(trim($CodUnidPesoBruto)>0))
        $aduanas->setCodUnidPesoBruto($CodUnidPesoBruto);

    if(trim($CodUnidPesoNeto)!="" && intval(trim($CodUnidPesoNeto)>0))
        $aduanas->setCodUnidPesoNeto($CodUnidPesoNeto);

    if(trim($TotBultos)!="" && intval(trim($TotBultos)>0))
        $aduanas->setTotBultos($TotBultos);

    if(trim($CodTpoBultos)!="" && intval(trim($CodTpoBultos)>0))
    {
        $aduanas->setTipoBultos();
        $aduanas->TipoBultos->setCodTpoBultos($CodTpoBultos);
    }   

    if(trim($CantBultos)!="" && intval(trim($CantBultos)>0))    
        $aduanas->TipoBultos->setCantBultos($CantBultos);

    if(trim($Marcas)!="")
        $aduanas->TipoBultos->setMarcas($Marcas);    
    
    if(trim($MntFlete)!="" && intval(trim($MntFlete)>0))    
        $aduanas->setMntFlete($MntFlete);

    if(trim($MntSeguro)!="" && intval(trim($MntSeguro)>0))    
        $aduanas->setMntSeguro($MntSeguro);

    if(trim($CodPaisRecep)!="" && intval(trim($CodPaisRecep)>0))    
        $aduanas->setCodPaisRecep($CodPaisRecep);

    if(trim($CodPaisDestin)!="" && intval(trim($CodPaisDestin)>0))    
        $aduanas->setCodPaisDestin($CodPaisDestin);

    if($variableAux==true)
    {
        $transportes->setAduana($aduanas);        
        $Documento->Encabezado->setTransporte($transportes);
    }   
       

    if(trim($tipo_moneda)!="")
        $totalesEncabezado->setTpoMoneda($tipo_moneda);
    if(trim($monto_exento)!="")
        $totalesEncabezado->setMntExe(trim(floatval($monto_exento)));
    if(trim($monto_total)!="")
        $totalesEncabezado->setMntTotal(trim(floatval($monto_total)));
    
    $Documento->Encabezado->setTotales($totalesEncabezado);

    $otrasMonedas->setTpoMoneda('PESO CL');    
    $otrasMonedas->setTpoCambio(trim(number_format($cambioCLP,2,".","")));
    $otrasMonedas->setMntExeOtrMnda(trim(number_format($montoExentoOtraMoneda,2,".","")));
    $otrasMonedas->setMntTotOtrMnda(trim(number_format($montoTotalOtraMoneda,2,".","")));
    $Documento->Encabezado->setOtraMoneda($otrasMonedas);


#Seteando Detalle
for( $x = 0; $x <= count($_POST['boxNmbItem'])-1; $x++)
{
    $linea++;
    $detalle = new Detalle;    
    $detalle->setNroLinDet("$linea");
    $detalle->setIndExe($_POST['boxIndExe'][$x]);
    $detalle->setNmbItem(utf8_decode(replaceSii(substr($_POST['boxNmbItem'][$x],0,80))));

    if(trim($_POST['boxQtyItem'][$x])!="")    
        $detalle->setQtyItem($_POST['boxQtyItem'][$x]);

    if(trim($_POST['boxUnmdItem'][$x])!="")
        $detalle->setUnmdItem(trim(substr($_POST['boxUnmdItem'][$x],0,4)));

    if(intval($_POST['boxPrcItem'][$x])>0)
        $detalle->setPrcItem(trim(number_format($_POST['boxPrcItem'][$x],2,".","")));  
    
    if(trim($_POST['boxDescuentoPct'][$x])!="" && intval($_POST['boxDescuentoPct'][$x])>0)
        $detalle->setDescuentoPct($_POST['boxDescuentoPct'][$x]);

    if($_POST['boxDescuentoMonto'][$x]>0)        
        $detalle->setDescuentoMonto("".trim(round($_POST['boxDescuentoMonto'][$x]))."");

    if(trim($_POST['boxRecargoPct'][$x])!="" && intval($_POST['boxRecargoPct'][$x])>0)
        $detalle->setRecargoPct($_POST['boxRecargoPct'][$x]);

    if($_POST['boxRecargoMonto'][$x]>0)
        $detalle->setRecargoMonto("".trim(round($_POST['boxRecargoMonto'][$x]))."");
    
    $detalle->setMontoItem("".trim(round($_POST['boxMontoItem'][$x]))."");
    $Documento->setDetalle($detalle);
}
#end for detalle  

//Descuento o Recargo

for($z = 0 ; $z <=count($_POST['DDLSelectTpoMov'])-1 ; $z++)
{
    $lineaDr++;
    if($_POST['DDLSelectTpoMov'][$z]=="D" or $_POST['DDLSelectTpoMov'][$z] =="R" and $_POST['DDLSelectTpoMov'][$z]!="")
    { 
        $DescuentoGlobal = new DscRcgGlobal();
        $DescuentoGlobal->setNroLinDR("$lineaDr");
        $DescuentoGlobal->setTpoMov($_POST['DDLSelectTpoMov'][$z]);
        $DescuentoGlobal->setGlosaDR($_POST['boxGlosaDR'][$z]);        
        $DescuentoGlobal->setTpoValor($_POST['DDLSelectTpoValor'][$z]);
        $DescuentoGlobal->setValorDR($_POST['boxValorDR'][$z]);
        if($_POST['DDLSelectIndExeDR'][$z]!="")      
             $DescuentoGlobal->setIndExeDR($_POST['DDLSelectIndExeDR'][$z]);        
        $Documento->setDscRcgGlobal($DescuentoGlobal);
    }
}
#end descuento o recargo

#Seteando Referencias
for($y = 0 ; $y <=count($_POST['DDLSelectTipDocRef'])-1 ; $y++)
{
    if($_POST['DDLSelectTipDocRef'][$y]!="")
    {
        $nroLinRef++;
        $referencia = new Referencia();
        $referencia->setNroLinRef("$nroLinRef");
        $referencia->setTpoDocRef($_POST['DDLSelectTipDocRef'][$y]);        
        $referencia->setFolioRef(trim($_POST['boxFolioRef'][$y]));
        $referencia->setFchRef(trim($_POST['boxFchRef'][$y]));
        if(intval($_POST['DDLCodRef'][$y])>0)
            $referencia->setCodRef(trim($_POST['DDLCodRef'][$y]));        
        if(trim($_POST['boxRazonRef'][$y]!=""))
            $referencia->setRazonRef(trim($_POST['boxRazonRef'][$y]));        
        $Documento->setReferencia($referencia);
    }
}
#end referencias

#Aca empieza el core del XML
    
    $pRutEmpresa   = substr ($Documento->Encabezado->Emisor->getRUTEmisor(),0, -2);
    $idDte = "T".$Documento->Encabezado->IdDoc->getTipoDte()."_F".$Documento->Encabezado->IdDoc->getFolio()."-".date("YMDHis");    
    $obj = new ObjectAndXML($idDte,$pRutEmpresa);
    $obj->setStartElement("DTE");
    $obj->setId($idDte);    
    $Documento->setTmstFirma($fechaTimbre);   
    $Documento->setTED();
    $Documento->TED->setDD();
    $Documento->TED->DD->setRE($Documento->Encabezado->Emisor->getRUTEmisor());
    $Documento->TED->DD->setTD($Documento->Encabezado->IdDoc->getTipoDte());
    $Documento->TED->DD->setF($Documento->Encabezado->IdDoc->getFolio());
    $Documento->TED->DD->setFE($Documento->Encabezado->IdDoc->getFchEmis());
    $Documento->TED->DD->setRR($Documento->Encabezado->Receptor->getRUTRecep());
    $Documento->TED->DD->setRSR(utf8_decode(replaceSii(substr($Documento->Encabezado->Receptor->getRznSocRecep(),0,40))));
    $Documento->TED->DD->setMNT($Documento->Encabezado->Totales->getMntTotal());    
	$Documento->TED->DD->setIT1(utf8_decode(replaceSii(substr($Documento->Detalle[0]->getNmbItem(),0,40))));//baby
    $Documento->TED->DD->setTSTED($fechaTimbre);
    
    $DTE->setExportaciones($Documento);
    utf8_encode_deep($DTE);    
    $recordsXML = $obj->objToXML($DTE);

    /********** OBTENER CAF, LLAVES PRIVADA Y PUBLICA DEL CAF **********/
    //error_log("\tObteniendo CAF y Llave privada en Netdte.cl",3,"error_log-Exportaciones");   
    
    $LCAFImport = new DOMDocument();
    $LCAFImport->formatOutput = TRUE;
    $LCAFImport->preserveWhiteSpace = TRUE;    
    $archivoFolio=validaFolio($folio,substr($rutEmisor,0,-2),$tipoDte);
    if($archivoFolio!="ERROR" and $archivoFolio!="")
    {
        if(!$LCAFImport->load("../procesos/folios/".substr($rutEmisor,0,-2)."/".$archivoFolio))
        {
            $XMLFOLIO = utf8_encode(file_get_contents("../procesos/folios/".substr($rutEmisor,0,-2)."/".$archivoFolio));
            if($LCAFImport->loadXML($XMLFOLIO))            
                $marcaFOLIO = 1;                        
        }
        else
        {
            #error_log("[".$tracklog."] \tFolio validado desde archivo /home/sisgenchile/www/sisgenfe/procesos/folios/".substr($_POST["rut_emisor"],0,-2)."/".$archivoFolio,0);
        }

    }
    else
    {
        echo 'no encontre caf para el folio';
        //error_log("[".$tracklog."] **EOP**|11|No se pudo encontrar un CAF para el folio" .$folio,3,"error_log-Exportaciones");
        exit("1:No se pudo encontrar un CAF para el folio ".$folio);
    }
    
    
    $CAF = $LCAFImport->getElementsByTagName("CAF")->item(0);
    $nodecaf = $LCAFImport->getElementsByTagName("CAF")->item(0);
    $priv_key = $LCAFImport->getElementsByTagName("RSASK")->item(0)->nodeValue;
    $CAF = $LCAFImport->saveXML($CAF);
    if($marcaFOLIO == 1)    
        $CAF = utf8_decode($CAF);
    
    /**********FIN CAF **********/            

    $DTE_TIMBRE = new DOMDocument();
    $DTE_TIMBRE->formatOutput = FALSE;
    $DTE_TIMBRE->preserveWhiteSpace = TRUE;
    
    if(is_file("../procesos/xml_emitidos/".substr($rutEmisor,0,-2)."/".$obj->getId().".xml"))
    {
        //error_log("\t\tCargado Correctamente",3,"error_log-Exportaciones");
        //echo '<br>Cargado correctamente';
        $DTE_TIMBRE->load("../procesos/xml_emitidos/".substr($rutEmisor,0,-2)."/".$obj->getId().".xml");
        $DTE_TIMBRE->encoding = "ISO-8859-1";
    }
    else
    {
        error_log("\t\tError archivo no existe netdte.cl",3,"error_log-Exportaciones");
    }    
    $import = $DTE_TIMBRE->importNode($nodecaf, true);
    $TSTED = $DTE_TIMBRE->getElementsByTagName("TSTED")->item(0);
    $TSTED->parentNode->insertBefore($import, $TSTED);    
    
    $DD2 = "<DD><RE>".$Documento->Encabezado->Emisor->getRUTEmisor()."</RE><TD>" .$tipoDte ."</TD><F>" . $folio . "</F><FE>".$fechaEmision."</FE><RR>" . $rutReceptor . "</RR><RSR>" .$Documento->TED->DD->getRSR(). "</RSR><MNT>" . $monto_total . "</MNT><IT1>" . $Documento->TED->DD->getIT1() ."</IT1>".replaceCaf($CAF)."<TSTED>". $fechaTimbre ."</TSTED></DD>";    

    $FRMT = buildSign($DD2, $priv_key);
    $fragment = $DTE_TIMBRE->createDocumentFragment();
    $fragment->appendXML("<FRMT algoritmo=\"SHA1withRSA\">$FRMT</FRMT>\n");
    $TED = $DTE_TIMBRE->getElementsByTagName("TED")->item(0);
    $TED->appendChild($fragment);    
    $code= trim(str_replace("> <","><",str_replace(">  <","><",str_replace(">   <","><",str_replace(">    <","><",str_replace("\n","",str_replace("\t","",utf8_decode($DTE_TIMBRE->saveXML($TED)))))))));
    
    $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();                    
    if(!file_exists("../certificados/".substr($rutEmisor,0,-2)."/".$certificadoNombre))
    {
        //error_log("\tNo se encontro certificado ",3,"error_log-Exportaciones");
        echo 'no existe certificado';
        error_log("\t\certificados/".substr($rutEmisor,0,-2)."/".$certificadoNombre,3,"error_log-Exportaciones");   
        exit;
    }    
    $pfx = file_get_contents("../certificados/".substr($rutEmisor,0,-2)."/".$certificadoNombre);    
    openssl_pkcs12_read($pfx, $key,$certificadoClave);
    if(empty($key["pkey"]))
    {
        $msg="[".$certificadoClave."]<--clave Al parecer el certificado no es valido y que no contiene una llave privada. Contactese con el proveedor de la firma.\n";
        //error_log("**EOP**|PKEY|$msg",3,"error_log-Exportaciones");          
        echo 'certificado o clave mala';
    }
    $xmlTool->setPrivateKey($key["pkey"]);
    $xmlTool->setpublickey($key["cert"]);
    $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);    
    $xmlTool->sign($DTE_TIMBRE, "Exportaciones");

    $DTE_TIMBRE->save("../procesos/xml_emitidos/".substr($rutEmisor,0,-2)."/".$obj->getId().".xml");
    $url = "http://certificaciones.netdte.cl/ws/dte_timbre.php?rut=".substr($rutEmisor,0,-2)."&id=".$obj->getId();
    $imagen = "../procesos/firmas/".substr($rutEmisor, 0 , -2)."/T".$tipoDte."_F".$folio.".png";
    file_put_contents($imagen, file_get_contents($url));
    
#Insertando Receptor en Base de Datos
$sql = "INSERT INTO receptor(rut,razon_social,giro,direccion,nacionalidad) VALUES('$rutReceptor','$razonReceptor','$giroReceptor',
'$direccionReceptor','$nacionalidad')";
if($conn->query($sql))
{
    $flag = true;
    $last_id_receptor = $conn->insert_id;    
}
else
echo '<br>no grabe el receptor';

//Insertando Aduana en Base de Datos
$sqlAduana = "INSERT INTO aduana(codmodventa,codclauventa,totclauventa,codviatransp,codptoembarque,
 idadicptoemb,codptodesemb,idadicptodesemb,codunidmedtara,codunidpesobruto,codunidpesoneto,totbultos,
  codtpobultos,cantbultos,marcas,mntflete,mntseguro,codpaisrecep,codpaisdestin) 
  VALUES('$CodModVenta','$CodClauVenta','$TotClauVenta','$CodViaTransp','$CodPtoEmbarque','$IdAdicPtoEmb','$CodPtoDesemb',
  '$IdAdicPtoDesemb','$CodUnidMedTara','$CodUnidPesoBruto','$CodUnidPesoNeto','$TotBultos','$CodTpoBultos','$CantBultos',
  '$Marcas','$MntFlete','$MntSeguro','$CodPaisRecep','$CodPaisDestin')";
if($conn->query($sqlAduana))
{
    $last_id_aduana = $conn->insert_id;    
}
else
echo '<br>no grabe el aduana';

#Insertando en tabla exportacion
$sqlExportacion = "INSERT INTO exportacion(sis_contribuyente_id,idreceptor,idaduana, tipo_documento, folio, fecha_emision,
ind_servicio,forma_pago, tipo_moneda,cambio_clp, monto_exento, monto_total,nombre_xml) VALUES('$idContribuyente','$last_id_receptor' ,'$last_id_aduana','$tipoDte',
'$folio','$fechaEmision','$indServicio','$formaPagoExp','$tipo_moneda','$cambioCLP','$monto_exento',$monto_total,'".$obj->getId().".xml')";      
if($conn->query($sqlExportacion))
{
   $lastIdExportacion = $conn->insert_id;    
}
else
echo '<br>no grabe el exp';

#INSERT detalle de exportacion
for( $a = 0; $a <= count($_POST['boxNmbItem'])-1; $a++)
{
    $sqlDetalle = "INSERT INTO detalle_exportacion(idexportacion,exento,nombre_item,cantidad,unidad,precio_unitario,
    descuento_pct,descuento_monto,recargo_pct,recargo_monto,total_item) VALUES('$lastIdExportacion',".
    $_POST['boxIndExe'][$a].",'". $_POST['boxNmbItem'][$a]."'," . $_POST['boxQtyItem'][$a] .",'".
    $_POST['boxUnmdItem'][$a]."',". $_POST['boxPrcItem'][$a] ."," . $_POST['boxDescuentoPct'][$a] .",".
    $_POST['boxDescuentoMonto'][$a]."," . $_POST['boxRecargoPct'][$a]."," .$_POST['boxRecargoMonto'][$a] .",".
    $_POST['boxMontoItem'][$a].")";
    
    if($conn->query($sqlDetalle))
    {
        echo "<br> Detalles grabados";
    }
    else
    echo "<br> Errores en el detalle";
}

#Insertando referencia exportacion
for($b = 0 ; $b <=count($_POST['DDLSelectTipDocRef'])-1 ; $b++)
{    
    if($_POST['DDLSelectTipDocRef'][$b]!="")
    {
        $sqlReferencia = "INSERT INTO referencia_exportacion(idexportacion,tipo_documento,folio_referencia,fecha_referencia,
        cod_referencia,razon_referencia) VALUES('$lastIdExportacion','". $_POST["DDLSelectTipDocRef"][$b]."','".
        $_POST["boxFolioRef"][$b]."','".$_POST["boxFchRef"][$b]."','".$_POST["DDLCodRef"][$b]."','".$_POST["boxRazonRef"][$b]."')";

        if($conn->query($sqlReferencia))       
        {
           error_log("<br> Referencias grabadas",3,"error_log-Exportaciones");
        }
        else
        error_log("<br>NO GRABE Referencias \n".$sqlReferencia,3,"error_log-Exportaciones");
    }   
 
}

#Insertando los descuentos o recargos globales

for($c = 0 ; $c <=count($_POST['DDLSelectTpoMov'])-1 ; $c++)
{
    
    if($_POST['DDLSelectTpoMov'][$c]=="D" or $_POST['DDLSelectTpoMov'][$c] =="R" and $_POST['DDLSelectTpoMov'][$c]!="")
    { 
        $sqlDscRecarg = "INSERT INTO desc_recarg_exportacion(idexportacion,tipo_movimiento,glosadr,tipo_valor,valor,exento) 
        VALUES('$lastIdExportacion','".$_POST['DDLSelectTpoMov'][$c]."','".$_POST['boxGlosaDR'][$c]."','".$_POST['DDLSelectTpoValor'][$c]."',".
        $_POST['boxValorDR'][$c].",".$_POST['DDLSelectIndExeDR'][$c].")";
        
        if($conn->query($sqlDscRecarg))
        {
            echo "<br> dsc recargos grabados>";
        }
        else
            echo "<br> dsc rec NO grabados";
    }
}
UpdateFolio($tipoDte,$idContribuyente);
#end descuento o recargo

#y por aca deberia finalizar el script
$_SESSION['IDexp'] = $lastIdExportacion;
$_SESSION['Documento'] = $tipoDte;
$_SESSION['Folio'] = $folio;
$_SESSION['Fecha'] = $fechaEmision;
$_SESSION['Receptor'] = $razonReceptor;
$_SESSION['Total'] = $monto_total;
header("Location: ../vistas/Resumen.php");
ob_end_flush();