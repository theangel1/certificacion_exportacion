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
                          <h1 class="box-title">Consulta Estado DTE </h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive">
                    <div class="content">  
        
            <?php
                $certificado="../certificados/".substr($_SESSION["rut"],0,-2)."/".$_SESSION['certificado'];
                if (!file_exists($certificado) or empty($_SESSION['certificado'])){?>
                <div class="alert alert-danger">
                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                    <strong>Error!</strong> El certificado digital de su empresa no ha sido instalado por favor comuniquese con soporte.<br><?=$certificado?>
                </div>
                <?php }?>
            <form id="formConsulta" name="formConsulta">                
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5>Seleccione Tipo DTE</h5>
                                <select id="tipoDte" name="tipoDte" class="form-control">
                                    <option value="" selected>&nbsp;</option>
                                    <option value="33">Factura Electr&oacute;nica</option>
                                    <option value="34">Factura Exenta Electr&oacute;nica</option>
                                    <option value="61">Nota Cr&eacute;dito Electr&oacute;nica</option>
                                    <option value="56">Nota de D&eacute;bito Electr&oacute;nica</option>
                                    <option value="52">Gu&iacute;a de Desapcho Electr&oacute;nica</option>
                                </select>                            
                        </div>
                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5>Ingrese Folio o Folios (separados por coma para folios separados o por gui&oacute;n para rangos de folios)</h5>
                                <input type="text" id="folios" name="folios" class="form-control">                            
                        </div>                                                
                            <? if(!empty($_SESSION['certificado'])){?>                                                         
                            <?}?>                        
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button type="button" id="btn-consultar" class="btn btn-primary">Consultar Documentos</button>  
                            </div>                                  
            </form>
            <div class="row">
                <div class="col-md-12" id="respuesta"></div>
            </div>
        </div>
                    
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
<script type="text/javascript" src="scripts/estado.js"></script>
