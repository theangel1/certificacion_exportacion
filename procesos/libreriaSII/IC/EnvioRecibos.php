<?php
require_once('Caratula.php');
require_once('DocumentoRecibo.php');

class SetRecibos{
    public $Caratula;
    public $Recibo;
    
    function setCaratula() {
        $this->Caratula = new Caratula();
    }
    
    function setRecibo(){
        $this->Recibo = new DocumentoRecibo();
        
    }
    
    
    
}