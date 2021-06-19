<?php
require_once('OtrosImp.php');
//Class Detalle del libro de 
//VENTAS
//
class Detalle {
    public $Folio;
    public $Anulado;
    public $Operacion;
    public $TpoOper;
    public $FchDoc;
    public $RUTDoc;
    public $RznSoc;
    public $MntNeto;
    public $TasaImp;
    public $IVA;
    public $MntTotal;
    public $MntModificado;
    public $TpoDocRef;
    public $FolioDocRef;
    public $FchDocRef;
        
    function setFolio($Folio){
        $this->Folio = $Folio;
    }
    
    function setAnulado($Anulado){
        $this->Anulado = $Anulado;
    }
    
    function setOperacion($Operacion){
        $this->Operacion = $Operacion;
    }
  
    function setTpoOper($TpoOper){
        $this->TpoOper = $TpoOper;
    }
    
    function setFchDoc($FchDoc){
        $this->FchDoc = $FchDoc;
    }
    
        
    function setRUTDoc($RUTDoc){
        $this->RUTDoc = $RUTDoc;
    }
    
    function setRznSoc($RznSoc){
        $this->RznSoc = $RznSoc;
    }
    
    function setMntNeto($MntNeto){
        $this->MntNeto = $MntNeto;
    }
    
    function setTasaImp($TasaImp){
        $this->TasaImp = $TasaImp;
    }
    
    function setIVA($IVA){
        $this->IVA = $IVA;
    }
    
    function setMntTotal($MntTotal){
        $this->MntTotal = $MntTotal;
    }
    
    function setMntModificado($MntModificado){
        $this->MntModificado = $MntModificado;
    }
    
    function setTpoDocRef($TpoDocRef){
        $this->TpoDocRef = $TpoDocRef;
    }
    
    function setFolioDocRef($FolioDocRef){
        $this->FolioDocRef = $FolioDocRef;
    }
    
    function setFchDocRef($FchDocRef){
        $this->FchDocRef = $FchDocRef;
    }
        
}
