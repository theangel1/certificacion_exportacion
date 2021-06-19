<?php

class DocumentoAec{
    public $Caratula;
    public $Cesiones;
    
    function getCaratula(){
        return $this->Caratula;
    }
    
    function getCesiones(){
        return $this->Cesiones;
    }

    function setCaratula(){
        $this->Caratula=new Caratula();
    }
    
    function setCesiones(){
        $this->Cesiones=new Cesiones();
    }
}