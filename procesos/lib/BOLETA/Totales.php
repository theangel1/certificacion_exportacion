<?php
/** 
 * @property ImptoReten[] $ImptoReten
*/
class Totales{
    public $MntNeto;
    public $MntExe;
    public $IVA;
    public $MontoNF;
    public $TotalPeriodo;
    public $SaldoAnterior;
    public $VlrPagar;

    function getMntTotal(){
        return $this->MntTotal;
    }
    
    function setMntNeto($MntNeto) {
        $this->MntNeto = $MntNeto;
    }

    function setMntExe($MntExe) {
        $this->MntExe = $MntExe;
    }

    function setIVA($IVA) {
        $this->IVA = $IVA;
    }

    function setMontoNF($MontoNF) {
        $this->MontoNF = $MontoNF;
    }

    function setTotalPeriodo($TotalPeriodo){
        $this->TotalPeriodo = $TotalPeriodo;
    }
    
    function setSaldoAnterior($SaldoAnterior) {
        $this->SaldoAnterior = $SaldoAnterior;
    }

    function setVlrPagar($VlrPagar) {
        $this->VlrPagar = $VlrPagar;
    }
 
}