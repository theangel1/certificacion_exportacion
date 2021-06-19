<?php
/** 
 * @property CdgItem $CdgItem Esta propiedad referencia a la clase Documento
*/
class Detalle{
    public $NroLinDet;
    public $CdgItem;
    public $TpoDocLiq;
    public $IndExe;
    public $ItemEspectaculo;
    public $RUTMandante;
    public $NmbItem;
    public $InfoTicket;
    public $DscItem;
    public $QtyItem;
    public $UnmdItem;
    public $PrcItem;
    public $DescuentoPct;
    public $DescuentoMonto;
    public $RecargoPct;
    public $RecargoMonto;
    public $MontoItem;
    
    function getNmbItem() {
        return $this->NmbItem;
    }
    
    function setNroLinDet($NroLinDet) {
        $this->NroLinDet = $NroLinDet;
    }

    function setCdgItem() {
        $this->CdgItem = new CdgItem();
    }

    function setTpoDocLiq($TpoDocLiq) {
        $this->TpoDocLiq = $TpoDocLiq;
    }

    function setIndExe($IndExe) {
        $this->IndExe = $IndExe;
    }

    function setItemEspectaculo($ItemEspectaculo){
        $this->ItemEspectaculo = $ItemEspectaculo;
    }
    
    function setRUTMandante($RUTMandante){
        $this->RUTMandante = $RUTMandante;
    }
    
    function setNmbItem($NmbItem) {
        $this->NmbItem = $NmbItem;
    }

    function setInfoTicket($InfoTicket){
        $this->InfoTicket = $InfoTicket;
    }
            
    function setDscItem($DscItem) {
        $this->DscItem = $DscItem;
    }

    function setQtyItem($QtyItem) {
        $this->QtyItem = $QtyItem;
    }

    function setUnmdItem($UnmdItem) {
        $this->UnmdItem = $UnmdItem;
    }

    function setPrcItem($PrcItem) {
        $this->PrcItem = $PrcItem;
    }

    function setDescuentoPct($DescuentoPct) {
        $this->DescuentoPct = $DescuentoPct;
    }

    function setDescuentoMonto($DescuentoMonto) {
        $this->DescuentoMonto = $DescuentoMonto;
    }

    function setRecargoPct($RecargoPct) {
        $this->RecargoPct = $RecargoPct;
    }

    function setRecargoMonto($RecargoMonto) {
        $this->RecargoMonto = $RecargoMonto;
    }

    function setMontoItem($MontoItem) {
        $this->MontoItem = $MontoItem;
    }

    
}