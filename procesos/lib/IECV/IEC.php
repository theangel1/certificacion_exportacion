<?php
require_once('compras/EnvioLibro.php');
require_once('compras/Cabecera.php');
require_once('compras/Caratula.php');
require_once('compras/ResumenPeriodo.php');
require_once('compras/Detalle.php');

class IEC {
   public $EnvioLibro;
    
    function getEnvioLibro() {
        return $this->EnvioLibro;
    }

    function setEnvioLibro($EnvioLibro) {
        $this->EnvioLibro = $EnvioLibro;
    }  

}
