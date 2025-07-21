<?php
include_once '../config/global.php';
include_once '../config/Conexion.php';
include_once '../models/Kichwa.php';

$database = new Conexion();
$db = $database->getConnection();
$kichwa = new Kichwa($db);

// Diccionario expandido de palabras Kichwa-Español extraído de los archivos
$diccionario = [
    // Frases comunes de FRASES COMUNES.txt
    ['palabra_kichwa' => 'Cutin rimapai', 'traduccion_espanol' => 'Repite por favor', 'categoria' => 'frases_comunes'],
    ['palabra_kichwa' => 'Cutin parlangui pay', 'traduccion_espanol' => 'Decirme otra vez, por favor', 'categoria' => 'frases_comunes'],
    ['palabra_kichwa' => 'Uyapay', 'traduccion_espanol' => 'Escuche por favor', 'categoria' => 'frases_comunes'],
    ['palabra_kichwa' => 'Ali Pacha', 'traduccion_espanol' => 'Muy bien, buenísimo', 'categoria' => 'expresiones'],
    ['palabra_kichwa' => 'Minga Chihuai', 'traduccion_espanol' => 'Con permiso', 'categoria' => 'cortesia'],
    ['palabra_kichwa' => 'Yupay Chihuai', 'traduccion_espanol' => 'Perdóname', 'categoria' => 'cortesia'],
    ['palabra_kichwa' => 'Cayacaman', 'traduccion_espanol' => 'Hasta luego', 'categoria' => 'despedidas'],
    ['palabra_kichwa' => 'ñucata ayudahuai ushapanguichu?', 'traduccion_espanol' => '¿Puedes ayudarme?', 'categoria' => 'preguntas'],
    ['palabra_kichwa' => 'huasica Maipita can?', 'traduccion_espanol' => '¿Dónde está la casa?', 'categoria' => 'preguntas'],
    ['palabra_kichwa' => 'Imapi trabajangui?', 'traduccion_espanol' => '¿A qué te dedicas?', 'categoria' => 'preguntas'],
    ['palabra_kichwa' => 'gustanguichu?', 'traduccion_espanol' => '¿Te gusta?', 'categoria' => 'preguntas'],
    ['palabra_kichwa' => 'Ninanda cushilla cani quiquinda rijshishcamanda', 'traduccion_espanol' => 'Mucho gusto', 'categoria' => 'cortesia'],
    ['palabra_kichwa' => 'Cushijuni rijshishcamanda', 'traduccion_espanol' => 'Mucho gusto', 'categoria' => 'cortesia'],
    ['palabra_kichwa' => 'Ninanda gushtarcani', 'traduccion_espanol' => 'Me gustó mucho', 'categoria' => 'expresiones'],
    ['palabra_kichwa' => 'Ari!', 'traduccion_espanol' => '¡Claro que sí!', 'categoria' => 'afirmaciones'],
    ['palabra_kichwa' => 'Pagui ayudashcamanda', 'traduccion_espanol' => 'Gracias por la ayuda', 'categoria' => 'cortesia'],
    ['palabra_kichwa' => 'Alcugu Úshala', 'traduccion_espanol' => 'Muévete perro', 'categoria' => 'comandos'],
    ['palabra_kichwa' => 'Na munanichu', 'traduccion_espanol' => 'No quiero', 'categoria' => 'negaciones'],
    ['palabra_kichwa' => 'Na lugarchu capani', 'traduccion_espanol' => 'Estoy ocupado', 'categoria' => 'estados'],
    ['palabra_kichwa' => 'NA rijuni', 'traduccion_espanol' => 'Estoy de salida', 'categoria' => 'estados'],
    ['palabra_kichwa' => 'Letra na canichu', 'traduccion_espanol' => 'No sé leer', 'categoria' => 'capacidades'],
    ['palabra_kichwa' => 'Huañujunimi', 'traduccion_espanol' => 'Estoy enfermo', 'categoria' => 'estados'],
    ['palabra_kichwa' => 'Ricuchingapaj munani', 'traduccion_espanol' => 'Quiero mostrarte', 'categoria' => 'deseos'],
    ['palabra_kichwa' => 'Yari', 'traduccion_espanol' => 'Pues', 'categoria' => 'expresiones'],
    ['palabra_kichwa' => 'Yanga', 'traduccion_espanol' => 'No vale', 'categoria' => 'expresiones'],
    ['palabra_kichwa' => 'Imashtami', 'traduccion_espanol' => 'mmm (estoy pensando)', 'categoria' => 'expresiones'],
    ['palabra_kichwa' => 'Familia "mari"', 'traduccion_espanol' => 'Familia con énfasis', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'Cunan tiempomari', 'traduccion_espanol' => 'Ahora mismo', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'Ñallami chayamujun', 'traduccion_espanol' => 'Ya mismo viene', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'Cushijushca cani', 'traduccion_espanol' => 'Estoy feliz', 'categoria' => 'emociones'],
    ['palabra_kichwa' => 'chayashunllama', 'traduccion_espanol' => 'Él llegará no más', 'categoria' => 'futuro'],
    ['palabra_kichwa' => 'Ama desanimarpanguichichu', 'traduccion_espanol' => 'No te desanimes', 'categoria' => 'consejos'],
    ['palabra_kichwa' => 'Paicunata rijsingapaj munapaimanmi', 'traduccion_espanol' => 'Quiere conocerlos', 'categoria' => 'deseos'],
    ['palabra_kichwa' => 'Yachachidor', 'traduccion_espanol' => 'Profesor', 'categoria' => 'profesiones'],
    ['palabra_kichwa' => 'Rin cashcanchi', 'traduccion_espanol' => 'Habíamos ido', 'categoria' => 'pasado'],
    ['palabra_kichwa' => 'Shamun cashcanchi', 'traduccion_espanol' => 'Habíamos venido', 'categoria' => 'pasado'],
    ['palabra_kichwa' => 'Ayudahuashca', 'traduccion_espanol' => 'Ayúdanos', 'categoria' => 'solicitudes'],

    // Palabras del diccionario principal (RK_diccionario_kichwa_castellano.txt)
    ['palabra_kichwa' => 'achachay', 'traduccion_espanol' => 'expresión de frío', 'categoria' => 'interjecciones'],
    ['palabra_kichwa' => 'achachaw', 'traduccion_espanol' => 'expresión de calor', 'categoria' => 'interjecciones'],
    ['palabra_kichwa' => 'achka', 'traduccion_espanol' => 'bastante, harto, mucho', 'categoria' => 'adverbios'],
    ['palabra_kichwa' => 'achik', 'traduccion_espanol' => 'luz, claridad, claro', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'achiklla', 'traduccion_espanol' => 'lúcido, claro, nítido', 'categoria' => 'adjetivos'],
    ['palabra_kichwa' => 'achikmama', 'traduccion_espanol' => 'madrina', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'achikyana', 'traduccion_espanol' => 'brillar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'achikyaya', 'traduccion_espanol' => 'padrino', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'achillik-yaya', 'traduccion_espanol' => 'dios', 'categoria' => 'religion'],
    ['palabra_kichwa' => 'achira', 'traduccion_espanol' => 'achera', 'categoria' => 'plantas'],
    ['palabra_kichwa' => 'achukcha', 'traduccion_espanol' => 'achogcha', 'categoria' => 'plantas'],
    ['palabra_kichwa' => 'achupalla', 'traduccion_espanol' => 'achupalla', 'categoria' => 'plantas'],
    ['palabra_kichwa' => 'ahana', 'traduccion_espanol' => 'ofender, insultar, denigrar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'akankaw', 'traduccion_espanol' => 'variedad de pava', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'akapana', 'traduccion_espanol' => 'remolino de viento', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'akcha', 'traduccion_espanol' => 'pelo, cabello', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'akcha shuwa', 'traduccion_espanol' => 'libélula, cortapelos', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'akichana', 'traduccion_espanol' => 'cernir en harnero o cedazo', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'akirinri', 'traduccion_espanol' => 'jengibre', 'categoria' => 'plantas'],
    ['palabra_kichwa' => 'akllana', 'traduccion_espanol' => 'elegir, escoger, seleccionar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'aklluna', 'traduccion_espanol' => 'tartamudear', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'akma', 'traduccion_espanol' => 'que no se cocina', 'categoria' => 'adjetivos'],
    ['palabra_kichwa' => 'aknina', 'traduccion_espanol' => 'eructar, dudar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'aksu', 'traduccion_espanol' => 'tipo de falda', 'categoria' => 'vestimenta'],

    // Palabras adicionales del diccionario LEXTN
    ['palabra_kichwa' => 'achachaw', 'traduccion_espanol' => '¡qué calor!', 'categoria' => 'interjecciones'],
    ['palabra_kichwa' => 'achuchu', 'traduccion_espanol' => 'es caliente, no toques', 'categoria' => 'advertencias'],
    ['palabra_kichwa' => 'achukcha', 'traduccion_espanol' => 'achogcha (verdura)', 'categoria' => 'alimentos'],
    ['palabra_kichwa' => 'achikyachik', 'traduccion_espanol' => 'foco', 'categoria' => 'objetos'],
    ['palabra_kichwa' => 'ajirinri', 'traduccion_espanol' => 'jengibre', 'categoria' => 'plantas'],

    // Vocabulario básico esencial
    ['palabra_kichwa' => 'warmi', 'traduccion_espanol' => 'mujer', 'categoria' => 'personas'],
    ['palabra_kichwa' => 'kari', 'traduccion_espanol' => 'hombre', 'categoria' => 'personas'],
    ['palabra_kichwa' => 'wawa', 'traduccion_espanol' => 'niño, bebé', 'categoria' => 'personas'],
    ['palabra_kichwa' => 'mama', 'traduccion_espanol' => 'madre', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'tayta', 'traduccion_espanol' => 'padre', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'yaya', 'traduccion_espanol' => 'papá', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'pani', 'traduccion_espanol' => 'hermana', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'wawki', 'traduccion_espanol' => 'hermano', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'uchilla', 'traduccion_espanol' => 'pequeño', 'categoria' => 'adjetivos'],
    ['palabra_kichwa' => 'hatun', 'traduccion_espanol' => 'grande', 'categoria' => 'adjetivos'],
    ['palabra_kichwa' => 'sumak', 'traduccion_espanol' => 'bonito, hermoso', 'categoria' => 'adjetivos'],
    ['palabra_kichwa' => 'alli', 'traduccion_espanol' => 'bueno', 'categoria' => 'adjetivos'],
    ['palabra_kichwa' => 'millay', 'traduccion_espanol' => 'feo', 'categoria' => 'adjetivos'],
    ['palabra_kichwa' => 'yura', 'traduccion_espanol' => 'blanco', 'categoria' => 'colores'],
    ['palabra_kichwa' => 'yana', 'traduccion_espanol' => 'negro', 'categoria' => 'colores'],
    ['palabra_kichwa' => 'puka', 'traduccion_espanol' => 'rojo', 'categoria' => 'colores'],
    ['palabra_kichwa' => 'killu', 'traduccion_espanol' => 'amarillo', 'categoria' => 'colores'],
    ['palabra_kichwa' => 'ankas', 'traduccion_espanol' => 'azul', 'categoria' => 'colores'],
    ['palabra_kichwa' => 'cuchu', 'traduccion_espanol' => 'verde', 'categoria' => 'colores'],
    ['palabra_kichwa' => 'inti', 'traduccion_espanol' => 'sol', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'killa', 'traduccion_espanol' => 'luna', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'yakana', 'traduccion_espanol' => 'estrella', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'yaku', 'traduccion_espanol' => 'agua', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'nina', 'traduccion_espanol' => 'fuego', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'wayra', 'traduccion_espanol' => 'viento', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'allpa', 'traduccion_espanol' => 'tierra', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'urku', 'traduccion_espanol' => 'cerro, montaña', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'rumi', 'traduccion_espanol' => 'piedra', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'wasi', 'traduccion_espanol' => 'casa', 'categoria' => 'lugares'],
    ['palabra_kichwa' => 'llakta', 'traduccion_espanol' => 'pueblo', 'categoria' => 'lugares'],
    ['palabra_kichwa' => 'chakra', 'traduccion_espanol' => 'chacra, terreno cultivado', 'categoria' => 'lugares'],
    ['palabra_kichwa' => 'sacha', 'traduccion_espanol' => 'bosque, selva', 'categoria' => 'lugares'],
    ['palabra_kichwa' => 'puyu', 'traduccion_espanol' => 'nube', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'tamya', 'traduccion_espanol' => 'lluvia', 'categoria' => 'naturaleza'],
    ['palabra_kichwa' => 'chiri', 'traduccion_espanol' => 'frío', 'categoria' => 'temperatura'],
    ['palabra_kichwa' => 'rupay', 'traduccion_espanol' => 'calor', 'categoria' => 'temperatura'],
    ['palabra_kichwa' => 'mikuy', 'traduccion_espanol' => 'comida', 'categoria' => 'alimentos'],
    ['palabra_kichwa' => 'upyana', 'traduccion_espanol' => 'bebida', 'categoria' => 'alimentos'],
    ['palabra_kichwa' => 'papa', 'traduccion_espanol' => 'papa', 'categoria' => 'alimentos'],
    ['palabra_kichwa' => 'sara', 'traduccion_espanol' => 'maíz', 'categoria' => 'alimentos'],
    ['palabra_kichwa' => 'kinwa', 'traduccion_espanol' => 'quinua', 'categoria' => 'alimentos'],
    ['palabra_kichwa' => 'tarwi', 'traduccion_espanol' => 'chocho', 'categoria' => 'alimentos'],
    ['palabra_kichwa' => 'ukucha', 'traduccion_espanol' => 'ratón', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'atuk', 'traduccion_espanol' => 'lobo', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'puma', 'traduccion_espanol' => 'puma', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'ukumari', 'traduccion_espanol' => 'oso', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'pisca', 'traduccion_espanol' => 'ave, pájaro', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'wallpa', 'traduccion_espanol' => 'gallina', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'atallpa', 'traduccion_espanol' => 'gallo', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'llama', 'traduccion_espanol' => 'llama', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'alpaca', 'traduccion_espanol' => 'alpaca', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'waka', 'traduccion_espanol' => 'vaca', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'chanchi', 'traduccion_espanol' => 'cerdo', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'allku', 'traduccion_espanol' => 'perro', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'misi', 'traduccion_espanol' => 'gato', 'categoria' => 'animales'],
    ['palabra_kichwa' => 'uma', 'traduccion_espanol' => 'cabeza', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'ñawi', 'traduccion_espanol' => 'ojo', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'rinri', 'traduccion_espanol' => 'oreja', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'simi', 'traduccion_espanol' => 'boca', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'kiru', 'traduccion_espanol' => 'diente', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'gallu', 'traduccion_espanol' => 'lengua', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'kunka', 'traduccion_espanol' => 'cuello', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'rikra', 'traduccion_espanol' => 'brazo', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'maki', 'traduccion_espanol' => 'mano', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'ruk\'ana', 'traduccion_espanol' => 'dedo', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'wiksa', 'traduccion_espanol' => 'barriga', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'chaki', 'traduccion_espanol' => 'pie', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'shunku', 'traduccion_espanol' => 'corazón', 'categoria' => 'cuerpo'],
    ['palabra_kichwa' => 'yana', 'traduccion_espanol' => 'cocinar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'mikuna', 'traduccion_espanol' => 'comer', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'upyana', 'traduccion_espanol' => 'beber', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'puñuna', 'traduccion_espanol' => 'dormir', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'rikuna', 'traduccion_espanol' => 'ver', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'uyarina', 'traduccion_espanol' => 'oír', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'rimana', 'traduccion_espanol' => 'hablar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'purirana', 'traduccion_espanol' => 'caminar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'llankana', 'traduccion_espanol' => 'trabajar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'yachana', 'traduccion_espanol' => 'aprender', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'yachachina', 'traduccion_espanol' => 'enseñar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'killkana', 'traduccion_espanol' => 'escribir', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'ñawparina', 'traduccion_espanol' => 'leer', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'tusuna', 'traduccion_espanol' => 'bailar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'takina', 'traduccion_espanol' => 'cantar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'shuk', 'traduccion_espanol' => 'uno', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'ishkay', 'traduccion_espanol' => 'dos', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'kimsa', 'traduccion_espanol' => 'tres', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'chusku', 'traduccion_espanol' => 'cuatro', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'pichka', 'traduccion_espanol' => 'cinco', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'sukta', 'traduccion_espanol' => 'seis', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'kanchis', 'traduccion_espanol' => 'siete', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'pusak', 'traduccion_espanol' => 'ocho', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'iskun', 'traduccion_espanol' => 'nueve', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'chunka', 'traduccion_espanol' => 'diez', 'categoria' => 'numeros'],
    ['palabra_kichwa' => 'ima', 'traduccion_espanol' => 'qué', 'categoria' => 'interrogativos'],
    ['palabra_kichwa' => 'pi', 'traduccion_espanol' => 'quién', 'categoria' => 'interrogativos'],
    ['palabra_kichwa' => 'may', 'traduccion_espanol' => 'dónde', 'categoria' => 'interrogativos'],
    ['palabra_kichwa' => 'imapak', 'traduccion_espanol' => 'para qué', 'categoria' => 'interrogativos'],
    ['palabra_kichwa' => 'mashna', 'traduccion_espanol' => 'cuánto', 'categoria' => 'interrogativos'],
    ['palabra_kichwa' => 'mashna', 'traduccion_espanol' => 'cuántos', 'categoria' => 'interrogativos'],
    ['palabra_kichwa' => 'kutin', 'traduccion_espanol' => 'vez', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'puncha', 'traduccion_espanol' => 'día', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'tuta', 'traduccion_espanol' => 'noche', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'punchaw', 'traduccion_espanol' => 'día', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'kunan', 'traduccion_espanol' => 'ahora', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'ñaupa', 'traduccion_espanol' => 'antes', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'kipa', 'traduccion_espanol' => 'después', 'categoria' => 'tiempo'],
    ['palabra_kichwa' => 'Kichwa', 'traduccion_espanol' => 'Kichwa (idioma)', 'categoria' => 'idioma'],
    ['palabra_kichwa' => 'shimi', 'traduccion_espanol' => 'idioma, lengua', 'categoria' => 'idioma'],
    ['palabra_kichwa' => 'rimay', 'traduccion_espanol' => 'palabra', 'categoria' => 'idioma'],
    ['palabra_kichwa' => 'willana', 'traduccion_espanol' => 'contar, narrar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'amuyana', 'traduccion_espanol' => 'callar', 'categoria' => 'verbos'],

    // Vestimenta tradicional
    ['palabra_kichwa' => 'anaku', 'traduccion_espanol' => 'vestido tradicional', 'categoria' => 'vestimenta'],
    ['palabra_kichwa' => 'chumbi', 'traduccion_espanol' => 'faja', 'categoria' => 'vestimenta'],
    ['palabra_kichwa' => 'ushuta', 'traduccion_espanol' => 'sandalia', 'categoria' => 'vestimenta'],
    ['palabra_kichwa' => 'mama chumbi', 'traduccion_espanol' => 'faja de la madre', 'categoria' => 'vestimenta'],
    ['palabra_kichwa' => 'wallkana', 'traduccion_espanol' => 'collar', 'categoria' => 'vestimenta'],

    // Elementos ceremoniales y culturales
    ['palabra_kichwa' => 'ayni', 'traduccion_espanol' => 'reciprocidad', 'categoria' => 'cultura'],
    ['palabra_kichwa' => 'minga', 'traduccion_espanol' => 'trabajo comunitario', 'categoria' => 'cultura'],
    ['palabra_kichwa' => 'ayllu', 'traduccion_espanol' => 'familia extensa', 'categoria' => 'cultura'],
    ['palabra_kichwa' => 'pachakamak', 'traduccion_espanol' => 'creador del mundo', 'categoria' => 'religion'],
    ['palabra_kichwa' => 'apu', 'traduccion_espanol' => 'espíritu de la montaña', 'categoria' => 'religion'],
    ['palabra_kichwa' => 'hatun tayta', 'traduccion_espanol' => 'abuelo, anciano respetado', 'categoria' => 'familia'],
    ['palabra_kichwa' => 'hatun mama', 'traduccion_espanol' => 'abuela, anciana respetada', 'categoria' => 'familia'],

    // Herramientas y objetos
    ['palabra_kichwa' => 'taklla', 'traduccion_espanol' => 'arado de pie', 'categoria' => 'herramientas'],
    ['palabra_kichwa' => 'runa', 'traduccion_espanol' => 'persona, gente', 'categoria' => 'personas'],
    ['palabra_kichwa' => 'kawsay', 'traduccion_espanol' => 'vida', 'categoria' => 'abstractos'],
    ['palabra_kichwa' => 'munay', 'traduccion_espanol' => 'amor, querer', 'categoria' => 'emociones'],
    ['palabra_kichwa' => 'llakiy', 'traduccion_espanol' => 'tristeza', 'categoria' => 'emociones'],
    ['palabra_kichwa' => 'kusiy', 'traduccion_espanol' => 'alegría', 'categoria' => 'emociones'],
    ['palabra_kichwa' => 'manchay', 'traduccion_espanol' => 'miedo', 'categoria' => 'emociones'],
    ['palabra_kichwa' => 'piñay', 'traduccion_espanol' => 'enojo', 'categoria' => 'emociones'],

    // Plantas medicinales y sagradas
    ['palabra_kichwa' => 'coca', 'traduccion_espanol' => 'coca', 'categoria' => 'plantas'],
    ['palabra_kichwa' => 'wayusa', 'traduccion_espanol' => 'wayusa', 'categoria' => 'plantas'],
    ['palabra_kichwa' => 'sacha inchi', 'traduccion_espanol' => 'sacha inchi', 'categoria' => 'plantas'],
    ['palabra_kichwa' => 'muna', 'traduccion_espanol' => 'menta andina', 'categoria' => 'plantas'],
    ['palabra_kichwa' => 'paico', 'traduccion_espanol' => 'paico', 'categoria' => 'plantas'],

    // Más vocabulario esencial
    ['palabra_kichwa' => 'kay', 'traduccion_espanol' => 'ser, estar', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'tiyay', 'traduccion_espanol' => 'estar sentado', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'sayay', 'traduccion_espanol' => 'estar parado', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'kani', 'traduccion_espanol' => 'soy', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'kanki', 'traduccion_espanol' => 'eres', 'categoria' => 'verbos'],
    ['palabra_kichwa' => 'kan', 'traduccion_espanol' => 'es', 'categoria' => 'verbos'],
];

echo "Insertando palabras en el diccionario Kichwa...\n";

$insertadas = 0;
$errores = 0;

foreach($diccionario as $entrada) {
    $kichwa->palabra_kichwa = $entrada['palabra_kichwa'];
    $kichwa->traduccion_espanol = $entrada['traduccion_espanol'];
    $kichwa->categoria = $entrada['categoria'];
    $kichwa->audio_url = null; // Se puede agregar después
    
    if($kichwa->create()) {
        $insertadas++;
        echo "✓ Insertada: {$entrada['palabra_kichwa']} - {$entrada['traduccion_espanol']}\n";
    } else {
        $errores++;
        echo "✗ Error al insertar: {$entrada['palabra_kichwa']}\n";
    }
}

echo "\n==========================================\n";
echo "Resumen de inserción:\n";
echo "Total palabras insertadas: $insertadas\n";
echo "Errores: $errores\n";
echo "==========================================\n";
?>