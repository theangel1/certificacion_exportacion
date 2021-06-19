<?php
class IVANoRec{
    public $CodIVANoRec;
    public $MntIVANoRec;
    
    function getMntIVANoRec(){
        return $this->MntIVANoRec;
    }
    
    function setCodIVANoRec($CodIVANoRec){
        $this->CodIVANoRec = $CodIVANoRec;
    }
    
    function setMntIVANoRec($MntIVANoRec){
        $this->MntIVANoRec = $MntIVANoRec;
    }
    
}