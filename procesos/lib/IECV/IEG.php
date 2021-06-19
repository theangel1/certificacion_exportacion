<?php
require_once('guias/EnvioLibro.php');
require_once('guias/Caratula.php');
require_once('guias/ResumenPeriodo.php');
require_once('guias/Detalle.php');

class IEG {
   public $EnvioLibro;
    
    function getEnvioLibro() {
        return $this->EnvioLibro;
    }

    function setEnvioLibro($EnvioLibro) {
        $this->EnvioLibro = $EnvioLibro;
    }  

}
