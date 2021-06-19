<?php
require_once('Receptor.php');
require_once('Exportaciones.php');
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
require_once('OtraMoneda.php');
require_once('Extranjero.php');
/** 
 * @property Documento $Documento Esta propiedad referencia a la clase Documento
*/
class DTE{
    public $Exportaciones;
    
    function getExportaciones() {
        return $this->Exportaciones;
    }

    function setExportaciones($Exportaciones) {
        $this->Exportaciones= $Exportaciones;
    }  

}