<?php

/** 
 * @property IdDoc $IdDoc 
 * @property Emisor $Emisor 
 * @property Receptor $Receptor
 * @property Transporte $Transporte
 * @property Totales $Totales
 * @property OtraMoneda $OtraMoneda
*/
class Encabezado
{
    public $IdDoc; // Representa la zona de descripcion del DTE actual
    public $Emisor; // Representa la zona del emisor del DTE actual
    public $Receptor; // Representa la zona del Receptor del DTE actual
    public $Transporte; // Representa la zona del transporte
    public $Totales; // Representa la zona totales del DTE actual
    public $OtraMoneda;
    
    //GETTERS
    function getIdDoc() {
        return $this->IdDoc;
    }

    function getEmisor() {
        return $this->Emisor;
    }

    function getReceptor() {
        return $this->Receptor;
    }

    function getTransporte() {
        return $this->Transporte;
    }

    function getTotales() {
        return $this->Totales;
    }
    function getOtraMoneda()
    {
        return $this->OtraMoneda;
    }

    //SETTERS
    function setIdDoc() {
        $this->IdDoc = new IdDoc();
    }
    
    function setEmisor() {
        $this->Emisor = new Emisor();
    }

    function setReceptor() {
        $this->Receptor = new Receptor();
    }

    function setTransporte($trans) {
        $this->Transporte = $trans;
    }

    function setTotales($totals) {
        $this->Totales = $totals;
    }

    function setOtraMoneda($OtraMoneda)
    {
        $this->OtraMoneda = $OtraMoneda;
    }    
}