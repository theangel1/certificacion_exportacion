$("input[name='boxDescuentoPct[]']").show();
$("input[name='boxRecargoPct[]']").hide();
function mostrar() {
    //aca deberia validar si viene vacio o no...
    let paramFolio = $(folio).val();
    let paramTipoDoc = $(tipoDoc).val();
    let paramIdExportacion = "";

    /*
         Peticion ajax para mostrar los encabezados
      */
    $.post("../ajax/notasElectronicas.php?op=mostrar", { tipoDoc: paramTipoDoc, folio: paramFolio }, function (data) {
        data = JSON.parse(data);
        paramIdExportacion = data.idexportacion;
        $("#FmaPagExp").val(data.forma_pago);
        $("#IndServicio").val(data.ind_servicio);
        //Datos del receptor
        $("#RznSocRecep").val(data.razon_social);
        $("#GiroRecep").val(data.giro);
        $("#DirRecep").val(data.direccion);
        $("#Nacionalidad").val(data.nacionalidad);
        //Datos ADUANA        
        $("#CodClauVenta").val(data.codclauventa);
        $("#CodModVenta").val(data.codmodventa);
        $("#MntFlete").val(data.mntflete);
        $("#TotClauVenta").val(data.totclauventa);
        $("#MntSeguro").val(data.mntseguro);
        $("#CodPtoEmbarque").val(data.codptoembarque);
        $("#IdAdicPtoEmb").val(data.idadicptoemb);
        $("#CodPtoDesemb").val(data.codptodesemb);
        $("#IdAdicPtoDesemb").val(data.idadicptodesemb);
        $("#CodPaisDestin").val(data.codpaisdestin);
        $("#CodPaisRecep").val(data.codpaisrecep);
        $("#CodViaTransp").val(data.codviatransp);
        $("#CodPtoDesemb").val(data.codptodesemb);
        //Bultos
        $("#CodTpoBultos").val(data.codtpobultos);
        $("#CodUnidPesoBruto").val(data.codunidpesobruto);
        $("#CodUnidMedTara").val(data.codunidmedtara);
        $("#CantBultos").val(data.cantbultos);
        $("#TotBultos").val(data.totbultos);
        $("#CodUnidPesoNeto").val(data.codunidpesoneto);
        $("#Marcas").val(data.marcas);
        //Totales
        $("#TpoMoneda").val(data.tipo_moneda);        
        $("#TpoCambio").val(data.cambio_clp);        
        $("#txtSubTotal").val(data.monto_total);
        $("#MntExe").val(data.monto_exento);
        $("#MntTotal").val(data.monto_total);        

        /*
            Peticion ajax para mostrar los Detalles
        */
        $.post("../ajax/notasElectronicas.php?op=mostrarDetalle", { idexportacion: paramIdExportacion }, function (detalle) {

            //Recuperar innerHtml de la tabla detalle de la pagina
            var htmlTablaDetalles = $("#tablaDetalle").html();
            detalle = JSON.parse(detalle);

            for (var i = 0; i < detalle.length; i++)
            {
                var tipoValor = "D";

                htmlTablaDetalles = htmlTablaDetalles + '<tr><td><input type="text" name="boxNmbItem[]" id="boxNmbItem[]" class="form-control" value="' + detalle[i].nombre_item + '"></td>';
                htmlTablaDetalles = htmlTablaDetalles + '<td><input type="number" name="boxQtyItem[]" id="boxQtyItem[]" class="form-control" value="' + detalle[i].cantidad + '"></td>';
                htmlTablaDetalles = htmlTablaDetalles + '<td><input type="text" name="boxUnmdItem[]" id="boxUnmdItem[]" class="form-control" value="' + detalle[i].unidad + '"></td>';
                htmlTablaDetalles = htmlTablaDetalles + '<td><input type="number" step="0.01" name="boxPrcItem[]" id="boxPrcItem[]" class="form-control" value="' + detalle[i].precio_unitario + '"></td>';

                //Si tiene descuento
                if (detalle[i].descuento_pct > 0) {
                    htmlTablaDetalles = htmlTablaDetalles + '<td><input type="number" step="0.01" name="boxDescuentoPct[]" id="boxDescuentoPct[]" class="form-control" value="' + detalle[i].descuento_pct + '">' +
                        '<input type="hidden" name="boxDescuentoMonto[]" id="boxDescuentoMonto[]" class="form-control" value="' + detalle[i].descuento_monto + '">';

                    htmlTablaDetalles = htmlTablaDetalles + '<input type="number" step="0.01" name="boxRecargoPct[]" id="boxRecargoPct[]" class="form-control" value="0" style="display: none;">' +
                        '<input type="hidden" name="boxRecargoMonto[]" id="boxRecargoMonto[]" class="form-control" value="0"></td>';                    
                    tipoValor = "D";
                }
                //Si tiene recargo
                else if(detalle[i].recargo_pct > 0)
                {
                    htmlTablaDetalles = htmlTablaDetalles + '<td><input type="number" step="0.01" name="boxDescuentoPct[]" id="boxDescuentoPct[]" class="form-control" value="0" style="display: none;">' +
                        '<input type="hidden" step="0.01" name="boxDescuentoMonto[]" id="boxDescuentoMonto[]" class="form-control" value="0">';

                    htmlTablaDetalles = htmlTablaDetalles + '<input type="number" step="0.01" name="boxRecargoPct[]" id="boxRecargoPct[]" class="form-control" value="' + detalle[i].recargo_pct + '">' +
                        '<input type="hidden" step="0.01" name="boxRecargoMonto[]"  id="boxRecargoMonto[]" class="form-control" value="' + detalle[i].recargo_monto + '"></td>';       
                    tipoValor = "R";
                }
                //Si no tiene descuento ni recargo
                else
                {
                    htmlTablaDetalles = htmlTablaDetalles + '<td><input type="number" step="0.01" name="boxDescuentoPct[]" id="boxDescuentoPct[]" class="form-control" value="0">' +
                        '<input type="hidden" step="0.01" name="boxDescuentoMonto[]" id="boxDescuentoMonto[]" class="form-control" value="0">';

                    htmlTablaDetalles = htmlTablaDetalles + '<input type="number" step="0.01" name="boxRecargoPct[]" id="boxRecargoPct[]" class="form-control" value="0" style="display: none;">' +
                        '<input type="hidden" name="boxRecargoMonto[]" id="boxRecargoMonto[]" class="form-control" value="0"></td>';       
                    //Se setea D por defecto
                    tipoValor = "D";
                }
                 htmlTablaDetalles = htmlTablaDetalles + '<td><select name="ddlTipoRecDesc" id="ddlTipoRecDesc" class="form-control" style="width: 56px">';
                 
                 //Define Tipo de valor seleccionado
                if(tipoValor == "D")
                {
                    htmlTablaDetalles = htmlTablaDetalles + '<option selected value="D">D</option>'+
                                                            '<option value="R">R</option></select>';     
                }
                else
                {
                    htmlTablaDetalles = htmlTablaDetalles +  '<option value="D">D</option>'+
                                                             '<option selected value="R">R</option></select>';                                      
                }                       
                htmlTablaDetalles = htmlTablaDetalles + '<td><input type="number" step="0.01" name="boxMontoItem[]" id="boxMontoItem[]" class="form-control" readonly="yes" value="' + detalle[i].total_item + '"></td>';
                htmlTablaDetalles = htmlTablaDetalles + '<td><select name="boxIndExe[]" id="boxIndExe[]" class="form-control" style="width: 66px">';
                //Define si es o no exento
                if(detalle[i].exento == 1)
                {                    
                    htmlTablaDetalles = htmlTablaDetalles +'<option value="1" selected="selected">SÍ</option>'+
                                                            '<option value="0">NO</option></select></td>';
                }
                else
                {
                    htmlTablaDetalles = htmlTablaDetalles +'<option value="1">SÍ</option>'+
                                                         '<option value="0" selected="selected">NO</option></select></td>';
                }                
                htmlTablaDetalles = htmlTablaDetalles + '<td><button type="button" id="btnEliminaLineaDet" class="btn btn-primary" onclick="eliminarLineaDet(this);">-</button></td></tr>';
            }
            
            //Insertar nuevo innerHtml a la tabla detalle
            $("#tablaDetalle").html(htmlTablaDetalles);
        })

        /*
            Peticion ajax para mostrar las Referencias
        */
        $.post("../ajax/notasElectronicas.php?op=mostrarReferencia", { idexportacion: paramIdExportacion }, function (referencia) {
            //ver formatos de la fecha en estearchivo y en la vista de php plz.
            //en el for faltan las validaciones... si vienen vacios, etc.

            //Recuperar innerHtml de la tabla referencia de la pagina
            var htmlTablaReferencias = $("#tablaReferencia").html();

            referencia = JSON.parse(referencia);

            for (var i = 0; i < referencia.length; i++)            
            {
                htmlTablaReferencias = htmlTablaReferencias + '<tr><td><select name="DDLSelectTipDocRef[]" id="DDLSelectTipDocRef[]" class="form-control"></select>'+
                                                              '<input type="hidden" name="TipDocRefCOD" id="TipDocRefCOD" value="'+ referencia[i].tipo_documento +'"></td>';
                htmlTablaReferencias = htmlTablaReferencias + '<td><input type="text" name="boxFolioRef[]" id="boxFolioRef[]" class="form-control" value="' + referencia[i].folio_referencia + '"></td>';
                htmlTablaReferencias = htmlTablaReferencias + '<td><input type="date" name="boxFchRef[]" id="boxFchRef[]" class="form-control" value="' + referencia[i].fecha_referencia + '"></td>';
                if(referencia[i].cod_referencia == 0)
                {
                     htmlTablaReferencias = htmlTablaReferencias + '<td><select name="DDLCodRef[]" id="DDLCodRef[]" class="form-control">' + 
                                                                    '<option value="" selected>Seleccione una opci&oacute;n</option></select>' +                
                                                                    '<input type="hidden" name="CodRefCOD" id="CodRefCOD" value="'+ referencia[i].cod_referencia +'"></td>';                    
                }
                else
                {
                htmlTablaReferencias = htmlTablaReferencias + '<td><select name="DDLCodRef[]" id="DDLCodRef[]" class="form-control"></select>'+                
                                                              '<input type="hidden" name="CodRefCOD" id="CodRefCOD" value="'+ referencia[i].cod_referencia +'"></td>';
                }
                htmlTablaReferencias = htmlTablaReferencias + '<td><input type="text" name="boxRazonRef[]" id="boxRazonRef[]" class="form-control" value="' + referencia[i].razon_referencia + '"></td>';
                htmlTablaReferencias = htmlTablaReferencias + '<td><button type="button" id="btnEliminaLineaRef" class="btn btn-primary" onclick=" eliminarLineaRef(this);">-</button></td></tr>'; 
            }
           
           //Insertar nuevo innerHtml a la tabla referencia
            $("#tablaReferencia").html(htmlTablaReferencias);
            poblarSelectReferenciaExistentes();
        })

        /*
            Peticion ajax para mostrar los Descuentos y Recargos Globales
        */
        $.post("../ajax/notasElectronicas.php?op=mostrarDscRec", { idexportacion: paramIdExportacion }, function (dscrec) {
            //ver formatos de la fecha en estearchivo y en la vista de php plz.
            //en el for faltan las validaciones... si vienen vacios, etc.

            //Recuperar innerHtml de la tabla detalle de la pagina
            var htmlTablaRecDesc = $("#tablaRecDesc").html();
            dscrec = JSON.parse(dscrec);
            
            for (var i = 0; i < dscrec.length; i++)
            {
                htmlTablaRecDesc = htmlTablaRecDesc +'<tr><td><select name="DDLSelectTpoMov[]" id="DDLSelectTpoMov[]" class="form-control">';
                if(dscrec[i].tipo_movimiento==="R")
                {
                    htmlTablaRecDesc = htmlTablaRecDesc + '<option value="D">DESCUENTO</option>' +
                                                          '<option value="R" selected>RECARGO</option></select></td>';
                }
                else if(dscrec[i].tipo_movimiento==="D")    
                {
                     htmlTablaRecDesc = htmlTablaRecDesc + '<option value="D" selected>DESCUENTO</option>' +
                                                           '<option value="R">RECARGO</option></select></td>';
                }
                htmlTablaRecDesc = htmlTablaRecDesc +'<td><input type="text" name="boxGlosaDR[]" id="boxGlosaDR[]" class="form-control" value="'+dscrec[i].glosadr+'"></td>';             
                htmlTablaRecDesc = htmlTablaRecDesc +'<td><input type="number" step="0.01" name="boxValorDR[]" id="boxValorDR[]" class="form-control" value="'+dscrec[i].valor+'"></td>';
                htmlTablaRecDesc = htmlTablaRecDesc +'<td><select name="DDLSelectTpoValor[]" id="DDLSelectTpoValor[]" class="form-control">';
                if(dscrec[i].tipo_valor=="%")
                {
                    htmlTablaRecDesc = htmlTablaRecDesc + '<option value="%" selected>%</option>' +
                                                                '<option value="$">$</option></select></td>';
                }
                else if(dscrec[i].tipo_valor=="$")    
                {
                    htmlTablaRecDesc = htmlTablaRecDesc + '<option value="%">%</option>' +
                                                            '<option value="$" selected>$</option></select></td>';
                }
                  htmlTablaRecDesc =htmlTablaRecDesc+'<td><select id="DDLSelectIndExeDR[]" name="DDLSelectIndExeDR[]" class="form-control">';
                //Define si es o no exento
                if(dscrec[i].exento == 1)
                {                    
                    htmlTablaRecDesc = htmlTablaRecDesc + '<option value="1" selected="selected">SÍ</option>'+
                                                          '<option value="0">NO</option></select></td>';
                }
                else
                {
                    htmlTablaRecDesc = htmlTablaRecDesc + '<option value="1">SÍ</option>'+
                                                         '<option value="0" selected="selected">NO</option></select></td>';
                }                
                htmlTablaRecDesc = htmlTablaRecDesc + '<td><button type="button" id="btnEliminaLineaRecDesc" class="btn btn-primary" onclick="eliminarLineaRecDesc(this);">-</button></td></tr>';

            }
            $("#tablaRecDesc").html(htmlTablaRecDesc);

        })

        jQuery.ajax({
            type: "POST",
            url: '../ajax/arreglosExportacion.php',
            data: {
                forma_pago: data.forma_pago, codclauventa: data.codclauventa, codmodventa: data.codmodventa,
                codptoembarque: data.codptoembarque, codptodesemb: data.codptodesemb, codpaisdestin: data.codpaisdestin,
                codviatransp: data.codviatransp, codtpobultos: data.codtpobultos, codunidpesobruto: data.codunidpesobruto,
                codunidmedtara: data.codunidmedtara, codunidpesoneto: data.codunidpesoneto, ind_servicio: data.ind_servicio,
                nacionalidad: data.nacionalidad
            },
            success: function (data)
            {
                data = JSON.parse(data);
                $("#FmaPagExpText").val(data[0]);
                $("#CodClauVentaText").val(data[1]);
                $("#CodModVentaText").val(data[2]);
                $("#CodPtoEmbarqueText").val(data[3]);
                $("#CodPtoDesembText").val(data[4]);
                $("#CodPaisDestinText").val(data[5]);
                $("#CodViaTranspText").val(data[6]);
                $("#CodTpoBultosText").val(data[7]);
                $("#CodUnidPesoBrutoText").val(data[8]);
                $("#CodUnidMedTaraText").val(data[9]);
                $("#CodUnidPesoNetoText").val(data[10]);      
                $("#IndServicioText").val(data[11]);   
                $("#NacionalidadText").val(data[12]);         
            }
        });

        //console.log("Datos cargados");
        bootbox.alert("Datos Cargados");

    })
}

$( window ).on( "load", function() 
{
    poblarSelectMoneda();
    document.getElementById('FchEmis').valueAsDate = new Date();   
});

$(document).ready(function () 
{ 
    //Al perder el foco los input, se hace el calculo del subtotal
    calcularSubTotal();    
    $(document).on("focusout", "input[name='boxQtyItem[]']", calcularSubTotal);
    $(document).on("focusout", "input[name='boxPrcItem[]']", calcularSubTotal);
    $(document).on("focusout", "input[name='DescRecPct']", calcularSubTotal);
    $(document).on("change", "[name='ddlTipoRecDesc']", calcularSubTotal);
    $(document).on("focusout", "input[name='boxValorDR[]']", calcularSubTotal);
    $(document).on("change", "[name='DDLSelectTpoMov[]']", calcularSubTotal);
    $(document).on("change", "[name='DDLSelectTpoValor[]']", calcularSubTotal);
    $(document).on("focusout", "input[name='boxDescuentoPct[]']", calcularSubTotal);
    $(document).on("focusout", "input[name='boxRecargoPct[]']", calcularSubTotal);

    //Asignar valor a textbox ocultos
    $(document).on("change", "[name='CodPtoEmbarque']", function()
    {
        var id = $('#CodPtoEmbarque').find(":selected").text();
        var idList = id.split("-");
        id = idList[0].trim();   
        $("#IdAdicPtoEmb").val(id);
    });
   
    $(document).on("change", "[name='CodPtoDesemb']", function()
    {
        var id = $('#CodPtoDesemb').find(":selected").text();
        var idList = id.split("-");
        id = idList[0].trim();   
        $("#IdAdicPtoDesemb").val(id);
    });

    $(document).on("change", "[name='CodPaisDestin']", function()
    {
        $("#CodPaisRecep").val($("#CodPaisDestin").val());
    });

    $(document).on("focusout", "[name='CantBultos']", function()
    {
        $("#TotBultos").val($("#CantBultos").val());
    });

    //POBLA LOS SELECT DE LA REFRENCIA DESPUES DE AGREGAR LA LINEA
    $(document).on("click", "button[name='btnAgregaLineaRef']", function()
    {
        $.when(agregarLineaRef()).done( function()
        {
            poblarSelectReferencia();
        });
    });


    //Esconde y muestra el textbox que corresponda según el tipo (descuento o recargo)
    $(document).on("change", "[name='ddlTipoRecDesc']", function()
    {        
            var indice = (this.parentNode.parentNode.rowIndex);
            indice = indice - Math.round(indice/2);
            var tipos = document.getElementsByName("ddlTipoRecDesc");
            var boxDesc = document.getElementsByName("boxDescuentoPct[]");
            var boxRec = document.getElementsByName("boxRecargoPct[]");
            var boxDescMonto = document.getElementsByName("boxDescuentoMonto[]");
            var boxRecMonto = document.getElementsByName("boxRecargoMonto[]");
            var tipo = tipos[indice].value;
            if(tipo =="D")
            {               
                //Setea el mismo valor del campo mostardo al campo oculto
                $(boxDesc[indice]).val($(boxRec[indice]).val());               
                $(boxDesc[indice]).show();
                //Deja en cero el valor y lo esconde
                $(boxRec[indice]).val(0);
                $(boxRecMonto[indice]).val(0);
                $(boxRec[indice]).hide();
            }  
            if(tipo =="R")
            {             
                //Setea el mismo valor del campo mostardo al campo oculto   
                $(boxRec[indice]).val($(boxDesc[indice]).val());
                $(boxRec[indice]).show();
                //Deja en cero el valor y lo esconde
                $(boxDesc[indice]).val(0);
                $(boxDescMonto[indice]).val(0);
                $(boxDesc[indice]).hide();
               
            }      
            calcularSubTotal();
    });
});

function poblarSelectMoneda()
{
    //Tipo Moneda
    var select = document.getElementById("TpoMoneda");

    var monedas = ["BOLIVAR", "BOLIVIANO", "CHELIN", "CORONA DIN", "CORONA NOR", "CORONA SC", "CRUZEIRO REAL", "DIRHAM", 
    "DOLAR AUST", "DOLAR CAN", "DOLAR HK", "DOLAR NZ", "DOLAR SIN", "DOLAR TAI", "DOLAR USA", "DRACMA", "ESCUDO", "EURO",
    "FLORIN", "FRANCO BEL", "FRANCO FR", "FRANCO SZ", "GUARANI", "LIBRA EST", "LIRA", "MARCO AL", "MARCO FIN", "NUEVO SOL",
    "OTRAS MONEDAS", "PESETA", "PESO", "PESO CL", "PESO COL", "PESO MEX", "PESO URUG", "RAND", "RENMINBI", "RUPIA", "SUCRE",
    "YEN"];

    for (i = 0; i < monedas.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = monedas[i];
        option.value = monedas[i];
        select.append(option);
    }
}

function poblarSelectReferenciaExistentes()
{
//Tipos Documento Referencia
    var ddl = document.getElementsByName("DDLSelectTipDocRef[]");
    var tipDocRefCOD = document.getElementsByName("TipDocRefCOD");
    for (var i = 0; i <  ddl.length; i++) 
    {
        var select = ddl[i];
        var tipoDocsRef = ["FACTURA", "FACTURA DE VENTA BIENES Y SERVICIOS NO AFECTOS O EXENTOS DE IVA", "BOLETA", "BOLETA EXENTA",
        "FACTURA DE COMPRA", "NOTA DE DÉBITO", "NOTA DE CRÉDITO", "LIQUIDACIÓN", "LIQUIDACIÓN FACTURA", "LIQUIDACIÓN FACTURA ELECTRÓNICA",
        "FACTURA ELECTRÓNICA", "FACTURA NO AFECTA O EXENTA ELECTRÓNICA", "BOLETA ELECTRÓNICA", "BOLETA EXENTA ELECTRÓNICA",
        "FACTURA DE COMPRA ELECTRÓNICA", "NOTA DE DÉBITO ELECTRÓNICA", "NOTA DE CRÉDITO ELECTRÓNICA", "GUÍA DE DESPACHO", 
        "GUÍA DE DESPACHO ELECTRÓNICA", "FACTURA DE EXPORTACIÓN ELECTRÓNICA", "NOTA DE DÉBITO DE EXPORTACIÓN ELECTRÓNICA",
        "NOTA DE CRÉDITO DE EXPORTACIÓN ELECTRÓNICA", "ORDEN DE COMPRA", "NOTA DE PEDIDO", "CONTRATO", "RESOLUCIÓN",
        "PROCESO CHILECOMPRA", "FICHA CHILECOMPRA", "DUS", "B/L (CONOCIMIENTO DE EMBARQUE)", "AWB (AIR WILL BILL)", "MIC/DTA",
        "CARTA DE PORTE", "RESOLUCIÓN DEL SNA DONDE CALIFICA SERVICIOS DE EXPORTACIÓN", "PASAPORTE", "CERTIFICADO DE DEPÓSITO BOLSA PROD. CHILE",
        "VALE DE PRENDA BOLSA PROD. CHILE", "SET - Certificacion"];
        var codTipoDocsRef = ["30", "32", "25", "38", "45", "55", "60", "103", "40", "43", "33", "34", "39", "41", "46", "56",
        "61", "50", "52", "110", "111", "112", "801", "802", "803", "804", "805", "806", "807", "808", "809", "810", "811", "812",
        "813", "814", "815", "SET"];

        for (j = 0; j < tipoDocsRef.length; j++) {
            var option = document.createElement('option');
            option.innerHTML = tipoDocsRef[j];
            option.value = codTipoDocsRef[j];
            if(option.value == tipDocRefCOD[i].value)
            {
                option.selected = true;
            }
            select.append(option);
        }
    }    

    //Cod Referencia
    var ddl =  document.getElementsByName("DDLCodRef[]"); 
    var codRefCOD = document.getElementsByName("CodRefCOD");
    
    for (var i = 0; i <  ddl.length; i++) 
    {
        var select = ddl[i];
        var codDocsRef = ["ANULA DOCUMENTO DE REFERENCIA", "CORRIGE TEXTO DOCUMENTO DE REFERENCIA", "CORRIGE MONTOS"];
        var codCodDocsRef = ["1", "2", "3"];

        for (j = 0; j < codDocsRef.length; j++) {
            var option = document.createElement('option');
            option.innerHTML = codDocsRef[j];
            option.value = codCodDocsRef[j];
            if(option.value == codRefCOD[i].value)
            {
                option.selected = true;
            }
            select.append(option);
        }
    }
}

//Poblar Select referencias
function poblarSelectReferencia()
{
    //Tipos Documento Referencia
    var ddl = document.getElementsByName("DDLSelectTipDocRef[]");
    var ultimaFila = (document.getElementsByName("DDLSelectTipDocRef[]").length) - 1;
    var select = ddl[ultimaFila];
    var tipoDocsRef = ["FACTURA", "FACTURA DE VENTA BIENES Y SERVICIOS NO AFECTOS O EXENTOS DE IVA", "BOLETA", "BOLETA EXENTA",
    "FACTURA DE COMPRA", "NOTA DE DÉBITO", "NOTA DE CRÉDITO", "LIQUIDACIÓN", "LIQUIDACIÓN FACTURA", "LIQUIDACIÓN FACTURA ELECTRÓNICA",
    "FACTURA ELECTRÓNICA", "FACTURA NO AFECTA O EXENTA ELECTRÓNICA", "BOLETA ELECTRÓNICA", "BOLETA EXENTA ELECTRÓNICA",
    "FACTURA DE COMPRA ELECTRÓNICA", "NOTA DE DÉBITO ELECTRÓNICA", "NOTA DE CRÉDITO ELECTRÓNICA", "GUÍA DE DESPACHO", 
    "GUÍA DE DESPACHO ELECTRÓNICA", "FACTURA DE EXPORTACIÓN ELECTRÓNICA", "NOTA DE DÉBITO DE EXPORTACIÓN ELECTRÓNICA",
    "NOTA DE CRÉDITO DE EXPORTACIÓN ELECTRÓNICA", "ORDEN DE COMPRA", "NOTA DE PEDIDO", "CONTRATO", "RESOLUCIÓN",
    "PROCESO CHILECOMPRA", "FICHA CHILECOMPRA", "DUS", "B/L (CONOCIMIENTO DE EMBARQUE)", "AWB (AIR WILL BILL)", "MIC/DTA",
    "CARTA DE PORTE", "RESOLUCIÓN DEL SNA DONDE CALIFICA SERVICIOS DE EXPORTACIÓN", "PASAPORTE", "CERTIFICADO DE DEPÓSITO BOLSA PROD. CHILE",
    "VALE DE PRENDA BOLSA PROD. CHILE", "SET - Certificacion"];
    var codTipoDocsRef = ["30", "32", "25", "38", "45", "55", "60", "103", "40", "43", "33", "34", "39", "41", "46", "56",
    "61", "50", "52", "110", "111", "112", "801", "802", "803", "804", "805", "806", "807", "808", "809", "810", "811", "812",
    "813", "814", "815", "SET"];

    for (j = 0; j < tipoDocsRef.length; j++) {
        var option = document.createElement('option');
        option.innerHTML = tipoDocsRef[j];
        option.value = codTipoDocsRef[j];
        select.append(option);
    }    

    //Cod Referencia
    var ddl =  document.getElementsByName("DDLCodRef[]"); 
    var ultimaFila = (document.getElementsByName("DDLCodRef[]").length) - 1;
    
    var select = ddl[ultimaFila];
    var codDocsRef = ["ANULA DOCUMENTO DE REFERENCIA", "CORRIGE TEXTO DOCUMENTO DE REFERENCIA", "CORRIGE MONTOS"];
    var codCodDocsRef = ["1", "2", "3"];

    for (j = 0; j < codDocsRef.length; j++) {
        var option = document.createElement('option');
        option.innerHTML = codDocsRef[j];
        option.value = codCodDocsRef[j];
        select.append(option);
    }    
}

//CALCULAR SUBTOTAL DE LOS DETALLES
function calcularSubTotal() {
    //Asingar valor a variables
    var cantidad = document.getElementsByName("boxQtyItem[]");
    var precio = document.getElementsByName("boxPrcItem[]");
    var descPorcDet = document.getElementsByName("boxDescuentoPct[]");
    var recPorDet = document.getElementsByName("boxRecargoPct[]");
    var tipoDescRecDet = document.getElementsByName("ddlTipoRecDesc");
    var subTotalProd = 0;
    var neto = 0;
    var exento = 0;
    var total = 0;
    var montoTotalDescRec = 0;
    var descRecGlobal = document.getElementsByName("boxValorDR[]"); 
    var tipoValorRecDesc = document.getElementsByName("DDLSelectTpoValor[]"); 
    var tipoDescRecGlobal = document.getElementsByName("DDLSelectTpoMov[]"); 
    /*
    * Comento esto porque se estaba haciend el calculo 2 veces
    var flete = document.getElementById("MntFlete").value;
    var seguro = document.getElementById("MntSeguro").value;
    */

    //Para cada detalle calcula su respectivo SubTotal
    for (var i = 0; i < cantidad.length; i++)
    {
        if (cantidad[i] != null && cantidad[i].value != "" && precio[i].value != null && precio[i].value != "") {
            var can = parseFloat(cantidad[i].value);
            var pre = parseFloat(precio[i].value);
            var montoDescDet = 0;
            var montoRecDet = 0;

            subTotalProd = can * pre;
            //Decuento por detalle
            if (tipoDescRecDet[i].value == "D")
            {
                if (descPorcDet[i] != null && descPorcDet[i].value != "") 
                {                
                    if (!isNaN(descPorcDet[i].value)) 
                    {
                        var desc = parseFloat(descPorcDet[i].value);
                        montoDescDet = subTotalProd * (desc / 100); 
                        subTotalProd = subTotalProd - (subTotalProd * (desc / 100));                                                      
                    }
                    else
                    {
                        montoDescDet = 0;
                        document.getElementsByName("boxDescuentoPct[]")[i].value = 0;
                    }
                }
                else
                {
                    montoDescDet = 0;
                    document.getElementsByName("boxDescuentoPct[]")[i].value = 0;
                }
            } 
            else if (tipoDescRecDet[i].value == "R")
            {
                if (recPorDet[i] != null && recPorDet[i].value != "") 
                {
                    if (!isNaN(recPorDet[i].value)) 
                    {
                        var rec = parseFloat(recPorDet[i].value);
                        montoRecDet = subTotalProd * (rec / 100);          
                        subTotalProd = subTotalProd + (subTotalProd * (rec / 100));                                                
                    } 
                    else
                    {
                        montoRecDet = 0;
                        document.getElementsByName("boxRecargoPct[]")[i].value = 0;
                    }                  
                }  
                else
                {
                    montoRecDet = 0;
                    document.getElementsByName("boxRecargoPct[]")[i].value = 0;
                }
            }

            if (isNaN(subTotalProd)) 
            {
                document.getElementsByName("boxMontoItem[]")[i].value = 0;
            }
            else 
            {
                document.getElementsByName("boxMontoItem[]")[i].value = subTotalProd;
                document.getElementsByName("boxDescuentoMonto[]")[i].value = montoDescDet;
                document.getElementsByName("boxRecargoMonto[]")[i].value = montoRecDet;               
                neto = neto + subTotalProd;
            }
        }
    }

    /*
    * Comento esto porque se estaba haciend el calculo 2 veces
    if (flete != null && flete != "") 
    {
        if (!isNaN(flete)) 
        {
            var fl = parseFloat(flete);
            neto = neto + fl;
        }
    }

    if (seguro != null && seguro != "") 
    {
        if (!isNaN(seguro)) 
        {
            var sg = parseFloat(seguro);
            neto = neto + sg;
        }
    }
    */
    exento = neto;

    //Para cada Recargo o Descuento lo añade al Exento
    for (var i = 0; i < descRecGlobal.length; i++)
    {
        if (descRecGlobal[i].value != null && descRecGlobal[i].value != "") {
            var descRecG = parseFloat(descRecGlobal[i].value);
            var montoDescRec = 0;            

            //Monto por Recargo / Descuento

            //Obtener el monto según tipo de valor
            if (tipoValorRecDesc[i].value != null && tipoValorRecDesc[i].value != "") 
            {

                if (tipoValorRecDesc[i].value == "%")
                {                    
                    montoDescRec = (exento * (descRecG / 100));
                }
                if (tipoValorRecDesc[i].value== "$")
                {
                    montoDescRec = descRecG;
                }  
            }
            //Sumar o restar el monto
            if(tipoDescRecGlobal[i].value != null && tipoDescRecGlobal[i].value != "")
            {
                if (tipoDescRecGlobal[i].value == "R")
                {                    
                    montoTotalDescRec = montoTotalDescRec + montoDescRec;
                }
                if (tipoDescRecGlobal[i].value== "D")
                {
                    montoTotalDescRec = montoTotalDescRec - montoDescRec;
                }        
            }            
        }
    }

    exento = exento + montoTotalDescRec; 
    total = exento;

    //Asigna el subtotal al input
    if (isNaN(neto) || isNaN(exento) || isNaN(total)) 
    {
        document.getElementById("txtSubTotal").value = 0;
        document.getElementById("MntExe").value = 0;
        document.getElementById("MntTotal").value = 0;
    }
    else 
    {
        document.getElementById("txtSubTotal").value = neto;
        document.getElementById("MntExe").value = exento;
        document.getElementById("MntTotal").value = total;
    }
}


//AGREGAR LINEA DE DETALLE
function agregarLineaDet() {
    var tabla = document.getElementById("tablaDetalle");
    var i = (document.getElementsByName("boxNmbItem[]").length) + 1;
    var row1 = tabla.insertRow(i);

    var cell1 = row1.insertCell(0);
    var cell2 = row1.insertCell(1);
    var cell3 = row1.insertCell(2);
    var cell4 = row1.insertCell(3);
    var cell5 = row1.insertCell(4);
    var cell6 = row1.insertCell(5);
    var cell7 = row1.insertCell(6);
    var cell8 = row1.insertCell(7);
    var cell9 = row1.insertCell(8);

    cell1.innerHTML = '<input type="text" name="boxNmbItem[]" id="boxNmbItem[]" class="js-example-basic-single js-states form-control">';
    cell2.innerHTML = '<input type="text" name="boxQtyItem[]" id="boxQtyItem[]" class="js-example-basic-single js-states form-control">';
    cell3.innerHTML = '<input type="text" name="boxUnmdItem[]" id="boxUnmdItem[]" class="js-example-basic-single js-states form-control">';
    cell4.innerHTML = '<input type="text" name="boxPrcItem[]" id="boxPrcItem[]" class="js-example-basic-single js-states form-control">';
    cell5.innerHTML = '<input type="text" name="boxDescuentoPct[]" id="boxDescuentoPct[]" class="js-example-basic-single js-states form-control" value="0">'+
                      '<input type="hidden" name="boxDescuentoMonto[]" id="boxDescuentoMonto[]">'+
                      '<input type="text" name="boxRecargoPct[]" id="boxRecargoPct[]" class="js-example-basic-single js-states form-control" value="0">'+
                      '<input type="hidden" name="boxRecargoMonto[]" id="boxRecargoMonto[]">';
    cell6.innerHTML = '<select name="ddlTipoRecDesc" id="ddlTipoRecDesc" class="js-example-basic-single js-states form-control" style="width: 56px">'+
                        '<option value="D">D</option>'+
                        '<option value="R">R</option></select>';
    cell7.innerHTML = '<input type="text" name="boxMontoItem[]" id="boxMontoItem[]" readonly="yes" class="js-example-basic-single js-states form-control">';
    cell8.innerHTML = '<select id="boxIndExe[]" name="boxIndExe[]" class="js-example-basic-single js-states form-control" style="width: 65px">'+                      
                       '<option value="1" selected="selected">SÍ</option>'+
                       '<option value="0">NO</option></select>';
    cell9.innerHTML = '<button type="button" id="btnEliminaLineaDet" class="btn btn-primary" onclick="eliminarLineaDet(this);">-</button>';
    
    var desc = document.getElementsByName("boxDescuentoPct[]");
    var rec = document.getElementsByName("boxRecargoPct[]");
    $(desc[i-1]).show();
    $(rec[i-1]).hide();
}

function eliminarLineaDet(boton) {
    var tabla = document.getElementById("tablaDetalle");
    var filas = tabla.rows.length;
    if(filas>2)
    {
        var indice = boton.parentNode.parentNode.rowIndex;
        tabla.deleteRow(indice);
        //calcularSubTotal();
    }        
}

function agregarLineaRef() {
    var tabla = document.getElementById("tablaReferencia");

    var row1 = tabla.insertRow(tabla.rows.length);

    var cell1 = row1.insertCell(0);
    var cell2 = row1.insertCell(1);
    var cell3 = row1.insertCell(2);
    var cell4 = row1.insertCell(3);
    var cell5 = row1.insertCell(4);
    var cell6 = row1.insertCell(5);

    cell1.innerHTML = '<select name="DDLSelectTipDocRef[]" id="DDLSelectTipDocRef[]" class="js-example-basic-single js-states form-control">'+
                       '<option value="" selected>Seleccione una opci&oacute;n</option></select>';
    cell2.innerHTML = '<input type="text" name="boxFolioRef[]" id="boxFolioRef[]" class="js-example-basic-single js-states form-control">';
    cell3.innerHTML = '<input type="date" name="boxFchRef[]" id="boxFchRef[]" class="js-example-basic-single js-states form-control">';
    cell4.innerHTML = '<select name="DDLCodRef[]" id="DDLCodRef[]" class="js-example-basic-single js-states form-control">'+
                       '<option value="" selected>Seleccione una opci&oacute;n</option></select>';
    cell5.innerHTML = '<input type="text" name="boxRazonRef[]" id="boxRazonRef[]" class="js-example-basic-single js-states form-control">';
    cell6.innerHTML = '<button type="button" id="btnEliminaLineaRef" class="btn btn-primary" onclick="eliminarLineaRef(this);">-</button>';
}

//ELIMINAR LÍNEA DE REFERENCIA
function eliminarLineaRef(boton) {
    var tabla = document.getElementById("tablaReferencia");
    var filas = tabla.rows.length;
    if(filas>2)
    {
        var indice = boton.parentNode.parentNode.rowIndex;
        tabla.deleteRow(indice);
    }    
}

//AGREGAR LINEA DE RECARGOS Y DESCUENTOS GLOBALES
function agregarLineaRecDesc() {
    var tabla = document.getElementById("tablaRecDesc");

    var row1 = tabla.insertRow(tabla.rows.length);

    var cell1 = row1.insertCell(0);
    var cell2 = row1.insertCell(1);
    var cell3 = row1.insertCell(2);
    var cell4 = row1.insertCell(3);
    var cell5 = row1.insertCell(4);
    var cell6 = row1.insertCell(5);

    cell1.innerHTML = '<select name="DDLSelectTpoMov[]" id="DDLSelectTpoMov[]" class="js-example-basic-single js-states form-control">'+
                      '<option value="" selected>Seleccione una opci&oacute;n</option>'+
                      '<option value="D">DESCUENTO</option>'+
                      '<option value="R">RECARGO</option></select>';
    cell2.innerHTML = '<input type="text" name="boxGlosaDR[]" id="boxGlosaDR[]" class="js-example-basic-single js-states form-control">';
    cell3.innerHTML = '<input type="number" step="0.01" name="boxValorDR[]" id="boxValorDR[]" class="js-example-basic-single js-states form-control">';
    cell4.innerHTML = '<select id="DDLSelectTpoValor[]" name="DDLSelectTpoValor[]" class="js-example-basic-single js-states form-control">'+
                       '<option value="%" selected="selected">%</option>'+
                       '<option value="$">$</option></select>';
    cell5.innerHTML = '<select id="DDLSelectIndExeDR[]" name="DDLSelectIndExeDR[]" class="js-example-basic-single js-states form-control">'+
                        '<option value="1" selected="selected">SÍ</option>'+
                        '<option value="0">NO</option></select>';
    cell6.innerHTML = '<button type="button" id="btnEliminaLineaRecDesc" class="btn btn-primary" onclick="eliminarLineaRecDesc(this);">-</button>';
}

//ELIMINAR LÍNEA DE RECARGOS Y DESCUENTOS GLOBALES
function eliminarLineaRecDesc(boton) {
    var tabla = document.getElementById("tablaRecDesc");
    var filas = tabla.rows.length;
    //if(filas>2)
    //{
        var indice = boton.parentNode.parentNode.rowIndex;
        tabla.deleteRow(indice);
    //}    
}