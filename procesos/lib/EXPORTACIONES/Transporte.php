<?php
class Transporte{
    public $Patente;
    public $RUTTrans;
    public $RUTChofer;
    public $NombreChofer;
    public $DirDest;
    public $CmnaDest;
    public $CiudadDest;
    public $Aduana;
    
    function getPatente() {
        return $this->Patente;
    }

    function getRUTTrans() {
        return $this->RUTTrans;
    }

    function getRUTChofer() {
        return $this->RUTChofer;
    }

    function getNombreChofer() {
        return $this->NombreChofer;
    }

    function getDirDest() {
        return $this->DirDest;
    }

    function getCmnaDest() {
        return $this->CmnaDest;
    }

    function getCiudadDest() {
        return $this->CiudadDest;
    }

    function setPatente($Patente) {
        $this->Patente = $Patente;
    }

    function setRUTTrans($RUTTrans) {
        $this->RUTTrans = $RUTTrans;
    }

    function setRUTChofer($RUTChofer) {
        $this->RUTChofer = $RUTChofer;
    }

    function setNombreChofer($NombreChofer) {
        $this->NombreChofer = $NombreChofer;
    }

    function setDirDest($DirDest) {
        $this->DirDest = $DirDest;
    }

    function setCmnaDest($CmnaDest) {
        $this->CmnaDest = $CmnaDest;
    }

    function setCiudadDest($CiudadDest) {
        $this->CiudadDest = $CiudadDest;
    }
    
    function setAduana($Aduana){
        $this->Aduana = $Aduana;
    }
}
