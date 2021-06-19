<?php

$xmlv = new DOMDocument(); 
$xmlv->load($argv[1]);
print_r($xmlv);

if (!$xmlv->schemaValidate($argv[2])) {
	libxml_display_errors();
	exit("ERROR DE VALIDACION");
}else{
	exit("VALIDADO");
}

function libxml_display_errors() {
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
		echo libxml_display_error($error);
    }
    libxml_clear_errors();
}

function libxml_display_error($error){
    
	$return = "|";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "$error->code|";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "$error->code|";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "$error->code|";
            break;
    }
    $return .= trim($error->message);
    if ($error->file) {
        $return .=    " en el archivo $error->file";
    }
    $return .= " en la linea $error->line\n";
	
    return $return;
}
