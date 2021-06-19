<?php
require_once('OtrosImp.php');
require_once('IVANoRec.php');
class Detalle {
    public $TpoDoc;
    public $Emisor;
    public $NroDoc;
    public $Anulado;
    public $Operacion;
    public $TpoImp;
    public $TasaImp;
    public $NumInt;
    public $FchDoc;
    public $CdgSIISucur;
    public $RUTDoc;
    public $RznSoc;
    public $MntExe;
    public $MntNeto;
    public $MntIVA;
    public $MntActivoFijo;
    public $MntIVAActivoFijo;
    public $IVANoRec;
    public $TotOpIVAUsoComun;
    public $IVAUsoComun;
    public $FctProp;
    public $TotCredIVAUsoComun;
    public $OtrosImp;
    public $MntSinCred;
    public $MntTotal;
    public $IVANoRetenido;
    public $TabPuros;
    public $TabCigarrillos;
    public $TabElaborado;
    public $ImpVehiculo;

    function getTpoDoc(){
        return $this->TpoDoc;
    }
	
	function getNroDoc(){
        return $this->NroDoc;
    }
	
	function getMntNeto(){
        return $this->MntNeto;
    }
    
    function getMntExe(){
        return $this->MntExe;
    }
    
    function getMntIVA(){
        return $this->MntIVA;
    }
    
    function getIVAUsoComun(){
        return $this->IVAUsoComun;
    }
    
    
    function setTpoDoc($TpoDoc){
        $this->TpoDoc = $TpoDoc;
    }
    
    function setEmisor($Emisor){
        $this->Emisor = $Emisor;
    }
    
    function setNroDoc($NroDoc){
        $this->NroDoc = $NroDoc;
    }
    
    function setAnulado($Anulado){
        $this->Anulado = $Anulado;
    }
    
    function setOperacion($Operacion){
        $this->Operacion = $Operacion;
    }
    
    function setTpoImp($TpoImp){
        $this->TpoImp = $TpoImp;
    }
    
    
    function setTasaImp($TasaImp){
        $this->TasaImp = $TasaImp;
    }
    
    function setNumInt($NumInt){
        $this->NumInt = $NumInt;
    }
    
    function setFchDoc($FchDoc){
        $this->FchDoc = $FchDoc;
    }
    
    function setCdgSIISucur($CdgSIISucur){
        $this->CdgSIISucur = $CdgSIISucur;
    }
    
    function setRUTDoc($RUTDoc){
        $this->RUTDoc = $RUTDoc;
    }
    
    function setRznSoc($RznSoc){
        $this->RznSoc = $RznSoc;
    }
    
    function setMntExe($MntExe){
        $this->MntExe = $MntExe;
    }
    
    function setMntNeto($MntNeto){
        $this->MntNeto = $MntNeto;
    }
    
    function setMntIVA($MntIVA){
        $this->MntIVA = $MntIVA;
    }
    
    function setMntActivoFijo($MntActivoFijo){
        $this->MntActivoFijo = $MntActivoFijo;
    }
    
    function setMntIVAActivoFijo($MntIVAActivoFijo){
        $this->MntIVAActivoFijo = $MntIVAActivoFijo;
    }
    
    function setIVANoRec($IVANoRec){
        $this->IVANoRec = $IVANoRec;
    }
    
    function setTotOpIVAUsoComun($TotOpIVAUsoComun){
        $this->TotOpIVAUsoComun = $TotOpIVAUsoComun;
    }
    
    function setIVAUsoComun($IVAUsoComun){
        $this->IVAUsoComun = $IVAUsoComun;
    }
    
    function setFctProp($FctProp){
        $this->FctProp = $FctProp;
    }
    
    function setTotCredIVAUsoComun($TotCredIVAUsoComun){
        $this->TotCredIVAUsoComun = $TotCredIVAUsoComun;
    }
    
    function setOtrosImp($OtrosImp){
        $this->OtrosImp[] = $OtrosImp;
    }
    
    function setMntSinCred($MntSinCred){
        $this->MntSinCred = $MntSinCred;
    }
    
    function setMntTotal($MntTotal){
        $this->MntTotal = $MntTotal;
    }
    
    function setIVANoRetenido($IVANoRetenido){
        $this->IVANoRetenido = $IVANoRetenido;
    }
    
    function setTabPuros($TabPuros){
        $this->TabPuros = $TabPuros;
    }
    
    function setTabCigarrillos($TabCigarrillos){
        $this->TabCigarrillos = $TabCigarrillos;
    }
    
    function setTabElaborado($TabElaborado){
        $this->TabElaborado = $TabElaborado;
    }
    
    function setImpVehiculo($ImpVehiculo){
        $this->ImpVehiculo = $ImpVehiculo;
    }
    
}
