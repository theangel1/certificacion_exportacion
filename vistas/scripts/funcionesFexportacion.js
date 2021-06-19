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
    //Comentado por calculo repetido
    //$(document).on("focusout", "input[name='MntFlete']", calcularSubTotal);
    //$(document).on("focusout", "input[name='MntSeguro']", calcularSubTotal);
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
   
    $(document).on("change", "[name='CodPtoDesemb']", function()
    {
        var id = $('#CodPtoDesemb').find(":selected").text();
        var idList = id.split("-");
        id = idList[0].trim();   
        $("#IdAdicPtoDesemb").val(id);
    });

    $(document).on("change", "[name='CodPaisDestin']", function()
    {
        $("#CodPaisRecep").val($("#CodPaisDestin").val());
    });

    $(document).on("focusout", "[name='CantBultos']", function()
    {
        $("#TotBultos").val($("#CantBultos").val());
    });

    //POBLA LOS SELECT DE LA REFRENCIA DESPUES DE AGREGAR LA LINEA
    $(document).on("click", "button[name='btnAgregaLineaRef']", function()
    {
        $.when(agregarLineaRef()).done( function()
        {
            poblarSelectReferencia();
        });
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

//POBLAR LOS SELECT
function poblarSelect() {   

    //Pais Destino
    var select = document.formFactura.CodPaisDestin;

    var paises = ["AFGHANISTAN", "ALBANIA", "ALEMANIA", "ANDORRA", "ANGOLA", "ANGUILA", "ANTILLAS NEERLANDESAS", 
    "ANTIGUA Y BARBUDA", "ARABIA SAUDITA", "ARGELIA", "ARGENTINA", "ARMENIA", "ARUBA", "AUSTRALIA", "AUSTRIA",
    "AZERBAIJAN", "BAHAMAS", "BAHREIN", "BANGLADESH", "BARBADOS", "BELAU", "BELARUS", "BELGICA", "BELICE", "BENIN",
    "BERMUDAS", "BOLIVIA", "BOSNIA Y HERZEGOVINA", "BOTSWANA", "BRASIL", "BRUNEI", "BULGARIA", "BURKINA FASO", "BURUNDI",
    "BUTAN", "CABO VERDE", "CAMBODIA", "CAMERUN", "CANADA", "CHAD", "CHILE", "CHIPRE", "CHINA", "COLOMBIA", "COMORAS",
    "CONGO", "COREA DEL NORTE", "COREA DEL SUR", "COSTA RICA", "COSTA DE MARFIL", "CROACIA", "CUBA", "DJIBOUTI", "DINAMARCA",
    "DOMINICA", "ECUADOR", "EGIPTO", "EL SALVADOR", "EMIRATOS ARABES UNIDOS", "ERITREA", "ESLOVENIA", "ESPAÑA", "ESTONIA",
    "ESTADOS UNIDOS DE AMÉRICA", "ETIOPIA", "FIJI", "FILIPINAS", "FINLANDIA", "FRANCIA", "GABON", "GAMBIA", "GEORGIA",
    "GHANA", "GIBRALTAR", "GRANADA", "GRECIA", "GROENLANDIA", "GUAM", "GUATEMALA", "GUERNSEY", "GUINEA", "GUINEA - BISSAU",
    "GUINEA ECUATORIAL", "GUYANA", "HAITI", "HOLANDA", "HONG KONG", "HONDURAS", "HUNGRIA", "INDIA", "INDONESIA", "IRAN",
    "IRAK", "IRLANDA", "ISLANDIA", "ISLAS CAYMAN", "ISLAS COOK", "ISLAS DE MAN", "ISLAS MALDIVAS", "ISLAS MARIANAS DEL NORTE",
    "ISLAS MARSHALL", "ISLAS SALOMON", "ISLAS TONGA", "ISLAS VIRGENES BRITANICAS", "ISLAS VIRGENES", "ISRAEL", "ITALIA",
    "JAMAICA", "JAPON", "JERSEY", "JORDANIA", "KASAJSTAN", "KENIA", "KIRGISTAN", "KIRIBATI", "KUWAIT", "LAOS", 
    "LESOTHO", "LETONIA", "LIBANO", "LIBERIA", "LIBIA", "LIECHTENSTEIN", "LITUANIA", "LUXEMBURGO", "MACAO","MACEDONIA",
    "MADAGASCAR", "MALASIA", "MALAWI", "MALI", "MALTA", "MARRUECOS", "MARTINICA", "MAURICIO", "MAURITANIA", "MEXICO",
    "MICRONESIA", "MOLDOVA", "MONACO", "MONGOLIA", "MONSERRAT", "MONTENEGRO", "MOZAMBIQUE", "MYANMAR (EX BIRMANIA)",
    "NAMIBIA", "NAURU", "NEPAL", "NICARAGUA", "NIGER", "NIGERIA", "NIUE", "NORUEGA", "NUEVA CALEDONIA", "NUEVA ZELANDIA",
    "OMAN", "PAKISTAN", "PANAMA", "PAPUA, NUEVA GUINEA", "PARAGUAY", "PERU", "POLINESIA FRANCESA", "POLONIA", "PORTUGAL",
    "PUERTO RICO", "QATAR", "REINO UNIDO", "REPUBLICA CENTRO AFRICANA", "REPUBLICA CHECA", "REPUBLICA DE SERBIA",
    "REPUBLICA DE YEMEN", "REPUBLICA DEMOCRATICA DEL CONGO", "REPUBLICA DOMINICANA", "REPUBLICA ESLOVACA", "RUMANIA",
    "RUSIA", "RWANDA", "SAHARAVI", "SAINT KITTS & NEVIS", "SAMOA OCCIDENTAL", "SAN MARINO", "SANTA SEDE",
    "SAN VICENTE Y LAS GRANADINAS", "SAO TOME Y PRINCIPE", "SANTA LUCIA (ISLAS OCCIDENTALES)", "SENEGAL", "SEYCHELLES", 
    "SIERRA LEONA", "SINGAPUR", "SIRIA", "SOMALIA", "SRI LANKA", "SWAZILANDIA", "SUDAFRICA", "SUDAN", "SUDAN DEL SUR",
    "SUECIA", "SUIZA", "SURINAM", "TAIWAN (FORMOSA)", "TANZANIA", "TADJIKISTAN", "TERRITORIO BRITÁNICO EN AFRICA",
    "TERRITORIO BRITÁNICO EN AMERICA", "TERRITORIO BRITÁNICO EN OCEANIA Y EL PACIFICO", "TERRITORIO DE DINAMARCA",
    "TERRITORIO ESPAÑOL EN AFRICA", "TERRITORIO FRANCES EN AFRICA", "TERRITORIO FRANCES EN AMERICA", 
    "TERRITORIO FRANCES EN OCEANIA Y EL PACIFICO", "TERRITORIO HOLANDES EN AMERICA", 
    "TERRITORIO NORTEAMERICANO EN OCEANIA Y EL PACIFICO", "TERRITORIO PORTUGUES EN ASIA", "THAILANDIA", "TIMOR ORIENTAL",
    "TOGO", "TRINIDAD Y TOBAGO", "TUNEZ", "TURCAS Y CAICOS", "TURKMENISTAN", "TURQUIA", "TUVALU", "UCRANIA", 
    "UGANDA", "URUGUAY", "UZBEKISTAN", "VANUATU", "VENEZUELA", "VIETNAM", "ZAMBIA", "ZIMBABWE"];

    var codPaises = ["308", "518", "563", "525", "140", "242", "247", "240", "302", "127", "224", "540", "243",
    "406", "509", "541", "207", "313", "321", "204", "420", "542", "514", "236", "150", "244", "221", "543", "113",
    "220", "344", "527", "161", "141", "318", "129", "315", "149", "226", "130", "997", "305", "336", "202", "118", "144",
    "334", "333", "211", "107", "547", "209", "155", "507", "231", "218", "124", "213", "341", "163", "548", "517",
    "549", "225", "139", "401", "335", "512", "505", "145", "102", "550", "108", "565", "232", "520", "253", "425",
    "215", "566", "104", "103", "147", "217", "208", "515", "342", "214", "530", "317", "328", "309", "307", "506",
    "516", "246", "427", "567", "327", "424", "164", "418", "403", "245", "249", "306", "504", "205", "331", "568",
    "301", "551", "137", "552", "416", "303", "316", "114", "553", "311", "106", "125", "534", "554", "532", "345",
    "555", "120", "329", "115", "133", "523", "128", "250", "119", "134", "216", "417", "556", "535", "337", "252", 
    "561", "121", "326", "159", "402", "320", "212", "131", "111", "421", "513", "423", "405", "304", "324", "210",
    "412", "222", "219", "422", "528", "501", "251", "312", "510", "148", "544", "546", "346", "143", "206", "545",
    "519", "562", "142", "165", "241", "404", "536", "524", "234", "146", "233", "101", "156", "105", "332", "310",
    "138", "314", "122", "112", "123", "160", "511", "508", "235", "330", "135", "557", "151", "227", "407", "230",
    "152", "153", "228", "408", "229", "409", "343", "319", "426", "109", "203", "126", "248", "558", "522", "419",
    "559", "136", "223", "560", "415", "201", "325", "117", "116"];

    for (i = 0; i < paises.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = paises[i];
        option.value = codPaises[i];
        select.append(option);
    }

    //Nacionalidad
    var select = document.formFactura.Nacionalidad;

    for (i = 0; i < paises.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = paises[i];
        option.value = codPaises[i];
        select.append(option);
    }


    //Indicador de servicio
    var select = document.formFactura.IndServicio;

    var indServicio = ["FACTURA DE SERVICIOS PERIÓDICOS DOMICILIARIOS", "FACTURA DE OTROS SERVICIOS PERIÓDICOS", 
                 "FACTURA DE SERVICIOS", "SERVICIOS DE HOTELERÍA", "SERVICIO DE TRANSPORTE TERRESTRE INTERNACIONAl"];
    var codIndServicio = ["1", "2", "3", "4", "5"]; 

    for (i = 0; i < indServicio.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = indServicio[i];
        option.value = codIndServicio[i];
        select.append(option);
    }

    //Tipo Moneda
    var select = document.formFactura.TpoMoneda;

    var monedas = ["BOLIVAR", "BOLIVIANO", "CHELIN", "CORONA DIN", "CORONA NOR", "CORONA SC", "CRUZEIRO REAL", "DIRHAM", 
    "DOLAR AUST", "DOLAR CAN", "DOLAR HK", "DOLAR NZ", "DOLAR SIN", "DOLAR TAI", "DOLAR USA", "DRACMA", "ESCUDO", "EURO",
    "FLORIN", "FRANCO BEL", "FRANCO FR", "FRANCO SZ", "GUARANI", "LIBRA EST", "LIRA", "MARCO AL", "MARCO FIN", "NUEVO SOL",
    "OTRAS MONEDAS", "PESETA", "PESO", "PESO CL", "PESO COL", "PESO MEX", "PESO URUG", "RAND", "RENMINBI", "RUPIA", "SUCRE",
    "YEN"];

    for (i = 0; i < monedas.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = monedas[i];
        option.value = monedas[i];
        select.append(option);
    }

    //Modalidad Venta
    var select = document.formFactura.CodModVenta;

    var modalidades = ["A FIRME", "BAJO CONDICIÓN", "EN CONSIGNACION LIBRE", "EN CONSIGNACION CON UN MINIMO A FIRME", "SIN PAGO"];
    var codMod = ["1", "2", "3", "4", "9"];

    for (i = 0; i < modalidades.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = modalidades[i];
        option.value = codMod[i];
        select.append(option);
    }

    //Unidad Peso Neto
    var select = document.formFactura.CodUnidPesoNeto;

    var unidadesNeto = ["Pieza o ítem", "Par" , "1000 Piezas o ítemes" , "Kilogramo", "Juego o mazo", "Litro", "1000 Kilowatt hora", "Metro",
     "Metro cuadrado", "Metro cúbico", "Kilate" ];
    var codUnidadesNeto = ["10", "17", "13", "6", "12", "9", "3", "14", "15", "16", "5"];
    for (i = 0; i < unidadesNeto.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = unidadesNeto[i];
        option.value = codUnidadesNeto[i];
        select.append(option);
    }  

    //Unidad Peso Bruto
    var select = document.formFactura.CodUnidPesoBruto;

    var unidadesBruto = ["Pieza o ítem", "Par" , "1000 Piezas o ítemes" , "Kilogramo", "Juego o mazo", "Litro", "1000 Kilowatt hora", "Metro",
     "Metro cuadrado", "Metro cúbico", "Kilate" ];
    var codUnidadesBruto = ["10", "17", "13", "6", "12", "9", "3", "14", "15", "16", "5"];
    for (i = 0; i < unidadesBruto.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = unidadesBruto[i];
        option.value = codUnidadesBruto[i];
        select.append(option);
    }  

    //Unidad Medida Tara
    var select = document.formFactura.CodUnidMedTara;

    var medidasTara = ["Pieza o ítem", "Par" , "1000 Piezas o ítemes" , "Kilogramo", "Juego o mazo", "Litro", "1000 Kilowatt hora", "Metro",
     "Metro cuadrado", "Metro cúbico", "Kilate" ];
    var codMedidasTara = ["10", "17", "13", "6", "12", "9", "3", "14", "15", "16", "5"];

    for (i = 0; i < medidasTara.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = medidasTara[i];
        option.value = codMedidasTara[i];
        select.append(option);
    }  

    //Forma de pago exportación
    var select = document.formFactura.FmaPagExp;

    var formasPago = ["COBRANZA HASTA 1 AÑO", "COBRANZA MÁS DE 1 AÑO", "ACREDITIVO HASTA 1 AÑO", 
    "CRÉDITO DE BANCOS Y ORG. FINANCIEROS MÁS DE 1 AÑO", "SIN PAGO" , "PAGO ANTICIPADO A LA FECHA DE EMBARQUE"];
    var codFormas = ["1", "2", "11", "12", "21", "32"];

    for (i = 0; i < formasPago.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = formasPago[i];
        option.value = codFormas[i];
        select.append(option);
    }

    //Tipo Documento
    var select = document.formFactura.TipoDTE;

    var tiposDoc = ["FACTURA EXPORTACIÓN", "NOTA DE DÉBITO EXPORTACIÓN", "NOTA DE CRÉDITO EXPORTACIÓN"];
    var codTiposDoc = ["110", "111", "112"];

    for (i = 0; i < tiposDoc.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = tiposDoc[i];
        option.value = codTiposDoc[i];
        select.append(option);
    }

    //Clausula venta
    var select = document.formFactura.CodClauVenta;

    var clausulas = ["COSTOS, SEGURO Y FLETE - CIF", "COSTOS Y FLETE - CFR", "EN FÁBRICA - EXW", "FRANCO AL COSTADO DEL BUQUE - FAS", 
    "FRANCO A BORDO - FOB", "SIN CLÁUSULA DE COMPRAVENTA - S/CL ", "ENTREGADAS DERECHOS PAGADOS - DDP", "FRANCO TRANSPORTISTA - FCA", 
    "TRANSPORTE PAGADO HASTA - CPT", "TRANSPORTE Y SEGURO PAGADO HASTA - CIP", "ENTREGADAS EN PUERTO DESTINO - DAT", 
    "ENTREGADAS EN LUGAR CONVENIDO - DAP", "OTROS"];

    //CLAUSULAS SIN ABREVIATURA (COMENTADO)
    /*var clausulas = ["COSTOS, SEGURO Y FLETE", "COSTOS Y FLETE", "EN FÁBRICA", "FRANCO AL COSTADO DEL BUQUE", "FRANCO A BORDO",
    "SIN CLÁUSULA DE COMPRAVENTA", "ENTREGADAS DERECHOS PAGADOS", "FRANCO TRANSPORTISTA", "TRANSPORTE PAGADO HASTA", 
    "TRANSPORTE Y SEGURO PAGADO HASTA", "ENTREGADAS EN PUERTO DESTINO", "ENTREGADAS EN LUGAR CONVENIDO", "OTROS"];*/

    var codClausulas = ["1", "2", "3", "4", "5", "6", "9", "10", "11", "12", "17", "18", "8"];

    for (i = 0; i < clausulas.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = clausulas[i];
        option.value = codClausulas[i];
        select.append(option);
    }

    //Vía de Transporte    
    var select = document.formFactura.CodViaTransp;

    var vias = ["MARÍTIMA, FLUVIAL Y LACUSTRE", "AÉREO", "POSTAL", "FERROVIARIO", "CARRETERO / TERRESTRE", "OLEODUCTOS, GASODUCTOS",
    "TENDIDO ELÉCTRICO (Aéreo, Subterráneo)", "OTRA"];
    var codVias = ["1", "4", "5", "6", "7", "8", "9", "10"];

    for (i = 0; i < vias.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = vias[i];
        option.value = codVias[i];
        select.append(option);
    }
  
    //Puerto Embarque
    var select = document.formFactura.CodPtoEmbarque;

    var puertosEmb = ["ARICA", "IQUIQUE", "PUERTO PATACHE", "PUERTO PATILLO", "TOCOPILLA", "ANTOFAGASTA", "MEJILLONES", 
    "MICHILLA", "PUERTO ANGAMOS", "CALETA COLOSO", "BARQUITO/CHAÑARAL", "CALDERA", "HUASCO", "COQUIMBO", "LOS VILOS", 
    "GUAYACAM", "VALPARAÍSO", "QUINTERO", "VENTANAS", "ISLA DE PASCUA", "JUAN FERNANDEZ", "SAN ANTONIO", "TALCAHUANO", 
    "SAN VICENTE", "LIRQUÉN", "CORONEL", "PENCO", "CORRAL", "PUERTO MONTT", "CASTRO", "ANCUD", "CHAITÉN", "QUELLÓN",
    "CALBUCO", "PUERTO CHACABUCO", "PUNTA ARENAS", "CALETA CLARENCIA", "CABO NEGRO", "BAHÍA SAN GREGORIO", "POSESIÓN",
    "PUERTO WILLIAMS", "TRES PUENTES", "PUERTO NATALES", "PUERTO PERCY", "PUERTO ISLA GUARELLO"];
    var codEmb = ["901", "902", "204", "913", "914", "903", "915", "206", "207", "950", "917", "918", "920", "904", "199", "946",
    "905", "921", "948", "929", "922", "906", "907", "908", "909", "926", "925", "930", "910", "932", "931", "934", "933", "205", 
    "911", "912", "940", "942", "941", "208", "943", "209", "936", "939", "937"];

    for (i = 0; i < puertosEmb.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = puertosEmb[i] + " - " + codEmb[i];
        option.value = codEmb[i];
        select.append(option);
    }

    //Puerto Desembarque
    var select = document.formFactura.CodPtoDesemb;

    //CANADA
    var canada = ["-------CANADÁ-------", "MONTREAL" , "COSTA DEL PACÍFICO, OTROS NO ESPECIFICADOS", "HALIFAX", "VANCOUVER", "SAINT JOHN",
    "TORONTO", "OTROS PUERTOS DE CANADÁ NO IDENTIFICADOS", "BAYSIDE", "PORT CARTIES", "QUEBEC", "PRINCE RUPERT", "HAMILTON"];
    var canadaCod = [ "", "111", "112", "113", "114", "115", "116", "117", "118", "120", "124", "125", "126"];
    //ESTADOS UNIDOS
    var estUnidos = ["-------ESTADOS UNIDOS-------", "BOSTON", "NEW HAVEN", "BRIDGEPORT", "NEW YORK", "FILADELFIA", "BALTIMORE", "NORFOLK",
    "CHARLESTON", "SAVANAH", "MIAMI", "COSTA DEL ATLÁNTICO, OTROS NO ESPECIFICADOS COMPRENDIDOS ENTRE MAINE Y KEY WEST", "TAMPA", 
    "PENSACOLA", "MOBILE", "NEW ORLEANS", "PORT ARTHUR", "GALVESTON", "CORPUS CRISTI", "BROWNSVILLE", "HOUSTON", 
    "PUERTOS DEL GOLFO DE MÉXICO, OTROS NO ESPECIFICADOS COMPRENDIDOS ENTRE KEY WEST Y BROWNSVILLE", "SEATTLE", "PORTLAND", "SAN FRANCISCO",
    "LOS ANGELES", "LONG BEACH", "SAN DIEGO", "COSTA DEL PACÍFICO, OTROS NO ESPECIFICADOS", "EVERGLADES", "JACKSONVILLE", "PALM BEACH",
    "BATON ROUGE", "COLUMBRES", "PITTSBURGH", "DULUTH", "MILWAUKEE", "OAKLAND", "STOCKTON", "OTROS PUERTOS DE ESTADOS UNIDOS NO ESPECIFICADOS"];
    var estUnidosCod = ["", "131", "132", "133", "134", "135", "136", "137", "139", "140","141", "121", "151", "152", "153", "154",
    "155", "156", "157", "158", "159", "122", "171", "172", "173", "174", "175", "176", "123", "142", "143", "145", "146", "147", "148",
    "149", "150", "160", "161", "180"];
    //MÉXICO
    var mexico = ["-------MÉXICO-------", "TAMPICO", "VERACRUZ", "GOLFO DE MÉXICO, OTROS NO ESPECIFICADOS", "COATZACOALCOS", "GUAYMAS",
    "MAZATLAN", "MANZANILLO", "ACAPULCO", "COSTA DEL PACÍFICO, OTROS PUERTOS", "OTROS PUERTOS DE MÉXICO NO ESPECIFICADOS", "ALTAMIRA"];
    var mexicoCod = [ "", "211", "213", "219", "214", "215", "216", "217", "218", "212", "210", "220"];
    //PANAMÁ
    var panama = ["-------PANAMÁ-------", "CRISTOBAL", "BALBOA", "COLON", "OTROS PUERTOS DE PANAMÁ NO ESPECIFICADOS"];
    var panamaCod = [ "", "221", "222", "223", "224"];
    //COLOMBIA
    var colombia = ["-------COLOMBIA-------", "BUENAVENTURA", "OTROS PUERTOS DE COLOMBIA NO ESPECIFICADOS", "BARRANQUILLA"];
    var colombiaCod = [ "", "232", "231", "233"];
    //ECUADOR
    var ecuador = ["-------ECUADOR-------", "GUAYAQUIL", "OTROS PUERTOS DE ECUADOR NO ESPECIFICADOS"];
    var ecuadorCod = [ "", "242", "241"];
    //PERÚ
    var peru = ["-------PERÚ-------", "CALLAO", "ILO", "IQUITOS", "OTROS PUERTOS DE PERÚ NO ESPECIFICADOS"];
    var peruCod = [ "", "252", "253", "254", "251"];
    //ARGENTINA
    var argentina = ["-------ARGENTINA-------", "BUENOS AIRES", "NECOCHEA", "MENDOZA", "CÓRDOBA", "OTROS PUERTOS DE ARGENTINA NO ESPECIFICADOS",
    "BAHÍA BLANCA", "COMODORO RIVADAVIA", "PUERTO MADRYN", "MAR DEL PLATA", "ROSARIO"];
    var argentinaCod = [ "", "262", "263", "264", "265", "261", "266", "267", "268", "269", "270"];
    //URUGUAY
    var uruguay = ["-------URUGUAY-------", "MONTEVIDEO" , "OTROS PUERTOS DE URUGUAY NO ESPECIFICADOS"];
    var uruguayCod = [ "", "272", "271"];
    //VENEZUELA
    var venezuela = ["-------VENEZUELA-------", "LA GUAIRA" , "OTROS PUERTOS DE VENEZUELA NO ESPECIFICADOS","MARACAIBO"];
    var venezuelaCod = [ "", "282", "281", "285"];
    //BRASIL
    var brasil = ["-------BRASIL-------", "SANTOS", "RIO DE JANEIRO", "RIO GRANDE DEL SUR", "PARANAGUA", "SAO PAULO", "SALVADOR",
    "OTROS PUERTOS DE BRASIL NO ESPECIFICADOS"];
    var brasilCod = [ "", "292", "293", "294", "295", "296", "297", "291"];
    //ANTILLAS HOLANDESAS
    var antillas = ["-------ANTILLAS HOLANDESAS-------", "CURAZAO" , "OTROS PUERTOS DE LAS ANTILLAS HOLANDESAS NO ESPECIFICADOS"];
    var antillasCod = [ "", "302", "301"];
   //CHINA
    var china = ["-------CHINA-------", "SHANGAI", "DAIREN", "OTROS PUERTOS DE CHINA NO ESPECIFICADOS"];
    var chinaCod = [ "", "411", "412", "413"];
    //COREA DEL NORTE 
    var coreaNorte = ["-------COREA DEL NORTE-------", "NAMPO" , "OTROS PUERTOS DE COREA DEL NORTE NO ESPECIFICADOS"];
    var coreaNorteCod = [ "", "421", "420"];
    //COREA DEL SUR
    var coreaSur = ["-------COREA DEL SUR-------", "BUSAN" , "OTROS PUERTOS DE COREA DEL SUR NO ESPECIFICADOS"];
    var coreaSurCod = [ "", "422", "423"];
    //FILIPINAS
    var filipinas = ["-------FILIPINAS-------", "MANILA" , "OTROS PUERTOS DE FILIPINAS NO ESPECIFICADOS"];
    var filipinasCod = [ "", "431", "432"];
    //JAPÓN
    var japon = ["-------JAPÓN-------", "OSAKA", "KOBE", "YOKOHAMA", "NAGOYA", "SHIMIZUI", "MOJI", "YAWATA", "FUKUYAMA",
    "OTROS PUERTOS DE JAPON NO ESPECIFICADOS"];
    var japonCod = [ "", "442", "443", "444", "445", "446", "447", "448", "449", "441"];
    //TAIWAN
    var taiwan = ["-------TAIWAN-------", "KAOHSIUNG" , "KEELUNG", "OTROS PUERTOS DE TAIWAN NO ESPECIFICADOS"];
    var taiwanCod = [ "", "451", "452", "453"];
    //IRAN
    var iran = ["-------IRAN-------", "KARHG ISLAND" , "OTROS PUERTOS DE IRAN NO ESPECIFICADOS"];
    var iranCod = [ "", "461", "462"];
    //INDIA
    var india = ["-------INDIA-------", "CALCUTA", "OTROS PUERTOS DE INDIA NO ESPECIFICADOSs"];
    var indiaCod = [ "", "471", "472"];
    //BANGLADESH
    var bangladesh = ["-------BANGLADESH-------", "CHALNA", "OTROS PUERTOS DE BANGLADESH NO ESPECIFICADOS"];
    var bangladeshCod = [ "", "481", "482"];
    //SINGAPUR
    var singapur = ["-------SINGAPUR-------", "HONG KONG", "OTROS PUERTOS DE SINGAPUR NO ESPECIFICADOS"];
    var singapurCod = [ "", "492", "491"];
    //RUMANIA
    var rumania = ["-------RUMANIA-------", "CONSTANZA", "OTROS PUERTOS DE RUMANIA NO ESPECIFICADOS"];
    var rumaniaCod = [ "", "511", "512"];
    //BULGARIA
    var bulgaria = ["-------BULGARIA-------", "VARNA", "OTROS PUERTOS DE BULGARIA NO ESPECIFICADOS"];
    var bulgariaCod = [ "", "521", "522"];
    //CROACIA
    var croacia = ["-------CROACIA-------", "RIJEKA", "OTROS PUERTOS DE CROACIA NO ESPECIFICADOS" ];
    var croaciaCod = [ "", "538", "537"];
    //ITALIA
    var italia = ["-------ITALIA-------", "GENOVA", "LIORNA, LIVORNO", "NAPOLES", "SALERNO", "AUGUSTA", "SAVONA",
    "OTROS PUERTOS DE ITALIA NO ESPECIFICADOS"];
    var italiaCod = [ "", "542", "543", "544", "545", "546", "547", "541"];
    //FRANCIA
    var francia = ["-------FRANCIA-------", "LA PALLICE", "LE HAVRE", "MARSELLA", "OTROS PUERTOS DE FRANCIA NO ESPECIFICADOS",
    "BURDEOS", "CALAIS", "BREST", "RUAN"];
    var franciaCod = [ "", "552", "553", "554", "551", "555", "556", "557", "558"];
    //ESPAÑA
    var espana = ["-------ESPAÑA-------", "CADIZ", "BARCELONA", "BILBAO", "HUELVA", "SEVILLA", "OTROS PUERTOS DE ESPAÑA NO ESPECIFICADOS",
    "TARRAGONA", "ALGECIRAS", "VALENCIA"];
    var espanaCod = [ "", "562", "563", "564", "565", "566", "561", "567", "568", "569"];
    //INGLATERRA
    var inglaterra = ["-------INGLATERRA-------", "LIVERPOOL", "LONDRES", "ROCHESTER", "ETEN SALVERRY", 
    "OTROS PUERTOS DE INGLATERRA NO ESPECIFICADOS", "DOVER", "PLYMOUTH"];
    var inglaterraCod = [ "", "571", "572", "573", "574", "576", "577", "578"];
    //FINLANDIA
    var finlandia = ["-------FINLANDIA-------", "HELSINSKI", "HANKO", "KEMI", "KOKKOLA", "KOTKA", "OULO", "PIETARSAARI", "PORI",
    "OTROS PUERTOS DE FINLANDIA NO ESPECIFICADOS"];
    var finlandiaCod = [ "", "581", "583", "584", "585", "586", "587", "588", "589", "582"];
    //ALEMANIA
    var alemania = ["-------ALEMANIA-------", "BREMEN", "HAMBURGO", "NUREMBERG", "FRANKFURT", "DUSSELDORF",
    "OTROS PUERTOS DE ALEMANIA NO ESPECIFICADOS", "CUXHAVEN", "ROSTOCK", "OLDENBURG"];
    var alemaniaCod = [ "", "591", "592", "593", "594", "595", "596", "597", "598", "599"];
    //BÉLGICA
    var belgica = ["-------BÉLGICA-------", "AMBERES", "OTROS PUERTOS DE BÉLGICA NO ESPECIFICADOS", "GHENT","OOSTENDE", "ZEEBRUGGE"];
    var belgicaCod = [ "", "601", "602", "604",  "605", "603"];
    //PORTUGAL
    var portugal = ["-------PORTUGAL-------", "LISBOA", "OTROS PUERTOS DE PORTUGAL NO ESPECIFICADOS", "SETUBAL"];
    var portugalCod = [ "", "611", "612", "613"];
    //HOLANDA
    var holanda = ["-------HOLANDA-------", "AMSTERDAM", "ROTTERDAM", "OTROS PUERTOS DE HOLANDA NO ESPECIFICADOS"];
    var holandaCod = [ "", "621", "622", "623"];
    //SUECIA
    var suecia = ["-------SUECIA-------", "GOTEMBURGO", "MALMO", "HELSIMBORG", "KALMAR", "OTROS PUERTOS DE SUECIA NO ESPECIFICADOS"];
    var sueciaCod = [ "", "631", "633", "634", "635", "632"];
    //DINAMARCA
    var dinamarca = ["-------DINAMARCA-------", "AARHUS", "COPENHAGEN", "OTROS PUERTOS DE DINAMARCA NO ESPECIFICADOS",
    "AALBORG", "ODENSE"];
    var dinamarcaCod = [ "", "641", "642", "643", "644", "645"];
    //NORUEGA
    var noruega = ["-------NORUEGA-------", "OSLO", "OTROS PUERTOS DE NORUEGA NO ESPECIFICADOS", "STAVANGER"];
    var noruegaCod = [ "", "651", "652", "653"];
    //REPÚBLICA DE SERBIA
    var serbia = ["-------REPÚBLICA DE SERBIA-------", "BELGRADO", "OTROS PUERTOS DE SERBIA NO ESPECIFICADOS"];
    var serbiaCod = [ "", "533", "534"];
    //MONTENEGRO
    var montenegro = ["-------MONTENEGRO-------", "PODGORITSA", "OTROS PUERTOS DE MONTENEGRO NO ESPECIFICADOS"];
    var montenegroCod = [ "", "535", "536"];
    //SUDÁFRICA
    var sudafrica = ["-------SUDÁFRICA-------", "DURBAM", "CIUDAD DEL CABO", "OTROS PUERTOS DE SUDÁFRICA NO ESPECIFICADOS", 
    "SALDANHA", "PORT-ELIZABETH", "MOSSEL-BAY", "EAST-LONDON"];
    var sudafricaCod = [ "", "711", "712", "713", "714", "715", "716", "717"];
    //AUSTRALIA
    var australia = ["-------AUSTRALIA-------", "SIDNEY", "FREMANTLE", "ADELAIDA", "DARWIN", "GERALDTON", 
    "OTROS PUERTOS DE AUSTRALIA NO ESPECIFICADOS"];
    var australiaCod = [ "", "811", "812", "814", "815", "816", "813"];
    //OTROS
    var otros = ["-------OTROS-------", "OTROS PUERTOS DE AMÉRICA NO ESPECIFICADOS", "OTROS PUERTOS ASIÁTICOS NO ESPECIFICADOS", 
    "OTROS PUERTOS DE EUROPA NO ESPECIFICADOS", "OTROS PUERTOS DE ÁFRICA NO ESPECIFICADOS", "OTROS PUERTOS DE OCEANÍA NO ESPECIFICADOS"];
    var otrosCod = [ "", "399", "499", "699", "799", "899"];

    //TODOS LOS PUERTOS Y SUS CÓDIGOS RESPECTIVOS
    var puertosDesemb = [canada, estUnidos, mexico, panama, colombia, ecuador, peru, argentina, uruguay, venezuela, brasil,
    antillas, china, coreaNorte, coreaSur, filipinas, japon, taiwan, iran, india, bangladesh, singapur, rumania, bulgaria,
    croacia, italia, francia, espana, inglaterra, finlandia, alemania, belgica, portugal, holanda, suecia, dinamarca,
    noruega, serbia, montenegro, sudafrica, australia, otros];

    var codDesemb = [canadaCod, estUnidosCod, mexicoCod, panamaCod, colombiaCod, ecuadorCod, peruCod, argentinaCod, uruguayCod, 
    venezuelaCod, brasilCod, antillasCod, chinaCod, coreaNorteCod, coreaSurCod, filipinasCod, japon, taiwanCod, iranCod,
    indiaCod, bangladeshCod, singapurCod, rumaniaCod, bulgariaCod, croaciaCod, italiaCod, franciaCod, espanaCod, inglaterra,
    finlandiaCod, alemaniaCod, belgicaCod, portugalCod, holandaCod, sueciaCod, dinamarcaCod, noruegaCod, serbiaCod, 
    montenegroCod, sudafricaCod, australiaCod, otrosCod];

    //Por cada país, recorre el arreglo con los nombres y códigos
    for (i = 0; i < puertosDesemb.length; i++) {

        for (j = 0; j < puertosDesemb[i].length; j++) {

            if(j == 0)
            {
                //Si es el primer elemento (correponde al nombre del país), lo hace no seleccionable
                var option = document.createElement('option');
                option.innerHTML = puertosDesemb[i][j];
                option.value = codDesemb[i][j];
                option.disabled = true;
                select.append(option);
            }
            else
            {
                var option = document.createElement('option');
                option.innerHTML = puertosDesemb[i][j] + " - " + codDesemb[i][j];
                option.value = codDesemb[i][j];
                select.append(option);
            }            
        }               
    }

    //FIN PUERTOS DESEMBARCO

    //Tipo bultos
    var select = document.formFactura.CodTpoBultos;

    var tiposBulto = ["GRANEL SÓLIDO, PARTICULAS FINAS (POLVO)", "GRANEL SÓLIDO, PARTICULAS GRANULARES (GRANOS)", 
    "GRANEL SÓLIDO, PARTICULAS GRANDES (NÓDULOS)", "GRANEL LÍQUIDO", "GRANEL GASEOSO", "PIEZAS", "TUBOS", "CILINDRO",
    "ROLLOS", "BARRAS", "LINGOTES", "TRONCOS", "BLOQUE", "ROLLIZO", "CAJÓN", "CAJAS DE CARTÓN", "FARDO", "BAÚL", "COFRE",
    "ARMAZÓN", "BANDEJA", "CAJAS DE MADERA", "CAJAS DE LATA", "BOTELLA DE GAS", "BOTELLA", "JAULAS", "BIDÓN", "JABAS",
    "CESTAS", "BARRILETE", "TONEL", "PIPAS", "JARRO", "FRASCO", "DAMAJUANA", "BARRIL", "TAMBOR", "CUÑETES", "TARROS", 
    "CUBO", "PAQUETE", "SACOS", "MALETA", "BOLSA", "BALA", "RED", "SOBRES", "CONTENEDOR DE 20 PIES DRY", "CONTENEDOR DE 40 PIES DRY",
    "CONTENEDOR REFRIGERADO 20 PIES", "CONTENEDOR REFRIGERADO 40 PIES", "ESTANQUE (no utilizar para contenedor Tank)", 
    "CONTENEDOR NO ESPECIFICADO (Open Top, Tank, Flat Rack, otros)", "PALLETS", "TABLERO", "LÁMINAS", "CARRETE", 
    "AUTOMOTOR", "ATAUD", "MAQUINARIA", "PLANCHAS", "ATADOS", "BOBINA", "OTROS BULTOS NO ESPECIFICADOS","NO EXISTE BULTO",
    "SIN EMBALAR"];
    var codBultos = ["1", "2", "3", "4", "5", "10", "11", "12", "13", "16", "17", "18", "19", "20", "21", "22",
    "23", "24", "25", "26", "27", "28", "29", "31", "32", "33", "34", "35", "36", "37", "38", "39", "41", "42", "43",
    "44", "45", "46", "47", "51", "61", "62", "63", "64", "65", "66", "67", "73", "74", "75", "76", "77", "78", "80",
    "81", "82", "83", "85", "86", "88", "89", "90", "91", "93", "98", "99"];

    for (i = 0; i < tiposBulto.length; i++) {
        var option = document.createElement('option');
        option.innerHTML = tiposBulto[i];
        option.value = codBultos[i];
        select.append(option);
    }
    
    //Tipos Documento Referencia
    var ddl = document.getElementsByName("DDLSelectTipDocRef[]");

    for (var i = 0; i <  ddl.length; i++) 
    {
        var select = ddl[i];
        var tipoDocsRef = ["FACTURA", "FACTURA DE VENTA BIENES Y SERVICIOS NO AFECTOS O EXENTOS DE IVA", "BOLETA", "BOLETA EXENTA",
        "FACTURA DE COMPRA", "NOTA DE DÉBITO", "NOTA DE CRÉDITO", "LIQUIDACIÓN", "LIQUIDACIÓN FACTURA", "LIQUIDACIÓN FACTURA ELECTRÓNICA",
        "FACTURA ELECTRÓNICA", "FACTURA NO AFECTA O EXENTA ELECTRÓNICA", "BOLETA ELECTRÓNICA", "BOLETA EXENTA ELECTRÓNICA",
        "FACTURA DE COMPRA ELECTRÓNICA", "NOTA DE DÉBITO ELECTRÓNICA", "NOTA DE CRÉDITO ELECTRÓNICA", "GUÍA DE DESPACHO", 
        "GUÍA DE DESPACHO ELECTRÓNICA", "FACTURA DE EXPORTACIÓN ELECTRÓNICA", "NOTA DE DÉBITO DE EXPORTACIÓN ELECTRÓNICA",
        "NOTA DE CRÉDITO DE EXPORTACIÓN ELECTRÓNICA", "ORDEN DE COMPRA", "NOTA DE PEDIDO", "CONTRATO", "RESOLUCIÓN",
        "PROCESO CHILECOMPRA", "FICHA CHILECOMPRA", "DUS", "B/L (CONOCIMIENTO DE EMBARQUE)", "AWB (AIR WILL BILL)", "MIC/DTA",
        "CARTA DE PORTE", "RESOLUCIÓN DEL SNA DONDE CALIFICA SERVICIOS DE EXPORTACIÓN", "PASAPORTE", "CERTIFICADO DE DEPÓSITO BOLSA PROD. CHILE",
        "VALE DE PRENDA BOLSA PROD. CHILE", "SET - Certificacion"];
        var codTipoDocsRef = ["30", "32", "25", "38", "45", "55", "60", "103", "40", "43", "33", "34", "39", "41", "46", "56",
        "61", "50", "52", "110", "111", "112", "801", "802", "803", "804", "805", "806", "807", "808", "809", "810", "811", "812",
        "813", "814", "815", "SET"];

        for (j = 0; j < tipoDocsRef.length; j++) {
            var option = document.createElement('option');
            option.innerHTML = tipoDocsRef[j];
            option.value = codTipoDocsRef[j];
            select.append(option);
        }
    }    

    //Cod Referencia
    var ddl =  document.getElementsByName("DDLCodRef[]"); 

    for (var i = 0; i <  ddl.length; i++) 
    {
        var select = ddl[i];
        var codDocsRef = ["ANULA DOCUMENTO DE REFERENCIA", "CORRIGE TEXTO DOCUMENTO DE REFERENCIA", "CORRIGE MONTOS"];
        var codCodDocsRef = ["1", "2", "3"];

        for (j = 0; j < codDocsRef.length; j++) {
            var option = document.createElement('option');
            option.innerHTML = codDocsRef[j];
            option.value = codCodDocsRef[j];
            select.append(option);
        }
    }   
}

function poblarSelectReferencia()
{
    //Tipos Documento Referencia
    var ddl = document.getElementsByName("DDLSelectTipDocRef[]");
    var ultimaFila = (document.getElementsByName("DDLSelectTipDocRef[]").length) - 1;
    var select = ddl[ultimaFila];
    var tipoDocsRef = ["FACTURA", "FACTURA DE VENTA BIENES Y SERVICIOS NO AFECTOS O EXENTOS DE IVA", "BOLETA", "BOLETA EXENTA",
    "FACTURA DE COMPRA", "NOTA DE DÉBITO", "NOTA DE CRÉDITO", "LIQUIDACIÓN", "LIQUIDACIÓN FACTURA", "LIQUIDACIÓN FACTURA ELECTRÓNICA",
    "FACTURA ELECTRÓNICA", "FACTURA NO AFECTA O EXENTA ELECTRÓNICA", "BOLETA ELECTRÓNICA", "BOLETA EXENTA ELECTRÓNICA",
    "FACTURA DE COMPRA ELECTRÓNICA", "NOTA DE DÉBITO ELECTRÓNICA", "NOTA DE CRÉDITO ELECTRÓNICA", "GUÍA DE DESPACHO", 
    "GUÍA DE DESPACHO ELECTRÓNICA", "FACTURA DE EXPORTACIÓN ELECTRÓNICA", "NOTA DE DÉBITO DE EXPORTACIÓN ELECTRÓNICA",
    "NOTA DE CRÉDITO DE EXPORTACIÓN ELECTRÓNICA", "ORDEN DE COMPRA", "NOTA DE PEDIDO", "CONTRATO", "RESOLUCIÓN",
    "PROCESO CHILECOMPRA", "FICHA CHILECOMPRA", "DUS", "B/L (CONOCIMIENTO DE EMBARQUE)", "AWB (AIR WILL BILL)", "MIC/DTA",
    "CARTA DE PORTE", "RESOLUCIÓN DEL SNA DONDE CALIFICA SERVICIOS DE EXPORTACIÓN", "PASAPORTE", "CERTIFICADO DE DEPÓSITO BOLSA PROD. CHILE",
    "VALE DE PRENDA BOLSA PROD. CHILE", "SET - Certificacion"];
    var codTipoDocsRef = ["30", "32", "25", "38", "45", "55", "60", "103", "40", "43", "33", "34", "39", "41", "46", "56",
    "61", "50", "52", "110", "111", "112", "801", "802", "803", "804", "805", "806", "807", "808", "809", "810", "811", "812",
    "813", "814", "815", "SET"];

    for (j = 0; j < tipoDocsRef.length; j++) {
        var option = document.createElement('option');
        option.innerHTML = tipoDocsRef[j];
        option.value = codTipoDocsRef[j];
        select.append(option);
    }
    

    //Cod Referencia
    var ddl =  document.getElementsByName("DDLCodRef[]"); 
    var ultimaFila = (document.getElementsByName("DDLCodRef[]").length) - 1;
    
    var select = ddl[ultimaFila];
    var codDocsRef = ["ANULA DOCUMENTO DE REFERENCIA", "CORRIGE TEXTO DOCUMENTO DE REFERENCIA", "CORRIGE MONTOS"];
    var codCodDocsRef = ["1", "2", "3"];

    for (j = 0; j < codDocsRef.length; j++) {
        var option = document.createElement('option');
        option.innerHTML = codDocsRef[j];
        option.value = codCodDocsRef[j];
        select.append(option);
    }
    
}

//VALIDAR TIPO DE DATOS
function validarDatos() {

    //Datos Documento
    var tipoDocumento = document.getElementById("TipoDTE").value;
    var fechaEmision = document.getElementById("FchEmis").value;
    var formaPago = document.getElementById("FmaPagExp").value;

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
    var nacRec = document.getElementById("Nacionalidad").value;
    var razonRec = document.getElementById("RznSocRecep").value;
    var direccionRec = document.getElementById("DirRecep").value;

    //Datos Pago
    var clauVenta = document.getElementById("CodClauVenta").value;
    var flete = document.getElementById("MntFlete").value;
    var modVenta = document.getElementById("CodModVenta").value;
    var totClaus = document.getElementById("TotClauVenta").value;
    var seguro = document.getElementById("MntSeguro").value;

    //Datos Envio
    var codPuertoEmb = document.getElementById("CodPtoEmbarque").value;
    var puertoEmb = document.getElementById("IdAdicPtoEmb").value;
    var codPuertoDes =  document.getElementById("CodPtoDesemb").value;
    var puertoDes =  document.getElementById("IdAdicPtoDesemb").value;
    var paisDest =  document.getElementById("CodPaisDestin").value;
    var paisRecp =  document.getElementById("CodPaisRecep").value;
    var codViaTrans = document.getElementById("CodViaTransp").value;

    //Datos Bultos
    var tipoBul = document.getElementById("CodTpoBultos").value;   
    var marcasBul = document.getElementById("Marcas").value;
    var cantBul = document.getElementById("CantBultos").value;
    var totBul = document.getElementById("TotBultos").value;
    var unPesoBru = document.getElementById("CodUnidPesoBruto").value;
    var unPesoNet = document.getElementById("CodUnidPesoNeto").value;
    var unMedTara = document.getElementById("CodUnidMedTara").value;

    //Valores
    var tpoMoneda = document.getElementById("TpoMoneda").value;
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

    //Referencias
    var tipoDocRef = document.getElementsByName("DDLSelectTipDocRef[]");
    var folioRef = document.getElementsByName("boxFolioRef[]");
    var fechaRef = document.getElementsByName("boxFchRef[]");
    var codRef = document.getElementsByName("DDLCodRef[]");
    var razonRef = document.getElementsByName("boxRazonRef[]");

    //Descuentos/Recargos Globales
    var tipoMov = document.getElementsByName("DDLSelectTpoMov[]");
    var glosa = document.getElementsByName("boxGlosaDR[]");
    var valorDR = document.getElementsByName("boxValorDR[]");
    var tipoDR = document.getElementsByName("DDLSelectTpoValor[]");

    //Datos Documento   
    if (tipoDocumento == ("")) {
        bootbox.alert("Seleccione el tipo de documento a emitir");
        return false;
    }
    if (fechaEmision == null) {
        bootbox.alert("Seleccione la fecha de emisión");
        return false;
    }
   /* if (formaPago == ("")) {
        bootbox.alert("Seleccione la forma de pago");
        return false;
    }*/

    //Datos Receptor  

    if (numIdRec.length < 1) {
        bootbox.alert("Ingrese el número identificador del receptor");
        return false;
    }
    if (razonRec.length < 1) {
        bootbox.alert("Ingrese la razón social del receptor");
        return false;
    }
    if (giroRec.length < 1) {
        bootbox.alert("Ingrese el giro del receptor");
        return false;
    }
    if (direccionRec.length < 1) {
        bootbox.alert("Ingrese la dirección del receptor");
        return false;
    }

    //Datos Pago

    //SI ESTÁN VALIDAR QUE SEAN NÚMEOS   
    if (totClaus.length > 0 ) {
        if(isNaN(totClaus))
        {
            bootbox.alert("El total de la cláusula de venta debe ser un número");
            return false; 
        }       
    }
    if (flete.length > 0 ) {
        if(isNaN(flete))
        {
            bootbox.alert("El monto del flete debe ser un número");
            return false;
        }
    }
    if (seguro.length > 0 ) {
        if(isNaN(seguro))
        {
            bootbox.alert("El monto del seguro debe ser un número");
            return false;
        }        
    }   

    //Bultos
    if(tipoBul !="" || marcasBul.length > 0 ||  cantBul.length > 0)
    {
        if (tipoBul == ("")) {
        bootbox.alert("Seleccione el tipo de bultos");
        return false;
        }
        if (marcasBul.length < 1) {
        bootbox.alert("Ingrese las marcas de los bultos");
        return false;
        }
        if (cantBul.length < 1) {
            bootbox.alert("Ingrese la cantidad de bultos");
            return false;
        }
    }
    
    //Valores
    if (tpoMoneda == ("")) {
        bootbox.alert("Seleccione el tipo de moneda de la operación");
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

    //Referencias
    for (var i = 0; i < tipoDocRef.length; i++) 
    {
        if(tipoDocRef[i].value!="" || folioRef[i].value.length>0 || fechaRef[i].value!="")
        {
            if (tipoDocRef[i].value=="") {
                bootbox.alert("Seleccione el tipo documento de la referencia de la línea " + (i + 1));
                return false;
            }
            if (folioRef[i].value.length < 1) {
                bootbox.alert("Ingrese el número de folio de la referencia de la línea " + (i + 1));
                return false;
            }
            if (fechaRef[i].value == "") {
                bootbox.alert("Ingrese la fecha de la referencia de la línea " + (i + 1));
                return false;
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
    
    document.getElementById("TipoDTE").selectedIndex = 0;
    document.getElementById("FmaPagExp").selectedIndex = 0;
    document.getElementById("FchEmis").value = "";

    document.getElementById("RUTRecep").value = "";
    document.getElementById("GiroRecep").value = "";
    document.getElementById("Nacionalidad").value = "";
    document.getElementById("RznSocRecep").value = "";
    document.getElementById("DirRecep").value = "";

    document.getElementById("CodClauVenta").selectedIndex = 0;
    document.getElementById("CodModVenta").selectedIndex = 0;
    document.getElementById("MntFlete").value = "";
    document.getElementById("TotClauVenta").value = "";
    document.getElementById("MntSeguro").value = "";

    document.getElementById("CodPtoEmbarque").selectedIndex = 0;
    document.getElementById("CodPaisDestin").selectedIndex = 0;
    document.getElementById("CodPtoDesemb").selectedIndex = 0;
    document.getElementById("CodViaTransp").selectedIndex = 0;
    document.getElementById("IdAdicPtoEmb").value = "";
    document.getElementById("CodPaisRecep").value = "";
    document.getElementById("IdAdicPtoDesemb").value = "";

    document.getElementById("CodTpoBultos").selectedIndex = 0;
    document.getElementById("CodUnidPesoBruto").selectedIndex = 0;
    document.getElementById("CodUnidMedTara").selectedIndex = 0;
    document.getElementById("CodUnidPesoNeto").selectedIndex = 0;
    document.getElementById("Marcas").value = "";
    document.getElementById("CantBultos").value = "";
    document.getElementById("TotBultos").value = "";

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

    var tipoDocumento = document.getElementsByName("DDLSelectTipDocRef[]");
    var boxFolioRef= document.getElementsByName("boxFolioRef[]");
    var fechaRef = document.getElementsByName("boxFchRef[]");
    var codRef = document.getElementsByName("DDLCodRef[]");
    var boxRazonRef= document.getElementsByName("boxRazonRef[]");

    for (var i = 0; i < nombre.length; i++) {
        tipoDocumento[i].selectedIndex = 0;
        boxFolioRef[i].value = "";
        fechaRef[i].value = fechaRef[i].defaultValue;
        codRef[i].selectedIndex = 0;
        boxRazonRef[i].value = "";
    }

    var tipoMov = document.getElementsByName("DDLSelectTpoMov[]");
    var glosaDR = document.getElementsByName("boxGlosaDR[]");
    var valorDR = document.getElementsByName("boxValorDR[]");
    var tipoDR = document.getElementsByName("DDLSelectTpoValor[]");
    var exentoDR= document.getElementsByName("DDLSelectIndExeDR[]");

    for (var i = 0; i < nombre.length; i++) {
        tipoMov[i].selectedIndex = 0;
        glosaDR[i].value = "";
        valorDR[i].value = "";
        tipoDR[i].selectedIndex = 0;
        exentoDR[i].selectedIndex = 0;
    }
    
    document.getElementById("TpoMoneda").selectedIndex = 0;    
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
            }
        }
    }

    /*
    * Comento esto porque se estaba haciend el calculo 2 veces
    if (flete != null && flete != "") 
    {
        if (!isNaN(flete)) 
        {
            var fl = parseFloat(flete);
            neto = neto + fl;
        }
    }

    if (seguro != null && seguro != "") 
    {
        if (!isNaN(seguro)) 
        {
            var sg = parseFloat(seguro);
            neto = neto + sg;
        }
    }
    */
    exento = neto;

    //Para cada Recargo o Descuento lo añade al Exento
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
                    montoDescRec = (exento * (descRecG / 100));
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

    exento = exento + montoTotalDescRec; 
    total = exento;

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
                       '<option value="1" selected="selected">SÍ</option>'+
                       '<option value="0">NO</option></select>';
    cell9.innerHTML = '<button type="button" id="btnEliminaLineaDet" class="btn btn-primary" onclick="eliminarLineaDet(this);">-</button>';
    
    var desc = document.getElementsByName("boxDescuentoPct[]");
    var rec = document.getElementsByName("boxRecargoPct[]");
    $(desc[i-1]).show();
    $(rec[i-1]).hide();
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

//AGREGAR LINEA DE REFERENCIA
function agregarLineaRef() {
    var tabla = document.getElementById("tablaReferencia");

    var row1 = tabla.insertRow(tabla.rows.length);

    var cell1 = row1.insertCell(0);
    var cell2 = row1.insertCell(1);
    var cell3 = row1.insertCell(2);
    var cell4 = row1.insertCell(3);
    var cell5 = row1.insertCell(4);
    var cell6 = row1.insertCell(5);

    cell1.innerHTML = '<select name="DDLSelectTipDocRef[]" id="DDLSelectTipDocRef[]" class="js-example-basic-single js-states form-control">'+
                       '<option value="" selected>Seleccione una opci&oacute;n</option></select>';
    cell2.innerHTML = '<input type="text" name="boxFolioRef[]" id="boxFolioRef[]" class="js-example-basic-single js-states form-control">';
    cell3.innerHTML = '<input type="date" name="boxFchRef[]" id="boxFchRef[]" class="js-example-basic-single js-states form-control">';
    cell4.innerHTML = '<select name="DDLCodRef[]" id="DDLCodRef[]" class="js-example-basic-single js-states form-control">'+
                       '<option value="" selected>Seleccione una opci&oacute;n</option></select>';
    cell5.innerHTML = '<input type="text" name="boxRazonRef[]" id="boxRazonRef[]" class="js-example-basic-single js-states form-control">';
    cell6.innerHTML = '<button type="button" id="btnEliminaLineaRef" class="btn btn-primary" onclick="eliminarLineaRef(this);">-</button>';
}

//ELIMINAR LÍNEA DE REFERENCIA
function eliminarLineaRef(boton) {
    var tabla = document.getElementById("tablaReferencia");
    var filas = tabla.rows.length;
    if(filas>2)
    {
        var indice = boton.parentNode.parentNode.rowIndex;
        tabla.deleteRow(indice);
    }    
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