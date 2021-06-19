<?php
class Caratula{
    public $RutCedente;
    public $RutCesionario;
    public $PeriodoTributario;
    public $NmbContacto;
    public $FonoContacto;
    public $MailContacto;
    public $TmstFirmaEnvio;
    
    function getRutCedente(){
        return $this->RutCedente;
    }
    
    function getRutCesionario(){
        return $this->RutCesionario;
    }
    
    function getPeriodoTributario(){
        return $this->PeriodoTributario;
    }
    
    function getNmbContacto(){
        return $this->NmbContacto;
    }
    
    function getFonoContacto(){
        return $this->FonoContacto;
    }
    
    function getMailContacto(){
        return $this->MailContacto;
    }
    
    function getTmstFirmaEnvio(){
        return $this->TmstFirmaEnvio;
    }

    
    function setRutCedente($RutCedente){
        $this->RutCedente=$RutCedente;
    }
    
    function setRutCesionario($RutCesionario){
        $this->RutCesionario=$RutCesionario;
    }
    
    function setPeriodoTributario($PeriodoTributario){
        $this->PeriodoTributario=$PeriodoTributario;
    }
    
    function setNmbContacto($NmbContacto){
        $this->NmbContacto=$NmbContacto;
    }
    
    function setFonoContacto($FonoContacto){
        $this->FonoContacto=$FonoContacto;
    }
    
    function setMailContacto($MailContacto){
        $this->MailContacto=$MailContacto;
    }
    
    function setTmstFirmaEnvio($TmstFirmaEnvio){
        $this->TmstFirmaEnvio=$TmstFirmaEnvio;
    }
    
    
}