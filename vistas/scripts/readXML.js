var galleryUploader = new qq.FineUploader({
    element: document.getElementById("fine-uploader-gallery"),
    template: 'qq-template-gallery',
    request: {
        endpoint: '../vendor/fine-uploader/servidor/endpoint.php'
    },
    thumbnails: {
        placeholders: {
            waitingPath: '../vendor/fine-uploader/node_modules/fine-uploader/all.fine-uploader/placeholders/waiting-generic.png',
            notAvailablePath: '../vendor/fine-uploader/node_modules/fine-uploader/all.fine-uploader/placeholders/not_available-generic.png'
        }
    },
    validation: {
        allowedExtensions: ['xml'],
        itemLimit: 1
    },
    callbacks:
    {
        onSubmit: function(id, name) {
            this.setUuid(id, "")
            console.log("onSubmit called")
        },

        onAllComplete: function(succeeded, failed) {
            if (failed.length > 0) {
                alert("Error: algunos archivos no fueron cargados");
            } else {
                if (succeeded.length > 0 ) {
                }
            }

            //OBTENER NOMBRE DE ARCHIVOS                                        
            for (var id in succeeded) 
            {

                $.ajax({
                 url: '../ajax/leer_xml.php',
                 type: "POST",
                 data: ({nombre_archivo: this.getName(id)}),
                 success: function(data){
                        if(data == "")
                        {
                            alert("XML incorrecto. Vuelva a cargar los archivos");
                            $("#folios").text("");
                            $("#info").attr("src","");
                        }
                        else
                        {
                            alert("Folios cargados exitosamente");
                            if(data.length<100)
                            { 
                                $("#folios").text("Se han cargado los folios del "+data);
                                $("#info").attr("src","../img/info.png");
                            }                                                       
                        }                                                   
                    }
                });  
            }
            this.reset(); 
        }
    }
});