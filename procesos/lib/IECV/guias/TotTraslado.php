<?php
class TotTraslado{
    public $TpoTraslado;
    public $CantGuia;
    public $MntGuia;
      
    function setTpoTraslado($TpoTraslado) {
        $this->TpoTraslado=$TpoTraslado;
    } 
    
    function setCantGuia($CantGuia){
        $this->CantGuia=$CantGuia;
    }
    
    function setMntGuia($MntGuia){
        $this->MntGuia=$MntGuia;
    }
}