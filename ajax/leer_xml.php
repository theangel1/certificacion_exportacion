<?php
session_start();

$nombre_archivo = $_POST['nombre_archivo'];
$carpeta = $_SESSION['carpeta'];
$url_carpeta = "../procesos/folios/".$carpeta."/";
$valido = false;
$directorio = opendir($url_carpeta);

//VALIDA EL ARCHIVO XML
while ($archivo = readdir($directorio))
{
    if(is_file($url_carpeta.$archivo))
    {
        $XMLFOLIO=utf8_encode(file_get_contents("../procesos/folios/".$carpeta."/".$nombre_archivo));
        try
        {
	        $Documento = new DOMDocument();
	        $Documento->formatOutput = FALSE;
	        $Documento->preserveWhiteSpace = TRUE;
	        $Documento->loadXML($XMLFOLIO);
	        $Documento->encoding = "ISO-8859-1";
        }catch (Exception $e){
            exit($e);
        }

        //COMPRUEBA QUE EL NODO EXTERNO SEA "AUTORIZACION"
        $url_archivo = "../procesos/folios/".$carpeta."/".$nombre_archivo;
        $xml = simplexml_load_file($url_archivo) or die("Archivo inválido");        
        $autorizacion = $xml->getName();
        if(!empty($autorizacion) && $autorizacion == "AUTORIZACION")
        {
        	//COMPRUEBA QUE EL TIPO DE DOCUMENTO EXISTE, Y QUE SEA UNO DE LOS 3 PERMITIDOS
        	$tipoDTEin = intval($Documento->getElementsByTagName("TD")->item(0)->nodeValue);        
	        if(!empty($tipoDTEin))
	        {
				if($tipoDTEin==110 || $tipoDTEin==111 || $tipoDTEin==112)
				{
		            $valido = true;
		            
		        }else{
		        	$valido = false;
		        }
	        }
	        else
	        {
	        	$valido = false;	        	
	        }      
        }
        else
        {
        	$valido = false;         	      
    	}            
    }
}

//CIERRA EL DIRECTORIO
closedir($directorio);   

//SI EL ARCHIVO NO ES VÁLIDO LO ELIMINA
if(!$valido)
{
 	unlink("../procesos/folios/".$carpeta."/".$nombre_archivo);
 	echo "";
}
else
{	
	//SI EL ARCHIVO ES VÁLIDO OBTIENE EL RANGO DE FOLIOS
	$url_archivo = "../procesos/folios/".$carpeta."/".$nombre_archivo;
	$xml = simplexml_load_file($url_archivo) or die("Archivo inválido");	

	$folioDesde = $xml->CAF->DA->RNG->D;
	$folioHasta = $xml->CAF->DA->RNG->H;
	$idContribuyente = $_SESSION['contribuyente'];

	//BUSCA EN LA BASE DE DATOS SI EXISTE EL FOLIO DEL CONTRIBUYENTE ASOCIADO AL TIPO DE DOCUMENTO
	$mysqli = new mysqli("netdte.cl","netdte_administrador","G(8r3,ru{]bx","netdte_dbexportacion");
	$sqlBuscar = "SELECT * FROM folios WHERE sis_contribuyente_id = '".$idContribuyente."' AND tipo_dte = '".$tipoDTEin."'";
	$resultadoBuscar = mysqli_query($mysqli, $sqlBuscar);
	if(mysqli_num_rows($resultadoBuscar)>0)
	{
		//SI ENCUENTRA EL FOLIO, LO ACTUALIZA
		$row = mysqli_fetch_row($resultadoBuscar);
		$idFolio = $row[0];
		$sqlActualizar = ("UPDATE folios SET rango_hasta = '".$folioHasta."' WHERE idfolios = '".$idFolio."'");
		$resultadoActualizar = mysqli_query($mysqli, $sqlActualizar);
		if($resultadoActualizar === TRUE)
		{
			echo $folioDesde." al ".$folioHasta;
		}
	}
	else
	{
		$sqlInsertar = ("INSERT INTO folios(sis_contribuyente_id,tipo_dte,folio_actual,rango_desde,rango_hasta) 
			VALUES('$idContribuyente', '$tipoDTEin','$folioDesde', '$folioDesde', '$folioHasta')");		
		$resultadoInsertar = mysqli_query($mysqli, $sqlInsertar);
		if($resultadoInsertar === TRUE)
		{
			echo $folioDesde." al ".$folioHasta;
		}
	}
}
?>