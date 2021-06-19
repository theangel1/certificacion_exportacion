<?php

 class RangoAnulados{
    public $Incial;
    public $Final;
     
    public function RangoAnulados(){
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