<?php
require_once('Receptor.php');
require_once('Documento.php');
require_once('DscRcgGlobal.php');
require_once('Referencia.php');
require_once('TED.php');
require_once('Encabezado.php');
require_once('Detalle.php');
require_once('IdDoc.php');
require_once('Emisor.php');
require_once('Totales.php');

/** 
 * @property Documento $Documento Esta propiedad referencia a la clase Documento
*/
class BOLETA{
    public $Documento;
    
    function getDocumento() {
        return $this->Documento;
    }

    function setDocumento($Documento) {
        $this->Documento = $Documento;
    }  

}