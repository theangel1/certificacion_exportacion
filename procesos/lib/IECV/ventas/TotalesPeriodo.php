<?php
require_once("TotOtrosImp.php");

class TotalesPeriodo{
    public $TpoDoc;
    public $TotDoc;
    public $TotAnulado;
    public $TotOpExe;
    public $TotMntExe;
    public $TotMntNeto;
    public $TotMntIVA;
    public $TotIVAFueraPlazo;
    public $TotIVAPropio;
    public $TotIVATerceros;
    public $TotLey18211;
    public $TotOtrosImp;
    
    public $TotOpIVARetTotal;
    public $TotIVARetTotal;
    public $TotOpIVARetParcial;
    public $TotIVARetParcial;
    public $TotCredEC;
    public $TotDepEnvase;
    public $TotLiquidaciones;
     
    public $TotMntTotal;
    public $TotOpIVANoRetenido;
    public $TotIVANoRetenido;
    public $TotMntNoFact;
    public $TotMntPeriodo;
    public $TotPsjNac;
    public $TotPsjInt;
    
      
    function setTpoDoc($TpoDoc) {
        $this->TpoDoc=$TpoDoc;
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
    
    function setTotMntIVA($TotMntIVA){
        $this->TotMntIVA = $TotMntIVA;
    }
    
    function setTotIVAFueraPlazo($TotIVAFueraPlazo){
        $this->TotIVAFueraPlazo = $TotIVAFueraPlazo;
    }
    
    function setTotIVAPropio($TotIVAPropio){
        $this->TotIVAPropio = $TotIVAPropio;
    }
    
    function setTotIVATerceros($TotIVATerceros){
        $this->TotIVATerceros = $TotIVATerceros;
    }
    
    function setTotLey18211($TotLey18211){
        $this->TotLey18211 = $TotLey18211;
    }
    
    function setTotOtrosImp($TotOtrosImp){
        $this->TotOtrosImp[] = $TotOtrosImp;
    }
    
    function setTotOpIVARetTotal($TotOpIVARetTotal){
        $this->TotOpIVARetTotal = $TotOpIVARetTotal;        
    }
    
    function setTotIVARetTotal($TotIVARetTotal){
        $this->TotIVARetTotal = $TotIVARetTotal;        
    }
    
    function setTotOpIVARetParcial($TotOpIVARetParcial){
        $this->TotOpIVARetParcial = $TotOpIVARetParcial;        
    }
    
    function setTotIVARetParcial($TotIVARetParcial){
        $this->TotIVARetParcial = $TotIVARetParcial;        
    }
    
    function setTotCredEC($TotCredEC){
        $this->TotCredEC = $TotCredEC;        
    }
    
    function setTotDepEnvase($TotDepEnvase){
        $this->TotDepEnvase = $TotDepEnvase;        
    }
    
    function setTotLiquidaciones(){
        $this->TotLiquidaciones[] = new TotLiquidaciones();        
    }
    
    function setTotMntTotal($TotMntTotal){
        $this->TotMntTotal = $TotMntTotal;
    }
    
    function setTotOpIVANoRetenido($TotOpIVANoRetenido){
        $this->TotOpIVANoRetenido = $TotOpIVANoRetenido;
    }
    
    function setTotIVANoRetenido($TotIVANoRetenido){
        $this->TotIVANoRetenido = $TotIVANoRetenido;
    }
    
    function setTotMntNoFact($TotMntNoFact){
        $this->TotMntNoFact = $TotMntNoFact;
    }
    
    function setTotMntPeriodo($TotMntPeriodo){
        $this->TotMntPeriodo = $TotMntPeriodo;
    }
    
    function setTotPsjNac($TotPsjNac){
        $this->TotPsjNac = $TotPsjNac;
    }
    
    function setTotPsjInt($TotPsjInt){
        $this->TotPsjInt = $TotPsjInt;
    }
}