<?php
require_once('RecepcionDTE.php');

class RecepcionEnvio{
    public $NmbEnvio;
    public $FchRecep;
    public $CodEnvio;
    public $EnvioDTEID;
    public $Digest;
    public $RutEmisor;
    public $RutReceptor;
    public $EstadoRecepEnv;
    public $RecepEnvGlosa;
    public $NroDTE;
    public $RecepcionDTE;
    
    function setNmbEnvio($NmbEnvio){
        $this->NmbEnvio = $NmbEnvio;
    }
    
    function setFchRecep($FchRecep){
        $this->FchRecep = $FchRecep;
    }
    
    function setCodEnvio($CodEnvio){
        $this->CodEnvio = $CodEnvio;
    }
    
    function setEnvioDTEID($EnvioDTEID){
        $this->EnvioDTEID = $EnvioDTEID;
    }
    
    function setDigest($Digest){
        $this->Digest = $Digest;
    }
    
    function setRutEmisor($RutEmisor){
        $this->RutEmisor = $RutEmisor;
    }
    
    function setRutReceptor($RutReceptor){
        $this->RutReceptor = $RutReceptor;
    }
    
    function setEstadoRecepEnv($EstadoRecepEnv){
        $this->EstadoRecepEnv = $EstadoRecepEnv;
    }
    
    function setRecepEnvGlosa($RecepEnvGlosa){
        $this->RecepEnvGlosa = $RecepEnvGlosa;
    }
    
    function setNroDTE($NroDTE){
        $this->NroDTE = $NroDTE;
    }
    
    function setRecepcionDTE($RecepcionDTE){
        $this->RecepcionDTE [] = $RecepcionDTE;
    }
    
}

