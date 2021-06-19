<?php
class DocumentoRecibo{
    public $TipoDoc;
    public $Folio;
    public $FchEmis;
    public $RUTEmisor;
    public $RUTRecep;
    public $MntTotal;
    public $Recinto;
    public $RutFirma;
    public $Declaracion;
    public $TmstFirmaRecibo;
    public $DocumentoRecibo;
    
    function setTipoDoc($TipoDoc){
        $this->TipoDoc = $TipoDoc;
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
    
    function setRecinto($Recinto){
        $this->Recinto = $Recinto;
    }
    
    function setRutFirma($RutFirma){
        $this->RutFirma = $RutFirma;
    }
    
    function setDeclaracion($Declaracion){
        $this->Declaracion = $Declaracion;
    }
    
    function setTmstFirmaRecibo($TmstFirmaRecibo){
        $this->TmstFirmaRecibo = $TmstFirmaRecibo;
    }
    
    function setDocumentoRecibo($DocumentoRecibo){
        $this->DocumentoRecibo [] = $DocumentoRecibo;
    }
}