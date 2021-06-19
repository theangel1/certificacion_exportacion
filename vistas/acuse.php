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
                          <h1 class="box-title">Documentos Recepcionados </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive">
                        <table  id="tablaAcuse" class="table table-striped table-bordered table-hover ui-datatable">
                            <thead>  
                                <th>Fecha Recepci&oacute;n</th>  
                                <th>Emisor</th>
                                <th>Documento</th>
                                <th>Folio</th>
                                <th>Fecha Recepci&oacute;n</th>
                                <th>Monto Total</th>
                                <th>rut</th>
                                <th>Tipo</th>
                                <th>Acuse Recibo</th>
                                <th>Opciones</th>
                            </thead>
                            <tbody>                           
                            </tbody>
                            <tfoot>  
                                <th>Fecha Recepci&oacute;n</th>  
                                <th>Emisor</th>
                                <th>Documento</th>
                                <th>Folio</th>
                                <th>Fecha Recepci&oacute;n</th>
                                <th>Monto Total</th>
                                <th>rut</th>
                                <th>Tipo</th>
                                <th>Acuse Recibo</th>
                                <th>Opciones</th>
                            </tfoot>
                        </table>
                    </div>
                      <!-- Modal acuse -->
                    <div id="acuseModal" class="modal modal-styled fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h3 class="modal-title">Acuse de recibo</h3>
                                </div> <!-- /.modal-header -->
                                <div class="modal-body">
                                    <form>
                                        <input type="hidden" id="numDte">
                                        <input type="hidden" id="tipoDte">
                                        <input type="hidden" id="rutDte">
                                        <fieldset>
                                            <legend>Detalle de documento recibido</legend>
                                            <div class="col-lg-6">
                                                <label>Emisor</label>
                                                <div id="txtEmisor"></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Folio</label>
                                                <div id="txtFolio"></div>
                                            </div>
                                            <div class="col-lg-12">
                                                <label>Fecha Emisi&oacute;n</label>
                                                <div id="txtEmiion"></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label>Monto Total</label>
                                                <div id="txtMonto"></div>
                                            </div>
                                        </fieldset>
                                        <fieldset>
                                            <legend>Estado del documento</legend>
                                            <div class="col-lg-2">
                                                <label class="control-label" for="tipoResp">Estado</label>
                                                <select class="form-control" id="tipoResp">
                                                    <option selected></option>
                                                    <option value="1-ERM">Recepción de Mercaderia o Servicios</option>
                                                    <option value="2-ACD">Acepta Contenido del Documento</option>
                                                    <option value="3-RCD">Reclamo al Contenido del Documento</option>
                                                    <option value="3-RFP">Reclamo por falta parcial de Mercaderías</option>
                                                    <option value="4-RFT">Reclamo por falta total de Mercaderías</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label class="control-label" for="motivo">Motivo Rechazo o Reclamo</label>
                                                <textarea class="form-control" id="motivo"></textarea>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div> <!-- /.modal-body -->
                                <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button id="btnAcuseDocumento" type="button" class="btn btn-primary">Grabar</button>
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
<script type="text/javascript" src="scripts/acuse.js"></script>
