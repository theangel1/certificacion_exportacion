<?php
require_once('ventas/EnvioLibro.php');
require_once('ventas/Cabecera.php');
require_once('ventas/Caratula.php');
require_once('ventas/ResumenPeriodo.php');
require_once('ventas/Detalle.php');

class IEV {
   public $EnvioLibro;
    
    function getEnvioLibro() {
        return $this->EnvioLibro;
    }

    function setEnvioLibro($EnvioLibro) {
        $this->EnvioLibro = $EnvioLibro;
    }  

}
