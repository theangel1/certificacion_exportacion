<?php
require_once('TotTraslado.php');


class ResumenPeriodo {
    public $TotFolAnulado;
    public $TotGuiaAnulada;
    public $TotGuiaVenta;
    public $TotMntGuiaVta;
    public $TotMntModificado;
    
    function setTotFolAnulado($TotFolAnulado){
        $this->TotFolAnulado=$TotFolAnulado;
    }
    
    function setTotGuiaAnulada($TotGuiaAnulada){
        $this->TotGuiaAnulada=$TotGuiaAnulada;
    }
    
    function setTotGuiaVenta($TotGuiaVenta){
        $this->TotGuiaVenta=$TotGuiaVenta;
    }
    
    function setTotMntGuiaVta($TotMntGuiaVta){
        $this->TotMntGuiaVta = $TotMntGuiaVta;
    }
    
    function setTotMntModificado($TotMntModificado){
        $this->TotMntModificado = $TotMntModificado;
    }
    
    function setTotTraslado($TotTraslado){
        $this->TotTraslado[] = $TotTraslado;
    }
}
