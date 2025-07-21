<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de Depuración - Palabras Kichwa</h2>";

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
    
    // Probar método getTotalCount()
    $total = $kichwa->getTotalCount();
    echo "<p><strong>Total de palabras en BD:</strong> $total</p>";
    
    // Probar método read() sin límite
    $stmt = $kichwa->read();
    $count = 0;
    $sample_words = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count++;
        if ($count <= 5) {
            $sample_words[] = $row;
        }
    }
    
    echo "<p><strong>Palabras leídas con read():</strong> $count</p>";
    
    // Probar método read() con límite (después de corrección)
    $stmt2 = $kichwa->read(5, 0);
    $limited_count = 0;
    $limited_words = array();
    
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $limited_count++;
        $limited_words[] = $row;
    }
    
    echo "<p><strong>Palabras leídas con read(5, 0) [CORREGIDO]:</strong> $limited_count</p>";
    
    // Mostrar muestra de palabras
    if (!empty($limited_words)) {
        echo "<h3>Primeras 5 palabras:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Kichwa</th><th>Español</th><th>Audio URL</th></tr>";
        foreach ($limited_words as $word) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($word['palabra_kichwa']) . "</td>";
            echo "<td>" . htmlspecialchars($word['traduccion_espanol']) . "</td>";
            echo "<td>" . htmlspecialchars($word['audio_url'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>❌ No se obtuvieron palabras con read(5, 0)</p>";
    }
    
    // Probar consulta directa
    echo "<h3>Consulta SQL directa:</h3>";
    $direct_query = "SELECT id, palabra_kichwa, traduccion_espanol, audio_url FROM palabras_kichwa LIMIT 3";
    $direct_stmt = $db->prepare($direct_query);
    $direct_stmt->execute();
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Kichwa</th><th>Español</th><th>Audio URL</th></tr>";
    while ($row = $direct_stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['palabra_kichwa']) . "</td>";
        echo "<td>" . htmlspecialchars($row['traduccion_espanol']) . "</td>";
        echo "<td>" . htmlspecialchars($row['audio_url'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>