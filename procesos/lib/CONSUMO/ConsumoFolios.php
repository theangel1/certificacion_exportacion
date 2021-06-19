<?php
require_once('Caratula.php');
require_once('Resumen.php');

class ConsumoFolios{
    public $DocumentoConsumoFolios;
    
    function getDocumentoConsumoFolios() {
        return $this->DocumentoConsumoFolios;
    }

    function setDocumentoConsumoFolios($DocumentoConsumoFolios) {
        $this->DocumentoConsumoFolios= $DocumentoConsumoFolios;
    }
}