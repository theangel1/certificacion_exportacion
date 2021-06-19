<?php
  function GetFolioActual($tipoDoc,$idC){
    $conn = conectaDB();
    switch ($tipoDoc) 
    {		
        case '110': //factura exportacion
            $query = "SELECT folio_actual+1 from folios where tipo_dte=110 and sis_contribuyente_id='".$idC."'";                
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($result))
            {
                $aux = $row['folio_actual+1'];			
            }
            return $aux;
        break;	

        case '111': //debito exportacion
            $query = "SELECT folio_actual+1 from folios where tipo_dte=111 and sis_contribuyente_id='".$idC."'";	
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($result))
            {
                $aux = $row['folio_actual+1'];			
            }
            return $aux;
        break;

        case '112': //credito exportacion
            $query = "SELECT folio_actual+1 from folios where tipo_dte=112 and sis_contribuyente_id='".$idC."'";	
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($result))
            {
                $aux = $row['folio_actual+1'];			
            }
            return $aux;
        break;

        default:
            die();
            break;
    }	
}

function UpdateFolio($tipoDoc, $idC){
    $conn = conectaDB();
    switch ($tipoDoc) 
    {
        case '110':
            $sqlFolioFactura="UPDATE folios set folio_actual =folio_actual+1 where tipo_dte=110 and sis_contribuyente_id='".$idC."'";            
            if($conn->query($sqlFolioFactura))
            error_log("folio factura exp actualizado correctamente",3,"error_log-Exportaciones");
            else
            error_log("folio factura exp no actualizado ",3,"error_log-Exportaciones");
        break;
        case '111':
            $sqlFolioFactura="UPDATE folios set folio_actual =folio_actual+1 where tipo_dte=111 and sis_contribuyente_id='".$idC."'";
            if($conn->query($sqlFolioFactura))
            error_log("folio debito EXP actualizado correctamente",3,"error_log-Exportaciones");
            else
            error_log("folio debito EXP no actualizado ",3,"error_log-Exportaciones");
        break;
        case '112':
            $sqlFolioFactura="UPDATE folios set folio_actual =folio_actual+1 where tipo_dte=112 and sis_contribuyente_id='".$idC."'";
            if($conn->query($sqlFolioFactura))
            error_log("folio credito exp actualizado correctamente",3,"error_log-Exportaciones");
            else
            error_log("folio credito exp no actualizado ",3,"error_log-Exportaciones");
        break;
        default:
            die();	
        break;
    }
}

function libxml_display_error($error){
    $return = "\n";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "\t\t\tWarning $error->code: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "\t\t\tError $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "\t\t\tFatal Error $error->code: ";
            break;
    }
    $return .= trim($error->message);
    if ($error->file) {
        $return .=    " en el archivo $error->file";
    }
    $return .= " on la liena $error->line\n";

    return $return;
}

function libxml_display_errors() {
    global $ficheroLog;
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        error_log(libxml_display_error($error),0);
    }
    libxml_clear_errors();
}

function validaFolio($folio,$rutEmpresa,$tipoDTE){
    global $ficheroLog;
	$fuente="../procesos/folios/$rutEmpresa/";
	$directorio = opendir($fuente);
	while ($archivo = readdir($directorio)){
            if(is_file($fuente.$archivo)){
                //error_log("\tValidando Folio $folio del tipo documento $tipoDTE en $archivo");
                $XMLFOLIO=utf8_encode(file_get_contents("../procesos/folios/$rutEmpresa/".$archivo));
                try{
                $Documento = new DOMDocument();
                $Documento->formatOutput = FALSE;
                $Documento->preserveWhiteSpace = TRUE;
                $Documento->loadXML($XMLFOLIO);
                $Documento->encoding = "ISO-8859-1";
                }catch (Exception $e){
                    exit($e);
                }

                $tipoDTEin = intval($Documento->getElementsByTagName("TD")->item(0)->nodeValue);

                if($tipoDTEin==intval($tipoDTE)){
                    $folioIni = intval($Documento->getElementsByTagName("D")->item(0)->nodeValue);
                    $folioFin = intval($Documento->getElementsByTagName("H")->item(0)->nodeValue);
                    if($folio>=$folioIni and $folio<=$folioFin){
                        $rt=$archivo;
                        return $rt;
                    }else{
                        //error_log("\t$folio>=$folioIni and $folio<=$folioFin");
                        echo 'error';
                        $rt="ERROR";
                    }
                }else{
                    //error_log("\tTipo de DTE Distintos".$tipoDTEin."==".intval($tipoDTE));
                    echo 'tipo dte distintos';
                    $rt= "ERROR";
                }
            }
	}
	return $rt;
}

function escribeLog($texto,$ficheroLog="error_log"){
    file_put_contents($ficheroLog,date("H:i:s")."|".$texto."\n",FILE_APPEND);
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

function decimales($n){
    $aux = (string) $n;
    if(strpos( $aux, "." )>0){
        $decimal = substr( $aux, strpos( $aux, "." )+1,2 );
        //error_log("---FACELEC--- Obtengo numero $aux y saco la perte decimal $decimal");
        $ret=substr($aux,0,strpos($aux,"."));
        //error_log("---FACELEC--- Retorno numero $ret");
        return $ret;
    }else{
        //error_log("---FACELEC--- Obtengo numero sin decimales, retorno numero $aux");
        return $aux;
    }
}

function setInterval($f, $milliseconds){
    $seconds=(int)$milliseconds/1000;
    while(true)
    {
        $f();
        sleep($seconds);
    }
}

function replaceSii($subject){
    return str_replace(array("&","<",">","\"","'"), array("&amp;","&lt;","&gt;","&quot;","&apos;"), $subject);
}
function TildesHtml($cadena) { 
    return str_replace(array("á","é","í","ó","ú","ñ","Á","É","Í","Ó","Ú","Ñ","°","\"","'"),array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;","&deg;","&quot;","&apos;"), $cadena);
}

