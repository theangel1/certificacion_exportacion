<?php

class EnvioLibro{
    public $Caratula;
    public $ResumenPeriodo;
    public $Detalle;
    public $TmstFirma;
    
    function getCaratula() {
        return $this->Caratula;
    }
    
    function getResumenPeriodo(){
        return $this->ResumenPeriodo;
    }
    
    
    function setCaratula() {
        $this->Caratula = new Caratula();
    }
    
    function setResumenPeriodo(){
        $this->ResumenPeriodo = new ResumenPeriodo();
    }
    
    function setDetalle($Detalle){
        $this->Detalle[] = $Detalle;
    }
    
    function setTmstFirma($TmstFirma){
        $this->TmstFirma = $TmstFirma;
    }
}