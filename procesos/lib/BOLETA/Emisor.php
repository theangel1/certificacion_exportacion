<?php
class Emisor{
    public $RUTEmisor;
    public $RznSoc;
    public $GiroEmis;
    public $CdgSIISucur;
    public $DirOrigen;
    public $CmnaOrigen;
    public $CiudadOrigen;
    
    function getRUTEmisor() {
        return $this->RUTEmisor;
    }

    function getRznSoc() {
        return $this->RznSoc;
    }

    function getGiroEmis() {
        return $this->GiroEmis;
    }

    function getTelefono() {
        return $this->Telefono;
    }

    function getCorreoEmisor() {
        return $this->CorreoEmisor;
    }

    function getActeco() {
        return $this->Acteco;
    }

    function getCdgTraslado() {
        return $this->CdgTraslado;
    }

    function getFolioAut() {
        return $this->FolioAut;
    }

    function getFchAut() {
        return $this->FchAut;
    }

    function getSucursal() {
        return $this->Sucursal;
    }

    function getCdgSIISucur() {
        return $this->CdgSIISucur;
    }

    function getCodAdicSucur() {
        return $this->CodAdicSucur;
    }

    function getDirOrigen() {
        return $this->DirOrigen;
    }

    function getCmnaOrigen() {
        return $this->CmnaOrigen;
    }

    function getCiudadOrigen() {
        return $this->CiudadOrigen;
    }

    function getCdgVendedor() {
        return $this->CdgVendedor;
    }

    function getIdAdicEmisor() {
        return $this->IdAdicEmisor;
    }

    function getRUTMandante() {
        return $this->RUTMandante;
    }
    
    function setRUTEmisor($RUTEmisor) {
        $this->RUTEmisor = $RUTEmisor;
    }

    function setRznSoc($RznSoc) {
        $this->RznSoc = $RznSoc;
    }

    function setGiroEmis($GiroEmis) {
        $this->GiroEmis = $GiroEmis;
    }

    function setCdgSIISucur($CdgSIISucur) {
        $this->CdgSIISucur = $CdgSIISucur;
    }

    function setDirOrigen($DirOrigen) {
        $this->DirOrigen = $DirOrigen;
    }

    function setCmnaOrigen($CmnaOrigen) {
        $this->CmnaOrigen = $CmnaOrigen;
    }

    function setCiudadOrigen($CiudadOrigen) {
        $this->CiudadOrigen = $CiudadOrigen;
    }
}