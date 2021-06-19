<?php
class Caratula{
    public $RutResponde;
    public $RutRecibe;
    public $IdRespuesta;
    public $NroDetalles;
    public $NmbContacto;
    public $FonoContacto;
    public $MailContacto;
    public $TmstFirmaResp;
    public $TmstFirmaEnv;
    
    function setRutResponde($RutResponde) {
        $this->RutResponde=$RutResponde;
    }
    
    function setRutRecibe($RutRecibe){
        $this->RutRecibe = $RutRecibe;
    }
    
    function setIdRespuesta($IdRespuesta) {
        $this->IdRespuesta= $IdRespuesta;
    }
    
    function setNroDetalles($NroDetalles){
        $this->NroDetalles = $NroDetalles;
    }
    
    function setNmbContacto($NmbContacto){
        $this->NmbContacto = $NmbContacto;
    }
    function setFonoContacto($FonoContacto){
        $this->FonoContacto = $FonoContacto;
    }
    
    function setMailContacto($MailContacto){
        $this->MailContacto = $MailContacto;
    }
    
    function setTmstFirmaResp($TmstFirmaResp){
        $this->TmstFirmaResp = $TmstFirmaResp;
    }   
    
    function setTmstFirmaEnv($TmstFirmaEnv){
        $this->TmstFirmaEnv = $TmstFirmaEnv;
    }
    
}