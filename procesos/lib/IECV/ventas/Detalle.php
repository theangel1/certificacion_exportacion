<?php
require_once('OtrosImp.php');
//Class Detalle del libro de 
//VENTAS
//
class Detalle {
    public $TpoDoc;
    public $Emisor;
    public $NroDoc;
    public $Anulado;
    public $Operacion;
    public $TasaImp;
    public $NumInt;
    public $IndServicio;
    public $IndSinCosto;
    public $FchDoc;
    public $CdgSIISucur;
    public $RUTDoc;
    public $RznSoc;
    public $NumId;
    public $Nacionalidad;
    public $TpoDocRef;
    public $FolioDocRef;
    public $MntExe;
    public $MntNeto;
    public $MntIVA;
    public $IVAFueraPlazo;
    public $IVAPropio;
    public $IVATerceros;
    public $Ley18211;
    public $OtrosImp;
    public $IVARetTotal;
    public $IVARetParcial;
    public $CredEC;
    public $DepEnvase;
    public $Liquidaciones;
    public $MntTotal;
    public $IVANoRetenido;
    public $MntNoFact;
    public $MntPeriodo;
    public $PsjNac;
    public $PsjInt;
        
    function setTpoDoc($TpoDoc){
        $this->TpoDoc = $TpoDoc;
    }
    
    function setEmisor($Emisor){
        $this->Emisor = $Emisor;
    }
    
    function setNroDoc($NroDoc){
        $this->NroDoc = $NroDoc;
    }
    
    function setAnulado($Anulado){
        $this->Anulado = $Anulado;
    }
    
    function setOperacion($Operacion){
        $this->Operacion = $Operacion;
    }
    
    function setTasaImp($TasaImp){
        $this->TasaImp = $TasaImp;
    }
    
    function setNumInt($NumInt){
        $this->NumInt = $NumInt;
    }
    
    function setIndServicio($IndServicio){
        $this->IndServicio = $IndServicio;
    }
    
    function setIndSinCosto($IndSinCosto){
        $this->IndSinCosto = $IndSinCosto;
    }
    
    function setFchDoc($FchDoc){
        $this->FchDoc = $FchDoc;
    }
    
    function setCdgSIISucur($CdgSIISucur){
        $this->CdgSIISucur = $CdgSIISucur;
    }
    
    function setRUTDoc($RUTDoc){
        $this->RUTDoc = $RUTDoc;
    }
    
    function setRznSoc($RznSoc){
        $this->RznSoc = $RznSoc;
    }
    
    function setNumId($NumId){
        $this->NumId = $NumId;
    }
    
    function setNacionalidad($Nacionalidad){
        $this->Nacionalidad = $Nacionalidad;
    }
    
    function setTpoDocRef($TpoDocRef){
        $this->TpoDocRef = $TpoDocRef;
    }
    
    function setFolioDocRef($FolioDocRef){
        $this->FolioDocRef = $FolioDocRef;
    }
    
    function setMntExe($MntExe){
        $this->MntExe = $MntExe;
    }
    
    function setMntNeto($MntNeto){
        $this->MntNeto = $MntNeto;
    }
    
    function setMntIVA($MntIVA){
        $this->MntIVA = $MntIVA;
    }
    
    function setIVAFueraPlazo($IVAFueraPlazo){
        $this->IVAFueraPlazo = $IVAFueraPlazo;
    }
    
    function setIVAPropio($IVAPropio){
        $this->IVAPropio = $IVAPropio;
    }
    
    function setIVATerceros($IVATerceros){
        $this->IVATerceros = $IVATerceros;
    }
    
    function setLey18211($Ley18211){
        $this->Ley18211 = $Ley18211;
    }
    
    function setOtrosImp($OtrosImp){
        $this->OtrosImp[] = $OtrosImp;
    }
	
    function setIVARetTotal($IVARetTotal){
        $this->IVARetTotal = $IVARetTotal;
    }
    
    function setIVARetParcial($IVARetParcial){
        $this->IVARetParcial = $IVARetParcial;
    }
    
    function setCredEC($CredEC){
        $this->CredEC = $CredEC;
    }
    
    function setDepEnvase($DepEnvase){
        $this->DepEnvase = $DepEnvase;
    }
    
    function setLiquidaciones($Liquidaciones){
        $this->Liquidaciones = $Liquidaciones;
    }
    
    function setMntTotal($MntTotal){
        $this->MntTotal = $MntTotal;
    }
    
    function setIVANoRetenido($IVANoRetenido){
        $this->IVANoRetenido = $IVANoRetenido;
    }
    
    function setMntNoFact($MntNoFact){
        $this->MntNoFact = $MntNoFact;
    }
    
    function setMntPeriodo($MntPeriodo){
        $this->MntPeriodo = $MntPeriodo;
    }
    
    function setPsjNac($PsjNac){
        $this->PsjNac = $PsjNac;
    }
    
    function setPsjInt($PsjInt){
        $this->PsjInt = $PsjInt;
    }
    
}
