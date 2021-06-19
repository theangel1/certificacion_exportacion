<?php

function connectDB()
{   
    return new mysqli("sisgenchile.com","sisgenchile_dbmanager","--d5!RWN[LIm","sisgenchile_sisgenfe");
}
function dbExportacion()
{    
    return new mysqli("netdte.cl","netdte_administrador","G(8r3,ru{]bx","netdte_dbexportacion");
}
function dbCertificacion()
{
    return new mysqli("netdte.cl","netdte_administrador","G(8r3,ru{]bx","netdte_certificacion");
}

