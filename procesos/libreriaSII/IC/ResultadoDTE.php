<?php

class ResultadoDTE {
    public $TipoDTE;
    public $Folio;
    public $FchEmis;
    public $RUTEmisor;
    public $RUTRecep;
    public $MntTotal;
    public $CodEnvio;
    public $EstadoDTE;
    public $EstadoDTEGlosa;
    public $CodRchDsc;
    
    function setTipoDTE($TipoDTE){
        $this->TipoDTE = $TipoDTE;
    }
    
    function setFolio($Folio){
        $this->Folio = $Folio;
    }
    
    function setFchEmis($FchEmis){
        $this->FchEmis = $FchEmis;
    }
    
    function setRUTEmisor($RUTEmisor){
        $this->RUTEmisor = $RUTEmisor;
    }
    
    function setRUTRecep($RUTRecep){
        $this->RUTRecep = $RUTRecep;
    }
    
    function setMntTotal($MntTotal){
        $this->MntTotal = $MntTotal;
    }
    
    function setCodEnvio($CodEnvio){
        $this->CodEnvio = $CodEnvio;
    }
    
    function setEstadoDTE($EstadoDTE){
        $this->EstadoDTE = $EstadoDTE;
    }
    
    function setEstadoDTEGlosa($EstadoDTEGlosa){
        $this->EstadoDTEGlosa = $EstadoDTEGlosa;
    }
    
    function setCodRchDsc($CodRchDsc){
        $this->CodRchDsc = $CodRchDsc;
    }
    
}
