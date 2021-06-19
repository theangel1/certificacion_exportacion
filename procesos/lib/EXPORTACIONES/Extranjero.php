<?php 

class Extranjero
{
    public $NumId;
    public $Nacionalidad;
    public $IdAdicRecep;
    
    function getNumId() 
    {
        return $this->NumId;
    }

    function getNacionalidad() 
    {
        return $this->Nacionalidad;
    }

    function getIdAdicRecep() 
    {
        return $this->IdAdicRecep;
    }

    function setNumId($NumId) 
    {
        $this->NumId = $NumId;
    }

    function setNacionalidad($Nacionalidad) 
    {
        $this->Nacionalidad = $Nacionalidad;
    }

    function setIdAdicRecep($IdAdicRecep) 
    {
        $this->IdAdicRecep = $IdAdicRecep;
    }

}
