<?php
class TotalesServicio{
    public $TpoServ;
    public $PeriodoDevengado;
    public $TotDoc;
    public $TotMntExe;
    public $TotMntNeto;
    public $TasaIVA;
    public $TotMntIVA;
    public $TotMntTotal;
    public $TotMntNoFact;
    public $TotMntPeriodo;
    public $TotSaldoAnt;
    public $TotVlrPagar;
    public $TotTicket;
      
    function getTpoServ(){
        return $this->TpoServ;
    }
    
    function getPeriodoDevengado(){
        return $this->PeriodoDevengado;
    }
    
    function getTotDoc(){
        return $this->TotDoc;
    }
    
    function getTotMntExe(){
        return $this->MntExe;
    }
    
    function getTotMntNeto(){
        return $this->MntNeto;
    }
    
    function getTasaIVA(){
        return $this->TasaIVA;
    }
    
    function getTotMntIVA(){
        return $this->MntIVA;
    }
    
    function getTotMntTotal(){
        return $this->MntTotal;
    }
    
    function getTotMntNoFact(){
        return $this->NoFact;
    }
    
    function getTotMntPeriodo(){
        return $this->MntPeriodo;
    }
    
    function getTotSaldoAnt(){
        return $this->SaldoAnt;
    }
    
    function getTotVlrPagar(){
        return $this->VlrPagar;
    }
    
    function getTotTicket(){
        return $this->TotTicket;
    }
    
    function setTpoServ($TpoServ){
        $this->TpoServ=$TpoServ;
    }
    
    function setPeriodoDevengado($PeriodoDevengado){
        $this->PeriodoDevengado=$PeriodoDevengado;
    }
    
    function setTotDoc($TotDoc){
        $this->TotDoc=$TotDoc;
    }
    
    function setTotMntExe($TotMntExe){
        $this->TotMntExe=$TotMntExe;
    }
    
    function setTotMntNeto($TotMntNeto){
        $this->TotMntNeto=$TotMntNeto;
    }
    
    function setTasaIVA($TasaIVA){
        $this->TasaIVA=$TasaIVA;
    }
    
    function setTotMntIVA($TotMntIVA){
        $this->TotMntIVA=$TotMntIVA;
    }
    
    function setTotMntTotal($TotMntTotal){
        $this->TotMntTotal=$TotMntTotal;
    }
    
    function setTotMntNoFact($TotMntNoFact){
        $this->TotMntNoFact=$TotMntNoFact;
    }
    
    function setTotMntPeriodo($TotMntPeriodo){
        $this->TotMntPeriodo=$TotMntPeriodo;
    }
    
    function setTotSaldoAnt($TotSaldoAnt){
        $this->TotSaldoAnt=$TotSaldoAnt;
    }
    
    function setTotVlrPagar($TotVlrPagar){
        $this->TotVlrPagar=$TotVlrPagar;
    }
    
    function setTotTicket($TotTicket){
        $this->TotTicket=$TotTicket;
    }
}