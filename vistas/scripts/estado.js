$('#btn-consultar').click(function(){
    $.ajax({
        url :'../ajax/estadoDte.php',
        datatype :"html",
        type :'POST',
        async: true,
        data :$("#formConsulta").serialize(),
        error: function(a,b,c){
            alert(a+" "+b+" +"+c)
        },
        success: function(ajaxResponse){
            $("#respuesta").html(ajaxResponse);
        }
    }); 
});

$('#btn-consultarAec').click(function(){
    $.ajax({
        url :'../ajax/estadoAec.php',
        datatype :"html",
        type :'POST',
        async: true,
        data :$("#formConsultaAec").serialize(),
        error: function(a,b,c){
            alert(a+" "+b+" +"+c)
        },
        success: function(ajaxResponse){
            $("#respuesta").html(ajaxResponse);
        }
    }); 
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