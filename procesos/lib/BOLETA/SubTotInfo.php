<?php
/** 
 * @property CdgItem $CdgItem Esta propiedad referencia a la clase Documento
*/
class SubTotInfo{
    public $NroSTI;
    public $GlosaSTI;
    public $OrdenSTI;
    public $SubTotNetoSTI;
    public $SubTotIVASTI;
    public $SubTotAdicSTI;
    public $SubTotExeSTI;
    public $ValSubtotSTI;
    public $LineasDeta;
    
    function setNroSTI($NroSTI){
        $this->NroSTI = $NroSTI;
    }
    
    function setGlosaSTI($GlosaSTI){
        $this->GlosaSTI = $GlosaSTI;
    }
    
    function setOrdenSTI($OrdenSTI){
        $this->OrdenSTI = $OrdenSTI;
    }
    
    function setSubTotNetoSTI($SubTotNetoSTI){
        $this->SubTotNetoSTI = $SubTotNetoSTI;
    }
    
    function setSubTotIVASTI($SubTotIVASTI){
        $this->SubTotIVASTI = $SubTotIVASTI;
    }
    
    function setSubTotAdicSTI($SubTotAdicSTI){
        $this->SubTotAdicSTI = $SubTotAdicSTI;
    }
    
    function setSubTotExeSTI($SubTotExeSTI){
        $this->SubTotExeSTI = $SubTotExeSTI;
    }
    
    function setValSubtotSTI($ValSubtotSTI){
        $this->ValSubtotSTI = $ValSubtotSTI;
    }
    
    function setLineasDeta($LineasDeta){
        $this->LineasDeta = $LineasDeta;
    }
    
}