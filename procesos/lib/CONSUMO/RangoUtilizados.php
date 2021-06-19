<?php

 class RangoUtilizados{
    public $Incial;
    public $Final;
     
    public function RangoUtilizados(){
        $this->setInicial($Incial);
        $this->setFinal($Final);
    }
        
    function setInicial($Incial) {
        $this->Incial = $Incial;
    }
    
    function setFinal($Final) {
        $this->Final= $Final;
    }
 }