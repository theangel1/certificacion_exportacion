<?php
require_once('boletas/EnvioLibro.php');
require_once('boletas/Caratula.php');
require_once('boletas/ResumenPeriodo.php');
require_once('boletas/Detalle.php');

class IEB {
   public $EnvioLibro;
    
    function getEnvioLibro() {
        return $this->EnvioLibro;
    }

    function setEnvioLibro($EnvioLibro) {
        $this->EnvioLibro = $EnvioLibro;
    }  

}
