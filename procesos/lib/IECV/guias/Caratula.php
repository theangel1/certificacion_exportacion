<?php
class Caratula{
    public $RutEmisorLibro;
    public $RutEnvia;
    public $PeriodoTributario;
    public $FchResol;
    public $NroResol;
    public $TipoOperacion;
    public $TipoLibro;
    public $TipoEnvio;
    public $NroSegmento;
    public $FolioNotificacion;
    
    
    function getRutEmisorLibro() {
        return $this->Emisor;
    }
    
    function getEnvia(){
        return $this->Envia;
    }
    
    function getPeriodo() {
        return $this->Periodo;
    }
    
    function getFchResol(){
        return $this->FchResol;
    }
    
    function getNroResol(){
        return $this->NroResol;
    }
    
    function getTipoOperacion(){
        return $this->TipoOperacion;
    }
    
    function getTipoLibro(){
        return $this->TipoLibro;
    }
    
    function getTipoEnvio(){
        return $this->TipoEnvio;
    }
    
    function getFolioNotificacion(){
        return $this->FolioNotificacion;
    }
    
    function setRutEmisorLibro($RutEmisorLibro) {
        $this->RutEmisorLibro=$RutEmisorLibro;
    }
    
    function setRutEnvia($RutEnvia){
        $this->RutEnvia = $RutEnvia;
    }
    
    function setPeriodoTributario($PeriodoTributario) {
        $this->PeriodoTributario = $PeriodoTributario;
    }
    
    function setFchResol($FchResol){
        $this->FchResol = $FchResol;
    }
    
    function setNroResol($NroResol){
        $this->NroResol = $NroResol;
    }
    
    function setTipoOperacion($TipoOperacion){
        $this->TipoOperacion = $TipoOperacion;
    }
    
    function setTipoLibro($TipoLibro){
        $this->TipoLibro = $TipoLibro;
    }
    
    function setTipoEnvio($TipoEnvio){
        $this->TipoEnvio = $TipoEnvio;
    }
    
    function setNroSegmento($NroSegmento){
        $this->NroSegmento = $NroSegmento;
    }
    
    function setFolioNotificacion($FolioNotificacion){
        $this->FolioNotificacion = $FolioNotificacion;
    }
}