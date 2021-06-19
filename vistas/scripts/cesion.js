$(document).ready(function() {
    var tabla = $('#tablaDocumento').DataTable( {
    "ajax": '../modelos/dataTable.php?func=Cesiones&par=',
    "orderFixed": [[ 6, "desc" ]],
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
                "targets": [ 3 ],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [ 4 ],
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
        dataRow= tabla.row('.active').data()
        //aData = dataRow.split(",")
        $("#numMonto").val(dataRow[4]);
        $("#numDoc").val(dataRow[6]);
        $("#numDte").val(dataRow[6]);
        $("#tipoDte").val(dataRow[0]);

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

    $(document).on("click","#btnBorraCesion",function(){
        idf=$(this).data("idf");
        bootbox.confirm("Esta seguro de elimnar esta cesión?", function(result){ 
            if(result){
                 $.ajax({
                    url :'../modelos/cesiones.php',
                    datatype :"json",
                    type :'POST',
                    data :"func=BorraCesion&par='"+idf+"'",
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
                               bootbox.alert(data.MENSAJE);
                           }
                       }
                    }
                });
            }
        });
    });
});