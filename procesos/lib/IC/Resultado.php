<?php
require_once('Caratula.php');
require_once('Recepcion.php');
require_once('ResultadoDTE.php');


class Resultado{
    public $Caratula;
    public $RecepcionEnvio;
    public $ResultadoDTE;
    
    function setCaratula() {
        $this->Caratula = new Caratula();
    }
    
    function setRecepcionEnvio(){
        $this->RecepcionEnvio = new RecepcionEnvio();
    }
    
    function setResultadoDTE($ResultadoDTE){
        $this->ResultadoDTE [] = $ResultadoDTE;
    }
    
}