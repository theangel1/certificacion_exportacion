var tabla = $('#tablaAcuse').DataTable( {
    "ajax": '../modelos/dataTable.php?func=Respuesta&par=',
    "orderFixed": [[ 0, "desc" ]],
    "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 1 ],
                "searchable": true
            },
            {
                "targets": [ 2 ],
                "searchable": true
            },
            {
                "targets": [ 3 ],
                "searchable": true
            },
            {
                "targets": [ 4 ],
                "searchable": true
            },
            {
                "targets": [ 5 ],
                "searchable": true
            },
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 7 ],
                "visible": false,
                "searchable": false
            },
    ]
    
} );

$(document).on("click",".btn",function(){
    if( $(this).closest("tr").hasClass('active')){
         $(this).closest("tr").removeClass('active');
    }else{
      tabla.$('tr.active').removeClass('active');
       $(this).closest("tr").addClass('active');
    }
     $(this).closest("tr").closest("tr").addClass('active');
});

$('#acuseModal').on('shown.bs.modal', function() {
    dataRow= tabla.row('.active').data()
    
    $("#txtEmisor").html(dataRow[1]);
    $("#txtFolio").html(dataRow[3]);
    $("#txtEmiion").html(dataRow[4]);
    $("#txtMonto").html(dataRow[5]);
    
    $("#numDte").val(dataRow[3]);
    $("#tipoDte").val(dataRow[6]);
    $("#rutDte").val(dataRow[7]);

});

$(document).on('change','#respuesta',function(){
    $.ajax({
        url :'../ajax/Respuestas.php',
        datatype :"json",
        type :'POST',
        data :"func=Respuesta&par="+$(this).val(),
        error: function(a,b,c){
            alert(a+" "+b+" +"+c)
        },
        success: function(ajaxResponse){
            var xJason = procesarRespuesta(ajaxResponse);
            for (var idx in xJason){
                data = xJason[idx];
                
                if(data.ERROR == 0){
                    tabla.ajax.reload();
                }else{
                    bootbox.alert(data.MENSAJE);
                }
            }
        }
    });
    
});

$("#btnAcuseDocumento").click(function(){
    //$("#btnAcuseDocumento").prop('disabled',true);
    $.ajax({
        url :'../ajax/Respuestas.php',
        datatype :"json",
        type :'POST',
        data :"func=Respuesta&par='"+$("#tipoDte").val()+"','"+$("#numDte").val()+"','"+$("#rutDte").val()
             +"','"+$("#tipoResp").val()+"','"+$("#motivo").val()+"'",
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
                        $("#btnAcuseDocumento").prop('disabled',false);
                    });
                }
            }
        }
    });
});

$(document).on("click","#btn-pdf",function(){
   $.redirect('viewPdf.php',{'tipo':$(this).data("tipo"),'ndoc':$(this).data("folio"),'f':$(this).data("file")},'POST','pdfView'); 
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