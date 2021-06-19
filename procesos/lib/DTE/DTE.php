<?php
require_once('Receptor.php');
require_once('Documento.php');
require_once('DscRcgGlobal.php');
require_once('Referencia.php');
require_once('TED.php');
require_once('Encabezado.php');
require_once('CdgItem.php');
require_once('Detalle.php');
require_once('IdDoc.php');
require_once('Emisor.php');
require_once('Totales.php');
require_once('ImptoReten.php');
require_once('TipoBultos.php');
require_once('Aduana.php');
require_once('Transporte.php');
/** 
 * @property Documento $Documento Esta propiedad referencia a la clase Documento
*/
class DTE{
    public $Documento;
    
    function getDocumento() {
        return $this->Documento;
    }

    function setDocumento($Documento) {
        $this->Documento = $Documento;
    }  

}