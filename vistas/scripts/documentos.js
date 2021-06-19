$(document).ready(function() {
    var tabla = $('#tablaDocumento').DataTable( {
    "ajax": '../modelos/dataTable.php?func=Emitidos&par=',
    "orderFixed": [[ 5, "desc" ]],
    "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 2 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 11 ],//12-13
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 12 ],
                "visible": false,
                "searchable": false
            }
        ]
} );

$('#fecVencimiento').datepicker({
    autoclose: true,
    todayHighlight: true,
    language: 'es'
});

$(document).on("click",".btn",function(){
    if( $(this).closest("tr").hasClass('active')){
         $(this).closest("tr").removeClass('active');
    }else{
      tabla.$('tr.active').removeClass('active');
       $(this).closest("tr").addClass('active');
    }
     $(this).closest("tr").closest("tr").addClass('active');
});

$('#cesionModal').on('shown.bs.modal', function() {
    dataRow= tabla.row('.active').data();
    $("#numMonto").val(dataRow[12]);
    $("#numDoc").val(dataRow[5]);
    $("#numDte").val(dataRow[5]);
    $("#tipoDte").val(dataRow[0]);
    $("#fecDte").val(dataRow[6]);
    $("#RutRecep").val(dataRow[11]);
    $("#Receptor").val(dataRow[3]);
});


$('#reenvioModal').on('shown.bs.modal', function() {
    dataRow= tabla.row('.active').data();
    $("#trackid").val(dataRow[7]);
});

$('#trazaModal').on('shown.bs.modal',function(){
    dataRow= tabla.row('.active').data()
    $('#tablaTraza').DataTable( {
    "ajax": '../modelos/dataTable.php?func=Traza&par='+dataRow[0]+','+dataRow[5],
    "orderFixed": [[ 1, "desc" ]],
    "columnDefs": [
            {
                "targets": [ 0 ],
                "searchable": true
            }
        ]
    });
});

$("#btnCederDocumento").click(function(){
    $("#btnCederDocumento").prop('disabled',true);
   
    $.ajax({
        url :'../ajax/Respuestas.php',
        datatype :"json",
        type :'POST',
        data :"func=Cesion&par='"+$("#tipoDte").val()+"','"+$("#numDte").val()+"','"+$("#txtRutCesionario").val()
             +"','"+$("#txtRazonCesionario").val()+"','"+$("#txtDireccionCesionario").val()+"','"+$("#txtMailCesionario").val()
             +"','"+$("#numMonto").val()+"','"+$("#fecVencimiento").val()+"','"+$("#fecDte").val()+"','"+$("#RutRecep").val()
             +"','"+$("#Receptor").val()+"'",
        error: function(a,b,c){
            alert(a+" "+b+" +"+c)
        },
        success: function(ajaxResponse){
            var xJason = procesarRespuesta(ajaxResponse);
            for (var idx in xJason){
                data = xJason[idx];
                
                if(data.ERROR == 0){
                     bootbox.alert(data.MENSAJE, function(){
                         location.reload();
                     });
                }else{
                    bootbox.alert(data.MENSAJE,function(){
                        $("#btnCederDocumento").prop('disabled',false);
                    });
                }
            }
        }
    });
});

$("#txtRutCesionario").blur(function(){
    $.ajax({
        url :'../ajax/Respuestas.php',
        datatype :"json",
        type :'POST',
        data :"func=BuscaRegistro&par='"+$("#txtRutCesionario").val()+"'",
        error: function(a,b,c){
            alert(a+" "+b+" +"+c)
        },
        success: function(ajaxResponse){
            var xJason = procesarRespuesta(ajaxResponse);
            for (var idx in xJason){
                data = xJason[idx];
                
                if(data.ERROR == 0){
                    $("#txtRazonCesionario").val(data.RAZON)
                    $("#txtDireccionCesionario").val(data.DIRECCION)
                    $("#txtMailCesionario").val(data.EMAIL)
                }
            }
        }
    });
});

$("#btnReenvioDocumento").click(function(){
    $.ajax({
        url :'../ajax/verificaDte.php',
        datatype :"json",
        type :'POST',
        data :"trackid="+$("#trackid").val(),
        error: function(a,b,c){
            alert(a+" "+b+" +"+c)
        },
        success: function(ajaxResponse){
            var xJason = procesarRespuesta(ajaxResponse);
            for (var idx in xJason){
                data = xJason[idx];
                
                if(data.ERROR == 0){
                     bootbox.alert(data.MENSAJE, function(){
                         location.reload();
                     });
                }else{
                    bootbox.alert(data.MENSAJE,function(){
                        //$("#reenvioModal").close();
                        location.reload();
                    });
                }
            }
        }
    });
});
$(document).on("click","#btn-pdf-venta",function(){
   $.redirect("viewPdf.php",{'tipo':$(this).data("tipo"),'ndoc':$(this).data("folio"),'f':$(this).data("file")},'POST','pdfView');  
});
$(document).on("click","#btn-pdf-exp",function(){
    $.redirect("ViewExportacion.php",{'tipo':$(this).data("tipo"),'ndoc':$(this).data("folio"),'f':$(this).data("file")},'POST','pdfView');  
 });
});

$(document).on("click","#btn-down-xml",function(){
    $.redirect("../ajax/descargaXml.php",
    {'carpeta':$(this).data('carpeta'),'tipo':$(this).data('tipo'),'folio':$(this).data('folio')},'POST','wDteD')
});
$(document).on("click","#btn-down-xml-emitido",function(){
    $.redirect("../ajax/descargaXmlEmitido.php",
    {'carpeta':$(this).data('carpeta'),'tipo':$(this).data('tipo'),'folio':$(this).data('folio')},'POST','wDteD')
});

function procesarRespuesta(ajaxResponse){ 
    // observa que aquí asumimos que el resultado es un objeto 
    // serializado en JSON, razón por la cual tomamos este dato
    // y lo procesamos para recuperar un objeto que podamos
    // manejar fácilmente
    if (typeof ajaxResponse == "string")
        ajaxResponse = $.parseJSON(ajaxResponse);
    return ajaxResponse;
}