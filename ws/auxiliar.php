<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);

require_once("../procesos/lib/tcpdf_min/tcpdf_barcodes_2d.php");
$string ="../procesos/xml_procesados/".$_GET["rut"]."/".$_GET["id"].".xml";

//echo "Abriendo archivo para firma $string\n";
$DTE_TIMBRE = new DOMDocument();
$DTE_TIMBRE->formatOutput = FALSE;
$DTE_TIMBRE->preserveWhiteSpace = TRUE;
$DTE_TIMBRE->load($string);
$DTE_TIMBRE->encoding = "ISO-8859-1";
$TED = $DTE_TIMBRE->getElementsByTagName("TED")->item(0);
$code= trim(str_replace("> <","><",str_replace(">  <","><",str_replace(">   <","><",str_replace(">    <","><",str_replace("\n","",str_replace("\t","",utf8_decode($DTE_TIMBRE->saveXML($TED)))))))));

$type="PDF417,3,5";
$barcodeobj = new TCPDF2DBarcode(trim($code), $type);
$barcodeobj->getBarcodePNG();

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