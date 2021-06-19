<?php
require_once("TotalesServicio.php");
//require_once("TotOtrosImp.php");

class TotalesPeriodo{
    public $TpoDoc;
    public $TotAnulado;
    public $TotDoc;
    public $TotalesServicio;
    
    function getTpoDoc(){
        return $this->TpoDoc;
    }
    
    function getTotAnulado(){
        return $this->TotAnulado;
    }
    
    function getTotDoc(){
        return $this->TotDoc;
    }
    
    function getTotalesServicio(){
        return $this->TotalesServicio;
    } 
    
    
    function setTpoDoc($TpoDoc) {
        $this->TpoDoc=$TpoDoc;
    }
    
    function setTotAnulado($TotAnulado){
        $this->TotAnulado = $TotAnulado;
    }
    
    function setTotDoc($TotDoc){
        $this->TotDoc=$TotDoc;
    }
    
    function setTotalesServicio($Totales){
        $this->TotalesServicio[] = $Totales;
    }
    
}