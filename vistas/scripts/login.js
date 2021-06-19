$("#btn-login").click(function(){  
    let paramEmail = $('#login-username').val();
    let paramPassword = $('#login-password').val();;
    $.ajax({        
        url :'../ajax/usuario.php?op=validaUser',
        datatype :"json",
        type :'POST',
        data :{email : paramEmail , password : paramPassword},        
        error: function(a,b,c){
            bootbox.alert("Opps, te pedimos disculpas pero por el momento no se pudo realizar el registro.<br>Por favor intenta nuevamente mas tarde");
        },
        success: function(ajaxResponse){
            var xJason = procesarRespuesta(ajaxResponse);
            for (var idx in xJason){
                data = xJason[idx];
                if(data.error == 1){
                    bootbox.alert(data.msg);
                }else if(data.error==2){
                    $("#ingreso").submit();
                }
            }
        }
        
    });
});

$("#btn-clave").click(function(){
    $('#credencialModal').modal('show');
});

$("#btnSolicitar").click(function(){   
    let paramRut = $('#txtRut').val();    
    $.ajax({
        
        url :'../ajax/usuario.php?op=solicita',
        datatype :"json",
        type :'POST',
        data :{rut : paramRut},        
        error: function(){            
            bootbox.alert("Opps, te pedimos disculpas pero por el momento no se pudo realizar el registro.<br>Por favor intenta nuevamente mas tarde");
        },
        success: function(response){              
            bootbox.alert("Hemos enviado un Email a "+ response +" con las credenciales de acceso.");
            $('#credencialModal').modal('hide');                                   
            },
        error : function()
        {
            bootbox.alert("No se ha podido obtener la información, comuniquese con Soporte Técnico.");
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