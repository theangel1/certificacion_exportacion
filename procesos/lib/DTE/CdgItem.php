<?php
class CdgItem{
    public $TpoCodigo;
    public $VlrCodigo;
    
    public function CdgItem($TpoCodigo = "INTERNA", $VlrCodigo = ""){
      //  $this->setTpoCodigo($TpoCodigo);
        //$this->setVlrCodigo($VlrCodigo);
		self::__construct($TpoCodigo, $VlrCodigo);
    }
	public function __construct($TpoCodigo = "INTERNA", $VlrCodigo = "")
	{		
		$this->setTpoCodigo($TpoCodigo);
        $this->setVlrCodigo($VlrCodigo);
	}
    
    function getTpoCodigo() {
        return $this->TpoCodigo;
    }

    function getVlrCodigo() {
        return $this->VlrCodigo;
    }

    function setTpoCodigo($TpoCodigo) {
        $this->TpoCodigo = $TpoCodigo;
    }

    function setVlrCodigo($VlrCodigo) {
        $this->VlrCodigo = $VlrCodigo;
    }

}