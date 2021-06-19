<?php
class Referencia{
    public $NroLinRef;//Representa el numero de linea del documento referenciado
    public $CodRef; //Representa el codigo de la referencia 1- Anula documento  2- Corrige texto del documento referenciado  3- Corrige montos
    public $RazonRef; //Representa la razon de la referencia
    public $CodVndor;
    public $CodCaja;

    function setNroLinRef($NroLinRef) {
        $this->NroLinRef = $NroLinRef;
    }

    function setCodRef($CodRef) {
        $this->CodRef = $CodRef;
    }

    function setRazonRef($RazonRef) {
        $this->RazonRef = $RazonRef;
    }
    
    function setCodVndor($CodVndor){
        $this->CodVndor = $CodVndor;
    }
    
    function setCodCaja($CodCaja){
        $this->CodCaja = $CodCaja;
    }
    

}