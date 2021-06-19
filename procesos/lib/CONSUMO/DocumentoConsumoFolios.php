<?php

class DocumentoConsumoFolios{
    public $Caratula;
    public $Resumen;
    
    function getCaratula(){
        return $this->Caratula;
    }
    
    function getResumen(){
        return $this->Resumen;
    }
    
    function setCaratula(){
        $this->Caratula=new Caratula();
    }
    
    function setResumen(){
        $this->Resumen=new Resumen();
    }
}