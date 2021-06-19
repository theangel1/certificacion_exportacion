<?php
class Comisiones{
    public $NroLinCom;
    public $TipoMovim;
    public $Glosa;
	public $tasaComision;
    public $ValComNeto;
    public $ValComExe;
    public $ValComIVA;
    
    function setNroLinCom($NroLinCom){
        $this->NroLinCom = $NroLinCom;
    }
	  function setTasaComision($tasaComision){
        $this->tasaComision = $tasaComision;
    }
    
    function setTipoMovim($TipoMovim){
        $this->TipoMovim = $TipoMovim;
    }
    
    function setGlosa($Glosa){
        $this->Glosa = $Glosa;
    }
    
    function setValComNeto($ValComNeto) {
        $this->ValComNeto = $ValComNeto;
    }

    function setValComExe($ValComExe) {
        $this->ValComExe = $ValComExe;
    }
    
    function setValComIVA($ValComIVA) {
        $this->ValComIVA = $ValComIVA;
    }
}