<?php
require_once("../modelos/Usuario.php");
$usuario = new Usuario();

$rut = isset($_POST['rut'])? trim($_POST['rut']):"";
$email = isset($_POST['email'])? trim($_POST['email']):"";
$password = isset($_POST['password'])? trim($_POST['password']):"";
$username = isset($_POST['login-username'])? trim($_POST['login-username']):"";
$passLogin = isset($_POST['login-password'])? trim($_POST['login-password']):"";

switch($_GET["op"])
{
    case 'solicita':
        $rspta = $usuario->solicita($rut);        
        echo json_encode($rspta);
    break;
    
    case 'validaUser':
    $rspta = $usuario->validaUser($email, $password);         
    echo json_decode($rspta);
    break;

    case 'logout':
        session_start();
        session_destroy();
        header("Location: ../vistas/login.html");
    break;
   
    case 'login':    
        session_start();        
        $post_data = "secret=6Ldw-jgUAAAAANfIQTK0HE-5dU964Z26_VPHw1AF&response=".
        $_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR'];

        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, 
        array('Content-Type: application/x-www-form-urlencoded; charset=utf-8', 
        'Content-Length: ' . strlen($post_data)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
        $googresp = curl_exec($ch);       
        $decgoogresp = json_decode($googresp);
        curl_close($ch);

        if ($decgoogresp->success == true)        	
            $usuario->login($username, $passLogin); 
        else        
            header("Location: ../vistas/login.html");       

    break;
}