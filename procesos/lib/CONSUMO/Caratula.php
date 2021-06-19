<?php
class Caratula{
    public $RutEmisor;
    public $RutEnvia;
    public $FchResol;
    public $NroResol;
    public $FchInicio;
    public $FchFinal;
    public $Correlativo;
    public $SecEnvio;
    public $TmstFirmaEnv;
    
    function getRutEmisor(){
        return $this->RutEmisor;
    }
    
    function getRutEnvia(){
        return $this->RutEnvia;
    }
    
    function getFchResol(){
        return $this->FchResol;
    }
    
    function getNroResol(){
        return $this->NroResol;
    }
    
    function getFchInicio(){
        return $this->FchInicio;
    }
    
    function getFchFinal(){
        return $this->FchFinal;
    }
    
    function getCorrelativo(){
        return $this->Correlativo;
    }
    
    function getSecEnvio(){
        return $this->SecEnvio;
    }
    
    function getTmstFirmaEnv(){
        return $this->FirmaEnv;
    }
        
    function setRutEmisor($RutEmisor){
        $this->RutEmisor=$RutEmisor;
    }
    
    function setRutEnvia($RutEnvia){
        $this->RutEnvia=$RutEnvia;
    }
    
    function setFchResol($FchResol){
        $this->FchResol=$FchResol;
    }
    
    function setNroResol($NroResol){
        $this->NroResol=$NroResol;
    }
    
    function setFchInicio($FchInicio){
        $this->FchInicio=$FchInicio;
    }
    
    function setFchFinal($FchFinal){
        $this->FchFinal=$FchFinal;
    }
    
    function setCorrelativo($Correlativo){
        $this->Correlativo=$Correlativo;
    }
    
    function setSecEnvio($SecEnvio){
        $this->SecEnvio=$SecEnvio;
    }
    
    function setTmstFirmaEnv($TmstFirmaEnv){
        $this->TmstFirmaEnv=$TmstFirmaEnv;
    }
    
}