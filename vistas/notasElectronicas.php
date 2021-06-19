<?php
require_once("../ws/Metodos.php");
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
                        <h1 class="box-title texto-petroleo upper">Notas de Exportación Electrónicas</h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive">
                        <form method="post" id="formBuscar">
                            <div class="panel panel-default">
                                <div class="centrado-md">
                                 <table class="table">
                                    <div class="panel-heading">
                                         <h3 class="texto-dark text-center"><strong>Buscar Documento</strong></h3>
                                    </div>
                                   
                                       <tr>
                                            <td>Folio </td>
                                            <td><input type="number" name="folio" id="folio" class="form-control" placeholder="Folio"></td>
                                       </tr>
                                       <tr>
                                        <td>Tipo de Documento</td>
                                        <td>
                                            <select id="tipoDoc" name="tipoDoc" class="form-control">
                                                    <option value="110" selected>Factura Exportación Electrónica</option>
                                                    <option value="111">Nota Débito Exportación Electrónica</option>
                                                    <option value="112">Nota Crédito Exportación Electrónica</option>
                                            </select>
                                         </td>
                                </table>
                                    <div class="text-center">
                                        <input type="button" class="btn btn-primary" value="Buscar" id="btn-BuscarDoc" name="btn-BuscarDoc" onclick="mostrar();return false;" />
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </form>
                        <form id="formFactura" name="formFactura" action="../ws/DteExp.php" method="post">
                            <div class="panel panel-default">
                                <table class="table">
                                    <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Datos Documento</strong></h4>
                                    </div>
                                    <tr>
                                        <td>Tipo de Documento(*)
                                            <select id="TipoDTE" name="TipoDTE" class="form-control">
                                                <option value="" selected>Seleccione una opci&oacute;n</option>
                                                <option value="110">Factura Exportación Electrónica</option>
                                                <option value="111">Nota Débito Exportación Electrónica</option>
                                                <option value="112">Nota Crédito Exportación Electrónica</option>
                                            </select>
                                        </td>
                                        <td>Fecha de Emisión (*)
                                            <input type="date" name="FchEmis" id="FchEmis" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Forma de Pago Exportación
                                            <input type='text' name='FmaPagExpText' id='FmaPagExpText' class='form-control' readonly="">
                                            <input type='hidden' name='FmaPagExp' id='FmaPagExp' class='form-control' readonly="">
                                        </td>
                                          <td>Indicador de Servicio
                                            <input type='text' name='IndServicioText' id='IndServicioText' class='form-control' readonly="">
                                            <input type='hidden' name='IndServicio' id='IndServicio' class='form-control' readonly="">
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="panel panel-default">
                                <table class="table">
                                    <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Datos Emisor</strong></h4>
                                    </div>
                                    <tr>
                                        <td>RUT
                                            <input type="text" name="RutEmi" id="RutEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['rut']; ?>">
                                            <input type="hidden" name="IdContribuyente" id="IdContribuyente" value="<?php echo $_SESSION['contribuyente']; ?>">
                                        </td>
                                        <td>Razón Social
                                            <input type="text" name="RznSocEmi" id="RznSocEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['razon']; ?>">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>Giro
                                            <input type="text" name="GiroEmi" id="GiroEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['giro']; ?>">
                                        </td>
                                        <td>Dirección
                                            <input type="text" name="DirEmi" id="DirEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['direccion']; ?>">
                                        </td>
                                    </tr>
                                    <tr>

                                        <td>Email
                                            <input type="text" name="EmailEmi" id="EmailEmi" class="form-control" readonly="yes" value="<?php echo $_SESSION['email']; ?>">
                                        </td>
                                        <td>Act. Económica
                                            <input type="text" name="Acteco" id="Acteco" class="form-control" readonly="yes" value="<?php echo $_SESSION['acteco']; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Comuna
                                            <input type="text" name="ComunaEmi" id="ComunaEmi" class="form-control" value="<?php echo $_SESSION['comuna']; ?>">
                                        </td>
                                        <td>Ciudad
                                            <input type="text" name="CiudadEmi" id="CiudadEmi" class="form-control" value="<?php echo $_SESSION['ciudad']; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Teléfono
                                            <input type="text" name="TelefonoEmi" id="TelefonoEmi" class="form-control" value="<?php echo $_SESSION['telefono']; ?>">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="panel panel-default">
                                <table class="table">
                                    <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Datos Receptor</strong></h4>
                                    </div>
                                    <tr>
                                        <td>RUT Receptor
                                            <input type="text" name="RUTRecep" id="RUTRecep" class="form-control" value="55555555-5" readonly="yes">
                                        </td>
                                        <td>Razón Social
                                            <input type="text" name="RznSocRecep" id="RznSocRecep" class="form-control" readonly="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Giro (*)
                                            <input type="text" name="GiroRecep" id="GiroRecep" class="form-control">
                                        </td>
                                        <td>Nacionalidad
                                            <input type="text" name="NacionalidadText" id="NacionalidadText" class="form-control" readonly="">
                                            <input type='hidden' name='Nacionalidad' id='Nacionalidad' class='form-control' readonly="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dirección
                                            <input type="text" name="DirRecep" id="DirRecep" class="form-control" readonly="">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="panel panel-default">
                                <table class="table">
                                    <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Datos de Pago y Envío</strong></h4>
                                    </div>
                                    <tr>

                                        <td> Cláusula de Venta
                                            <input type="text" name="CodClauVentaText" id="CodClauVentaText" class="form-control" value="" readonly="">
                                            <input type="hidden" name="CodClauVenta" id="CodClauVenta" class="form-control" value="">
                                        </td>
                                        <td> Modalidad de Venta (*)
                                            <input type="text" name="CodModVentaText" id="CodModVentaText" class="form-control" readonly="">
                                            <input type="hidden" name="CodModVenta" id="CodModVenta" class="form-control">
                                        </td>

                                    </tr>
                                    <table class="table">
                                        <tr>
                                            <td>Monto Flete
                                                <input type="number" step="0.01" name="MntFlete" id="MntFlete" class="form-control">
                                            </td>
                                            <td>Total Cláusula
                                                <input type="number" step="0.01" name="TotClauVenta" id="TotClauVenta" class="form-control">
                                            </td>
                                            <td>Monto Seguro
                                                <input type="number" step="0.01" name="MntSeguro" id="MntSeguro" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Puerto Embarque
                                                <input type="text" id="CodPtoEmbarqueText" name="CodPtoEmbarqueText" class="form-control" readonly="">
                                                <input type="hidden" id="CodPtoEmbarque" name="CodPtoEmbarque" class="form-control">
                                                <input type="hidden" name="IdAdicPtoEmb" id="IdAdicPtoEmb">
                                            </td>
                                            <td>Puerto Desembarque
                                                <input type="text" id="CodPtoDesembText" name="CodPtoDesembText" class="form-control" readonly="">
                                                <input type="hidden" id="CodPtoDesemb" name="CodPtoDesemb" class="form-control">
                                                <input type="hidden" name="IdAdicPtoDesemb" id="IdAdicPtoDesemb">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>País destino
                                                <input type="text" id="CodPaisDestinText" name="CodPaisDestinText" class="form-control" readonly="">
                                                <input type="hidden" id="CodPaisDestin" name="CodPaisDestin" class="form-control">
                                                <input type="hidden" name="CodPaisRecep" id="CodPaisRecep">
                                            </td>
                                            <td>Vía de Transporte
                                                <input type="text" name="CodViaTranspText" id="CodViaTranspText" class="form-control" readonly="">
                                                <input type="hidden" name="CodViaTransp" id="CodViaTransp" class="form-control">
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                            <div class="panel panel-default">
                                <table class="table">
                                    <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Datos Bulto</strong></h4>
                                    </div>
                                    <tr>

                                        <td>Tipo de Bulto
                                            <input type="text" name="CodTpoBultosText" id="CodTpoBultosText" class="form-control" readonly="">
                                            <input type="hidden" name="CodTpoBultos" id="CodTpoBultos" class="form-control">
                                        </td>
                                        <td>Unidad Peso Bruto
                                            <input type="text" name="CodUnidPesoBrutoText" id="CodUnidPesoBrutoText" class="form-control" readonly="">
                                            <input type="hidden" name="CodUnidPesoBruto" id="CodUnidPesoBruto" class="form-control">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unidad de Medida TARA
                                            <input type="text" name="CodUnidMedTaraText" id="CodUnidMedTaraText" class="form-control" readonly="">
                                            <input type="hidden" name="CodUnidMedTara" id="CodUnidMedTara" class="form-control">
                                        </td>
                                        <td>Cantidad de Bultos
                                            <input type="text" name="CantBultos" id="CantBultos" class="form-control">
                                            <input type="hidden" name="TotBultos" id="TotBultos">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Unidad Peso Neto
                                            <input type="text" name="CodUnidPesoNetoText" id="CodUnidPesoNetoText" class="form-control" readonly="">
                                            <input type="hidden" name="CodUnidPesoNeto" id="CodUnidPesoNeto" class="form-control">
                                        </td>
                                        <td>Marcas Bultos
                                            <input type="text" name="Marcas" id="Marcas" class="form-control">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="panel panel-default">
                                <table class="table" id="tablaDetalle">
                                    <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Detalle</strong></h4>
                                    </div>
                                    <tr>
                                        <td style="width: 20%;">Producto (*)</td>
                                        <td style="width: 14%;">Cantidad (*)</td>
                                        <td style="width: 10%;">Unidad</td>
                                        <td>Precio (*)</td>
                                        <td style="width: 8%;">Descuento / Recargo %</td>
                                        <td>Tipo</td>
                                        <td>SubTotal</td>
                                        <td>Exento</td>
                                        <td></td>
                                    </tr>
                                </table>
                                <br>
                                <div class="text-center">
                                    <button type="button" id="btnAgregaLineaDet" class="btn btn-primary" onclick="agregarLineaDet()">Agregar línea Detalle</button>
                                </div>                          
                                <br>   
                            </div>

                            <div class="panel panel-default">
                                <table class="table" id="tablaReferencia">
                                    <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Referencias</strong></h4>
                                    </div>
                                    <tr>
                                        <td>Tipo de Documento (*)</td>
                                        <td>Folio (*)</td>
                                        <td>Fecha (*)</td>
                                        <td>Código</td>
                                        <td>Razón</td>
                                        <td></td>
                                    </tr>                                    
                                </table>
                                <br>
                                <div class="text-center">
                                    <button type="button" id="btnAgregaLineaRef" name="btnAgregaLineaRef" class="btn btn-primary">Agregar línea Referencia</button>
                                </div> 
                                <br>
                            </div>

                            <div class="panel panel-default">
                                <table class="table" id="tablaRecDesc">
                                    <div class="panel-heading">
                                        <h4 class="texto-dark"><strong>Recargos y Descuentos Globales</strong></h4>
                                    </div>
                                    <tr>
                                        <td>Movimiento (*)</td>
                                        <td>Glosa</td>
                                        <td>Valor (*)</td>
                                        <td style="width: 75px;">Tipo (*)</td>
                                        <td>Exento</td>
                                        <td></td>
                                    </tr>
                                </table>
                                <br>
                                <div class="text-center">
                                    <button type="button" id="btnAgregaLineaRecDesc" class="btn btn-primary" onclick="agregarLineaRecDesc()">Agregar línea Recargo/Descuento</button>
                                </div> 
                                <br>
                            </div>

                            <div class="panel panel-default">
                                <table class="table">
                                    <div class="panel-heading">
                                        <h4><strong>Totales</strong></h4>
                                    </div>
                                    <tr>
                                        <td>Moneda de pago (*)</td>
                                        <td><select name="TpoMoneda" id="TpoMoneda" class="js-example-basic-single js-states form-control">
                                                <option value="" selected>Seleccione una opci&oacute;n</option>
                                              </select>
                                        </td>
                                      </tr>
                                       <tr>
                                        <td>Tipo cambio a CLP (*)</td>
                                        <td><input type="number" step="0.01" name="TpoCambio" id="TpoCambio" class="form-control" ></td>
                                      </tr> 
                                      <tr>
                                        <td>SubTotal</td>
                                        <td><input type="text" step="0.01" name="txtSubTotal" id="txtSubTotal" class="form-control" readonly="yes"></td>
                                      </tr>                       
                                      <tr>
                                        <td>Monto Exento</td>
                                        <td><input type="text" step="0.01" name="MntExe" id="MntExe" class="form-control" readonly="yes"></td>
                                      </tr>                          
                                      <tr>
                                        <td>Total</td>
                                        <td><input type="text" step="0.01" name="MntTotal" id="MntTotal" class="form-control" readonly="yes"></td>
                                      </tr>
                                </table>
                            </div>
                            <div style="text-align: center;">
                                <input type="submit" id="btnValidar" class="btn btn-primary" value="Validar y visualizar" />                                
                                
                            </div> 


                        </form>
                    </div>
                </div>
                <!--Fin centro -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->

</div>
<!-- /.content-wrapper -->
<!--Fin-Contenido-->

<?php
require 'footer.php';
?>
<script src="scripts/notasElectronicas.js"></script>