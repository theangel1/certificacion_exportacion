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
        $("#IndServicio").val(data.ind_servicio);
        //Datos del receptor
        $("#RUTRecep").val(data.razon_social);
        $("#RznSocRecep").val(data.razon_social);
        $("#GiroRecep").val(data.giro);
        $("#DirRecep").val(data.direccion);
        //Totales
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

        jQuery.ajax({
            type: "POST",
            url: '../ajax/arreglosBoleta.php',
            data: {
                ind_servicio: data.ind_servicio
            },
            success: function (data)
            {
                data = JSON.parse(data);
                $("#IndServicioText").val(data[0]);          
            }
        });

        console.log("Datos cargados");
        //bootbox.alert("Datos Cargados");

    })
}

$( window ).on( "load", function() 
{
    poblarSelect();
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
    $(document).on("change", "[name='boxIndExe[]']", calcularSubTotal);
    $(document).on("focusout", "input[name='boxDescuentoPct[]']", calcularSubTotal);
    $(document).on("focusout", "input[name='boxRecargoPct[]']", calcularSubTotal);
    $("input[name='boxDescuentoPct[]']").show();
    $("input[name='boxRecargoPct[]']").hide();

    //Asignar valor a textbox ocultos

    $(document).on("change",  "[name='FchEmis']", function()
    {
        $("#PeriodoDesde").val($("#FchEmis").val());
        $("#PeriodoHasta").val($("#FchEmis").val());
        $("#FchVenc").val($("#FchEmis").val());
    });

    $(document).on("change",  "[name='TipoDTE']", function()
    {
        cambiarTipoDTE();        
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


function cambiarTipoDTE()
{
    var exentos = document.getElementsByName("boxIndExe[]");
    for (var i = 0; i < exentos.length; i++)
    {
        if($("#TipoDTE").val()==41)
        {
            $(exentos[i]).val(1);
            $(exentos[i]).attr("disabled", true); 
        }
        if($("#TipoDTE").val()==39)
        {
            $(exentos[i]).attr("disabled", false); 
        }
    }
    calcularSubTotal();
}

//POBLAR LOS SELECT
function poblarSelect() {   

    //Tipo Documento
    var select = document.formBoleta.TipoDTE;
        
    var tiposDoc = ["BOLETA ELECTRÓNICA",  "BOLETA NO AFECTA O EXENTA ELECTRÓNICA", "NOTA DE DÉBITO ELECTRÓNICA",
    "NOTA DE CRÉDITO ELECTRÓNICA"];
    var codTiposDoc = ["39", "41", "56", "61"];


    for (i = 0; i < tiposDoc.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = tiposDoc[i];
        option.value = codTiposDoc[i];
        select.append(option);
    }

    //Indicador de servicio
    var select = document.formBoleta.IndServicio;

    var indServicio = ["BOLETAS DE SERVICIOS PERIÓDICOS", "BOLETAS DE SERVICIOS PERIÓDICOS DOMICILIARIOS", 
                 "BOLETAS DE VENTA Y SERVICIOS", "BOLETA DE ESPECTÁCULO EMITIDA POR CUENTA DE TERCEROS"];
    var codIndServicio = ["1", "2", "3", "4"]; 

    for (i = 0; i < indServicio.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = indServicio[i];
        option.value = codIndServicio[i];
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
    var indExento = document.getElementsByName("boxIndExe[]"); 
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
                if(indExento[i].value == 1)
                {
                    exento = exento + subTotalProd;
                }
            }
        }
        
    }

     //Para cada Recargo o Descuento lo añade al Neto
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
                    montoDescRec = (neto * (descRecG / 100));
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
    neto = neto + montoTotalDescRec
    total = neto;

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
    cell2.innerHTML = '<input type="text" name="boxQtyItem[]" id="boxQtyItem[]" step="0.01" class="js-example-basic-single js-states form-control">';
    cell3.innerHTML = '<input type="text" name="boxUnmdItem[]" id="boxUnmdItem[]" class="js-example-basic-single js-states form-control">';
    cell4.innerHTML = '<input type="text" name="boxPrcItem[]" id="boxPrcItem[]" step="0.01" class="js-example-basic-single js-states form-control">';
    cell5.innerHTML = '<input type="text" name="boxDescuentoPct[]" id="boxDescuentoPct[]" step="0.01" class="js-example-basic-single js-states form-control" value="0">'+
                      '<input type="hidden" name="boxDescuentoMonto[]" id="boxDescuentoMonto[]" step="0.01">'+
                      '<input type="text" name="boxRecargoPct[]" id="boxRecargoPct[]"  step="0.01"class="js-example-basic-single js-states form-control" value="0">'+
                      '<input type="hidden" name="boxRecargoMonto[]" id="boxRecargoMonto[]" step="0.01">';
    cell6.innerHTML = '<select name="ddlTipoRecDesc" id="ddlTipoRecDesc" class="js-example-basic-single js-states form-control" style="width: 56px">'+
                        '<option value="D">D</option>'+
                        '<option value="R">R</option></select>';
    cell7.innerHTML = '<input type="text" name="boxMontoItem[]" id="boxMontoItem[]" readonly="yes" step="0.01" class="js-example-basic-single js-states form-control">';
    cell8.innerHTML = '<select id="boxIndExe[]" name="boxIndExe[]" class="js-example-basic-single js-states form-control" style="width: 66px">'+                      
                       '<option value="0" selected="selected">NO</option>'+
                       '<option value="1">SÍ</option></select>';
    cell9.innerHTML = '<button type="button" id="btnEliminaLineaDet" class="btn btn-primary" onclick="eliminarLineaDet(this);">-</button>';
    
    var desc = document.getElementsByName("boxDescuentoPct[]");
    var rec = document.getElementsByName("boxRecargoPct[]");
    $(desc[i-1]).show();
    $(rec[i-1]).hide();
    cambiarTipoDTE()
}

//ELIMINAR LÍNEA DE DETALLE
function eliminarLineaDet(boton) {
    var tabla = document.getElementById("tablaDetalle");
    var filas = tabla.rows.length;
    if(filas>2)
    {
        var indice = boton.parentNode.parentNode.rowIndex;
        tabla.deleteRow(indice);        
    }   
    calcularSubTotal();     
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
    cell4.innerHTML = '<select id="DDLSelectTpoValor[]" name="DDLSelectTpoValor[]" class="js-example-basic-single js-states form-control"  style="width: 59px;">'+
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
    if(filas>2)
    {
        var indice = boton.parentNode.parentNode.rowIndex;
        tabla.deleteRow(indice);
    }  
    calcularSubTotal();  
}