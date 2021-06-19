<?php
require_once('TotalesPeriodo.php');


class ResumenPeriodo {
    public $TotalesPeriodo; //ARReglo
    
    function setTotalesPeriodos($Totales){
        $this->TotalesPeriodo[] = $Totales;
    }
}
