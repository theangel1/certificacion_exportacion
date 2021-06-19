<?php
class TotOtrosImp{
    public $TotValComNeto;
    public $TotValComExe;
    public $TotValComIVA;

    function setTotValComNeto($TotValComNeto){
        $this->TotValComNeto = $TotValComNeto;
    }
    function TotValComExe($TotValComExe){
        $this->TotValComExe = $TotValComExe;
    }
    
    function TotValComIVA($TotValComIVA){
        $this->TotValComIVA = $TotValComIVA;
    }
}