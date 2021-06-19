<?php
class Cesiones{
    public $DTECedido;
    public $Cesion;

    function getDTECedido(){
        return $this->DTECedido;
    }

    function getCesion(){
        return $this->Cesion;
    }


    function setDTECedido($DTECedido){
        $this->DTECedido=$DTECedido;
    }

    function setCesion($Cesion){
        $this->Cesion=$Cesion;
    }
}
