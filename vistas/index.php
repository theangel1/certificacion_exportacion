<?php
require 'header.php';
$apiUrl = 'https://mindicador.cl/api';
//Es necesario tener habilitada la directiva allow_url_fopen para usar file_get_contents
if ( ini_get('allow_url_fopen') ) {
    $json = file_get_contents($apiUrl);
} else {
    //De otra forma utilizamos cURL
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    curl_close($curl);
} 
$dailyIndicators = json_decode($json);
?>
<link rel="stylesheet" href="../public/css/Ionicons/css/ionicons.min.css">
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Ambiente de Certificación </h1>
                          <hr>
                        <div class="box-tools pull-right">
                        </div>

                         <div class="row">
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-aqua">
                                <div class="inner">
                                  <p>El valor actual de la UF es $</p>
                                  <h3><?php echo $dailyIndicators->uf->valor;?></h3>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-social-usd"></i>
                                </div>            
                              </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-green">
                                <div class="inner">
                                <p>El valor actual del Dólar es $</p>  
                                <h3><?php echo $dailyIndicators->dolar->valor; ?></h3>
                                  
                                </div>
                                <div class="icon">
                                  <i class="ion ion-social-usd"></i>
                                </div>            
                              </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                              <!-- small box -->
                              <div class="small-box bg-yellow">
                                <div class="inner">
                                <p>El valor actual de la UTM es $</p>
                                  <h3><?php echo $dailyIndicators->utm->valor; ?></h3>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-social-usd"></i>
                                </div>            
                              </div>
                            </div>     
              </div>
             </div>

            
             <div class="card" style="width: 18rem; margin: auto">
                <img class="card-img-top" src="../public/images/pdf.jpg" alt="Card image cap" width="80">
                <div class="card-body">
                  <h5 class="card-title">Descarga tu PDF</h5>
                   <form action="#" method="POST">
                  <select name="ddlTipoDoc" id="ddlTipoDoc">
                    <option value="">Seleccione un tipo de Documento</option>
                    <option value="110" <?php if($_POST['ddlTipoDoc']=="110") { echo 'selected="selected"'; } ?> >Factura Exportación</option>
                    <option value="111" <?php if($_POST['ddlTipoDoc']=="111") { echo 'selected="selected"'; } ?>>Nota Débito Exportación</option>
                    <option value="112" <?php if($_POST['ddlTipoDoc']=="112") { echo 'selected="selected"'; } ?>>Nota Crédito Exportación</option>
                  </select>
                  <input type="number" name="folio" id="folio" placeholder="Folio" value="<?php echo $_POST['folio'] ?>">      
                  <?php
                  if(isset($_POST["btn-buscar"]))
                  {   

                    $tipo_doc = $_POST['ddlTipoDoc'];
                    $folio = $_POST['folio'];
                    $conn = new mysqli("netdte.cl","netdte_administrador","G(8r3,ru{]bx","netdte_certificacion");
                    $sqlConsulta= "SELECT * from exportacion where folio='".$folio. "' and tipo_documento= '".$tipo_doc."'";

                    $result = mysqli_query($conn, $sqlConsulta);
                    while($row = mysqli_fetch_array($result))
                    {
                        $xml = $row['nombre_xml'];      
                    }                    
                  }

                  ?>

                  <input type="hidden" id="tipoDocComprobar" name="tipoDocComprobar" value='<?php echo  $tipo_doc ?>'>
                  <input type="hidden" id="folioComprobar" name="folioComprobar" value='<?php echo  $folio ?>'>                  
                  <input type="hidden" id="nombreXmlComprobar" name="nombreXmlComprobar" value='<?php echo  $xml ?>'>

                  <input type='submit' class='btn btn-primary' id='btn-buscar' name='btn-buscar' value="Buscar Folio"> 
                  <button type='button' class='btn btn-primary' id='btn-pdf-exp' name='btn-pdf-exp' data-tipo='PROCESADOS' data-folio='<?php echo $folio ?>' data-file='<?php echo $xml ?>'>Ver PDF</button>
                  </form>
                </div>
            </div>
          
            <div class="panel panel-default">
                <h3>Disponemos de una nueva herramienta para verificar el estado de los documentos no publicados</h3>
                <br>
                <h4 class="text-center">Descargar SII_Tools</h4>
                <h1 class="text-center">
                  <a href="../inc/Tools_SisgenChile.rar" download><i class="fa fa-save"></i></a></h1>
                  <div class= "content">
                    <h3>Instrucciones de uso:</h3>
                        <div class="content">   
                            <p>1.- Descomprimir archivo</p>
                            <p>2.- Abrir el archivo "DocumentosNOPublicados.exe.config" con un editor de texto (Se recomienda Notepad++)</p>
                            <p>3.- Ingresar el Rut de su empresa en el campo rut</p>
                            <p>4.- Guardar el archivo</p>
                            <p>5.- Ejecutar el programa "DocumentosNOPublicados.exe"</p>
                            <br>
                            <h4>Atte.</h4>
                            <h4>Departamento de Informática Sisgen Chile</h4>
                        </div>
                  </div>
                  </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                   
                    
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
require 'footer.php';
 ?>
  <script type="text/javascript" src="scripts/documentos.js"></script>
  <script>
   
    $("#btn-pdf-exp").click(function()
    {       
        if($("#nombreXmlComprobar").val()=="")
        {
            alert("Debe buscar el folio");
            return false;
        }

        if($("#tipoDocComprobar").val()!=$("#ddlTipoDoc").val())
        {
            alert("Debe buscar el folio");
            return false;
        }       
        if($("#folioComprobar").val()!=$("#folio").val())
        {
           alert("Debe buscar el folio");
           return false;
        }
    });
    $("#btn-buscar").click(function()
    {
        if($("#ddlTipoDoc").val()=="")
        {
            alert("Seleccione el tipo de documento");
            return false;
        }       
        if($("#folio").val()=="")
        {
           alert("Ingrese el número de folio");
           return false;
        }
        return true;
    });

    $( window ).on( "load", function() 
    {
        if($("#ddlTipoDoc").val()!="" || $("#folio").val()!="")
        {  
          if($("#nombreXmlComprobar").val()=="")
          {
              alert("Folio no encontrado");
              return false;
          } 
        }
    });                                                                                               
   
 </script>