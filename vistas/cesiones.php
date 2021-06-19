<?php
ob_start();
require 'header.php';
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Cesiones </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive">
                        <table  id="tablaDocumento" class="table table-striped table-bordered table-condensed table-hover">
                                <thead>                                
                                    <th>Documento</th>
                                    <th>Rut</th>
                                    <th>Direccion</th>
                                    <th>Correo</th>
                                    <th>Monto</th>
                                    <th>Documento</th>
                                    <th>Folio</th>
                                    <th>Fecha Emisi&oacute;n</th>
                                    <th>Fecha de Cesión</th>
                                    <th>Cesionario</th>
                                    <th>Envio</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </thead>
                                <tbody></tbody>
                                <tfoot>                                
                                    <th>Documento</th>
                                    <th>Rut</th>
                                    <th>Direccion</th>
                                    <th>Correo</th>
                                    <th>Monto</th>
                                    <th>Documento</th>
                                    <th>Folio</th>
                                    <th>Fecha Emisi&oacute;n</th>
                                    <th>Fecha de Cesión</th>
                                    <th>Cesionario</th>
                                    <th>Envio</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tfoot>
                        </table>
                    </div>
                    
                    <!-- /.modal cesion -->    
                    <div id="cesionModal" class="modal modal-styled fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title">Ceder Documentos</h3>
                                </div> <!-- /.modal-header -->
                                <div class="modal-body">
                                    <form>
                                        <input type="hidden" id="numDte">
                                        <input type="hidden" id="tipoDte">
                                        <input type="hidden" id="fecDte">
                                        <input type="hidden" id="RutRecep">
                                        <input type="hidden" id="Receptor">
                                        <fieldset>
                                        <fieldset>
                                            <legend>Datos del Cesionario (Nuevo Dueño del documento)</legend>
                                            <div class="form-group col-lg-6">
                                                <label class="control-label" for="txtRutCesionario">R.U.T</label>
                                                <input type="text" class="form-control" id="txtRutCesionario">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label class="control-label" for="txtRazonCesionario">Nombre o Raz&oacute;n Social</label>
                                                <input type="text" class="form-control" id="txtRazonCesionario">
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <label class="control-label" for="txtDireccionCesionario">Direcci&oacute;n</label>
                                                <input type="text" class="form-control" id="txtDireccionCesionario">
                                            </div>
                                            <div class="form-group col-lg-12">
                                                <label class="control-label" for="txtMailCesionario">Correo Electr&oacute;nico</label>
                                                <input type="text" class="form-control" id="txtMailCesionario">
                                            </div>
                                        </fieldset>
                                        <fieldset>
                                            <legend>Datos de la Cesi&oacute;n</legend>
                                            <div class="form-group col-lg-2">
                                                <label class="control-label" for="numDoc">Nº Fact.</label>
                                                <input type="text" class="form-control" id="numDoc">
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label class="control-label" for="fecVencimiento">Vencimiento</label>
                                                <input type="text" class="form-control" id="fecVencimiento" placeholder="dd/mm/aaaa">
                                            </div>
                                            <div class="form-group col-lg-7">
                                                <label class="control-label" for="numMonto">Monto a Ceder</label>
                                                <input type="text" class="form-control" id="numMonto">
                                            </div>
                                        </fieldset>
                                    </form>
                                </div> <!-- /.modal-body -->
                                <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button id="btnCederDocumento" type="button" class="btn btn-primary">Ceder</button>
                                </div> <!-- /.modal-footer -->
                            </div> <!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->    

                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
require 'footer.php';
ob_end_flush();
?>
<script type="text/javascript" src="scripts/cesion.js"></script>
