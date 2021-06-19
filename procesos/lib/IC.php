<?php
require_once('IC/Resultado.php');
require_once('IC/EnvioRecibos.php');

class IC {
    public $Resultado;
    public $Recibos;
    
    function setResultado($Resultado) {
        $this->Resultado = $Resultado;
    }
    
    function SetRecibos($Recibos) {
        $this->SetRecibos = $Recibos;
    }

}