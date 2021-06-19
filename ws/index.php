<?php
//ini_set('display_errors', 1);
//error_reporting(0);
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ALL ^ E_NOTICE);

/*
******************Procedimiento para host Sisgen Chile***************
*/
require_once('funciones.php');
require_once('EnviaDocumento.php');
require_once("../procesos/lib/xmlseclibs/XmlseclibsAdapter.php");
require_once("../procesos/lib/ObjectAndXML.php");
require_once("../procesos/lib/SII.php");
//require_once '../procesos/lib/tcpdf_min/tcpdf_barcodes_2d.php';

$conn = conectaDB();
date_default_timezone_set('America/Santiago');
eval($_REQUEST["func"]."();");

function generaDoc(){
    global $conn,$debug;
    $tracklog=0;
    $linea=0;
    if(intval($_POST["id"])==0){
        echo("1:Revise la configuracion del archivo INI ya que no se encuentra el parametro IDCONT");
        exit(200);
    }

    $sql="select sis_contribuyente_certificado,sis_contribuyente_clave,sis_contribuyente_rutrl,sis_contribuyente_fecresol,sis_contribuyente_numresol from sis_contribuyente where "
    . "sis_contribuyente_id=".$_POST["id"];
    error_log("*************Iniciando Servicio de Facturación Netdte.cl*************",0);  


    $query = $conn->query($sql);
    $con= $query->fetch_assoc();
    
    $aExento = explode("|",substr($_POST["indExento"],0,-1));
    $aNombre = explode("|",substr($_POST["nombreItem"],0,-1));
    $aDescripcion = explode("|",substr($_POST["descriItem"],0,-1));
    $aCantidad = explode("|",substr($_POST["cantidadItem"],0,-1));
    $aUnidad = explode("|",substr($_POST["unidadItem"],0,-1));
    $aPrecio = explode("|",substr($_POST["valorItem"],0,-1));
    $aDescuento = explode("|",substr($_POST["porcentajeDescuentoItem"],0,-1));
    $aDescuentoM = explode("|",substr($_POST["montoDescuentoItem"],0,-1));
    $aRecargo = explode("|",substr($_POST["porcentajeRecargoItem"],0,-1));
    $aRecargoM = explode("|",substr($_POST["montoRecargoItem"],0,-1));
    $aImpAdic1 = explode("|",substr($_POST["codImp1"],0,-1));
    $aImpAdic2 = explode("|",substr($_POST["codImp2"],0,-1));
    $aTotal = explode("|",substr($_POST["totalItem"],0,-1));
    $aCodigo = explode("|",substr($_POST["codigoItem"],0,-1));
    $aOtraMoneda = explode("|",substr($_POST["otraMoneda"],0,-1));
    $aPrecioMoneda = explode("|",substr($_POST["precioMoneda"],0,-1));
    $afactorMoneda = explode("|",substr($_POST["factorMoneda"],0,-1));
    $acodigoMoneda = explode("|",substr($_POST["codigoMoneda"],0,-1));
    $acodigoDocLiq =  explode("|",substr($_POST["codigoDocLiq"],0,-1));
    /*Array Descuento Recargo*/
    $atipoMov=explode("|",substr($_POST["tipoMov"],0,-1));
    $aGlosaDR=explode("|",substr($_POST["GlosaDR"],0,-1));
    $aTpoValor=explode("|",substr($_POST["TpoValor"],0,-1));
    $aValorDR=explode("|",substr($_POST["ValorDR"],0,-1));
    $aIndExeDR=explode("|",substr($_POST["IndExeDR"],0,-1));
    
    /*Array Referencias*/
    $aTpoDocRef = explode("|",substr($_POST["TpoDocRef"],0,-1));
    $aIndGlobal = explode("|",substr($_POST["IndGlobal"],0,-1));
    $aFolioRef = explode("|",substr($_POST["FolioRef"],0,-1));
    $aFchRef = explode("|",substr($_POST["FchRef"],0,-1));
    $aCodRef = explode("|",substr($_POST["CodRef"],0,-1));
    $aRazonRef = explode("|",substr($_POST["RazonRef"],0,-1));
    
    /*Array Comisiones*/
    $aTipoMovim = explode("|",substr($_POST["TipoMovim"],0,-1));
    $aGlosaCom = explode("|",substr($_POST["GlosaCom"],0,-1));
    $aTasaComision = explode("|",substr($_POST["TasaComision"],0,-1));
    $aValComNeto = explode("|",substr($_POST["ValComNeto"],0,-1));
    $aValComExe = explode("|",substr($_POST["ValComExe"],0,-1));
    $aValComIVA = explode("|",substr($_POST["ValComIVA"],0,-1));
    
    /*PROCESO GENERADOR DEL DOCUMENTO*/
    error_log("[".$tracklog."] Generando documento tipo ".$_POST["tipoDte"],0);    
    
    if($_POST["tipoDte"]==43){
        require_once("../procesos/lib/LIQUIDACION/DTE.php");
        $DTE = new DTE();
        $Documento = new Liquidacion();
    }else{
        require_once("../procesos/lib/DTE/DTE.php");
        $DTE = new DTE();
        $Documento = new Documento();
    }
    
    $Documento->setEncabezado();
    $Documento->Encabezado->setIdDoc();
    $Documento->Encabezado->IdDoc->setTipoDTE($_POST["tipoDte"]);
    $Documento->Encabezado->IdDoc->setFolio($_POST["folioDocumento"]);
    $Documento->Encabezado->IdDoc->setFchEmis($_POST["fec_emision"]);
    
    if($_POST["tipo_despacho"]!=""){//Tienen codigo de despacho
        $Documento->Encabezado->IdDoc->setTipoDespacho($_POST["tipo_despacho"]);
    }
    
    if($_POST["tipo_traslado"]!=""){
        $Documento->Encabezado->IdDoc->setIndTraslado($_POST["tipo_traslado"]);
    }
    
    if($_POST["forma_pago"]!==""){
        $Documento->Encabezado->IdDoc->setFmaPago($_POST["forma_pago"]);
    }
    
    $Documento->Encabezado->setEmisor();
    $Documento->Encabezado->Emisor->setRUTEmisor($_POST["rut_emisor"]);
    $Documento->Encabezado->Emisor->setRznSoc($_POST["razon_social"]);
    $Documento->Encabezado->Emisor->setActeco($_POST["codigo_actividad"]);
    if($_POST["tipoDte"]==52){
        if($_POST["tipo_traslado"]==8 or $_POST["tipo_traslado"]==9){
            $Documento->Encabezado->Emisor->setCdgTraslado($_POST["codigo_traslado"]);
            if($_POST["codigo_traslado"]==4){
                $Documento->Encabezado->Emisor->setFolioAut($_POST["folio_autorizacion"]);
                $Documento->Encabezado->Emisor->setFchAut($_POST["fecha_autorizacion"]);
            }
        }
    }
    $Documento->Encabezado->Emisor->setGiroEmis($_POST["giro"]);
    //$Documento->Encabezado->Emisor->setTelefono($Telefono);
    //$Documento->Encabezado->Emisor->setCorreoEmisor($CorreoEmisor);
    if($_POST["sucursal"]!="")
    {
       $Documento->Encabezado->Emisor->setSucursal($_POST["sucursal"]);	
   }	
   $Documento->Encabezado->Emisor->setDirOrigen($_POST["direccion_sucursal"]);
   $Documento->Encabezado->Emisor->setCmnaOrigen($_POST["comuna_origen"]);
   $Documento->Encabezado->Emisor->setCiudadOrigen($_POST["ciudad_origen"]);

   $Documento->Encabezado->setReceptor();
   $Documento->Encabezado->Receptor->setRUTRecep($_POST["rut_receptor"]);
   $Documento->Encabezado->Receptor->setRznSocRecep($_POST["razon_social_recep"]);
   $Documento->Encabezado->Receptor->setGiroRecep(substr($_POST["giro_receptor"],0,40));
   $Documento->Encabezado->Receptor->setCmnaRecep($_POST["comuna_recep"]);
   $Documento->Encabezado->Receptor->setCiudadRecep($_POST["ciudad_recep"]);
   $Documento->Encabezado->Receptor->setDirRecep($_POST["direccion_receptor"]);

    //Detalle
   for($d=0;$d<=count($aDescripcion)-1;$d++){
    $linea++;
    $detalle = new Detalle;
    $detalle->setCdgItem();
        $detalle->CdgItem->setTpoCodigo("Interna");//ESTA VA FIJO
        if(trim($aCodigo[$d])!=""){
            $detalle->CdgItem->setVlrCodigo(trim($aCodigo[$d]));
        }else{
            $detalle->CdgItem->setVlrCodigo("0");
        }
        $detalle->setNroLinDet("$linea");
        //$detalle->setNmbItem(htmlspecialchars(trim($aNombre[$d]),ENT_IGNORE));
        $detalle->setNmbItem(utf8_decode(replaceSii(substr($aNombre[$d],0,40))));
		//$Documento->TED->DD->setIT1(utf8_decode(replaceSii(substr($Documento->Detalle[0]->getNmbItem(),0,40))));//baby
		//baby
        if(trim($aDescripcion[$d])!=""){
            $detalle->setDscItem(trim($aDescripcion[$d]));
        }
        if(trim($aCantidad[$d])!=""){
            $detalle->setQtyItem($aCantidad[$d]);
        }
        
        if(trim($aUnidad[$d])!=""){
            $detalle->setUnmdItem(trim(substr($aUnidad[$d],0,4)));
        }
        if(intval($aPrecio[$d])>0){
            $detalle->setPrcItem(trim(number_format($aPrecio[$d],2,".","")));
        }
        
        if(trim($aDescuento[$d])!=""){
            $detalle->setDescuentoPct(trim($aDescuento[$d]));
        }
        if(trim($aDescuentoM[$d])!=""){
            $detalle->setDescuentoMonto(trim($aDescuentoM[$d]));
        }
        
        if(trim($aImpAdic1[$d])!=""){
          $detalle->setCodImpAdic(trim($aImpAdic1[$d]));
      }

      if(trim($aImpAdic2[$d])!=""){
          $detalle->setCodImpAdic(trim($aImpAdic12[$d]));
      }

        //Campo del documento que se liquida
      if($_POST["tipoDte"]==43){
        $detalle->setTpoDocLiq(trim($acodigoDocLiq[$d]));
    }

    $detalle->setMontoItem("".trim(round($aTotal[$d]))."");
    if(intval($aExento[$d]) == 1 && $_POST["tipoDte"]!=34){
        $detalle->setIndExe($aExento[$d]); 
    }else if($_POST["tipoDte"]==34){
        $detalle->setIndExe($aExento[$d]); 
    }
    for($r=0;$r<=count($aTpoDocRef)-1;$r++){
        if(($$aTpoDocRef[$r]==34 || $aExento[$d] == 1)  && ($_POST["tipoDte"]==56 || $_POST["tipoDte"]==61)){
            $detalle->setIndExe("1"); 
        }
    }
    $Documento->setDetalle($detalle);
}

    //Descuento o Recargo
$lineaDr=0;
for($dr=0;$dr<=count($atipoMov)-1;$dr++){
    $lineaDr++;
        if($atipoMov[$dr]=="D" or $atipoMov[$dr]=="R"){ //Descuento o Recargo de un monto global
            $DescuentoGlobal = new DscRcgGlobal();
            $DescuentoGlobal->setNroLinDR("$lineaDr");
            $DescuentoGlobal->setTpoMov($atipoMov[$dr]);
            $DescuentoGlobal->setGlosaDR($aGlosaDR[$dr]);
            $DescuentoGlobal->setTpoValor($aTpoValor[$dr]);
            $DescuentoGlobal->setValorDR($aValorDR[$dr]);
            if($aIndExeDR[$dr]!=""){
                $DescuentoGlobal->setIndExeDR($aIndExeDR[$dr]);
            }
            $Documento->setDscRcgGlobal($DescuentoGlobal);
        }
    }
    
    //Referencias
    $nroLinRef=0;
    for($r=0;$r<=count($aTpoDocRef)-1;$r++){
        if(trim($aTpoDocRef[$r])!=""){
            $nroLinRef++;
            $referencia = new Referencia();
            $referencia->setNroLinRef("$nroLinRef");
            $referencia->setTpoDocRef($aTpoDocRef[$r]);
            if(intval($aIndGlobal[$r])==1){
                $referencia->setIndGlobal(trim($aIndGlobal[$r]));
            }
            $referencia->setFolioRef(trim($aFolioRef[$r]));
            $referencia->setFchRef(trim($aFchRef[$r]));
            if(intval($aCodRef[$r])>0){
                $referencia->setCodRef(trim($aCodRef[$r]));
            }
            $referencia->setRazonRef(trim($aRazonRef[$r]));
            $Documento->setReferencia($referencia);
        }
    }
    if($_POST["tipoDte"]==43 and !empty($aTipoMovim[0])){
        //Comisiones
        $nroLinCom=0;
        for($r=0;$r<=count($aTipoMovim)-1;$r++){
            if(trim($aTipoMovim[$r])!=""){
                $nroLinCom++;
                $comision = new Comisiones();
                $comision->setNroLinCom("$nroLinCom");
                $comision->setTipoMovim($aTipoMovim[$r]);
                $comision->setGlosa($aGlosaCom[$r]);
                if(!empty($aTasaComision[$r])){
                    $comision->setTasaComision($aTasaComision[$r]);
                }
                if(!empty($aValComNeto[$r])){
                    $comision->setValComNeto($aValComNeto[$r]);
                }
                $comision->setValComExe(strval(intval($aValComExe[$r])));
                
                if(!empty($aValComIVA[$r])){
                    $comision->setValComIVA($aValComIVA[$r]);
                }
                $Documento->setComisiones($comision);
            }
        }
    }

    $totalesEncabezado = new Totales();
    switch($_POST["tipoDte"])
    {        
        case "33":                   			
			if($_POST["monto_neto"]!="0")
			{
                $totalesEncabezado->setMntNeto(trim(round($_POST["monto_neto"])));               
			}
			else
			{
				error_log("\n\nTuvimos un problema en el encabezado con los montos en cero. Cliente: ".$_POST["rut_emisor"] . " Razon Social: ". $_POST["razon_social"] ." Documento: ".$_POST['tipoDte']. "Folio: ".$_POST['folioDocumento'],3, "errorEncabezados");
				exit("Monto neto viene en cero");
			} 
            
            if($_POST["monto_exento"]!="" or $_POST["monto_exento"]!="0")
            {
                $totalesEncabezado->setMntExe(trim(round($_POST["monto_exento"])));
            }        
            $totalesEncabezado->setTasaIVA(trim($_POST["tasa_iva"]));        
            $totalesEncabezado->setIVA(trim(round($_POST["iva"])));        
            
               
                $totalesEncabezado->setMntTotal(trim(round($_POST["total"])));
              //  error_log("\n\nTuvimos un problema en el encabezado con los montos. Cliente: ".$_POST["rut_emisor"] . " Razon Social: ". $_POST["razon_social"] ." Documento: ".$_POST['tipoDte']. "Folio: ".$_POST['folioDocumento'],3, "errorEncabezados");
            //    exit("Finalizado por errores en encabezado");
            
            $Documento->Encabezado->setTotales($totalesEncabezado);          
        
        break;

        case "34":
            $totalesEncabezado->setMntExe(trim(decimales($_POST["monto_exento"])));
            $totalesEncabezado->setMntTotal(trim(decimales($_POST["total"])));
            $Documento->Encabezado->setTotales($totalesEncabezado);          
        break;
        
        case "43":
            $totalesEncabezado->setMntNeto(trim(round($_POST["monto_neto"])));
        if($_POST["monto_exento"]!="")
        {
            $totalesEncabezado->setMntExe(trim(round($_POST["monto_exento"])));
        }
            $totalesEncabezado->setTasaIVA(trim($_POST["tasa_iva"]));
            $totalesEncabezado->setIVA(trim(round($_POST["iva"])));
            
        if(!empty($_POST["neto_comision"])){
            $comisionTotales = new Comisiones();
            $comisionTotales->setValComNeto(trim(round($_POST["neto_comision"])));
            $comisionTotales->setValComExe(trim(round($_POST["exento_comision"])));
            $comisionTotales->setValComIVA(trim(round($_POST["iva_comision"])));
            $Documento->Encabezado->Totales->setComisiones($comisionTotales);                    
        }
        $totalesEncabezado->setMntTotal(trim(round($_POST["total"])));        
        $Documento->Encabezado->setTotales($totalesEncabezado);
        break;
        
        case "52":
        if($monto_exento!="")            
            $totalesEncabezado->setMntExe(trim(round($_POST["monto_exento"])));            
            $totalesEncabezado->setTasaIVA(trim($_POST["tasa_iva"]));
            $totalesEncabezado->setIVA(trim(round($_POST["iva"])));
            $totalesEncabezado->setMntTotal(trim(round($_POST["total"])));   
            $Documento->Encabezado->setTotales($totalesEncabezado);
            break;
        
        case "56":
        if($aTpoDocRef[0]=="33"){
            $totalesEncabezado->setMntNeto(trim(round($_POST["monto_neto"])));
            if($monto_exento!="")
            $totalesEncabezado->setMntExe(trim(round($_POST["monto_exento"])));                
            $totalesEncabezado->setTasaIVA(trim($_POST["tasa_iva"]));
            $totalesEncabezado->setIVA(trim(round($_POST["iva"])));
            $totalesEncabezado->setMntTotal(trim(round($_POST["total"])));   
            $Documento->Encabezado->setTotales($totalesEncabezado);
        }else if($aTpoDocRef[0]=="34"){
            $totalesEncabezado->setMntExe(trim(round($_POST["monto_exento"])));
            $totalesEncabezado->setMntTotal(trim(round($_POST["total"]))); 
            $Documento->Encabezado->setTotales($totalesEncabezado);
        }else{
            $totalesEncabezado->setMntNeto(trim(round($_POST["monto_neto"])));
            if($monto_exento!=""){
                $totalesEncabezado->setMntExe(trim(round($_POST["monto_exento"])));
            }
            $totalesEncabezado->setTasaIVA(trim($_POST["tasa_iva"]));
            $totalesEncabezado->setIVA(trim(round($_POST["iva"])));                    
            $totalesEncabezado->setMntTotal(trim(round($_POST["total"])));
            $Documento->Encabezado->setTotales($totalesEncabezado);
        }
        break;
        
        case "61":            
        if($aTpoDocRef[0]=="33" or $aTpoDocRef[0]=="35" or $aTpoDocRef[0]=="39")
        {            
            $totalesEncabezado->setMntNeto(trim(round($_POST["monto_neto"])));
            
            if($monto_exento!="")
            $totalesEncabezado->setMntExe(trim(round($_POST["monto_exento"])));                
            $totalesEncabezado->setTasaIVA(trim($_POST["tasa_iva"]));
            $totalesEncabezado->setIVA(trim(round($_POST["iva"])));
            $totalesEncabezado->setMntTotal(trim(round($_POST["total"])));
            $Documento->Encabezado->setTotales($totalesEncabezado);
        }
        else
        {         
            $totalesEncabezado->setMntNeto(trim(round($_POST["monto_neto"])));
            $totalesEncabezado->setTasaIVA(trim($_POST["tasa_iva"]));
            $totalesEncabezado->setIVA(trim(round($_POST["iva"])));
        }
        if($monto_exento!="" or $monto_exento=="0")                     
        {
            $totalesEncabezado->setMntExe(trim(round($_POST["monto_exento"])));	                				
        }
            $totalesEncabezado->setMntTotal(trim(round($_POST["total"])));
            $Documento->Encabezado->setTotales($totalesEncabezado);
        
        break;   
    
    }//end switch   
  
    
    if($_POST["codigo_imp_adicional1"]!=""){
        $ImptoReten1 = new ImptoReten();
        $ImptoReten1->setTipoImp($_POST["codigo_imp_adicional1"]);
        $ImptoReten1->setTasaImp($_POST["tasa_imp_adicional1"]);
        $ImptoReten1->setMontoImp($_POST["monto_imp_adicional1"]);
        $Documento->Encabezado->Totales->setImptoReten($ImptoReten1);
    }
    
    if($_POST["codigo_imp_adicional2"]!=""){
        $ImptoReten2 = new ImptoReten();
        $ImptoReten2->setTipoImp($_POST["codigo_imp_adicional2"]);
        $ImptoReten2->setTasaImp($_POST["tasa_imp_adicional2"]);
        $ImptoReten2->setMontoImp($_POST["monto_imp_adicional2"]);
        $Documento->Encabezado->Totales->setImptoReten($ImptoReten2);
    }
    
    if($_POST["codigo_imp_adicional3"]!=""){
        $ImptoReten3 = new ImptoReten();
        $ImptoReten3->setTipoImp($_POST["codigo_imp_adicional3"]);
        $ImptoReten3->setTasaImp($_POST["tasa_imp_adicional3"]);
        $ImptoReten3->setMontoImp($_POST["monto_imp_adicional3"]);
        $Documento->Encabezado->Totales->setImptoReten($ImptoReten3);
    }
    
    if($_POST["codigo_imp_adicional4"]!=""){
        $ImptoReten4 = new ImptoReten();
        $ImptoReten4->setTipoImp($_POST["codigo_imp_adicional4"]);
        $ImptoReten4->setTasaImp($_POST["tasa_imp_adicional4"]);
        $ImptoReten4->setMontoImp($_POST["monto_imp_adicional4"]);
        $Documento->Encabezado->Totales->setImptoReten($ImptoReten4);
    }
    
    if($_POST["codigo_imp_adicional5"]!=""){
        $ImptoReten5 = new ImptoReten();
        $ImptoReten5->setTipoImp($_POST["codigo_imp_adicional5"]);
        $ImptoReten5->setTasaImp($_POST["tasa_imp_adicional5"]);
        $ImptoReten5->setMontoImp($_POST["monto_imp_adicional5"]);
        $Documento->Encabezado->Totales->setImptoReten($ImptoReten5);
    }
    
    if(intval($_POST["iva_no_retenido"])>0){
        $Documento->Encabezado->Totales->setIVANoRet($_POST["iva_no_retenido"]);
    }
    
    if(intval($_POST["monto_no_facturable"])>0){
        $Documento->Encabezado->Totales->setMontoNF($_POST["monto_no_facturable"]);
    }
    
    if(intval($_POST["ceec"])>0){
        $Documento->Encabezado->Totales->setCredEC($_POST["ceec"]);
    }    
    
    $pRutEmpresa   = substr ($Documento->Encabezado->Emisor->getRUTEmisor(),0, -2);
    $idDte = "T".$Documento->Encabezado->IdDoc->getTipoDte()."_F".$Documento->Encabezado->IdDoc->getFolio()."-".date("YMDHis");
    
    $obj = new ObjectAndXML($idDte,$pRutEmpresa);

    $obj->setStartElement("DTE");
    $obj->setId($idDte);

    #Cambio para el timestamp
    $timezone = new DateTimeZone('America/Santiago'); 
	$date = new DateTime('', $timezone);
	$TmstFirma = $date->format('Y-m-d\TH:i:s');

    $Documento->setTmstFirma($TmstFirma);   
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
    $Documento->TED->DD->setTSTED($TmstFirma);                

    if($_POST["tipoDte"]==43){
        $DTE->setLiquidacion($Documento);
    }else{
        $DTE->setDocumento($Documento);
    }
    utf8_encode_deep($DTE);
    
    $recordsXML = $obj->objToXML($DTE);

    /********** OBTENER CAF, LLAVES PRIVADA Y PUBLICA DEL CAF **********/
    error_log("[".$tracklog."] \tObteniendo CAF y Llave privada.COM",0);
    $marcaFOLIO=0;
    //RBB
    $LCAFImport = new DOMDocument();
    $LCAFImport->formatOutput = TRUE;
    $LCAFImport->preserveWhiteSpace = TRUE;
    
    $archivoFolio=validaFolio($_POST["folioDocumento"],substr($_POST["rut_emisor"],0,-2),$_POST["tipoDte"]);
    if($archivoFolio!="ERROR" and $archivoFolio!=""){
        if(!$LCAFImport->load("/home/netdte/www/procesos/folios/".substr($_POST["rut_emisor"],0,-2)."/".$archivoFolio)){
            $XMLFOLIO = utf8_encode(file_get_contents("/home/netdte/www/procesos/folios/".substr($_POST["rut_emisor"],0,-2)."/".$archivoFolio));
            if($LCAFImport->loadXML($XMLFOLIO)){
                $marcaFOLIO = 1;
                #error_log("[".$tracklog."] \t[MARCA=1]Folio validado desde archivo $archivoFolio",0);
            }
        }else{
            #error_log("[".$tracklog."] \tFolio validado desde archivo /home/sisgenchile/www/sisgenfe/procesos/folios/".substr($_POST["rut_emisor"],0,-2)."/".$archivoFolio,0);
        }

    }else{
        error_log("[".$tracklog."] **EOP**|11|No se pudo encontrar un CAF para el folio" .$_POST["folioDocumento"],0);
        exit("1:No se pudo encontrar un CAF para el folio ".$_POST["folioDocumento"]);
    }
    
    
    $CAF = $LCAFImport->getElementsByTagName("CAF")->item(0);
    $nodecaf = $LCAFImport->getElementsByTagName("CAF")->item(0);
    $priv_key = $LCAFImport->getElementsByTagName("RSASK")->item(0)->nodeValue;
    $CAF = $LCAFImport->saveXML($CAF);
    if($marcaFOLIO == 1){ 
        $CAF = utf8_decode($CAF);
    }
    /********** OBTENER CAF, LLAVES PRIVADA Y PUBLICA DEL CAF **********/                

    $DTE_TIMBRE = new DOMDocument();
    $DTE_TIMBRE->formatOutput = FALSE;
    $DTE_TIMBRE->preserveWhiteSpace = TRUE;

    #error_log("[".$tracklog."] \tCargando XML procesos/xml_emitidos/".substr($_POST["rut_emisor"],0,-2)."/".$obj->getId().".xml",0);
    if(is_file("/home/netdte/www/procesos/xml_emitidos/".substr($_POST["rut_emisor"],0,-2)."/".$obj->getId().".xml")){
        #error_log("[".$tracklog."] \t\tCargado Correctamente",0);
        $DTE_TIMBRE->load("/home/netdte/www/procesos/xml_emitidos/".substr($_POST["rut_emisor"],0,-2)."/".$obj->getId().".xml");
        $DTE_TIMBRE->encoding = "ISO-8859-1";
    }else{
        error_log("[".$tracklog."] \t\tError archivo no existe",0);
    }
    //$DTE_TIMBRE->formatOutput = true;
    $import = $DTE_TIMBRE->importNode($nodecaf, true);
    $TSTED = $DTE_TIMBRE->getElementsByTagName("TSTED")->item(0);
    $TSTED->parentNode->insertBefore($import, $TSTED);
    
    //Detalle del timbre
    //$CAF=str_replace(">  <","><",str_replace(">   <","><",str_replace(">    <","><",str_replace(">      <","><",str_replace(array("\n","\t"),array("",""),$CAF)))));
    //error_log("CAF FIRMADO;".$CAF);
    $DD2 = "<DD><RE>".$Documento->Encabezado->Emisor->getRUTEmisor()."</RE><TD>" . $_POST["tipoDte"] ."</TD><F>" . $_POST["folioDocumento"] . "</F><FE>".$_POST["fec_emision"]."</FE><RR>" . $_POST["rut_receptor"] . "</RR><RSR>" .$Documento->TED->DD->getRSR(). "</RSR><MNT>" . trim(decimales($_POST["total"])) . "</MNT><IT1>" . $Documento->TED->DD->getIT1() ."</IT1>".replaceCaf($CAF)."<TSTED>". $_POST["fechaTimbre"] ."</TSTED></DD>";
    
    $FRMT = buildSign($DD2, $priv_key); //se firma con la llave del servicio, esta funcion se encuntra en globals.php
    $fragment = $DTE_TIMBRE->createDocumentFragment();
    $fragment->appendXML("<FRMT algoritmo=\"SHA1withRSA\">$FRMT</FRMT>\n");
    $TED = $DTE_TIMBRE->getElementsByTagName("TED")->item(0);
    $TED->appendChild($fragment);
    
    $code= trim(str_replace("> <","><",str_replace(">  <","><",str_replace(">   <","><",str_replace(">    <","><",str_replace("\n","",str_replace("\t","",utf8_decode($DTE_TIMBRE->saveXML($TED)))))))));
    
    $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();                    
    if(!file_exists("/home/netdte/www/certificados/".substr($_POST["rut_emisor"],0,-2)."/".$con["sis_contribuyente_certificado"])){
        error_log("[".$tracklog."] \tNo se encontro certificado ",0);
        error_log("[".$tracklog."] \t\certificados/".substr($_POST["rut_emisor"],0,-2)."/".$con["sis_contribuyente_certificado"],0);
        exit;
    }    
    $pfx = file_get_contents("/home/netdte/www/certificados/".substr($_POST["rut_emisor"],0,-2)."/".$con["sis_contribuyente_certificado"]);	
    openssl_pkcs12_read($pfx, $key, $con["sis_contribuyente_clave"]);
    if(empty($key["pkey"])){
        $msg="[".$con["sis_contribuyente_clave"]."]Al parecer el certificado no es valido y que no contiene una llave privada. Contactese con el proveedor de la firma.\n";
        error_log("[".$tracklog."] **EOP**|PKEY|$msg",0);
        exit("1:Al parecer el certificado no es valido, o bien caduco. Debera ser subido manualmente al servidor de en SISGEN");
    }
    $xmlTool->setPrivateKey($key["pkey"]);
    $xmlTool->setpublickey($key["cert"]);
    $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
    
    if($_POST["tipoDte"]==43){
        $xmlTool->sign($DTE_TIMBRE, "LIQUIDACION");
    }else{
        $xmlTool->sign($DTE_TIMBRE, "DTE");
    }
    $DTE_TIMBRE->save("/home/netdte/www/procesos/xml_emitidos/".substr($_POST["rut_emisor"],0,-2)."/".$obj->getId().".xml");
    
    if(isset($_POST["proservice"]))
    {
        if(enviaDocumento($obj->getId().".xml",$_POST["rut_emisor"],$con["sis_contribuyente_rutrl"],$con["sis_contribuyente_fecresol"],$con["sis_contribuyente_numresol"],$con["sis_contribuyente_certificado"],$con["sis_contribuyente_clave"],$_POST["email_recep"],0))
        {
        #error_log("Sending data...");
        }
        else
        {
            error_log("[".$tracklog."] **ERROR**||Se produjo un error al procesar el sobre en POST PROSERVICE\nProceso Finalizado ".date("H:i:s"),0);
        }
    }else{
        ////genera imagen de firma
        #header("Content-Type: image/png");    
        #header("Content-Disposition: attachment; filename=T".$_POST["tipoDte"] ."_F".$_POST["folioDocumento"].".png");  
        #header("Pragma: no-cache"); 
        #header("Expires: 0");

        if($_POST["etapa"]==0){
            #error_log("ENVIANDO CORREO A ".$_POST["email_recep"]."!!!!!!!!!!!!!!!!!!!!",0);
            if(enviaDocumento($obj->getId().".xml",$_POST["rut_emisor"],$con["sis_contribuyente_rutrl"],$con["sis_contribuyente_fecresol"],$con["sis_contribuyente_numresol"],$con["sis_contribuyente_certificado"],$con["sis_contribuyente_clave"],$_POST["email_recep"],0)){
                //$type = "PDF417";

                #$type="PDF417,3,5";
                #error_log("[".$tracklog."] Generando Firma");
                //error_log("\n\t Code:".$code." Tipo:".$type);
                #$barcodeobj = new TCPDF2DBarcode(trim($code), $type);
                #$barcodeobj->getBarcodePNG();

            }else{
                error_log("[".$tracklog."] **ERROR**||Se produjo un error al procesar el sobre\nProceso Finalizado ".date("H:i:s"),0);
            }
        }else{
            //$type = "PDF417";
            #$type="PDF417,3,5";
            #error_log("[".$tracklog."] Generando Firma \n\t Code:".$code." Tipo:".$type,0);
            #$barcodeobj = new TCPDF2DBarcode(trim($code), $type);
            #$barcodeobj->getBarcodePNG(1,1);
        }
    }

    
}

function enrola(){
    global $conn,$debug;
    $sql="SELECT * FROM sis_contribuyente where sis_contribuyente_rut='" .$_POST['rut']. "' and sis_contribuyente_estado<>0";
    #error_log("Enrolando nuevo cliente\n",3,'Error_Enrola');
    #error_log($sql.'\n',3,'Error_Enrola');
    
    $query= $conn->query($sql);
    $numRows = $query->num_rows;
    $mac = str_replace(":", "", $_POST["id"]);
    $licencia = md5($mac.":".$_POST["pos"]);
    if($numRows>0){
        $dato = $query->fetch_assoc();
        #error_log('Cadena Enrola\n\t0:'.$dato["sis_contribuyente_razon"].':'.$licencia.':'.$dato["sis_contribuyente_id"].':'.$dato["sis_contribuyente_razon"].':'.$dato["sis_contribuyente_fantasia"].':'.$dato["sis_contribuyente_giro"].':'.$dato["sis_contribuyente_direccion"].':'.$dato["sis_contribuyente_fono"].':'.$dato["sis_contribuyente_email"].':'.$dato["sis_contribuyente_representante"].':'.$dato["sis_contribuyente_rutrl"]."\n",3,'Error_Enrola');
        echo '0:'.$dato["sis_contribuyente_razon"].':'.$licencia.':'.$dato["sis_contribuyente_id"].':'.$dato["sis_contribuyente_razon"].':'.$dato["sis_contribuyente_fantasia"].':'.$dato["sis_contribuyente_giro"].':'.$dato["sis_contribuyente_direccion"].':'.$dato["sis_contribuyente_fono"].':'.$dato["sis_contribuyente_email"].':'.$dato["sis_contribuyente_representante"].':'.$dato["sis_contribuyente_rutrl"];
    }else{
        #error_log('Cadena Enrola\n\t1:El cliente '.$_POST['rut'].' no se encuentra debidamente enrolado\n',3,'Error_Enrola');
        echo '1:El cliente '.$_POST['rut'].' no se encuentra debidamente enrolado';
    }
}

function contribuyente(){	
    global $conn,$debug;
    $tracklog=0;
    $sql="SELECT sis_contribuyente_id FROM sis_contribuyente where sis_contribuyente_rut='" .$_POST['rut']. "' and sis_contribuyente_estado<>0";
    $query= $conn->query($sql);
    $ctb = $query->fetch_assoc();
    //error_log($sql,3,'error_enrola');
    if(intval($ctb["sis_contribuyente_id"]>0))
    {
        $id=$ctb["sis_contribuyente_id"];
        //error_log("[".$tracklog."] COntribuyente existe",3,'error_enrola');
        $sql2="update sis_contribuyente set sis_contribuyente_rut='".$_POST["rut"]."',sis_contribuyente_razon='".$_POST["razon"]."',"
        . "sis_contribuyente_fantasia='".$_POST["fantasia"]."',sis_contribuyente_giro='".$_POST["giro"]."',"
        . "sis_contribuyente_direccion='".$_POST["direccion"]."',sis_contribuyente_telefono='".$_POST["telefono"]."',"
        . "sis_contribuyente_email='".$_POST["email"]."',sis_contribuyente_representante='".$_POST["representante"]."',sis_contribuyente_rutrl='".$_POST["rutrl"]."'";
        if($_POST["certificado"]!="")
        {
            $sql2.=",sis_contribuyente_certificado='".$_POST["certificado"].".pfx'";
        }
        if($_POST["clave"]!="")
        {
            $sql2.=",sis_contribuyente_clave='".$_POST["clave"]."'";
        }
        $sql2.=" where sis_contribuyente_id=".$ctb["sis_contribuyente_id"];
		//error_log($sql2, 3, 'error_enrola');
        if($query= $conn->query($sql2))
        {

			//error_log("\n\n".$query, 3, 'error_enrola');
			#error_log("[".$tracklog."] Enrola:",3,'error_enrola');
            #error_log("[".$tracklog."] Query:".$sql,3,'error_enrola');
            //$sql="update sisgenchile_intradb.cliente set sis_contribuyente_id=".$id." where cliente_rut='".$_POST["rut"]."'"; flag aca, cambio por $ctb
         //$sql="update sisgenchile_intradb.cliente set sis_contribuyente_id=".$ctb["sis_contribuyente_id"]." where cliente_rut='".$_POST["rut"]."'";
         //$conn->query($sql);
//            echo "0:".$id; flag cambio por ctb
				#error_log("[".$tracklog."] Recibo esto:".$ctb["sis_contribuyente_id"],3,'error_enrola');
         echo "0:".$ctb["sis_contribuyente_id"];
     }
     else
     {
        exit("SEGUNDO IF netdte");
    }
}
else
{
        #error_log("[".$tracklog."] COntribuyente No existe!!!!!!!!!!!!!!!!",3,'error_enrola');
    $sql="insert into sis_contribuyente values(0,'".$_POST["rut"]."','".$_POST["razon"]."','".$_POST["fantasia"]."','".$_POST["giro"]."',"
    . "'".$_POST["direccion"]."','".$_POST["telefono"]."','".$_POST["email"]."','',1,'".$_POST["certificado"].".pfx','".$_POST["clave"]."',"
    . "1,'".$_POST["representante"]."','".$_POST["rutrl"]."','2014-08-22','80')";
    if($query= $conn->query($sql))
    {
        error_log("[".$tracklog."] Enrola:",3,'Error_Enrola');
        error_log("[".$tracklog."] Query:".$sql,3,'Error_Enrola');

        $sql="select * from sis_contribuyente where sis_contribuyente_rut='".$_POST["rut"]."'";
        $query= $conn->query($sql);
        $ctb = $query->fetch_assoc();
        $id=$ctb["sis_contribuyente_id"];
        echo "0:".$id;
    }
    else
    {
        exit(200);
    }
}

$oldmask = umask(0);
if (!file_exists('/home/netdte/www/certificados/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/certificados/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/certificados/'.substr($_POST["rut"],0,-2),'netdte');
    chgrp('/home/netdte/www/certificados/'.substr($_POST["rut"],0,-2),'netdte');
}
if (!file_exists('/home/netdte/www/documentos/cesiones/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/documentos/cesiones/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/documentos/cesiones/'.substr($_POST["rut"],0,-2),'netdte');
    chgrp('/home/netdte/www/documentos/cesiones/'.substr($_POST["rut"],0,-2), 'netdte');
}
if (!file_exists('/home/netdte/www/documentos/emitidos/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/documentos/emitidos/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/documentos/emitidos/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/documentos/emitidos/'.substr($_POST["rut"],0,-2), 'netdte');
}
if (!file_exists('/home/netdte/www/procesos/folios/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/procesos/folios/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/procesos/folios/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/procesos/folios/'.substr($_POST["rut"],0,-2), 'netdte');
}
if (!file_exists('/home/netdte/www/procesos/xml_emitidos/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/procesos/xml_emitidos/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/procesos/xml_emitidos/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/procesos/xml_emitidos/'.substr($_POST["rut"],0,-2), 'netdte');
}
if (!file_exists('/home/netdte/www/procesos/xml_respuestas/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/procesos/xml_respuestas/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/procesos/xml_respuestas/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/procesos/xml_respuestas/'.substr($_POST["rut"],0,-2), 'netdte');

}
if (!file_exists('/home/netdte/www/procesos/xml_envios/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/procesos/xml_envios/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/procesos/xml_envios/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/procesos/xml_envios/'.substr($_POST["rut"],0,-2), 'netdte');
}
if (!file_exists('/home/netdte/www/procesos/xml_procesados/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/procesos/xml_procesados/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/procesos/xml_procesados/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/procesos/xml_procesados/'.substr($_POST["rut"],0,-2), 'netdte');
}
if (!file_exists('/home/netdte/www/procesos/xml_libros/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/procesos/xml_libros/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/procesos/xml_libros/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/procesos/xml_libros/'.substr($_POST["rut"],0,-2), 'netdte');
}
if (!file_exists('/home/netdte/www/procesos/tmplibros/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/procesos/tmplibros/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/procesos/tmplibros/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/procesos/tmplibros/'.substr($_POST["rut"],0,-2), 'netdte');
}
if (!file_exists('/home/netdte/www/procesos/xml_intercambio/'.substr($_POST["rut"],0,-2))) {
    mkdir('/home/netdte/www/procesos/xml_intercambio/'.substr($_POST["rut"],0,-2), 0777, true);
    chown('/home/netdte/www/procesos/xml_intercambio/'.substr($_POST["rut"],0,-2), 'netdte');
    chgrp('/home/netdte/www/procesos/xml_intercambio/'.substr($_POST["rut"],0,-2), 'netdte');
}

if(!file_exists('/home/netdte/www/documentos/emitidos/'.substr($_POST["rut"],0,-2).'/dte')){
	symlink('/home/netdte/www/procesos/xml_emitidos/'.substr($_POST["rut"],0,-2), '/home/netdte/www/documentos/emitidos/'.substr($_POST["rut"],0,-2).'/dte');
}
umask($oldmask);

}

function comprueba(){
    global $conn,$debug;
    $sql="select pos_mac_id,pos_pc_id from sis_contribuyente_pos where pos_token_id ='".str_replace(array("**hamp**","**mas**","**cre**","**baks**"),array("&","+","''","\\\\"),$_POST["licencia"])."'";
    $query= $conn->query($sql);
    $numRows = $conn->affected_rows;
    #error_log("Comprobando cliente\n", 3, 'Error_Enrola');
    #error_log($sql."\n", 3, 'Error_Enrola');
    
    if($numRows>0){
        $dato = $query->fetch_assoc();
        if($dato["pos_mac_id"]==$_POST["id"]){
            if($dato["pos_pc_id"]==$_POST["pos"]){
                mensajeria();
                echo "0:";
            }else{
                #error_log("ERROR DE ID DE PC\n\tLa licencia que esta instalada no corresponde a este computador\n\t".$dato["pos_pc_id"]."==".$_POST["pos"]."\n",3,'error_enrola');
                echo "97:La licencia que esta instalada no corresponde a este computador.";
            }
        }else{
            #error_log("ERROR DE MAC\n\tLa licencia que esta instalada no corresponde a este computador\n\t".$dato["pos_pc_id"]."==".$_POST["pos"]."\n",3,'error_enrola');
            #error_log("ERROR DE MAC:".$dato["pos_mac_id"]."==".$_POST["id"]."--",3,'error_enrola');
        }
    }else{
        #error_log("Punto de venta no enrolado\n",3,'error_enrola');
        echo "99:Este punto de venta no esta enrolado [".$_POST["licencia"]."]";
    }
}

function licencia(){
    global $conn,$debug;
    $tracklog=0;
    $sqlAngel="select sis_contribuyente_rut from sis_contribuyente where sis_contribuyente_id=".$_POST["contribuyente"];
    $query=$conn->query($sqlAngel);	 
    $rutE = $query->fetch_assoc();
    $rutEmpresa=$rutE["sis_contribuyente_rut"]; 
	#error_log("[".$tracklog."] Recibo esto:".$rutEmpresa,3,'error_enrola');

    $sql="insert into sis_contribuyente_pos values(".$_POST["contribuyente"].",'".$_POST["mac"]."','".$_POST["pos"]."',"
    . "'".str_replace(array("**hamp**","**mas**","**cre**","**baks**"),array("&","+","''","\\\\"),$_POST["licencia"])."',"
    . "'CREATE',current_timestamp) "
    . "on duplicate key update pos_status_date='".date("Y-m-d h:i:s")."',"
    . "pos_pc_id='".$_POST["pos"]."',"
    . "pos_token_id='".$_POST["licencia"]."'";
    $query= $conn->query($sql);
    $numRows = $conn->affected_rows;

    if($numRows>0){
		//flag arreglar
       #error_log("El enrolamiento para el contribuyente ".$rutEmpresa. " fue realizado con exito\n",3,'error_enrola');
     echo '0:El enrolamiento para el contribuyente '.$rutEmpresa. " fue realizado con exito";	
 }else{
    switch($conn->errno){
        case 1062:
        $msg="El servidor '".$_POST["pos"]."' ya se encuentra enrolado";
                #error_log("El servidor '".$_POST["pos"]."' ya se encuentra enrolado\n\n",3,'error_enrola');
        break;
        case 1064:
                #error_log("CADENA MAL FORMADA...".$sql."\n\n",3,'error_enrola');
        $msg="La licencia generada no esta correctamente formada, contactese con soporte ";
        break;
        default:
                #error_log("CADENA MAL FORMADA...".$sql."\n\n",3,'error_enrola');
        $msg="Intente nuevamente mas tarde\nError ".$conn->errno;
    }        
        #error_log("El enrolamiento para el contribuyente ".$_POST["rut"]. " no se pudo realizar.\n",3,'error_enrola');
    echo '1:El enrolamiento para el contribuyente '.$_POST["rut"]. " no se pudo realizar.\n" . $msg;
}
}

function mensajeria(){
    global $conn;
    $timezone = new DateTimeZone('America/Santiago'); 
    $date = new DateTime('', $timezone);
    $fechaLectura = $date->format('Y-m-d H:i:s');
    $sql="update sis_contribuyente_pos set pos_status='ONLINE',pos_status_date=CURRENT_TIMESTAMP "
    . "where sis_contribuyente_id=".$_POST["contribuyente"]." and pos_pc_id='".$_POST["pcname"]."'";
    $query= $conn->query($sql);
    #error_log("Mensajeria para net",3,Error_Enrola);
	#error_log($sql, 0);
    
    /*$sql="select sis_mensajeria_id,sis_mensajeria_mensaje from sis_mensajeria where sis_contribuyente_id =".$_POST["contribuyente"]." and sis_mensajeria_estado=1";
    $query= $conn->query($sql);
    $numRows = $conn->affected_rows;
    
    if($numRows>0){
        $dato = $query->fetch_assoc();
        echo '0:'.utf8_decode($dato["sis_mensajeria_mensaje"]);
        $sql="update sis_mensajeria set sis_mensajeria_estado=2 where sis_mensajeria_id=".$dato["sis_mensajeria_id"];
        $query= $conn->query($sql);
    }*/
}

function conectaDB(){    
    return new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
}

function replaceCaf($subject){
    //error_log("ReplaceCaf:".$subject);
    $return = str_replace(PHP_EOL, "", $subject);
    $return = str_replace("> <", "", $return);    
    return $return;
}
