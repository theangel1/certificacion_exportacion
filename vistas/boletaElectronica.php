<?php
require 'header.php';
?>
<link rel="stylesheet" href="../public/css/exportacion.css">
<!--Contenido-->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h1 class="box-title texto-petroleo upper">Boleta Electrónica</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive">
                        <!--FORM-->
                        <form id="formBoleta" name="formBoleta" action="#" method="post" onsubmit="return validarDatos()">
                          <div class="panel panel-default">
                            <table class="table mitad">
                              <div class="panel-heading">
                                <h4 class="texto-dark"><strong>Datos Documento</strong></h4>
                              </div>
                              <tr>
                                <td>Fecha de Emisión (*)<input type="date" name="FchEmis" id="FchEmis" class="form-control"></td>  
                                <input type="hidden" name="PeriodoDesde" id="PeriodoDesde">
                                <input type="hidden" name="PeriodoHasta" id="PeriodoHasta">
                                <input type="hidden" name="FchVenc" id="FchVenc">
                                <td>Tipo de Documento (*)
                                    <select name="TipoDTE" id="TipoDTE" class="form-control">
                                      <option value="" selected>Seleccione una opci&oacute;n</option>
                                    </select>
                                  </td>
                              </tr>
                              <tr>
                                <td>Indicador de Servicio
                                    <select name="IndServicio" id="IndServicio" class="form-control">
                                      <option value="" selected>Seleccione una opci&oacute;n</option>
                                    </select>
                                  </td>
                              </tr>
                          </table>
                        </div>
                             <div class="panel panel-default">
                              <table  class="table mitad">
                                <div class="panel-heading">
                                  <h4 class="texto-dark"><strong>Datos Emisor</strong></h4>
                                </div>
                                 
                                      <tr>
                                        <td>RUT
                                          <input type="text" name="RutEmi" id="RutEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['rut']; ?>">
                                         <input type="hidden" name="IdContribuyente" id="IdContribuyente" 
                                         value="<?php echo $_SESSION['contribuyente']; ?>">                            
                                        </td>
                                         <td>Razón Social
                                          <input type="text" name="RznSocEmi" id="RznSocEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['razon']; ?>"></td>
                                      </tr>
                                       <tr>
                                        <td>Giro
                                          <input type="text" name="GiroEmi" id="GiroEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['giro']; ?>"></td>
                                        <td>Act. Económica
                                          <input type="text" name="Acteco" id="Acteco" class="form-control" readonly="yes" value="<?php echo $_SESSION['acteco']; ?>">
                                        </td>
                                      </tr>          
                                      <tr>
                                        <td>Dirección
                                          <input type="text" name="DirEmi" id="DirEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['direccion']; ?>"></td>
                                         <td>Comuna
                                          <input type="text" name="ComunaEmi" id="ComunaEmi" class="form-control" value="<?php echo $_SESSION['comuna']; ?>"></td>
                                      </tr>                        
                                       <tr>
                                        <td>Email
                                          <input type="text" name="EmailEmi" id="EmailEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['email']; ?>"></td>
                                         <td>Ciudad
                                          <input type="text" name="CiudadEmi" id="CiudadEmi" class="form-control" value="<?php echo $_SESSION['ciudad']; ?>"></td>
                                      </tr>
                                      <tr>                            
                                        <td>Teléfono
                                          <input type="text" name="TelefonoEmi" id="TelefonoEmi" class="form-control" value="<?php echo $_SESSION['telefono']; ?>"></td>
                                      </tr>
                                    </table>        
                              </div> 
                             <div class="panel panel-default">
                              <table class="table mitad">
                                    <div class="panel-heading">
                                      <h4 class="texto-dark"><strong>Datos Receptor</strong></h4>
                                    </div>                       
                                      <tr>
                                          <td>RUT Receptor
                                            <input type="text" name="RUTRecep" id="RUTRecep" class="form-control" value="9999999-9"></td>
                                          <td>Razón Social 
                                            <input type="text" name="RznSocRecep" id="RznSocRecep" class="form-control" ></td>
                                      </tr>
                                      <tr>
                                          <td>Giro 
                                            <input type="text" name="GiroRecep" id="GiroRecep" class="form-control" ></td>
                                          <td>Dirección 
                                            <input type="text" name="DirRecep" id="DirRecep" class="form-control" ></td>
                                      </tr>   
                                      <tr>
                                          <td>Comuna
                                          <input type="text" name="CmnaRecep" id="CmnaRecep" class="form-control"></td>
                                           <td>Ciudad
                                          <input type="text" name="CiudadRecep" id="CiudadRecep" class="form-control"></td>
                                      </tr>                            
                                </table>                        
                              </div>    
                             
                             <div class="panel panel-default">
                                  <div class="panel-heading">
                                      <h4 class="texto-dark"><strong>Detalles</strong></h4>
                                  </div>
                                     <table id="tablaDetalle" class="table">
                                              <tr>
                                                <td>Producto (*)</td>
                                                <td>Cantidad (*)</td>
                                                <td>Unidad</td>
                                                <td>Precio (*)</td>
                                                <td>Descuento / Recargo %</td>
                                                <td>Tipo</td>
                                                <td>SubTotal</td>
                                                <td>Exento</td>
                                                <td style="background:none;"></td>
                                              </tr>
                                              <tr>
                                                <td><input type="text" name="boxNmbItem[]" id="boxNmbItem[]" class="form-control"></td>
                                                <td><input type="number" name="boxQtyItem[]" id="boxQtyItem[]" class="form-control" step="0.01"></td>
                                                <td><input type="text" name="boxUnmdItem[]" id="boxUnmdItem[]" class="form-control" value=""></td>
                                                <td><input type="number" name="boxPrcItem[]" id="boxPrcItem[]" class="form-control" step="0.01"></td>
                                                <td>
                                                  <input type="number" name="boxDescuentoPct[]" id="boxDescuentoPct[]" class="form-control" value="0" step="0.01">
                                                  <input type="hidden" name="boxDescuentoMonto[]" id="boxDescuentoMonto[]">
                                                  <input type="number" name="boxRecargoPct[]" id="boxRecargoPct[]" class="form-control" value="0" step="0.01">
                                                  <input type="hidden" name="boxRecargoMonto[]" id="boxRecargoMonto[]" step="0.01">
                                                </td>
                                                <td>
                                                  <select name="ddlTipoRecDesc" id="ddlTipoRecDesc" class="form-control" style="width: 56px">
                                                    <option value="D">D</option>
                                                    <option value="R">R</option>
                                                  </select>
                                                </td>  
                                                <td><input type="number" name="boxMontoItem[]" id="boxMontoItem[]" class="form-control" readonly="yes" step="0.01"></td>    
                                                <td>
                                                  <select id="boxIndExe[]" name="boxIndExe[]" class="form-control" 
                                                  style="width: 66px">                                                   
                                                    <option value="0" selected="selected">NO</option>
                                                    <option value="1">SÍ</option>
                                                  </select>
                                                </td>     
                                                <td><button type="button" id="btnEliminaLineaDet" class="btn btn-primary" onclick="eliminarLineaDet(this);">-</button></td>                                                      
                                              </tr>
                                            </table>   
                                          <br>
                                          <div class="text-center">
                                            <button type="button" id="btnAgregaLineaDet" class="btn btn-primary" onclick="agregarLineaDet()">Agregar línea Detalle</button>
                                          </div>   
                                        <br> 
                                    </div>  
                              <div class="panel panel-default">
                                  <div class="panel-heading">
                                      <h4 class="texto-dark"><strong>Recargos y Descuentos Globales</strong></h4>
                                  </div>
                                     <table id="tablaRecDesc" class="table">
                                          <tr>
                                            <td>Movimiento (*)</td>
                                            <td>Glosa</td>
                                            <td>Valor (*)</td>
                                            <td>Tipo (*)</td>
                                            <td>Exento</td>
                                            <td style="background:none;"></td>
                                          </tr>
                                          <tr>
                                            <td>
                                              <select name="DDLSelectTpoMov[]" id="DDLSelectTpoMov[]" class="form-control">
                                                <option value="" selected>Seleccione una opci&oacute;n</option>
                                                <option value="D">DESCUENTO</option>
                                                <option value="R">RECARGO</option>
                                              </select>
                                            </td>
                                            <td><input type="text" name="boxGlosaDR[]" id="boxGlosaDR[]" class="form-control"></td>
                                            <td><input type="number" step="0.01" name="boxValorDR[]" id="boxValorDR[]" class="form-control"></td>
                                            <td style="width: 59px;">
                                              <select id="DDLSelectTpoValor[]" name="DDLSelectTpoValor[]" class="form-control"  style="width: 59px;">
                                                <option value="%" selected="selected">%</option>
                                                <option value="$">$</option>                                    
                                              </select></td>
                                            <td>
                                              <select id="DDLSelectIndExeDR[]" name="DDLSelectIndExeDR[]" class="form-control">                                                
                                                <option value="1" selected="selected">SÍ</option>
                                                <option value="0">NO</option>
                                              </select>
                                            </td>     
                                             <td><button type="button" id="btnEliminaLineaRecDesc" class="btn btn-primary" onclick="eliminarLineaRecDesc(this);">-</button></td>                       
                                          </tr>
                                        </table>    
                                      <br>
                                      <div class="text-center">
                                        <button type="button" id="btnAgregaLineaRecDesc" class="btn btn-primary" onclick="agregarLineaRecDesc()">Agregar línea Recargo/Descuento</button>
                                      </div>                          
                                      <br>   
                                </div>   
                               <div class="panel panel-default">
                                 <table class="table mitad">
                                   <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Totales</strong></h4>                        
                                    </div>                  
                                          <tr>
                                            <td>Monto Exento</td>
                                            <td><input type="text" name="MntExe" id="MntExe" class="form-control" readonly="yes"></td>
                                          </tr>  
                                          <tr>
                                            <td>SubTotal</td>
                                            <td><input type="text" name="txtSubTotal" id="txtSubTotal" class="form-control" readonly="yes"></td>
                                          </tr>                          
                                          <tr>
                                            <td>Total</td>
                                            <td><input type="text" name="MntTotal" id="MntTotal" class="form-control" readonly="yes"></td>
                                          </tr>
                                    </table>   
                                  </div>
                                </div>  
                            <div style="text-align: center;">
                                <input type="submit" id="btnValidar" class="btn btn-primary" value="Validar y visualizar" onclick="return validarDatos()"/>
                                <!--<button type="button" id="btnLimpiar" onclick='limpiar()' class="btn btn-primary">Limpiar</button>
                                <button type="button" id="btnVolver" class="btn btn-primary">Volver</button>-->
                                <button type="button" id="btnGuardar" class="btn btn-primary">Guardar Borrador</button>
                            </div> 
                            <br>
                          </form>  <!--FIN FORM-->                      
                    </div> <!-- .content -->    
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/funcionesBoletaElectronica.js"></script>