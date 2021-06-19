<?php 
session_start();
if (!isset($_SESSION["contribuyente"]))
{
  header("Location: login.html");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>NetDte | www.netdte.cl</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="stylesheet" href="../public/css/datepicker3.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/favicon.ico">

    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">    
    <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

  </head>
  <body class="hold-transition skin-black-light sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>N</b>Dte</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"> <img src="../public/images/logo.png" width="100" alt=""></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../public/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $_SESSION["razon"]?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../public/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                    <p>
                      www.netdte.cl - Soluciones Informáticas
                      <small>www.netdte.cl</small>
                    </p>
                  </li>
                  
                  <!-- Menu Footer-->
                  <li class="user-footer">                    
                    <div class="pull-right">
                    <a href="../ajax/usuario.php?op=logout" class="btn btn-primary btn-flat">Cerrar Sesión</a>                      
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>

        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">       
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>
            <li>
              <a href="index.php">
                <i class="fa fa-tasks"></i> <span>Inicio</span>
              </a>
            </li>            
            <li class="treeview">
              <a href="estado.php">
                <i class="fa fa-laptop"></i>
                <span>Consulta Estado Dte</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              
            </li>            
           
            <li class="treeview">
              <a href="cesiones.php">
                <i class="fa fa-shopping-cart"></i>
                <span>Cesiones</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              
            </li>                     
           
            <li class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Dte Emitidos</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="documentos.php"><i class="fa fa-circle-o"></i> Consulta Ventas</a></li>                
              </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-bar-chart"></i> <span>Dte Recepcionados</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="acuse.php"><i class="fa fa-circle-o"></i> Consulta Compras</a></li>                
              </ul>
            </li>
            <li>
              <a href="#">
                </i> <span>Estado SII</span>
                <small class="label pull-right bg-green">Online</small>
              </a>
            </li>
            <li>
              <a href="../procesos/ProcEnvio.php">
                <i class="fa fa-info-circle"></i> <span>Enviar Documentos</span>
                <small class="label pull-right bg-yellow">SII</small>
              </a>
            </li>
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Emisión DTE</span><small class="label pull-right bg-green">Online</small>                
              </a>
              <ul class="treeview-menu">
                <li><a href="exportacion.php"><i class="fa fa-circle-o"></i>Factura Exportación</a></li>                
              </ul>  
              <ul class="treeview-menu">
                <li><a href="notasElectronicas.php"><i class="fa fa-circle-o"></i>Notas Elect. Exportación</a></li>                
              </ul>  
              <ul class="treeview-menu">
                <li><a href="cargar_folios.php"><i class="fa fa-circle-o"></i>Carga de folios</a></li>                
              </ul>              
              <ul class="treeview-menu">
                <li><a href="https://www.aduana.cl/compendio-de-normas-anexo-51/aduana/2008-02-18/165942.html" target="_blank"><i class="fa fa-circle-o"></i>Aduana.cl</a></li>                
              </ul>              
            </li>            
                
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
