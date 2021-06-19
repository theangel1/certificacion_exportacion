<?php

session_start();
//CREA LA SESION CARPETA, LA SETEA CON EL RUT MENOS EL DÍGITO VERIFICADOR
$_SESSION['carpeta'] = substr($_SESSION['rut'], 0, strlen($_SESSION['rut'])-2);

require 'header.php';

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Fine Uploader CSS
    ====================================================================== -->
    <link href="../vendor/fine-uploader/node_modules/fine-uploader/fine-uploader/fine-uploader-gallery.css" rel="stylesheet">

    <!-- Fine Uploader JS
    ====================================================================== -->
    <script src="../vendor/fine-uploader/node_modules/fine-uploader/fine-uploader/fine-uploader.js"></script>

    <!-- PLANTILLA de Fine Uploader Gallery
    ====================================================================== -->
    <script type="text/template" id="qq-template-gallery">
        <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Arrastre aquí sus archivos">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button" style="width: 150px; margin-left: 43%;">
                <div>Seleccionar archivo</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Procesando archivos ingresados...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                    <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <div class="qq-thumbnail-wrapper">
                        <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                    </div>
                    <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                    <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                        <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                        Reintentar
                    </button>

                    <div class="qq-file-info">
                        <div class="qq-file-name">
                            <span class="qq-upload-file-selector qq-upload-file"></span>
                            <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                        </div>
                        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                        <span class="qq-upload-size-selector qq-upload-size"></span>
                        <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                            <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                            <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                            <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                        </button>
                    </div>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cerar</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Sí</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancelar</button>
                    <button type="button" class="qq-ok-button-selector">Ok</button>
                </div>
            </dialog>
        </div>
    </script>                       


<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Cargar Folios</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div style="margin: 10px 0 0 20px;">
                        <p  style="font-weight: bold;">Instructivo: </p>
                        <p style="margin-left: 15px;">-Si requiere solicitar nuevos folios de documentos, diríjase al siguiente <a target="_blank" href="https://palena.sii.cl/cvc_cgi/dte/of_solicita_folios" style="font-weight: bold;">link</a></p>
                        <p style="margin-left: 15px;">-Una vez descargados sus archivos en formato XML, selecciónelos o arrástrelos en el recuadro inferior</p>

                    </div>
                        <!-- Fine Uploader DOM Element
                        ====================================================================== -->
                        <div id="fine-uploader-gallery" style="margin: 0 20px 0 20px;"></div>

                        <!-- Código que crea una instancia de Fine Uploader y hace el bind al DOM/template
                        ====================================================================== -->
                        <script>
                           
                        </script>    

                        <div style="margin: 10px 0 0 20px;">                           
                            <img id="info" name="info" src="" width="20"><span id="folios" name="folios" style="font-weight: bold;
                            margin-left: 8px; margin-top: 10px; color:#303030;"></span>
                        </div>
                        </br>
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
<script src="scripts/readXML.js"></script>
