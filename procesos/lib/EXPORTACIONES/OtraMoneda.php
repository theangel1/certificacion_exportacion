<?php 
class OtraMoneda
{
    public $TpoMoneda;
    public $TpoCambio;
    public $MntExeOtrMnda;
    public $MntTotOtrMnda;
    
    //GETTERS
    function getTpoMoneda() {
        return $this->TpoMoneda;
    }

    function getTpoCambio() {
        return $this->TpoCambio;
    }

    function getMntExeOtrMnda() {
        return $this->MntExeOtrMnda;
    }

    function getMntTotOtrMnda() {
        return $this->MntTotOtrMnda;
    }

    //SETTERS
    function setTpoMoneda($TpoMoneda) {
        $this->TpoMoneda = $TpoMoneda;
    }    

    function setTpoCambio($TpoCambio) {
        $this->TpoCambio = $TpoCambio;
    }

    function setMntExeOtrMnda($MntExeOtrMnda) {
        $this->MntExeOtrMnda = $MntExeOtrMnda;
    }

    function setMntTotOtrMnda($MntTotOtrMnda) {
        $this->MntTotOtrMnda = $MntTotOtrMnda;
    }   
}
