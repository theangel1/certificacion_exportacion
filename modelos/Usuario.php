<?php
require '../config/Conexion.php';
require_once '../ws/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

Class Usuario
{
    public function __construct()
	{
            
    }

    function login($username, $password)
    {
        
        $_SESSION['SESSION_ID'] = md5(rand("100000","10000000"));
        
            $claveMd5 = md5("visoft:".$password.":sisgenfe");                
            $conn = dbCertificacion();
            $sql="select sis_contribuyente.sis_contribuyente_id,sis_usuario_id,sis_usuario_nombre,sis_contribuyente_email,sis_contribuyente_giro,sis_contribuyente_fecresol, sis_contribuyente_numresol, facturador_online, "
            ."sis_contribuyente_fantasia,sis_contribuyente_razon,sis_contribuyente_rut,sis_contribuyente_certificado,sis_contribuyente_clave,"
            . "sis_contribuyente_representante,sis_contribuyente_rutrl,sis_contribuyente_direccion, sis_contribuyente.sis_acteco,sis_contribuyente.sis_contribuyente_comuna,sis_contribuyente_ciudad,sis_contribuyente_unidad_sii"
            . " from sis_usuario,sis_contribuyente where sis_usuario_correo='".$username."' and sis_usuario_clave='".$claveMd5."' "
            . "and sis_contribuyente.sis_contribuyente_id=sis_usuario.sis_contribuyente_id";        
            
            
            $query = $conn->query($sql);        
            $usr_d= $query->fetch_assoc();     

            $_SESSION['usuario']= $usr_d["sis_usuario_id"];
            $_SESSION['Nombre'] = $usr_d["sis_usuario_nombre"];
            $_SESSION['contribuyente']= $usr_d["sis_contribuyente_id"];
            $_SESSION['razon'] = $usr_d["sis_contribuyente_razon"];
            $_SESSION['giro'] = $usr_d["sis_contribuyente_giro"];
            $_SESSION['direccion'] = $usr_d["sis_contribuyente_direccion"];
            $_SESSION['comuna'] = $usr_d["sis_contribuyente_comuna"];
            $_SESSION['representante']= $usr_d["sis_contribuyente_representante"];        
            $_SESSION['rut']= $usr_d["sis_contribuyente_rut"];
            $_SESSION['rut_rl']= $usr_d["sis_contribuyente_rutrl"];
            $_SESSION['email']= $usr_d["sis_contribuyente_email"];
            $_SESSION['fantasia']= $usr_d["sis_contribuyente_fantasia"];
            $_SESSION['telefono']= $usr_d["sis_contribuyente_telefono"];
            $_SESSION['usuario_estado']=$usr_d["sis_usuario_estado"];        
            $_SESSION['certificado']=$usr_d["sis_contribuyente_certificado"];
            $_SESSION['clave']=$usr_d["sis_contribuyente_clave"];
            //agrego nuevas variables para factura de exportacion
            $_SESSION['acteco']=$usr_d["sis_acteco"];        
            $_SESSION['ciudad']=$usr_d["sis_contribuyente_ciudad"];
            $_SESSION['unidadsii']=$usr_d["sis_contribuyente_unidad_sii"];
            $_SESSION['fechaResol'] = $usr_d["sis_contribuyente_fecresol"];
            $_SESSION['numResol'] = $usr_d["sis_contribuyente_numresol"];
            $_SESSION['facturador_online'] = $usr_d["facturador_online"];
            $_SESSION['carpeta'] = substr($_SESSION['rut'], 0, strlen($_SESSION['rut'])-2);        
            header("Location: ../vistas/index.php");
    }

    function solicita($rut)
    {     
        $conn = dbCertificacion();        
        $sql="SELECT * FROM sis_contribuyente where sis_contribuyente_rut='$rut'";
        
        $query = $conn->query($sql);
        $cont = $query->fetch_assoc();
        $email = $cont["sis_contribuyente_email"];
        if($cont["sis_contribuyente_email"]!="")
        {
            $rut1=substr($cont["sis_contribuyente_rut"],-6);
            $rut=substr($rut1,0,4); 
            $clave = md5("visoft:".$rut.":sisgenfe");   
              
            $sql3="insert into sis_usuario(sis_contribuyente_id,sis_usuario_nombre,sis_usuario_correo,sis_usuario_clave,sis_usuario_estado,
                facturador_online) values(".$cont["sis_contribuyente_id"].",'Admin ".$cont["sis_contribuyente_razon"]."',"
                . "'".$cont["sis_contribuyente_email"]."','".$clave."',1,0)";
            $conn->query($sql3);
            $this->enviaMail($cont["sis_contribuyente_razon"],$cont["sis_contribuyente_email"],$rut);
           
            return $email;
        }
       
    }

    function validaUser($email, $password)
    {
        $conn = dbCertificacion();
        $claveMd5 = md5("visoft:".$password.":sisgenfe");

        $sql="SELECT sis_usuario_correo,sis_usuario_clave FROM sis_usuario where sis_usuario_correo='".$email."' and sis_usuario_clave='".$claveMd5."'";        
        $query = $conn->query($sql);
        if($conn->affected_rows>0)
        {
            echo '[{"error":"2","msg":"granted"}]';            
        }
        else
        {
            echo '[{"error":"1","msg":"Ooops, el usuario o la contrase&ntilde;a estan incorrectas.<br>Intenta nuevamente<br>'.$query->affected_rows.'"}]'; 
        }
    }

    function enviaMail($razon,$email,$clave)
    {
        $mail = new PHPMailer;  
        $mail->IsSMTP(); 
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Port=587; 
        $mail->SMTPSecure ='tls';
        $mail->SMTPAuth = true;
        $mail->Username = "dte@netdte.cl";
        $mail->Password = "sisgenchile2018";      
        $mail->setFrom('dte@netdte.cl', 'Facturacion Electronica Sisgen Chile');
        
        $body='<p><strong><span style="font-family:arial,helvetica,sans-serif;">Se&ntilde;ores '.$razon.'</span></strong></p>
            <p><span style="font-family:arial,helvetica,sans-serif;">Seg&uacute;n lo solicitado por ustedes enviamos credenciales 
            de acceso para el portal de Facturaci&oacute;n Electr&oacute;nica donde podr&aacute; administrar sus documentos tributarios electr&oacute;nicos.</span></p>
            <p><span style="font-family:arial,helvetica,sans-serif;">Para acceder a este portal usted debe dirigirse con su navegador web a la direccion 
            https://portal.netdte.cl, en esta p&aacute;gina se le solicitar&aacute; la siguiente informaci&oacute;n.</span></p>
            <p><strong><span style="font-family:arial,helvetica,sans-serif;">correo electr&oacute;nico : '.$email.'<br /> 
                contrase&ntilde;a: '.$clave.'</span></strong></p>
            <p><span style="font-family:arial,helvetica,sans-serif;">Una vez ingresada esta informaci&oacute;n debe presionar el bot&oacute;n 
            &quot;Ingresar&quot;.</span></p>
            <p>
            <br />
            <span style="font-family:arial,helvetica,sans-serif;">Atentamente<br />Departamento de Inform&aacute;tica<br />Sisgen Chile Limitada</span></p>
        <p><span style="font-family:arial,helvetica,sans-serif;">NOTA: Este es un correo automatico por favor no responder</span></p>';
        $mail->Subject = "Credenciales Escritorio Sisgen Chile";    
        $mail->AddAddress($email);
        $mail->MsgHTML($body);
        if(!$mail->Send())
        {
            error_log('[{"error":"1","msg":'.$mail->ErrorInfo.'}]',0);
            exit;
        }
    }
   
}