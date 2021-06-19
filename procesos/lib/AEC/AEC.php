<?php
require_once('DocumentoAec.php');
require_once('Caratula.php');
require_once('Cesiones.php');
/** 
 * @property Documento $Documento Esta propiedad referencia a la clase Documento
*/
class AEC{
    public $DocumentoAEC;
    
    function getDocumentoAEC() {
        return $this->DocumentoAEC;
    }

    function setDocumentoAEC($DocumentoAEC) {
        $this->DocumentoAEC= $DocumentoAEC;
    }  

}