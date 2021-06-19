<?php

class RecepcionDTE {
    public $TipoDTE;
    public $Folio;
    public $FchEmis;
    public $RUTEmisor;
    public $RUTRecep;
    public $MntTotal;
    public $EstadoRecepDTE;
    public $RecepDTEGlosa;
    
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
    
    function setEstadoRecepDTE($EstadoRecepDTE){
        $this->EstadoRecepDTE = $EstadoRecepDTE;
    }
    
    function setRecepDTEGlosa($RecepDTEGlosa){
        $this->RecepDTEGlosa = $RecepDTEGlosa;
    }
    
}

