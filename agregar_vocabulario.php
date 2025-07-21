<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Agregando Vocabulario Kichwa Adicional</h2>";

try {
    include_once 'config/Conexion.php';
    include_once 'models/Kichwa.php';

    $database = new Conexion();
    $db = $database->getConnection();
    
    if (!$db) {
        echo "<p style='color: red;'>Error: No se pudo conectar a la base de datos</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✓ Conexión a base de datos exitosa</p>";

    $kichwa = new Kichwa($db);
    $added_count = 0;
    $skipped_count = 0;

    // Vocabulario adicional extraído de los archivos info_kichwa
    $nuevo_vocabulario = [
        // Del diccionario RK_diccionario_kichwa_castellano.txt
        ['akankaw', 'variedad de pava', 'animales'],
        ['akapana', 'remolino de viento', 'naturaleza'],
        ['akichana', 'cernir en harnero o cedazo', 'verbos'],
        ['akirinri', 'jengibre', 'plantas'],
        ['akllana', 'elegir, escoger, seleccionar', 'verbos'],
        ['aklluna', 'tartamudear', 'verbos'],
        ['akma', 'que no se cocina', 'adjetivos'],
        ['aknina', 'eructar, dudar', 'verbos'],
        ['aksu', 'tipo de falda', 'vestimenta'],
        ['araray', 'expresión de calor', 'interjecciones'],
        ['rupakuk', 'que da calor', 'adjetivos'],
        
        // Más palabras del cuerpo humano
        ['tullu', 'hueso', 'cuerpo'],
        ['aycha', 'carne', 'cuerpo'],
        ['yawar', 'sangre', 'cuerpo'],
        ['samay', 'respiración', 'cuerpo'],
        ['chukcha', 'cabello, pelo', 'cuerpo'],
        ['qallu', 'lengua', 'cuerpo'],
        ['much\'ay', 'beso', 'cuerpo'],
        ['llakana', 'muela', 'cuerpo'],
        ['sillpu', 'uña', 'cuerpo'],
        ['kurkur', 'garganta', 'cuerpo'],
        
        // Animales del ecosistema andino
        ['kuntur', 'cóndor', 'animales'],
        ['vicuña', 'vicuña', 'animales'],
        ['taruka', 'venado', 'animales'],
        ['chinchilla', 'chinchilla', 'animales'],
        ['vizcacha', 'vizcacha', 'animales'],
        ['cuy', 'cuy, conejillo de indias', 'animales'],
        ['sacha cuy', 'cuy silvestre', 'animales'],
        ['achuni', 'coatí', 'animales'],
        ['yaku runa', 'nutria', 'animales'],
        ['amaru', 'serpiente', 'animales'],
        ['churu', 'caracol', 'animales'],
        ['pilpintu', 'mariposa', 'animales'],
        ['añanku', 'araña', 'animales'],
        ['chuspi', 'mosca', 'animales'],
        ['tanku', 'abeja', 'animales'],
        
        // Plantas medicinales y alimenticias
        ['wayusa', 'wayusa (planta energizante)', 'plantas'],
        ['sangre de grado', 'sangre de grado', 'plantas'],
        ['uña de gato', 'uña de gato', 'plantas'],
        ['chanca piedra', 'chanca piedra', 'plantas'],
        ['matico', 'matico', 'plantas'],
        ['hierba luisa', 'hierba luisa', 'plantas'],
        ['manzanilla', 'manzanilla', 'plantas'],
        ['eucalipto', 'eucalipto', 'plantas'],
        ['ruda', 'ruda', 'plantas'],
        ['retama', 'retama', 'plantas'],
        ['capulí', 'capulí', 'plantas'],
        ['chamburo', 'chamburo', 'plantas'],
        ['chirimoya', 'chirimoya', 'plantas'],
        ['tuna', 'tuna', 'plantas'],
        ['pitajaya', 'pitajaya', 'plantas'],
        
        // Instrumentos musicales
        ['quena', 'quena', 'instrumentos'],
        ['zampoña', 'zampoña', 'instrumentos'],
        ['charango', 'charango', 'instrumentos'],
        ['bombo', 'bombo', 'instrumentos'],
        ['tambor', 'tambor', 'instrumentos'],
        ['pingulluy', 'flauta pequeña', 'instrumentos'],
        ['wankara', 'tambor grande', 'instrumentos'],
        ['antara', 'flauta de pan', 'instrumentos'],
        ['cascabeles', 'cascabeles', 'instrumentos'],
        ['sonajero', 'sonajero', 'instrumentos'],
        
        // Ceremonias y rituales
        ['ayahuasca', 'ayahuasca', 'ceremonias'],
        ['yage', 'yagé', 'ceremonias'],
        ['mambe', 'mambe', 'ceremonias'],
        ['rapé', 'rapé', 'ceremonias'],
        ['temazcal', 'temazcal', 'ceremonias'],
        ['inipi', 'inipi', 'ceremonias'],
        ['chanupa', 'chanupa', 'ceremonias'],
        ['wachuma', 'wachuma', 'ceremonias'],
        ['kapé', 'café ceremonial', 'ceremonias'],
        ['cacao sagrado', 'cacao sagrado', 'ceremonias'],
        
        // Fenómenos naturales
        ['earthquake', 'terremoto', 'naturaleza'],
        ['pakarina', 'amanecer', 'naturaleza'],
        ['intip wañuy', 'atardecer', 'naturaleza'],
        ['k\'anchay', 'luz', 'naturaleza'],
        ['llanthu', 'sombra', 'naturaleza'],
        ['phuyuy', 'nublado', 'naturaleza'],
        ['ch\'aska', 'estrella', 'naturaleza'],
        ['k\'uyllurmama', 'arcoíris', 'naturaleza'],
        ['illapa', 'rayo', 'naturaleza'],
        ['khunuq', 'granizo', 'naturaleza'],
        ['rit\'i', 'nieve', 'naturaleza'],
        ['wayku', 'quebrada', 'naturaleza'],
        ['pukyu', 'manantial', 'naturaleza'],
        ['qucha', 'laguna', 'naturaleza'],
        ['mayu', 'río', 'naturaleza'],
        
        // Herramientas tradicionales
        ['raucana', 'azadón', 'herramientas'],
        ['taclla', 'arado de pie', 'herramientas'],
        ['lampa', 'pala', 'herramientas'],
        ['kuchuna', 'cuchillo', 'herramientas'],
        ['t\'ipana', 'alfiler', 'herramientas'],
        ['pushka', 'huso', 'herramientas'],
        ['awana', 'telar', 'herramientas'],
        ['qiru', 'vaso ceremonial', 'herramientas'],
        ['arybalo', 'cántaro', 'herramientas'],
        ['p\'uku', 'plato', 'herramientas'],
        
        // Estados de ánimo y emociones
        ['kusikuy', 'alegrarse', 'emociones'],
        ['llakikuy', 'entristecerse', 'emociones'],
        ['phiñakuy', 'enojarse', 'emociones'],
        ['manchakuy', 'asustarse', 'emociones'],
        ['ch\'iqnikuy', 'odiar', 'emociones'],
        ['khuyana', 'amar', 'emociones'],
        ['sumakkay', 'hermosura', 'emociones'],
        ['millana', 'asco', 'emociones'],
        ['p\'unchawniy', 'vergüenza', 'emociones'],
        ['muspay', 'sueño', 'emociones'],
        
        // Actividades cotidianas
        ['ch\'uyay', 'lavar', 'actividades'],
        ['maqchiy', 'bañarse', 'actividades'],
        ['ch\'uspiy', 'peinar', 'actividades'],
        ['p\'uchuy', 'hilar', 'actividades'],
        ['away', 'tejer', 'actividades'],
        ['tullpuy', 'teñir', 'actividades'],
        ['asiy', 'reír', 'actividades'],
        ['waqay', 'llorar', 'actividades'],
        ['tusuy', 'bailar', 'actividades'],
        ['takiy', 'cantar', 'actividades'],
        ['pukllay', 'jugar', 'actividades'],
        ['llamkay', 'trabajar', 'actividades'],
        ['samay', 'descansar', 'actividades'],
        ['sayay', 'pararse', 'actividades'],
        ['tiyay', 'sentarse', 'actividades'],
        
        // Conceptos espirituales
        ['Wiracocha', 'Wiracocha (dios creador)', 'espiritualidad'],
        ['Mamaqucha', 'Madre Lago', 'espiritualidad'],
        ['Yakamama', 'Madre Agua', 'espiritualidad'],
        ['Sachamama', 'Madre Selva', 'espiritualidad'],
        ['animu', 'alma', 'espiritualidad'],
        ['supay', 'diablo, espíritu', 'espiritualidad'],
        ['huaca', 'lugar sagrado', 'espiritualidad'],
        ['despacho', 'ofrenda', 'espiritualidad'],
        ['k\'intuy', 'masticar coca', 'espiritualidad'],
        ['willay', 'rezar', 'espiritualidad']
    ];

    // Función para verificar si una palabra ya existe
    function palabraExiste($db, $palabra) {
        $query = "SELECT COUNT(*) as count FROM palabras_kichwa WHERE palabra_kichwa = :palabra";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':palabra', $palabra);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }

    // Insertar nuevo vocabulario
    echo "<h3>Insertando nuevo vocabulario...</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Estado</th><th>Kichwa</th><th>Español</th><th>Categoría</th></tr>";

    foreach ($nuevo_vocabulario as $palabra_data) {
        $palabra_kichwa = $palabra_data[0];
        $traduccion_espanol = $palabra_data[1];
        $categoria = $palabra_data[2];

        if (palabraExiste($db, $palabra_kichwa)) {
            echo "<tr style='background-color: #ffffcc;'>";
            echo "<td>⚠ Ya existe</td>";
            echo "<td>" . htmlspecialchars($palabra_kichwa) . "</td>";
            echo "<td>" . htmlspecialchars($traduccion_espanol) . "</td>";
            echo "<td>" . htmlspecialchars($categoria) . "</td>";
            echo "</tr>";
            $skipped_count++;
        } else {
            $kichwa->palabra_kichwa = $palabra_kichwa;
            $kichwa->traduccion_espanol = $traduccion_espanol;
            $kichwa->categoria = $categoria;
            $kichwa->audio_url = null; // Por ahora sin audio

            if ($kichwa->create()) {
                echo "<tr style='background-color: #ccffcc;'>";
                echo "<td>✓ Agregada</td>";
                echo "<td>" . htmlspecialchars($palabra_kichwa) . "</td>";
                echo "<td>" . htmlspecialchars($traduccion_espanol) . "</td>";
                echo "<td>" . htmlspecialchars($categoria) . "</td>";
                echo "</tr>";
                $added_count++;
            } else {
                echo "<tr style='background-color: #ffcccc;'>";
                echo "<td>❌ Error</td>";
                echo "<td>" . htmlspecialchars($palabra_kichwa) . "</td>";
                echo "<td>" . htmlspecialchars($traduccion_espanol) . "</td>";
                echo "<td>" . htmlspecialchars($categoria) . "</td>";
                echo "</tr>";
            }
        }
    }
    echo "</table>";

    // Resumen final
    echo "<div style='background-color: #e8f5e8; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>📊 Resumen de la operación:</h3>";
    echo "<p><strong>✅ Palabras agregadas:</strong> $added_count</p>";
    echo "<p><strong>⚠ Palabras ya existentes:</strong> $skipped_count</p>";
    echo "<p><strong>📝 Total procesadas:</strong> " . count($nuevo_vocabulario) . "</p>";
    echo "</div>";

    // Mostrar estadísticas actuales
    $total_query = "SELECT COUNT(*) as total FROM palabras_kichwa";
    $stmt = $db->prepare($total_query);
    $stmt->execute();
    $total_words = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $categories_query = "SELECT categoria, COUNT(*) as count FROM palabras_kichwa WHERE categoria IS NOT NULL GROUP BY categoria ORDER BY count DESC";
    $stmt = $db->prepare($categories_query);
    $stmt->execute();
    
    echo "<h3>📈 Estadísticas del diccionario:</h3>";
    echo "<p><strong>Total de palabras:</strong> $total_words</p>";
    echo "<h4>Palabras por categoría:</h4>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Categoría</th><th>Cantidad</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
        echo "<td>" . htmlspecialchars($row['count']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p style='color: green;'><strong>✅ Proceso de agregado de vocabulario completado!</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>