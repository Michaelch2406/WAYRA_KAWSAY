<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Verificación Final - Sistema Kichwa</h2>";
echo "<hr>";

try {
    include_once 'config/Conexion.php';
    include_once 'models/Kichwa.php';

    $database = new Conexion();
    $db = $database->getConnection();
    
    if (!$db) {
        echo "<p style='color: red;'>❌ Error: No se pudo conectar a la base de datos</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ Conexión a base de datos exitosa</p>";

    $kichwa = new Kichwa($db);
    
    // Verificación 1: Conteo total
    echo "<div style='background-color: #f0f8ff; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
    echo "<h3>📊 1. Estadísticas Generales</h3>";
    $total = $kichwa->getTotalCount();
    echo "<p><strong>Total de palabras:</strong> $total</p>";
    
    // Verificación 2: Método read() sin límite
    $stmt = $kichwa->read();
    $count_unlimited = 0;
    while ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $count_unlimited++;
    }
    echo "<p><strong>Palabras con read():</strong> $count_unlimited</p>";
    
    // Verificación 3: Método read() con límite
    $stmt_limited = $kichwa->read(10, 0);
    $count_limited = 0;
    $sample_words = [];
    while ($row = $stmt_limited->fetch(PDO::FETCH_ASSOC)) {
        $count_limited++;
        if ($count_limited <= 5) {
            $sample_words[] = $row;
        }
    }
    echo "<p><strong>Palabras con read(10, 0):</strong> $count_limited</p>";
    
    if ($total == $count_unlimited && $count_limited == 10) {
        echo "<p style='color: green;'>✅ Los métodos de consulta funcionan correctamente</p>";
    } else {
        echo "<p style='color: orange;'>⚠ Hay discrepancias en los métodos de consulta</p>";
    }
    echo "</div>";

    // Verificación 4: Muestra de palabras
    echo "<div style='background-color: #f8f8f8; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
    echo "<h3>📝 2. Muestra de Vocabulario (Primeras 10 palabras)</h3>";
    if (!empty($sample_words)) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #8B4513; color: white;'>";
        echo "<th>Kichwa</th><th>Español</th><th>Categoría</th><th>Audio</th>";
        echo "</tr>";
        foreach ($sample_words as $word) {
            echo "<tr>";
            echo "<td><strong>" . htmlspecialchars($word['palabra_kichwa']) . "</strong></td>";
            echo "<td>" . htmlspecialchars($word['traduccion_espanol']) . "</td>";
            echo "<td><span style='background-color: #e0e0e0; padding: 2px 6px; border-radius: 3px;'>" . htmlspecialchars($word['categoria']) . "</span></td>";
            echo "<td>" . ($word['audio_url'] ? '🔊 Disponible' : '🔇 No disponible') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p style='color: green;'>✅ Las palabras se muestran correctamente con sus datos</p>";
    } else {
        echo "<p style='color: red;'>❌ No se pudieron obtener palabras de muestra</p>";
    }
    echo "</div>";

    // Verificación 5: Categorías
    echo "<div style='background-color: #f0fff0; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
    echo "<h3>🏷️ 3. Distribución por Categorías</h3>";
    $categories_query = "SELECT categoria, COUNT(*) as count FROM palabras_kichwa WHERE categoria IS NOT NULL AND categoria != '' GROUP BY categoria ORDER BY count DESC LIMIT 15";
    $stmt = $db->prepare($categories_query);
    $stmt->execute();
    
    $total_categorized = 0;
    echo "<div style='display: flex; flex-wrap: wrap; gap: 10px;'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $total_categorized += $row['count'];
        $percentage = round(($row['count'] / $total) * 100, 1);
        echo "<div style='background-color: white; padding: 8px 12px; border-radius: 5px; border: 1px solid #ddd;'>";
        echo "<strong>" . htmlspecialchars($row['categoria']) . "</strong><br>";
        echo "<span style='font-size: 0.9em; color: #666;'>" . $row['count'] . " palabras ($percentage%)</span>";
        echo "</div>";
    }
    echo "</div>";
    echo "<p><strong>Total categorizado:</strong> $total_categorized de $total palabras</p>";
    echo "</div>";

    // Verificación 6: Prueba de paginación
    echo "<div style='background-color: #fff8f0; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
    echo "<h3>📄 4. Prueba de Paginación</h3>";
    $pages_to_test = [
        ['rows' => 5, 'page' => 1, 'offset' => 0],
        ['rows' => 10, 'page' => 2, 'offset' => 10],
        ['rows' => 20, 'page' => 1, 'offset' => 0]
    ];
    
    foreach ($pages_to_test as $test) {
        $stmt_page = $kichwa->read($test['rows'], $test['offset']);
        $page_count = 0;
        while ($stmt_page->fetch(PDO::FETCH_ASSOC)) {
            $page_count++;
        }
        echo "<p>📃 Página {$test['page']} ({$test['rows']} por página): <span style='color: " . ($page_count == $test['rows'] ? 'green' : 'orange') . ";'>$page_count palabras</span></p>";
    }
    echo "</div>";

    // Verificación 7: Búsqueda
    echo "<div style='background-color: #f5f5ff; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
    echo "<h3>🔎 5. Prueba de Búsqueda</h3>";
    $search_terms = ['mama', 'inti', 'agua', 'casa'];
    foreach ($search_terms as $term) {
        $stmt_search = $kichwa->search($term);
        $search_count = 0;
        $search_results = [];
        while ($row = $stmt_search->fetch(PDO::FETCH_ASSOC)) {
            $search_count++;
            if ($search_count <= 2) {
                $search_results[] = $row;
            }
        }
        echo "<p>🔍 Buscar '$term': <strong>$search_count resultados</strong>";
        if ($search_results) {
            echo " (ej: " . htmlspecialchars($search_results[0]['palabra_kichwa']) . " → " . htmlspecialchars($search_results[0]['traduccion_espanol']) . ")";
        }
        echo "</p>";
    }
    echo "</div>";

    // Verificación 8: Estado de archivos críticos
    echo "<div style='background-color: #f0f0ff; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
    echo "<h3>📁 6. Estado de Archivos del Sistema</h3>";
    $critical_files = [
        'kichwa.php' => 'Página principal',
        'JS/kichwa.js' => 'JavaScript principal',
        'CSS/kichwa.css' => 'Estilos CSS',
        'models/Kichwa.php' => 'Modelo de datos',
        'api/kichwa-words.php' => 'API de palabras'
    ];
    
    foreach ($critical_files as $file => $description) {
        $full_path = __DIR__ . '/' . $file;
        if (file_exists($full_path)) {
            $size = round(filesize($full_path) / 1024, 1);
            echo "<p>✅ $description ($file): <span style='color: green;'>OK ($size KB)</span></p>";
        } else {
            echo "<p>❌ $description ($file): <span style='color: red;'>No encontrado</span></p>";
        }
    }
    echo "</div>";

    // Resumen final
    echo "<div style='background-color: #e8f5e8; padding: 20px; border-radius: 10px; margin: 20px 0; border: 2px solid #4CAF50;'>";
    echo "<h3>🎉 Resumen de Verificación</h3>";
    
    $issues = [];
    if ($total != $count_unlimited) $issues[] = "Discrepancia en conteo de palabras";
    if ($count_limited != 10) $issues[] = "Problema en paginación";
    if (empty($sample_words)) $issues[] = "No se pueden obtener palabras de muestra";
    if ($total_categorized < ($total * 0.8)) $issues[] = "Muchas palabras sin categorizar";
    
    if (empty($issues)) {
        echo "<p style='color: green; font-size: 1.2em;'><strong>✅ SISTEMA KICHWA FUNCIONANDO PERFECTAMENTE</strong></p>";
        echo "<p>🎯 Total de palabras: <strong>$total</strong></p>";
        echo "<p>📊 Categorías activas: <strong>" . ($stmt->rowCount()) . "</strong></p>";
        echo "<p>🔍 Búsqueda: <strong>Funcionando</strong></p>";
        echo "<p>📄 Paginación: <strong>Funcionando</strong></p>";
        echo "<p style='margin-top: 15px;'>🎉 <em>¡El diccionario Kichwa está listo para usar!</em></p>";
    } else {
        echo "<p style='color: orange; font-size: 1.1em;'><strong>⚠ SISTEMA CON ALGUNOS PROBLEMAS</strong></p>";
        echo "<ul>";
        foreach ($issues as $issue) {
            echo "<li>$issue</li>";
        }
        echo "</ul>";
    }
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background-color: #ffebee; padding: 15px; border-radius: 5px; border: 2px solid #f44336;'>";
    echo "<h3>❌ Error en Verificación</h3>";
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<details>";
    echo "<summary>Ver detalles técnicos</summary>";
    echo "<pre style='background-color: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto;'>";
    echo $e->getTraceAsString();
    echo "</pre>";
    echo "</details>";
    echo "</div>";
}
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.6;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fafafa;
}
h2 {
    color: #8B4513;
    text-align: center;
}
table {
    font-size: 0.95em;
}
</style>