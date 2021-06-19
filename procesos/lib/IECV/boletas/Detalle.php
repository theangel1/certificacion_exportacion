<?php
/** 
 * @property CdgItem $CdgItem Esta propiedad referencia a la clase Documento
*/
class Detalle{
    public $TpoDoc;
    public $FolioDoc;
    public $Anulado;
    public $TpoServ;
    public $FchEmiDoc;
    public $FchVencDoc;
    public $PeriodoDesde;
    public $PeriodoHasta;
    public $CdgSIISucur;
    public $RUTCliente;
    public $CodIntCli;
    public $MntExe;
    public $MntTotal;
    public $MntNoFact;
    public $MntPeriodo;
    public $SaldoAnt;
    public $VlrPagar;
    public $TotTicketBoleta;
    
    function getTpoDoc(){
        return $this->TpoDoc;
    }
    
    function getNroDoc(){
        return $this->NroDoc;
    }
    
    function getAnulado(){
        return $this->getAnulado;
    }
    
    function getTpoServ(){
        return $this->TpoServ;
    }
    
    function getFchEmiDoc(){
        return $this->EmiDoc;
    }
    
    function getFchVencDoc(){
        return $this->VencDoc;
    }
    
    function getPeriodoDesde(){
        return $this->PeriodoDesde;
    }
    
    function getPeriodoHasta(){
        return $this->PeriodoHasta;
    }
    
    function getCdgSIISucur(){
        return $this->CdgSIISucur;
    }
    
    function getRUTCliente(){
        return $this->getRUTCliente;
    }
    
    function getCodIntCli(){
        return $this->IntCli;
    }
    
    function getMntExe(){
        return $this->MntExe;
    }
    
    function getMntTotal(){
        return $this->MntTotal;
    }
    
    function getMntNoFact(){
        return $this->NoFact;
    }
    
    function getMntPeriodo(){
        return $this->MntPeriodo;
    }
    
    function getSaldoAnt(){
        return $this->SaldoAnt;
    }
    
    function getVlrPagar(){
        return $this->VlrPagar;
    }
    
    function getTotTicketBoleta(){
        return $this->TicketBoleta;
    }
    
    
    function setTpoDoc($TpoDoc){
        $this->TpoDoc=$TpoDoc;
    }
    
    function setFolioDoc($FolioDoc){
        $this->FolioDoc=$FolioDoc;
    }
    
    function setAnulado($Anulado){
        $this->Anulado=$Anulado;
    }
    
    function setTpoServ($TpoServ){
        $this->TpoServ=$TpoServ;
    }
    
    function setFchEmiDoc($FchEmiDoc){
        $this->FchEmiDoc=$FchEmiDoc;
    }
    
    function setFchVencDoc($FchVencDoc){
        $this->FchVencDoc=$FchVencDoc;
    }
    
    function setPeriodoDesde($PeriodoDesde){
        $this->PeriodoDesde=$PeriodoDesde;
    }
    
    function setPeriodoHasta($PeriodoHasta){
        $this->PeriodoHasta=$PeriodoHasta;
    }
    
    function setCdgSIISucur($CdgSIISucur){
        $this->CdgSIISucur=$CdgSIISucur;
    }
    
    function setRUTCliente($RUTCliente){
        $this->RUTCliente=$RUTCliente;
    }
    
    function setCodIntCli($CodIntCli){
        $this->CodIntCli=$CodIntCli;
    }
    
    function setMntExe($MntExe){
        $this->MntExe=$MntExe;
    }
    
    function setMntTotal($MntTotal){
        $this->MntTotal=$MntTotal;
    }
    
    function setMntNoFact($MntNoFact){
        $this->MntNoFact=$MntNoFact;
    }
    
    function setMntPeriodo($MntPeriodo){
        $this->MntPeriodo=$MntPeriodo;
    }
    
    function setSaldoAnt($SaldoAnt){
        $this->SaldoAnt=$SaldoAnt;
    }
    
    function setVlrPagar($VlrPagar){
        $this->VlrPagar=$VlrPagar;
    }
    
    function setTotTicketBoleta($TotTicketBoleta){
        $this->TotTicketBoleta=$TotTicketBoleta;
    }
}