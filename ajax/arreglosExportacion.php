<?php


$forma_pago = $_POST['forma_pago'];
$codclauventa = $_POST['codclauventa'];
$codmodventa = $_POST['codmodventa'];
$codptoembarque = $_POST['codptoembarque'];
$codptodesemb = $_POST['codptodesemb'];
$codpaisdestin = $_POST['codpaisdestin'];
$codviatransp = $_POST['codviatransp'];
$codtpobultos = $_POST['codtpobultos'];
$codunidpesobruto = $_POST['codunidpesobruto'];
$codunidmedtara = $_POST['codunidmedtara'];
$codunidpesoneto = $_POST['codunidpesoneto'];
$ind_servicio = $_POST['ind_servicio'];
$nacionalidad = $_POST['nacionalidad'];


$indServicio = ["FACTURA DE SERVICIOS PERIÓDICOS DOMICILIARIOS", "FACTURA DE OTROS SERVICIOS PERIÓDICOS", 
                 "FACTURA DE SERVICIOS", "SERVICIOS DE HOTELERÍA", "SERVICIO DE TRANSPORTE TERRESTRE INTERNACIONAl"];
$codIndServicio = ["1", "2", "3", "4", "5"]; 
$IndServicio = array_combine ( $codIndServicio, $indServicio );

//---------FORMA DE PAGO EXPO----------
$FmaPagExp = ["1" => "COBRANZA HASTA 1 AÑO", "2" => "COBRANZA MÁS DE 1 AÑO", "11" => "ACREDITIVO HASTA 1 AÑO",
             "12" =>  "CRÉDITO DE BANCOS Y ORG. FINANCIEROS MÁS DE 1 AÑO", "21" =>  "SIN PAGO", "32" => "PAGO ANTICIPADO A LA FECHA DE EMBARQUE"];

//---------CLAUSULA DE VENTA-------------
$CodClauVenta =  ["1" => "CIF", "2" => "CFR", "3" => "EXW", "4" => "FAS", "5" => "FOB", "6" => "S/CL", "9" => "DDP", "10" => "FCA", "11" => "CPT", "12" => "CIP", "17" => "DAT", "18" => "DAP", "8" => "OTROS"];

//--------MODALIDAD D EVENTA----------
$CodModVenta = ["1" => "A FIRME", "2" => "BAJO CONDICIÓN", "3" => "EN CONSIGNACION LIBRE", "4" => "EN CONSIGNACION CON UN MINIMO A FIRME", "9" => "SIN PAGO"];

//----------PUERTO EMBARQUE--------------
$puertosEmb = ["ARICA", "IQUIQUE", "PUERTO PATACHE", "PUERTO PATILLO", "TOCOPILLA", "ANTOFAGASTA", "MEJILLONES", 
"MICHILLA", "PUERTO ANGAMOS", "CALETA COLOSO", "BARQUITO/CHAÑARAL", "CALDERA", "HUASCO", "COQUIMBO", "LOS VILOS", 
"GUAYACAM", "VALPARAÍSO", "QUINTERO", "VENTANAS", "ISLA DE PASCUA", "JUAN FERNANDEZ", "SAN ANTONIO", "TALCAHUANO", 
"SAN VICENTE", "LIRQUÉN", "CORONEL", "PENCO", "CORRAL", "PUERTO MONTT", "CASTRO", "ANCUD", "CHAITÉN", "QUELLÓN",
"CALBUCO", "PUERTO CHACABUCO", "PUNTA ARENAS", "CALETA CLARENCIA", "CABO NEGRO", "BAHÍA SAN GREGORIO", "POSESIÓN",
"PUERTO WILLIAMS", "TRES PUENTES", "PUERTO NATALES", "PUERTO PERCY", "PUERTO ISLA GUARELLO"];
$codEmb = ["901", "902", "204", "913", "914", "903", "915", "206", "207", "950", "917", "918", "920", "904", "199", "946",
"905", "921", "948", "929", "922", "906", "907", "908", "909", "926", "925", "930", "910", "932", "931", "934", "933", "205", 
"911", "912", "940", "942", "941", "208", "943", "209", "936", "939", "937"];

$CodPtoEmbarque = array_combine ( $codEmb, $puertosEmb );

//------------PUERTO DESEMBARQUE------------
   //CANADA
$canada = array("MONTREAL" , "COSTA DEL PACÍFICO, OTROS NO ESPECIFICADOS", "HALIFAX", "VANCOUVER", "SAINT JOHN",
 "TORONTO", "OTROS PUERTOS DE CANADÁ NO IDENTIFICADOS", "BAYSIDE", "PORT CARTIES", "QUEBEC", "PRINCE RUPERT", "HAMILTON");
$canadaCod = array("111", "112", "113", "114", "115", "116", "117", "118", "120", "124", "125", "126");
//ESTADOS UNIDOS
$estUnidos = array("BOSTON", "NEW HAVEN", "BRIDGEPORT", "NEW YORK", "FILADELFIA", "BALTIMORE", "NORFOLK",
"CHARLESTON", "SAVANAH", "MIAMI", "COSTA DEL ATLÁNTICO, OTROS NO ESPECIFICADOS COMPRENDIDOS ENTRE MAINE Y KEY WEST", "TAMPA", 
"PENSACOLA", "MOBILE", "NEW ORLEANS", "PORT ARTHUR", "GALVESTON", "CORPUS CRISTI", "BROWNSVILLE", "HOUSTON", 
"PUERTOS DEL GOLFO DE MÉXICO, OTROS NO ESPECIFICADOS COMPRENDIDOS ENTRE KEY WEST Y BROWNSVILLE", "SEATTLE", "PORTLAND", "SAN FRANCISCO",
"LOS ANGELES", "LONG BEACH", "SAN DIEGO", "COSTA DEL PACÍFICO, OTROS NO ESPECIFICADOS", "EVERGLADES", "JACKSONVILLE", "PALM BEACH",
"BATON ROUGE", "COLUMBRES", "PITTSBURGH", "DULUTH", "MILWAUKEE", "OAKLAND", "STOCKTON", "OTROS PUERTOS DE ESTADOS UNIDOS NO ESPECIFICADOS");
$estUnidosCod = array("131", "132", "133", "134", "135", "136", "137", "139", "140","141", "121", "151", "152", "153", "154",
"155", "156", "157", "158", "159", "122", "171", "172", "173", "174", "175", "176", "123", "142", "143", "145", "146", "147", "148",
"149", "150", "160", "161", "180");
//MÉXICO
$mexico = array("TAMPICO", "VERACRUZ", "GOLFO DE MÉXICO, OTROS NO ESPECIFICADOS", "COATZACOALCOS", "GUAYMAS",
"MAZATLAN", "MANZANILLO", "ACAPULCO", "COSTA DEL PACÍFICO, OTROS PUERTOS", "OTROS PUERTOS DE MÉXICO NO ESPECIFICADOS", "ALTAMIRA");
$mexicoCod = array("211", "213", "219", "214", "215", "216", "217", "218", "212", "210", "220");
//PANAMÁ
$panama = array("CRISTOBAL", "BALBOA", "COLON", "OTROS PUERTOS DE PANAMÁ NO ESPECIFICADOS");
$panamaCod = array("221", "222", "223", "224");
//COLOMBIA
$colombia = array("BUENAVENTURA", "OTROS PUERTOS DE COLOMBIA NO ESPECIFICADOS", "BARRANQUILLA");
$colombiaCod = array("232", "231", "233");
//ECUADOR
$ecuador = array("GUAYAQUIL", "OTROS PUERTOS DE ECUADOR NO ESPECIFICADOS");
$ecuadorCod = array("242", "241");
//PERÚ
$peru = array("CALLAO", "ILO", "IQUITOS", "OTROS PUERTOS DE PERÚ NO ESPECIFICADOS");
$peruCod = array("252", "253", "254", "251");
//ARGENTINA
$argentina = array("BUENOS AIRES", "NECOCHEA", "MENDOZA", "CÓRDOBA", "OTROS PUERTOS DE ARGENTINA NO ESPECIFICADOS",
"BAHÍA BLANCA", "COMODORO RIVADAVIA", "PUERTO MADRYN", "MAR DEL PLATA", "ROSARIO");
$argentinaCod = array("262", "263", "264", "265", "261", "266", "267", "268", "269", "270");
//URUGUAY
$uruguay = array("MONTEVIDEO" , "OTROS PUERTOS DE URUGUAY NO ESPECIFICADOS");
$uruguayCod = array("272", "271");
//VENEZUELA
$venezuela = array("LA GUAIRA" , "OTROS PUERTOS DE VENEZUELA NO ESPECIFICADOS","MARACAIBO");
$venezuelaCod = array("282", "281", "285");
//BRASIL
$brasil = array("SANTOS", "RIO DE JANEIRO", "RIO GRANDE DEL SUR", "PARANAGUA", "SAO PAULO", "SALVADOR",
"OTROS PUERTOS DE BRASIL NO ESPECIFICADOS");
$brasilCod = array("292", "293", "294", "295", "296", "297", "291");
//ANTILLAS HOLANDESAS
$antillas = array("CURAZAO" , "OTROS PUERTOS DE LAS ANTILLAS HOLANDESAS NO ESPECIFICADOS");
$antillasCod = array("302", "301");
//CHINA
$china = array("SHANGAI", "DAIREN", "OTROS PUERTOS DE CHINA NO ESPECIFICADOS");
$chinaCod = array("411", "412", "413");
//COREA DEL NORTE 
$coreaNorte = array("NAMPO" , "OTROS PUERTOS DE COREA DEL NORTE NO ESPECIFICADOS");
$coreaNorteCod = array("421", "420");
//COREA DEL SUR
$coreaSur = array("BUSAN" , "OTROS PUERTOS DE COREA DEL SUR NO ESPECIFICADOS");
$coreaSurCod = array("422", "423");
//FILIPINAS
$filipinas = array("MANILA" , "OTROS PUERTOS DE FILIPINAS NO ESPECIFICADOS");
$filipinasCod = array("431", "432");
//JAPÓN
$japon = array("OSAKA", "KOBE", "YOKOHAMA", "NAGOYA", "SHIMIZUI", "MOJI", "YAWATA", "FUKUYAMA",
"OTROS PUERTOS DE JAPON NO ESPECIFICADOS");
$japonCod = array("442", "443", "444", "445", "446", "447", "448", "449", "441");
//TAIWAN
$taiwan = array("KAOHSIUNG" , "KEELUNG", "OTROS PUERTOS DE TAIWAN NO ESPECIFICADOS");
$taiwanCod = array("451", "452", "453");
//IRAN
$iran = array("KARHG ISLAND" , "OTROS PUERTOS DE IRAN NO ESPECIFICADOS");
$iranCod = array("461", "462");
//INDIA
$india = array("CALCUTA", "OTROS PUERTOS DE INDIA NO ESPECIFICADOS");
$indiaCod = array("471", "472");
//BANGLADESH
$bangladesh = array("CHALNA", "OTROS PUERTOS DE BANGLADESH NO ESPECIFICADOS");
$bangladeshCod = array("481", "482");
//SINGAPUR
$singapur = array("HONG KONG", "OTROS PUERTOS DE SINGAPUR NO ESPECIFICADOS");
$singapurCod = array("492", "491");
//RUMANIA
$rumania = array("CONSTANZA", "OTROS PUERTOS DE RUMANIA NO ESPECIFICADOS");
$rumaniaCod = array("511", "512");
//BULGARIA
$bulgaria = array("VARNA", "OTROS PUERTOS DE BULGARIA NO ESPECIFICADOS");
$bulgariaCod = array("521", "522");
//CROACIA
$croacia = array("RIJEKA", "OTROS PUERTOS DE CROACIA NO ESPECIFICADOS");
$croaciaCod = array("538", "537");
//ITALIA
$italia = array("GENOVA", "LIORNA, LIVORNO", "NAPOLES", "SALERNO", "AUGUSTA", "SAVONA",
"OTROS PUERTOS DE ITALIA NO ESPECIFICADOS");
$italiaCod = array("542", "543", "544", "545", "546", "547", "541");
//FRANCIA
$francia = array("LA PALLICE", "LE HAVRE", "MARSELLA", "OTROS PUERTOS DE FRANCIA NO ESPECIFICADOS",
"BURDEOS", "CALAIS", "BREST", "RUAN");
$franciaCod = array("552", "553", "554", "551", "555", "556", "557", "558");
//ESPAÑA
$espana = array("CADIZ", "BARCELONA", "BILBAO", "HUELVA", "SEVILLA", "OTROS PUERTOS DE ESPAÑA NO ESPECIFICADOS",
"TARRAGONA", "ALGECIRAS", "VALENCIA");
$espanaCod = array("562", "563", "564", "565", "566", "561", "567", "568", "569");
//INGLATERRA
$inglaterra = array("LIVERPOOL", "LONDRES", "ROCHESTER", "ETEN SALVERRY", 
"OTROS PUERTOS DE INGLATERRA NO ESPECIFICADOS", "DOVER", "PLYMOUTH");
$inglaterraCod = array("571", "572", "573", "574", "576", "577", "578");
//FINLANDIA
$finlandia = array("HELSINSKI", "HANKO", "KEMI", "KOKKOLA", "KOTKA", "OULO", "PIETARSAARI", "PORI",
"OTROS PUERTOS DE FINLANDIA NO ESPECIFICADOS");
$finlandiaCod = array("581", "583", "584", "585", "586", "587", "588", "589", "582");
//ALEMANIA
$alemania = array("BREMEN", "HAMBURGO", "NUREMBERG", "FRANKFURT", "DUSSELDORF",
"OTROS PUERTOS DE ALEMANIA NO ESPECIFICADOS", "CUXHAVEN", "ROSTOCK", "OLDENBURG");
$alemaniaCod = array("591", "592", "593", "594", "595", "596", "597", "598", "599");
//BÉLGICA
$belgica = array("AMBERES", "OTROS PUERTOS DE BÉLGICA NO ESPECIFICADOS", "GHENT","OOSTENDE", "ZEEBRUGGE");
$belgicaCod = array("601", "602", "604",  "605", "603");
//PORTUGAL
$portugal = array("LISBOA", "OTROS PUERTOS DE PORTUGAL NO ESPECIFICADOS", "SETUBAL");
$portugalCod = array("611", "612", "613");
//HOLANDA
$holanda = array("AMSTERDAM", "ROTTERDAM", "OTROS PUERTOS DE HOLANDA NO ESPECIFICADOS");
$holandaCod = array("621", "622", "623");
//SUECIA
$suecia = array("GOTEMBURGO", "MALMO", "HELSIMBORG", "KALMAR", "OTROS PUERTOS DE SUECIA NO ESPECIFICADOS");
$sueciaCod = array("631", "633", "634", "635", "632");
//DINAMARCA
$dinamarca = array("AARHUS", "COPENHAGEN", "OTROS PUERTOS DE DINAMARCA NO ESPECIFICADOS",
"AALBORG", "ODENSE");
$dinamarcaCod = array("641", "642", "643", "644", "645");
//NORUEGA
$noruega = array("OSLO", "OTROS PUERTOS DE NORUEGA NO ESPECIFICADOS", "STAVANGER");
$noruegaCod = array("651", "652", "653");
//REPÚBLICA DE SERBIA
$serbia = array("BELGRADO", "OTROS PUERTOS DE SERBIA NO ESPECIFICADOS");
$serbiaCod = array("533", "534");
//MONTENEGRO
$montenegro = array("PODGORITSA", "OTROS PUERTOS DE MONTENEGRO NO ESPECIFICADOS");
$montenegroCod = array("535", "536");
//SUDÁFRICA
$sudafrica = array("DURBAM", "CIUDAD DEL CABO", "OTROS PUERTOS DE SUDÁFRICA NO ESPECIFICADOS", 
"SALDANHA", "PORT-ELIZABETH", "MOSSEL-BAY", "EAST-LONDON");
$sudafricaCod = array("711", "712", "713", "714", "715", "716", "717");
//AUSTRALIA
$australia = array("SIDNEY", "FREMANTLE", "ADELAIDA", "DARWIN", "GERALDTON", 
"OTROS PUERTOS DE AUSTRALIA NO ESPECIFICADOS");
$australiaCod = array("811", "812", "814", "815", "816", "813");
//OTROS
$otros = array("OTROS PUERTOS DE AMÉRICA NO ESPECIFICADOS", "OTROS PUERTOS ASIÁTICOS NO ESPECIFICADOS", 
"OTROS PUERTOS DE EUROPA NO ESPECIFICADOS", "OTROS PUERTOS DE ÁFRICA NO ESPECIFICADOS", "OTROS PUERTOS DE OCEANÍA NO ESPECIFICADOS");
$otrosCod = array( "399", "499", "699", "799", "899");

//TODOS LOS PUERTOS Y SUS CÓDIGOS RESPECTIVOS
$puertosDesemb = array_merge ($canada, $estUnidos, $mexico, $panama, $colombia, $ecuador, $peru, $argentina, $uruguay, 
$venezuela, $brasil, $antillas, $china, $coreaNorte, $coreaSur, $filipinas, $japon, $taiwan, $iran, $india, $bangladesh, 
$singapur, $rumania, $bulgaria, $croacia, $italia, $francia, $espana, $inglaterra, $finlandia, $alemania, $belgica, $portugal,
$holanda, $suecia, $dinamarca, $noruega, $serbia, $montenegro, $sudafrica, $australia, $otros);

$codDesemb = array_merge ( $canadaCod, $estUnidosCod, $mexicoCod, $panamaCod, $colombiaCod, $ecuadorCod, $peruCod,
$argentinaCod, $uruguayCod, $venezuelaCod, $brasilCod, $antillasCod, $chinaCod, $coreaNorteCod, $coreaSurCod, $filipinasCod,
$japonCod, $taiwanCod, $iranCod, $indiaCod, $bangladeshCod, $singapurCod, $rumaniaCod, $bulgariaCod, $croaciaCod, $italiaCod,
$franciaCod, $espanaCod, $inglaterraCod, $finlandiaCod, $alemaniaCod, $belgicaCod, $portugalCod, $holandaCod, $sueciaCod,
$dinamarcaCod, $noruegaCod, $serbiaCod, $montenegroCod, $sudafricaCod, $australiaCod, $otrosCod);

$CodPtoDesemb = array_combine ( $codDesemb, $puertosDesemb );

//----------PAIS DESTINO / RECEPTOR----------

$paises = array("AFGHANISTAN", "ALBANIA", "ALEMANIA", "ANDORRA", "ANGOLA", "ANGUILA", "ANTILLAS NEERLANDESAS", 
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
"UGANDA", "URUGUAY", "UZBEKISTAN", "VANUATU", "VENEZUELA", "VIETNAM", "ZAMBIA", "ZIMBABWE");

$codPaises = array("308", "518", "563", "525", "140", "242", "247", "240", "302", "127", "224", "540", "243",
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
"559", "136", "223", "560", "415", "201", "325", "117", "116");

$CodPaisDestin = array_combine ( $codPaises, $paises );
$Nacionalidad = array_combine ( $codPaises, $paises );

//------------- VIA DE TRANSPORTE-----------
$CodViaTransp = ["1" => "MARÍTIMA, FLUVIAL Y LACUSTRE", "4" => "AÉREO", "5" =>  "POSTAL", "6" => "FERROVIARIO", "7" => "CARRETERO / TERRESTRE", "8" => "OLEODUCTOS, GASODUCTOS", "9" => "TENDIDO ELÉCTRICO (Aéreo, Subterráneo)", "10" => "OTRA"];

//---------------TIPO DE BULTOS---------------
$tiposBulto = array ("GRANEL SÓLIDO, PARTICULAS FINAS (POLVO)", "GRANEL SÓLIDO, PARTICULAS GRANULARES (GRANOS)", 
"GRANEL SÓLIDO, PARTICULAS GRANDES (NÓDULOS)", "GRANEL LÍQUIDO", "GRANEL GASEOSO", "PIEZAS", "TUBOS", "CILINDRO",
"ROLLOS", "BARRAS", "LINGOTES", "TRONCOS", "BLOQUE", "ROLLIZO", "CAJÓN", "CAJAS DE CARTÓN", "FARDO", "BAÚL", "COFRE",
"ARMAZÓN", "BANDEJA", "CAJAS DE MADERA", "CAJAS DE LATA", "BOTELLA DE GAS", "BOTELLA", "JAULAS", "BIDÓN", "JABAS",
"CESTAS", "BARRILETE", "TONEL", "PIPAS", "JARRO", "FRASCO", "DAMAJUANA", "BARRIL", "TAMBOR", "CUÑETES", "TARROS", 
"CUBO", "PAQUETE", "SACOS", "MALETA", "BOLSA", "BALA", "RED", "SOBRES", "CONTENEDOR DE 20 PIES DRY", "CONTENEDOR DE 40 PIES DRY",
"CONTENEDOR REFRIGERADO 20 PIES", "CONTENEDOR REFRIGERADO 40 PIES", "ESTANQUE (no utilizar para contenedor Tank)", 
"CONTENEDOR NO ESPECIFICADO (Open Top, Tank, Flat Rack, otros)", "PALLETS", "TABLERO", "LÁMINAS", "CARRETE", 
"AUTOMOTOR", "ATAUD", "MAQUINARIA", "PLANCHAS", "ATADOS", "BOBINA", "OTROS BULTOS NO ESPECIFICADOS","NO EXISTE BULTO",
"SIN EMBALAR");
$codBultos = array ("1", "2", "3", "4", "5", "10", "11", "12", "13", "16", "17", "18", "19", "20", "21", "22",
"23", "24", "25", "26", "27", "28", "29", "31", "32", "33", "34", "35", "36", "37", "38", "39", "41", "42", "43",
"44", "45", "46", "47", "51", "61", "62", "63", "64", "65", "66", "67", "73", "74", "75", "76", "77", "78", "80",
"81", "82", "83", "85", "86", "88", "89", "90", "91", "93", "98", "99");

$CodTpoBultos = array_combine ( $codBultos, $tiposBulto );

//----------------UNIDAD MEDIDA TARA-----------------
$medidasTara = array ("U", "2U" , "MU" , "KN", "U(JUEGO/MAZO)", "LT", "MKWH", "M", "MT2", "MT3", "KLT");
$codMedidasTara = array ("10", "17", "13", "6", "12", "9", "3", "14", "15", "16", "5");

$CodUnidMedTara  = array_combine ( $codMedidasTara , $medidasTara );

//----------------PESO NETO---------------------
$CodUnidPesoNeto = array_combine ( $codMedidasTara , $medidasTara );

//----------------PESO BRUTO---------------------
$CodUnidPesoBruto = array_combine ( $codMedidasTara , $medidasTara );

//ENVIAR ARRAY COMO JSON

if(!empty($forma_pago))
{
      $FmaPagExpText = $FmaPagExp[$forma_pago];
}
else
{
      $FmaPagExpText = "";
}
if(!empty($codclauventa))
{
    $CodClauVentaText = $CodClauVenta[$codclauventa];
}
else
{
      $CodClauVentaText = "";
}

if(!empty($codmodventa))
{
      $CodModVentaText =  $CodModVenta[$codmodventa];
}
else
{
      $CodModVentaText = "";
}

if(!empty($codptoembarque))
{
     $CodPtoEmbarqueText = $CodPtoEmbarque[$codptoembarque];
}
else
{
      $CodPtoEmbarqueText = "";
}

if(!empty($codptodesemb))
{
     $CodPtoDesembText =  $CodPtoDesemb[$codptodesemb];
}
else
{
      $CodPtoDesembText = "";
}

if(!empty($codpaisdestin))
{
      $CodPaisDestinText = $CodPaisDestin[$codpaisdestin];
}
else
{
      $CodPaisDestinText = "";
}

if(!empty($codviatransp))
{
     $CodViaTranspText = $CodViaTransp[$codviatransp];
}
else
{
      $CodViaTranspText = "";
}

if(!empty($codtpobultos))
{
      $CodTpoBultosText =$CodTpoBultos[$codtpobultos];
}
else
{
      $CodTpoBultosText = "";
}

if(!empty($codunidpesobruto))
{
      $CodUnidPesoBrutoText = $CodUnidPesoBruto[$codunidpesobruto];
}
else
{
      $CodUnidPesoBrutoText = "";
}

if(!empty($codunidmedtara))
{
      $CodUnidMedTaraText = $CodUnidMedTara[$codunidmedtara];
}
else
{
      $CodUnidMedTaraText = "";
}

if(!empty($codunidpesoneto))
{
      $CodUnidPesoNetoText = $CodUnidPesoNeto[$codunidpesoneto];
}
else
{
      $CodUnidPesoNetoText = "";
}

if(!empty($ind_servicio))
{
      $IndServicio = $IndServicio[$ind_servicio];
}
else
{
      $IndServicio = "";
}

if(!empty($nacionalidad))
{
      $Nacionalidad = $Nacionalidad[$nacionalidad];
}
else
{
      $Nacionalidad = "";
}

$valores = array ($FmaPagExpText,  $CodClauVentaText, $CodModVentaText, $CodPtoEmbarqueText , $CodPtoDesembText, $CodPaisDestinText,
      $CodViaTranspText, $CodTpoBultosText, $CodUnidPesoBrutoText, $CodUnidMedTaraText, $CodUnidPesoNetoText, $IndServicio, $Nacionalidad);

echo json_encode($valores);

?>
