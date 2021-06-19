<?php
require_once("TotOtrosImp.php");
require_once("TotIVANoRec.php");

class TotalesPeriodo{
    public $TpoDoc;
    public $TpoImp;
    public $TotDoc;
    public $TotAnulado;
    public $TotOpExe;
    public $TotMntExe;
    public $TotMntNeto;
    public $TotOpIVARec;
    public $TotMntIVA;
    public $TotOpActivoFijo;
    public $TotMntActivoFijo;
    public $TotMntIVAActivoFijo;
    public $TotIVANoRec;
    public $TotOpIVAUsoComun;
    public $TotIVAUsoComun;
    public $FctProp;
    public $TotCredIVAUsoComun;
    public $TotOtrosImp;
    public $TotImpSinCredito;
    public $TotMntTotal;
    public $TotIVANoRetenido;
    public $TotTabPuros;
    public $TotTabCigarrillos;
    public $TotTabElaborado;
    public $TotImpVehiculo;
    
    
    
    function getTotMntNeto(){
        return $this->TotMntNeto;
    }
    
    function getTotMntExe() {
        return $this->TotMntExe;
    }
    
    function getTotMntIVA(){
        return $this->TotMntIVA;
    }
    
    function getTotOpIVAUsoComun(){
        return $this->TotOpIVAUsoComun;
    }
    
    function getTotIVAUsoComun(){
        return $this->TotIVAUsoComun;
    }
    
    function getTotIVANoRec(){
        return $this->TotIVANoRec;        
    }
    
    function getTotOtrosImp(){
        return $this->TotOtrosImp;
    }
    
    function setTpoDoc($TpoDoc) {
        $this->TpoDoc=$TpoDoc;
    }
    
    function setTpoImp($TpoImp){
        $this->TpoImp = $TpoImp;
    }
    
    
    function setTotDoc($TotDoc){
        $this->TotDoc = $TotDoc;
    }
    
    function setTotAnulado($TotAnulado){
        $this->TotAnulado = $TotAnulado;
    }
    
    function setTotOpExe($TotOpExe){
        $this->TotOpExe = $TotOpExe;
    }
    
    function setTotMntExe($TotMntExe) {
        $this->TotMntExe = $TotMntExe;
    }
    
    function setTotMntNeto($TotMntNeto){
        $this->TotMntNeto = $TotMntNeto;
    }
    
    function setTotOpIVARec($TotOpIVARec){
        $this->TotOpIVARec = $TotOpIVARec;
    }
    
    function setTotMntIVA($TotMntIVA){
        $this->TotMntIVA = $TotMntIVA;
    }
    
    function setTotOpActivoFijo($TotOpActivoFijo){
        $this->TotOpActivoFijo = $TotOpActivoFijo;
    }
    
    function setTotMntActivoFijo($TotMntActivoFijo){
        $this->TotMntActivoFijo = $TotMntActivoFijo;
    }
    
    function setTotMntIVAActivoFijo($TotMntIVAActivoFijo){
        $this->TotMntIVAActivoFijo = $TotMntIVAActivoFijo;
    }
    
    function setTotIVANoRec($TotIVANoRec){
        $this->TotIVANoRec = $TotIVANoRec;        
    }
    
    function setTotOpIVAUsoComun($TotOpIVAUsoComun){
        $this->TotOpIVAUsoComun = $TotOpIVAUsoComun;
    }
    
    function setTotIVAUsoComun($TotIVAUsoComun){
        $this->TotIVAUsoComun = $TotIVAUsoComun;
    }
    
    function setFctProp($FctProp){
        $this->FctProp = $FctProp;
    }
    
    function setTotCredIVAUsoComun($TotCredIVAUsoComun){
        $this->TotCredIVAUsoComun = $TotCredIVAUsoComun;
    }
    
    function setTotOtrosImp($TotOtrosImp){
        $this->TotOtrosImp[] = $TotOtrosImp;
    }
    
    function setTotImpSinCredito($TotImpSinCredito){
        $this->TotImpSinCredito = $TotImpSinCredito;
    }
    
    function setTotMntTotal($TotMntTotal){
        $this->TotMntTotal = $TotMntTotal;
    }
    
    function setTotIVANoRetenido($TotIVANoRetenido){
        $this->TotIVANoRetenido = $TotIVANoRetenido;
    }
    
    function setTotTabPuros($TotTabPuros){
        $this->TotTabPuros = $TotTabPuros;
    }
    
    function setTotTabCigarrillos($TotTabCigarrillos){
        $this->TotTabCigarrillos = $TotTabCigarrillos;
    }
    
    function setTotTabElaborado($TotTabElaborado){
        $this->TotTabElaborado = $TotTabElaborado;
    }
    
    function setTotImpVehiculo($TotImpVehiculo){
        $this->TotImpVehiculo = $TotImpVehiculo;
    }
    
    
}