<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ERROR);
ini_set("display_errors",1);
//require 'inc/html2pdf/html2pdf.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
require '../config/Conexion.php';
use Spipu\Html2Pdf\Html2Pdf;

//echo "<pre>";
//print_r($_GET);
session_start();

$conn = dbCertificacion();
$XMLDTE = new DOMDocument();
$XMLDTE->formatOutput = FALSE;
$XMLDTE->preserveWhiteSpace = TRUE;
$XMLDTE->encoding = "ISO-8859-1";
$content = "<page>";
$unidad=$_SESSION["unidadsii"];
$numResol = $_SESSION['numResol'];
$fechaResol = $_SESSION['fechaResol'];
$carpeta=$_SESSION["carpeta"];   
if($_POST["tipo"]=="PROCESADOS"){
    $rutaDte="../procesos/xml_procesados/".substr($_SESSION["rut"],0,-2)."/".$_POST["f"];
}else{
    $rutaDte="../procesos/xml_emitidos/".substr($_SESSION["rut"],0,-2)."/".$_POST["f"];
}


if($XMLDTE->load($rutaDte)){
    $DTE=$XMLDTE->getElementsByTagName("DTE");
    foreach($DTE as $Documento){
        if($Documento->getElementsByTagName("Folio")->item(0)->nodeValue==$_POST["ndoc"]){
            if($Documento->getElementsByTagName("FchVenc")->item(0)->nodeValue!=""){
                $fechaVenc=date("d-m-Y", strtotime($Documento->getElementsByTagName("FchVenc")->item(0)->nodeValue));
            }else{
                $fechaVenc="";
            }

            $TED = $Documento->getElementsByTagName("TED")->item(0);
            
            if($_REQUEST["debug"]==1){
                echo $Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
            }
            switch(intval($Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue)){
                case 110:
                    $TipoDocumento="FACTURA DE EXPORTACIÓN ELECTRÓNICA";
                    break;
                case 111:
                    $TipoDocumento="NOTA DE DÉBITO DE EXPORTACIÓN ELECTRÓNICA";
                    break;
                case 112:
                    $TipoDocumento="NOTA DE CRÉDITO DE EXPORTACIÓN ELECTRÓNICA";
                    break;
                default:
                    $TipoDocumento=$Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
            }
            $rutaTimbre = "../procesos/firmas/".$_SESSION["carpeta"]."/T".intval($Documento->getElementsByTagName("TipoDTE")->item(0)->nodeValue).
           "_F".$Documento->getElementsByTagName("Folio")->item(0)->nodeValue.".png";
            
            $FmaPagExp = ["1" => "COBRANZA HASTA 1 AÑO", "2" => "COBRANZA MÁS DE 1 AÑO", "11" => "ACREDITIVO HASTA 1 AÑO",
             "12" =>  "CRÉDITO DE BANCOS Y ORG. FINANCIEROS MÁS DE 1 AÑO", "21" =>  "SIN PAGO", "32" => "PAGO ANTICIPADO A LA FECHA DE EMBARQUE"];

            $CodModVenta = ["1" => "A FIRME", "2" => "BAJO CONDICIÓN", "3" => "EN CONSIGNACION LIBRE", "4" => "EN CONSIGNACION CON UN MINIMO A FIRME", "9" => "SIN PAGO"];

            $CodClauVenta =  ["1" => "CIF", "2" => "CFR", "3" => "EXW", "4" => "FAS", "5" => "FOB", "6" => "S/CL", "9" => "DDP", "10" => "FCA", "11" => "CPT", "12" => "CIP", "17" => "DAT", "18" => "DAP", "8" => "OTROS"];

            $CodViaTransp = ["1" => "MARÍTIMA, FLUVIAL Y LACUSTRE", "4" => "AÉREO", "5" =>  "POSTAL", "6" => "FERROVIARIO", "7" => "CARRETERO / TERRESTRE", "8" => "OLEODUCTOS, GASODUCTOS", "9"=> "TENDIDO ELÉCTRICO (Aéreo, Subterráneo)", "10" => "OTRA"];

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
            $codBultos = array("1", "2", "3", "4", "5", "10", "11", "12", "13", "16", "17", "18", "19", "20", "21", "22",
            "23", "24", "25", "26", "27", "28", "29", "31", "32", "33", "34", "35", "36", "37", "38", "39", "41", "42", "43",
            "44", "45", "46", "47", "51", "61", "62", "63", "64", "65", "66", "67", "73", "74", "75", "76", "77", "78", "80",
            "81", "82", "83", "85", "86", "88", "89", "90", "91", "93", "98", "99");

            $CodTpoBultos = array_combine ( $codBultos, $tiposBulto );

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

            $CodPaisRecep = array_combine ( $codPaises, $paises );

            $tipoDocsRef = array("FACTURA", "FACTURA DE VENTA BIENES Y SERVICIOS NO AFECTOS O EXENTOS DE IVA", "BOLETA", "BOLETA EXENTA",
            "FACTURA DE COMPRA", "NOTA DE DÉBITO", "NOTA DE CRÉDITO", "LIQUIDACIÓN", "LIQUIDACIÓN FACTURA", "LIQUIDACIÓN FACTURA ELECTRÓNICA",
            "FACTURA ELECTRÓNICA", "FACTURA NO AFECTA O EXENTA ELECTRÓNICA", "BOLETA ELECTRÓNICA", "BOLETA EXENTA ELECTRÓNICA",
            "FACTURA DE COMPRA ELECTRÓNICA", "NOTA DE DÉBITO ELECTRÓNICA", "NOTA DE CRÉDITO ELECTRÓNICA", "GUÍA DE DESPACHO", 
            "GUÍA DE DESPACHO ELECTRÓNICA", "FACTURA DE EXPORTACIÓN ELECTRÓNICA", "NOTA DE DÉBITO DE EXPORTACIÓN ELECTRÓNICA",
            "NOTA DE CRÉDITO DE EXPORTACIÓN ELECTRÓNICA", "ORDEN DE COMPRA", "NOTA DE PEDIDO", "CONTRATO", "RESOLUCIÓN",
            "PROCESO CHILECOMPRA", "FICHA CHILECOMPRA", "DUS", "B/L (CONOCIMIENTO DE EMBARQUE)", "AWB (AIR WILL BILL)", "MIC/DTA",
            "CARTA DE PORTE", "RESOLUCIÓN DEL SNA DONDE CALIFICA SERVICIOS DE EXPORTACIÓN", "PASAPORTE", "CERTIFICADO DE DEPÓSITO BOLSA PROD. CHILE", "VALE DE PRENDA BOLSA PROD. CHILE", "SET - Certificacion");
            $codTipoDocsRef = array("30", "32", "25", "38", "45", "55", "60", "103", "40", "43", "33", "34", "39", "41", "46", "56",
            "61", "50", "52", "110", "111", "112", "801", "802", "803", "804", "805", "806", "807", "808", "809", "810", "811", "812",
            "813", "814", "815", "SET");

            $TipoDocumentoRef = array_combine ( $codTipoDocsRef, $tipoDocsRef );

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

            $medidasTara =  array("U", "2U" , "MU" , "KN", "U(JUEGO/MAZO)", "LT", "MKWH", "M", "MT2", "MT3", "KLT" );
            $codMedidasTara = array("10", "17", "13", "6", "12", "9", "3", "14", "15", "16", "5");

            $CodUnidMedTara = array_combine ( $codMedidasTara, $medidasTara );

            $CodUnidPesoNeto = array_combine ( $codMedidasTara, $medidasTara );

            $CodUnidPesoBruto = array_combine ( $codMedidasTara, $medidasTara );

            $Telefono = $Documento->getElementsByTagName("Telefono")->item(0)->nodeValue;

            $Marcas = $Documento->getElementsByTagName("Marcas")->item(0)->nodeValue;
            

            $content.=$watermark.
            '<style media="screen" type="text/css">
body{font-family:Arial,Verdana,sans-serif;font-size:12px}#container{margin-left:30px;margin-top:40px;margin-right:30px;height:950px}.emisor{font:inherit;font-size:14px}#emisor{position:relative;left:0;font-weight:700;width:450px;line-height:120%;font-size:10px}#datosFactura{position:absolute;margin-top:60px;width:170px;right:0}#factura{border-width:3px;border-style:solid;border-color:#d64431;padding:0 3px 8px;text-align:center;line-height:90%;width:210px;margin-right:4px}#factura p{margin:5px;font-weight:700}#sii{text-align:center}.receptor{position:relative;width:681px;height:72px;margin-top:35px;font-weight:700;font-size:10px;border:1px solid #929292;border-radius:6px;padding:6px 6px 12px}.receptor table td{padding-right:3px}.tabla1{position:absolute;left:0;margin:6px 8px}.tabla2{position:absolute;margin:6px 8px;width:180px;right:0}.aduana{position:relative;width:681px;height:133px;margin-top:7px;font-weight:700;font-size:10px;border:1px solid #929292;border-radius:6px;padding:6px 6px 3px}.aduana table td{padding-right:3px;word-break:break-all}.aduana1{position:absolute;left:0;margin:6px 8px}.tablaAduana1{border-collapse:separate;border-spacing:0 3px}.aduana2{position:absolute;right:0;margin:6px 8px;width:250px}.tablaAduana2{border-collapse:separate;border-spacing:0 3px}#detalle{position:relative;width:695px;margin-top:7px}#tablaDetalle{position:absolute;border-collapse:collapse;font-size:10px;text-align:center;table-layout:fixed}#tablaDetalle td,#tablaDetalle th{padding:2px 13px}#tablaDetalle th{background-color:#0f7e9a;color:#fff;font-weight:700;border:1px solid #0f7e9a;height:8px}#tablaDetalle td{border-left:1px solid #929292;border-right:1px solid #929292;height:8px;word-wrap:break-word}#detallesInferior td{border:none;background:none}#tablaRef{border-collapse:collapse;margin-top:5px;font-size:10px;width:645px;text-align:center}#tablaRef th{background-color:#0f7e9a;color:#fff;font-weight:700;border:1px solid #0f7e9a;padding:2px 13px}#tablaRef td{border-left:1px solid #929292;border-right:1px solid #929292;word-wrap:break-word}#tablaValores{border-collapse:collapse;margin-left:10px;margin-top:5px}#tablaValores td{font-weight:700;font-size:9px;border:1px solid #929292}#recibo{position:absolute;margin-top:890px;margin-left:305px;width:350px;float:right;padding:5px;font-size:11px;text-align:justify}#datosRecibo td:nth-child(1){font-weight:700}#datosRecibo2 td:nth-child(1){font-weight:700}#acuse{border:1px solid #000;border-radius:3px;padding:5px;font-size:7px}#timbre{position:absolute;margin-left:30px;margin-top:890px;text-align:center;font-size:8px;font-weight:700;color:#d64431}.rojo{color:#d64431}.azul{color:#0f7e9a}.b{font-weight:700}
       </style>
    <body>    
        <div id="container">
            <div id="emisor"> 
                <span class="azul emisor"><b>'.strtoupper($Documento->getElementsByTagName("RznSoc")->item(0)->nodeValue).'</b></span><br>
                <b>'.strtoupper(substr($Documento->getElementsByTagName("GiroEmis")->item(0)->nodeValue,0,49)).'</b><br>
                '.strtoupper(substr($Documento->getElementsByTagName("DirOrigen")->item(0)->nodeValue,0,30)).'&nbsp;,&nbsp;'
                 .strtoupper($Documento->getElementsByTagName("CmnaOrigen")->item(0)->nodeValue).',&nbsp;'				
				 .strtoupper($Documento->getElementsByTagName("CiudadOrigen")->item(0)->nodeValue).'<br>'
				 .'E-mail: '.strtoupper($Documento->getElementsByTagName("CorreoEmisor")->item(0)->nodeValue).'<br>';
                if($Telefono!="")
                {
                    $content.='Teléfono: '.$Telefono;
                }
                $content.='<br><br><br>   
            </div>
            <div id="datosFactura">
                <div id="factura" class="rojo">
                    <p><b>R.U.T.: '.rut_format($Documento->getElementsByTagName("RUTEmisor")->item(0)->nodeValue).'</b></p>
                    <p><b>'.strtoupper($TipoDocumento).'</b></p>
                    <p><b>Nº&nbsp;'.str_pad($Documento->getElementsByTagName("Folio")->item(0)->nodeValue, 9,"0",STR_PAD_LEFT).'</b></p>
                </div>
                <div id="sii" class="rojo"><b>S.I.I. - '.$unidad.'</b></div>
            </div>   
            <div class="receptor">
                <div class="tabla1">
                    <table>
                        <tr>
                            <td><b>Emisión:</b></td><td>:</td>
                            <td style="padding-right: 5px;  width: 330px;">'.date("d-m-Y",  strtotime($Documento->getElementsByTagName("FchEmis")->item(0)->nodeValue)).'</td>                            
                        </tr>
                        <tr>                         
                            <td><b>Cliente</b></td><td>:</td>
                            <td style="padding-right: 5px;width: 330px;" >'.strtoupper($Documento->getElementsByTagName("RznSocRecep")->item(0)->nodeValue).'</td>
                        </tr>
                        <tr>                        
                            <td><b>Dirección</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 330px;">'.strtoupper($Documento->getElementsByTagName("DirRecep")->item(0)->nodeValue).'</td>
                        </tr>           
                        <tr>
                            <td><b>Giro</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 330px;">'.strtoupper($Documento->getElementsByTagName("GiroRecep")->item(0)->nodeValue).'</td>
                        </tr>                       
                    </table>
                </div>
                <div class="tabla2">
                    <table>
                        <tr>
                            <td><b>Forma de pago: </b></td>
                            <td style="padding-right: 5px; width: 170px;">'.$FmaPagExp[strtoupper($Documento->getElementsByTagName("FmaPagExp")->item(0)->nodeValue)].'</td>
                        </tr>
                        <tr>   
                            <td><b>R.U.T</b></td>
                            <td style="padding-right: 5px; width: 170px;">'.$Documento->getElementsByTagName("RUTRecep")->item(0)->nodeValue.'</td>
                        </tr>
                        <tr>       
                             <td><b>Nacionalidad</b></td>
                            <td style="padding-right: 5px; width: 170px;">'.strtoupper($Documento->getElementsByTagName("Nacionalidad")->item(0)->nodeValue).'</td>
                        </tr>                    
                    </table>
                </div>
            </div>    
            <div class="aduana">
                <div class="aduana1">
                    <table class=tablaAduana1>
                        <tr>
                            <td><b>Modalidad de venta</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 310px">'. $CodModVenta[strtoupper($Documento->getElementsByTagName("CodModVenta")->item(0)->nodeValue)].'</td>                           
                        </tr>
                        <tr>
                           <td><b>Vía de transporte</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 310px">'.$CodViaTransp[strtoupper($Documento->getElementsByTagName("CodViaTransp")->item(0)->nodeValue)].'</td>                            
                          
                        </tr>
                        <tr>
                            <td><b>Puerto Embarque</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 310px">'.$CodPtoEmbarque[strtoupper($Documento->getElementsByTagName("CodPtoEmbarque")->item(0)->nodeValue)].'</td>
                        </tr>
                        <tr>
                             <td><b>Puerto Desembarque</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 310px">'.$CodPtoDesemb[strtoupper($Documento->getElementsByTagName("CodPtoDesemb")->item(0)->nodeValue)].'</td>
                        </tr>   
                        <tr>
                            <td><b>País receptor</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 310px">'.$CodPaisRecep[strtoupper($Documento->getElementsByTagName("CodPaisRecep")->item(0)->nodeValue)].'</td>                         
                        </tr>   
                        <tr>  
                            <td><b>País destino</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 310px">'.$CodPaisDestin[strtoupper($Documento->getElementsByTagName("CodPaisDestin")->item(0)->nodeValue)].'</td>
                        </tr>
                        <tr>
                            <td><b>Bultos</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 310px">'.strtoupper($Documento->getElementsByTagName("CantBultos")->item(0)->nodeValue).' '
                            .$CodTpoBultos[strtoupper($Documento->getElementsByTagName("CodTpoBultos")->item(0)->nodeValue)].'&nbsp;';
                            if($Marcas!="")
                            {
                                $content.=' ('.$Marcas.')';
                            }
                        $content.='</td></tr>
                        <tr>
                            <td><b>Total Bultos</b></td><td>:</td>
                            <td style="padding-right: 5px; width: 310px">'.strtoupper($Documento->getElementsByTagName("TotBultos")->item(0)->nodeValue).'</td>
                        </tr>            
                    </table>
                </div>
                <div class="aduana2">
                    <table class=tablaAduana2>
                        <tr>                      
                            <td><b>Cláusula de venta</b></td>
                            <td style="padding-right: 15px;">:&nbsp;&nbsp;'.$CodClauVenta[$Documento->getElementsByTagName("CodClauVenta")->item(0)->nodeValue].'</td>
                        </tr>
                        <tr>
                            <td><b>Total Cláusula</b></td>
                            <td style="padding-right: 15px;">:&nbsp;&nbsp;'.strtoupper($Documento->getElementsByTagName("TotClauVenta")->item(0)->nodeValue).'</td>
                        </tr>
                        <tr>
                             <td><b>Monto flete</b></td>
                            <td style="padding-right: 15px;">:&nbsp;&nbsp;'.strtoupper($Documento->getElementsByTagName("MntFlete")->item(0)->nodeValue).'</td>
                        </tr>
                        <tr>
                            <td><b>Monto seguro</b></td>
                            <td style="padding-right: 15px;">:&nbsp;&nbsp;'.strtoupper($Documento->getElementsByTagName("MntSeguro")->item(0)->nodeValue).'</td>
                        </tr>   
                        <tr>
                            <td><b>Unidad Tara</b></td>
                            <td style="padding-right: 15px;">:&nbsp;&nbsp;'.$CodUnidMedTara[strtoupper($Documento->getElementsByTagName("CodUnidMedTara")->item(0)->nodeValue)].
                            '</td> 
                        </tr>   
                        <tr>  
                             <td><b>Unidad peso Neto</b></td>
                            <td style="padding-right: 15px;">:&nbsp;&nbsp;'.$CodUnidPesoNeto[strtoupper($Documento->getElementsByTagName("CodUnidPesoNeto")->item(0)->nodeValue)].'</td>                                                       
                        </tr>
                        <tr>                              
                            <td><b>Unidad peso Bruto</b></td>
                            <td style="padding-right: 5px;">:&nbsp;&nbsp;'.$CodUnidPesoBruto[strtoupper($Documento->getElementsByTagName("CodUnidPesoBruto")->item(0)->nodeValue)].'</td>
                        </tr>         
                    </table>
                </div>
            </div>  
            <div id="detalle" >
                <table id="tablaDetalle">
                    <tr>
                        <th style="width: 17px;"><b>Cantidad</b></th>
                        <th style="width: 200px;"><b>Descripción</b></th>
                        <th style="width: 16px;"><b>Unidad</b></th>
                        <th style="width: 11px;"><b>Desc. %</b></th>
                        <th style="width: 9px;"><b>Rec. %</b></th>
                        <th style="width: 28px;"><b>Unitario</b></th>
                        <th style="width: 28px;"><b>Total</b></th>
                    </tr>';
                    $Detalles = $Documento->getElementsByTagName("Detalle");
                    $lin = 0;
                    foreach($Detalles as $Detalle) 
                    { 
                        if(trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue)!="")
                        {
                            if(trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue)!=trim($Detalle->getElementsByTagName("NmbItem")->item(0)->nodeValue))
                            {
                                $DescItem = trim($Detalle->getElementsByTagName("DscItem")->item(0)->nodeValue);
                            }
                        }else
                        {
                            $DescItem ="";
                        }
                        $content.=
                        '<tr>
                            <td>'.number_format($Detalle->getElementsByTagName("QtyItem")->item(0)->nodeValue,2,".",",").'</td>
                           <td style="width: 200px;">'.substr($Detalle->getElementsByTagName("NmbItem")->item(0)->nodeValue,0,60).substr($DescItem,0,72).'</td>                                
                            <td>'.$Detalle->getElementsByTagName("UnmdItem")->item(0)->nodeValue.'</td>
                            <td>'.number_format($Detalle->getElementsByTagName("DescuentoPct")->item(0)->nodeValue,2,'.',',').'</td>
                            <td>'.number_format($Detalle->getElementsByTagName("RecargoPct")->item(0)->nodeValue,2,'.',',').'</td>
                            <td>'.number_format($Detalle->getElementsByTagName("PrcItem")->item(0)->nodeValue,2,'.',',').'</td>                            
                            <td>'.number_format($Detalle->getElementsByTagName("MontoItem")->item(0)->nodeValue,2,'.',',').'</td>
                        </tr>';
                        $lin++;
                    }
                    $hasta=20 - $lin;
                    for($l=0;$l<=$hasta;$l++)
                    {
                        $content.='<tr><td>&nbsp;</td>';
                        $content.='<td>&nbsp;</td>';
                        $content.='<td>&nbsp;</td>';
                        $content.='<td>&nbsp;</td>';
                        $content.='<td>&nbsp;</td>';
                        $content.='<td>&nbsp;</td>';
                        $content.='<td>&nbsp;</td></tr>';
                    }
                    $content.='<tr><td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td>';
                    $content.='<td style="border-bottom: 1px solid #929292;">&nbsp;</td></tr>
                </table>
                <table id=detallesInferior>
                <tr>
                <td style="width: 510px; font-size:10px; padding-top:5px;">';
                $Referencias = $Documento->getElementsByTagName("Referencia");
                    $contador = 1;
                    foreach($Referencias as $Referencia) 
                    {

                        $content.='<b>Referencia: </b>'.$contador.' - '.$TipoDocumentoRef[$Referencia->getElementsByTagName("TpoDocRef")->item(0)->nodeValue].
                        ' N° '.$Referencia->getElementsByTagName("FolioRef")->item(0)->nodeValue.' del '.$Referencia->getElementsByTagName("FchRef")->item(0)->nodeValue.': '.$Referencia->getElementsByTagName("RazonRef")->item(0)->nodeValue.'<br>';
                        $contador++;
                    }
                     $content.='</td>
                <td>
                <table id="tablaValores">';     
                    $DctoRecs = $Documento->getElementsByTagName("DscRcgGlobal");
                            foreach($DctoRecs as $DctoRec) 
                            {
                                if(($DctoRec->getElementsByTagName("TpoMov")->item(0)->nodeValue) == "D")
                                {
                                    $tipoMov = "Descuento";
                                }
                                else if(($DctoRec->getElementsByTagName("TpoMov")->item(0)->nodeValue) == "R")
                                {
                                    $tipoMov = "Recargo";
                                }
                                $content.='<tr><td style=" padding-right: 10px; width: 50px">'.$tipoMov.'&nbsp;'.$DctoRec->getElementsByTagName("TpoValor")->item(0)->nodeValue.'</td><td style="padding-left: 10px;  text-align: right; width: 60px; background-color: #ececec;">'.$DctoRec->getElementsByTagName("ValorDR")->item(0)->nodeValue.'</td><tr>';
                            }
                        $content.='<tr>
                            <td style=" padding-right: 10px; width: 50px">Exento $</td><td style="padding-left: 10px;  text-align: right; width: 60px; background-color: #ececec;">'.number_format($Documento->getElementsByTagName("MntExe")->item(0)->nodeValue,2,".",",").'</td>
                        </tr>
                        <tr>
                            <td style=" padding-right: 10px; width: 50px;">Moneda</td><td style="padding-left: 10px; text-align: right; width: 60px; background-color: #ececec;">'.$Documento->getElementsByTagName("TpoMoneda")->item(0)->nodeValue.'</td>
                        </tr>
                        <tr>
                            <td style=" padding-right: 10px; width: 50px">Exento $</td><td style="padding-left: 10px;  text-align: right; width: 60px; background-color: #ececec;">'.number_format($Documento->getElementsByTagName("MntExe")->item(0)->nodeValue,2,".",",").'</td>
                        </tr>
                        <tr>
                            <td style=" padding-right: 10px; width: 50px">Total $</td><td style="padding-left: 10px;  text-align: right; width: 60px; background-color: #ececec;">'.number_format($Documento->getElementsByTagName("MntTotal")->item(0)->nodeValue,2,".",",").'</td>
                        </tr>
                        <tr>
                            <td style=" padding-right: 10px; width: 50px">Total CLP $</td><td style="padding-left: 10px; text-align: right; width: 60px; background-color: #ececec;">'.number_format($Documento->getElementsByTagName("MntTotOtrMnda")->item(0)->nodeValue,2,".",",").'</td>
                        </tr>
                    </table>
                </td> </tr> </table>
            </div>';
            $content.=
                '<div id="timbre">                           
                        <img src="'.$rutaTimbre.'" width=60mm;/><br><br>
                        Timbre Electr&oacute;nico SII<br>
                        Resolución '.$numResol.' de '.getDate(strtotime($fechaResol))["year"].'<br>
                       Verifique documento: www.sii.cl
                </div> 
            </div>

    </body>'; 
        }
    }
}else{
    $content.=$rutaDte;
}
$content.="</page>";

if(!isset($_REQUEST["debug"])){
    $html2pdf = new Html2Pdf('P','letter','es');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf');
}else{
    echo $content;   
}
    
function numtoletras($xcifra){ 
    $xarray = array(0 => "Cero",1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA", 100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS");

    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)){
	if ($xpos_punto == 0)		{
            $xcifra = "0".$xcifra;
            $xpos_punto = strpos($xcifra, ".");
	}
	$xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
	$xdecimales = substr($xcifra."00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }
 
    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for($xz = 0; $xz < 3; $xz++){
	$xaux = substr($XAUX, $xz * 6, 6);
	$xi = 0; $xlimite = 6; // inicializo el contador de centenas xi y establezco el l&#65533;mite a 6 d&#65533;gitos en la parte entera
	$xexit = true; // bandera para controlar el ciclo del While	
	while ($xexit)
		{
		if ($xi == $xlimite) // si ya lleg&#65533; al l&#65533;mite máximo de enteros
			{
			break; // termina el ciclo
			}
 
		$x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
		$xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres d&#65533;gitos)
		for ($xy = 1; $xy < 4; $xy++) // ciclo para revisar centenas, decenas y unidades, en ese orden
			{
			switch ($xy) 
				{
				case 1: // checa las centenas
					if (substr($xaux, 0, 3) < 100) // si el grupo de tres d&#65533;gitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
						{
						}
					else
						{
						$xseek = $xarray[substr($xaux, 0, 3)]; // busco si la centena es n&#65533;mero redondo (100, 200, 300, 400, etc..)
						if ($xseek)
							{
							$xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Mill&#65533;n, Millones, Mil o nada)
							if (substr($xaux, 0, 3) == 100) 
								$xcadena = " ".$xcadena." CIEN ".$xsub;
							else
								$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
							$xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
							}
						else // entra aqu&#65533; si la centena no fue numero redondo (101, 253, 120, 980, etc.)
							{
							$xseek = $xarray[substr($xaux, 0, 1) * 100]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
							$xcadena = " ".$xcadena." ".$xseek;
							} // ENDIF ($xseek)
						} // ENDIF (substr($xaux, 0, 3) < 100)
					break;
				case 2: // checa las decenas (con la misma l&#65533;gica que las centenas)
					if (substr($xaux, 1, 2) < 10)
						{
						}
					else
						{
						$xseek = $xarray[substr($xaux, 1, 2)];
						if ($xseek)
							{
							$xsub = subfijo($xaux);
							if (substr($xaux, 1, 2) == 20)
								$xcadena = " ".$xcadena." VEINTE ".$xsub;
							else
								$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
							$xy = 3;
							}
						else
							{
							$xseek = $xarray[substr($xaux, 1, 1) * 10];
							if (substr($xaux, 1, 1) * 10 == 20)
								$xcadena = " ".$xcadena." ".$xseek;
							else	
								$xcadena = " ".$xcadena." ".$xseek." Y ";
							} // ENDIF ($xseek)
						} // ENDIF (substr($xaux, 1, 2) < 10)
					break;
				case 3: // checa las unidades
					if (substr($xaux, 2, 1) < 1) // si la unidad es cero, ya no hace nada
						{
						}
					else
						{
						$xseek = $xarray[substr($xaux, 2, 1)]; // obtengo directamente el valor de la unidad (del uno al nueve)
						$xsub = subfijo($xaux);
						$xcadena = " ".$xcadena." ".$xseek." ".$xsub;
						} // ENDIF (substr($xaux, 2, 1) < 1)
					break;
				} // END SWITCH
			} // END FOR
			$xi = $xi + 3;
		} // ENDDO
 
		if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
			$xcadena.= " DE";
 
		if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
			$xcadena.= " DE";
 
		// ----------- esta l&#65533;nea la puedes cambiar de acuerdo a tus necesidades o a tu pa&#65533;s -------
		if (trim($xaux) != "")
			{
			switch ($xz)
				{
				case 0:
					if (trim(substr($XAUX, $xz * 6, 6)) == "1")
						$xcadena.= "UN BILLON ";
					else
						$xcadena.= " BILLONES ";
					break;
				case 1:
					if (trim(substr($XAUX, $xz * 6, 6)) == "1")
						$xcadena.= "UN MILLON ";
					else
						$xcadena.= " MILLONES ";
					break;
				case 2:
					if ($xcifra < 1 )
						{
						$xcadena = "CERO PESOS";
						}
					if ($xcifra >= 1 && $xcifra < 2)
						{
						$xcadena = "UN PESO";
						}
					if ($xcifra >= 2)
						{
						$xcadena.= " PESOS"; // 
						}
					break;
				} // endswitch ($xz)
			} // ENDIF (trim($xaux) != "")
		// ------------------      en este caso, para M&#65533;xico se usa esta leyenda     ----------------
		$xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
		$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
		$xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
		$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles 
		$xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
		$xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
		$xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
	} // ENDFOR	($xz)
	return trim($xcadena);
} // END FUNCTION
 
 
function subfijo($xx){ // esta funci&#65533;n regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
    //	
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
    //
    return $xsub;
}

function rut_format( $rut ) {
    return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
}