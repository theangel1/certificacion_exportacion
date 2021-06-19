<?php
require '../config/Conexion.php';

Class Exportacion
{
    public function __construct()
	{
            
    }

    public function mostrar($tipDoc, $folio, $idContribuyente)
	{
        //FALTA WHERE ID CONTRIBUYENTE
        $conn = dbCertificacion();
        $sql="SELECT exportacion.*,receptor.*,aduana.* FROM ((exportacion 
        INNER JOIN receptor ON exportacion.idreceptor = receptor.idreceptor)
        INNER JOIN aduana ON exportacion.idaduana = aduana.idaduana)
        WHERE tipo_documento='$tipDoc' and folio='$folio' and sis_contribuyente_id='$idContribuyente'";             
        $query = $conn->query($sql);
        $row = $query->fetch_assoc();
		return $row;
    }
    
    public function loadDetalle($idExportacion)
    {
        $conn = dbCertificacion(); 
        $output = array();       
        $sql = "SELECT * FROM detalle_exportacion where idexportacion='$idExportacion'";
        $record = mysqli_query($conn,$sql);
        while($result = mysqli_fetch_array($record))
        {            
            $output[] = $result;
        }               
        
		return $output;
    }

    public function loadDscRcg($idExportacion)
    {
        $conn = dbCertificacion(); 
        $output = array();       
        $sql = "SELECT * FROM desc_recarg_exportacion where idexportacion='$idExportacion'";
        $record = mysqli_query($conn,$sql);
        while($result = mysqli_fetch_array($record))
        {            
            $output[] = $result;
        }               
        
		return $output;
    }

    public function loadReferencia($idExportacion)
    {
        $conn = dbCertificacion(); 
        $output = array();       
        $sql = "SELECT * FROM referencia_exportacion where idexportacion='$idExportacion'";
        $record = mysqli_query($conn,$sql);
        while($result = mysqli_fetch_array($record))
        {            
            $output[] = $result;
        }              
        
		return $output;
    }
    
}
