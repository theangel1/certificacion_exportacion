<?php
class ObjectAndXML {
	private $xml;
	public $id;
 
	// Constructor
	public function __construct($idic, $rutEmpresa="") {
		$this->xml = new XmlWriter();
		$this->xml->openMemory();
                touch("/home/sisgenchile/www/sisgenfe/procesos/xml_respuestas/$rutEmpresa/$idic.xml");
                $uri = realpath("/home/sisgenchile/www/sisgenfe/procesos/xml_respuestas/$rutEmpresa/$idic.xml");
                $this->xml->openURI("$uri");
		$this->xml->startDocument('1.0', 'ISO-8859-1');
		$this->xml->setIndent(TRUE);
                $this->xml->setIndentString("\t");
	}
        
        public function setStartElement($startElement = ""){
            $this->xml->startElement($startElement);
            $this->xml->writeAttribute("version", "1.0");
            $this->xml->writeAttribute("xmlns", "http://www.sii.cl/SiiDte");
            $this->xml->writeAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
            if($startElement=="EnvioRecibos"){
                $this->xml->writeAttribute("xsi:schemaLocation","http://www.sii.cl/SiiDte SiiTypes_v10.xsd");
            }else{
                $this->xml->writeAttribute("xsi:schemaLocation","http://www.sii.cl/SiiDte Recibos_v10.xsd");
            }
            
        }
        
        public function setId($id){
            $this->id = $id;
        }
        
        public function getId(){
            return $this->id;
        }
 
	// Method to convert Object into XML string
	public function objToXML($obj) {
		$this->getObject2XML($this->xml, $obj);
 
		$this->xml->endElement();
                $this->xml->endDocument();
		return $this->xml->outputMemory(true);
	}
 
	// Method to convert XML string into Object
	public function xmlToObj($xmlString) {
		return simplexml_load_string($xmlString);
	}
 
	private function getObject2XML(XMLWriter $xml, $data) {
		foreach($data as $key => $value) {
                    
			if(is_object($value)) {
				$xml->startElement($key);
                                if($key == "SetRecibos" or $key == "Resultado"){
                                    $xml->writeAttribute("ID", $this->id);      
                                }
                               if($key == "Caratula" or $key == "Recibo"){
                                    $xml->writeAttribute("version", "1.0");      
                                }
				$this->getObject2XML($xml, $value);
				$xml->endElement();
				continue;
			}
			else if(is_array($value)) {
                             $this->getArray2XML($xml, $key, $value);
                             
			}
 
			if (is_string($value)) {
				$xml->writeElement($key, $value);
			}
		}
	}
 
	private function getArray2XML(XMLWriter $xml, $keyParent, $data) {

		foreach($data as $key => $value) {
			if (is_string($value)) {
                        echo "texto";   
				$xml->writeElement($keyParent, $value);
				continue;
			}
 
			if (is_numeric($key)) {
				$xml->startElement($keyParent);
			}
 
			if(is_object($value)) {
                           
                           if($keyParent=="DocumentoRecibo"){
                               $xml->writeAttribute("ID", "T35");
                           }
				$this->getObject2XML($xml, $value);
			}
			else if(is_array($value)) {
                            
				$this->getArray2XML($xml, $key, $value);
				continue;
			}
 
			if (is_numeric($key)) {
				$xml->endElement();
			}
		}
	}
}

?>