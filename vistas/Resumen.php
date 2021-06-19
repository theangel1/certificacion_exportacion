<?php
require 'header.php';
require '../config/Conexion.php';

$conn =dbCertificacion();
$sqlConsulta= "SELECT * from exportacion where idexportacion='".$_SESSION['IDexp'] . "'";

$result = mysqli_query($conn, $sqlConsulta);
while($row = mysqli_fetch_array($result))
{
    $aux = $row['nombre_xml'];			
}

$name_tip_doc = '';
switch($_SESSION['Documento'])
{
    case '110': $name_tip_doc = 'Factura Exportación Electrónica';
    break;
    case '111':$name_tip_doc = 'Nota Débito Exportación Electrónica';
    break;
    case '112':$name_tip_doc = 'Nota Crédito Exportación Electrónica';
    break;
}


?>
 <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Resumen de su Documento </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>  
                                <th>Documento</th>  
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Receptor</th>
                                <th>Total</th>                                
                            </thead>
                            <tr>
                                <td>
                                <label><?php echo $name_tip_doc ?></label>
                                </td>
                                <td>
                                <label><?php echo $_SESSION['Folio'] ?></label>
                                </td>
                                <td>
                                <label><?php echo $_SESSION['Fecha'] ?></label>
                                </td>
                                <td>
                                <label><?php echo $_SESSION['Receptor'] ?></label>
                                </td>
                                <td>
                                <label>$<?php echo $_SESSION['Total'] ?></label>
                                </td>                            
                            </tr>                            
                        </table>
                        <table class="table table-striped table-bordered table-hover">
                            <tr>                        
                                <td>
                                    <button type='button' class='btn btn-default' id='btn-down-xml-emitido' data-carpeta='<?php echo $_SESSION['carpeta'] ?>' data-folio='<?php echo $aux ?>'>Descargar XML</button>
                                </td>
                                <td>
                                 <button type='button' class='btn btn-primary' id='btn-pdf-exp' data-tipo='VENTA' data-folio='<?php echo $_SESSION['Folio'] ?>' data-file='<?php echo $aux ?>' >Ver PDF</button>
                                </td>                            
                            </tr>
                        </table>
                    </div>
                     
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
<?php
require 'footer.php';
?>
<script type="text/javascript" src="scripts/documentos.js"></script>