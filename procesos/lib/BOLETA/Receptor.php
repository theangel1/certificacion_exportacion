<?php
class Receptor{
    public $RUTRecep;
    public $CdgIntRecep;
    public $RznSocRecep;
    public $Contacto;
    public $DirRecep;
    public $CmnaRecep;
    public $CiudadRecep;
    public $DirPostal;
    public $CmnaPostal;
    public $CiudadPostal;
    
    function getRUTRecep() {
        return $this->RUTRecep;
    }
    
    function getRznSocRecep(){
        return $this->RznSocRecep;
    }
    
    function setRUTRecep($RUTRecep) {
        $this->RUTRecep = $RUTRecep;
    }

    function setCdgIntRecep($CdgIntRecep) {
        $this->CdgIntRecep = $CdgIntRecep;
    }

    function setRznSocRecep($RznSocRecep) {
        $this->RznSocRecep = $RznSocRecep;
    }

    function setContacto($Contacto) {
        $this->Contacto = $Contacto;
    }


    function setDirRecep($DirRecep) {
        $this->DirRecep = $DirRecep;
    }

    function setCmnaRecep($CmnaRecep) {
        $this->CmnaRecep = $CmnaRecep;
    }

    function setCiudadRecep($CiudadRecep) {
        $this->CiudadRecep = $CiudadRecep;
    }

    function setDirPostal($DirPostal) {
        $this->DirPostal = $DirPostal;
    }

    function setCmnaPostal($CmnaPostal) {
        $this->CmnaPostal = $CmnaPostal;
    }

    function setCiudadPostal($CiudadPostal) {
        $this->CiudadPostal = $CiudadPostal;
    }

}