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
    $(document).on("change", "[name='CodPtoEmbarque']", function()
    {
        var id = $('#CodPtoEmbarque').find(":selected").text();
        var idList = id.split("-");
        id = idList[0].trim();   
        $("#IdAdicPtoEmb").val(id);
    });

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

    var tiposDoc = ["BOLETA ELECTRÓNICA",  "BOLETA NO AFECTA O EXENTA ELECTRÓNICA"];
    var codTiposDoc = ["39", "41"];


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


//VALIDAR TIPO DE DATOS
function validarDatos() {

    //Datos Documento
    var fechaEmision = document.getElementById("FchEmis").value;
    var indServicio = document.getElementById("IndServicio").value;
    var tipoDocumento = document.getElementById("TipoDTE").value;
    //Datos Emisor
    var razonEmi = document.getElementById("RznSocEmi").value;
    var comunaEmi = document.getElementById("ComunaEmi").value;
    var emailEmi = document.getElementById("EmailEmi").value;
    var giroEmi = document.getElementById("GiroEmi").value;
    var direccionEmi = document.getElementById("DirEmi").value;
    var ciudadEmi = document.getElementById("CiudadEmi").value;
    var telefonoEmi = document.getElementById("TelefonoEmi").value;
    var actEcoEmi = document.getElementById("Acteco").value;

    //Datos Receptor
    var numIdRec = document.getElementById("RUTRecep").value;
    var giroRec = document.getElementById("GiroRecep").value;
    var razonRec = document.getElementById("RznSocRecep").value;
    var direccionRec = document.getElementById("DirRecep").value;
   
    //Valores
    var subTotal = document.getElementById("txtSubTotal").value;
    var montoExento = document.getElementById("MntExe").value;
    var total = document.getElementById("MntTotal").value;

    //Detalles  
    var nomProducto = document.getElementsByName("boxNmbItem[]");
    var cantidad = document.getElementsByName("boxQtyItem[]");
    var unidad = document.getElementsByName("boxUnmdItem[]");
    var precio = document.getElementsByName("boxPrcItem[]");
    var descPorcDet = document.getElementsByName("boxDescuentoPct[]");
    var descMontoDet = document.getElementsByName("boxDescuentoMonto[]");
    var recPorcDet = document.getElementsByName("boxRecargoPct[]");
    var recMontoDet = document.getElementsByName("boxRecargoMonto[]");
    var subTotalProd = document.getElementsByName("boxMontoItem[]");
    var tipoDescRecDet = document.getElementsByName("ddlTipoRecDesc");

    //Datos Documento  
   
    if (fechaEmision == null) {
        bootbox.alert("Seleccione la fecha de emisión");
        return false;
    }
    if (tipoDocumento == ("")) {
        bootbox.alert("Seleccione el tipo de documento a emitir");
        return false;
    }
   
    //Detalles

    //Debe existir al menos un detalle
    if(nomProducto[0].value.length<1 && cantidad[0].value.length<1 && unidad[0].value.length<1 && precio[0].value.length<1)
    {
        bootbox.alert("Debe ingresar al menos un detalle");
        return false;
    }

    //Si se ha ingresado un datos, debe ingresar los demás que son obligatorios
    for (var i = 0; i < nomProducto.length; i++) 
    {

        if(nomProducto[i].value.length>0 || cantidad[i].value.length>0 || unidad[i].value.length>0 || precio[i].value.length>0)
        {
            if (nomProducto[i].value.length < 1) {
                bootbox.alert("Ingrese el nombre del producto de la línea " + (i + 1));
            return false;
            }
            if (cantidad[i].value.length < 1) {
                bootbox.alert("Ingrese la cantidad de producto de la línea " + (i + 1));
                return false;
            }
            
            if (precio[i].value.length < 1) {
                bootbox.alert("Ingrese el precio del producto de la línea " + (i + 1));
                return false;
            }

            if (isNaN(cantidad[i].value)) {
                bootbox.alert("La cantidad de producto de la línea " + (i + 1) + " debe ser un número");
                return false;
            }
            else  if(cantidad[i].value < 0)
            {
                bootbox.alert("La cantidad de producto de la línea " + (i + 1) + " debe ser un número positivo");
                return false;
            }
    
            if (isNaN(precio[i].value)) {
                bootbox.alert("El precio del producto de la línea " + (i + 1) + " debe ser un número");
                return false;
            }
            else  if(precio[i].value < 0)
            {
                bootbox.alert("El precio del producto de la línea " + (i + 1) + " debe ser un número positivo");
                return false;
            }

            if(tipoDescRecDet[i].value == "D")
            {
                if (descPorcDet[i].value.length > 0) 
                {
                    if(isNaN(descPorcDet[i].value))
                    {
                        bootbox.alert("El descuento de la línea " + (i + 1) + " debe ser un número");
                        return false;
                    }
                    else  if(descPorcDet[i].value < 0)
                    {
                        bootbox.alert("El descuento de la línea " + (i + 1) + " debe ser un número positivo");
                        return false;
                    }
                    else  if(descPorcDet[i].value >=100)
                    {
                        bootbox.alert("El descuento de la línea " + (i + 1) + " debe estar en el rango de 0 a 100");
                        return false;
                    }
                }  
            }    

            if(tipoDescRecDet[i].value == "R")
            {
                if (recPorcDet[i].value.length > 0) 
                {
                    if(isNaN(recPorcDet[i].value))
                    {
                        bootbox.alert("El recargo de la línea " + (i + 1) + " debe ser un número");
                        return false;
                    }
                    else  if(recPorcDet[i].value < 0)
                    {
                        bootbox.alert("El recargo de la línea " + (i + 1) + " debe ser un número positivo");
                        return false;
                    }
                    else  if(recPorcDet[i].value >=100)
                    {
                        bootbox.alert("El recargo de la línea " + (i + 1) + " debe estar en el rango de 0 a 100");
                        return false;
                    }
                }  
            }   

        }        
    }

     //Descuentos/Recargos Globales
    for (var i = 0; i < tipoMov.length; i++) 
    {
        if(tipoMov[i].value!="" || valorDR[i].value.length>0)
        {
            if (tipoMov[i].value=="") {
                bootbox.alert("Seleccione el tipo de movimiento del descuento o recargo de la línea " + (i + 1));
                return false;
            }
            if (valorDR[i].value.length < 1) {
                bootbox.alert("Ingrese el valor del descuento o recargo de la línea " + (i + 1));
                return false;
            }
            if(isNaN(valorDR[i].value))
            {
                bootbox.alert("El valor del descuento o recargo de la línea " + (i + 1) + " debe ser un número");
                return false;
            }
            else  if(valorDR[i].value <= 0)
            {
                bootbox.alert("El valor del descuento o recargo de la línea " + (i + 1) + " debe ser un número mayor a cero");
                return false;
            }
        }
    }   
    return true;
}

//LIMPIAR CAMPOS
function limpiar() {
   
    document.getElementById("FchEmis").value = "";

    document.getElementById("RUTRecep").value = "";
    document.getElementById("GiroRecep").value = "";
    document.getElementById("Nacionalidad").value = "";
    document.getElementById("RznSocRecep").value = "";
    document.getElementById("DirRecep").value = "";

    var nombre = document.getElementsByName("boxNmbItem[]");
    var cantidad = document.getElementsByName("boxQtyItem[]");
    var unidad = document.getElementsByName("boxUnmdItem[]");
    var precio = document.getElementsByName("boxPrcItem[]");
    var dscPctDet = document.getElementsByName("boxDescuentoPct[]");
    var descMontoDet = document.getElementsByName("boxDescuentoMonto[]");
    var recPctDet = document.getElementsByName("boxRecargoPct[]");
    var recMontoDet = document.getElementsByName("boxRecargoMonto[]");
    var tpoDescRedDet = document.getElementsByName("ddlTipoRecDesc");
    var subtotal = document.getElementsByName("boxMontoItem[]");
    var exeDet =  document.getElementsByName("boxIndExe[]");

    for (var i = 0; i < nombre.length; i++) {
        nombre[i].value = "";
        cantidad[i].value = "";
        unidad[i].value = "";
        precio[i].value = "";
        dscPctDet[i].value = "";
        descMontoDet[i].value = "";
        recPctDet[i].value = "";
        recMontoDet[i].value = "";
        tpoDescRedDet[i].selectedIndex = 0;
        subtotal[i].value = "";
        exeDet[i].selectedIndex = 0;
    }

    document.getElementById("txtSubTotal").value = "";
    document.getElementById("MntExe").value = "";
    document.getElementById("MntTotal").value = "";
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