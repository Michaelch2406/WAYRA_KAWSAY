<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Limpieza de Base de Datos - Eliminando Filas Vacías</h2>";

try {
    include_once 'config/Conexion.php';

    $database = new Conexion();
    $db = $database->getConnection();
    
    if (!$db) {
        echo "<p style='color: red;'>Error: No se pudo conectar a la base de datos</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✓ Conexión a base de datos exitosa</p>";

    // Mostrar estado inicial
    $count_query = "SELECT COUNT(*) as total FROM palabras_kichwa";
    $stmt = $db->prepare($count_query);
    $stmt->execute();
    $initial_count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<p><strong>Registros antes de limpiar:</strong> $initial_count</p>";

    // ---- INICIO DE LA CORRECCIÓN ----
    // Contar registros con datos vacíos
    // Se cambió el alias 'empty' por 'total_vacios' para evitar el conflicto con la palabra reservada de MySQL.
    $empty_query = "SELECT COUNT(*) as total_vacios FROM palabras_kichwa WHERE 
                    (palabra_kichwa IS NULL OR palabra_kichwa = '') AND 
                    (traduccion_espanol IS NULL OR traduccion_espanol = '')";
    $stmt = $db->prepare($empty_query);
    $stmt->execute();
    // Se actualizó aquí también para usar el nuevo alias.
    $empty_count = $stmt->fetch(PDO::FETCH_ASSOC)['total_vacios'];
    // ---- FIN DE LA CORRECCIÓN ----
    
    echo "<p><strong>Registros vacíos encontrados:</strong> $empty_count</p>";

    if ($empty_count > 0) {
        // Eliminar registros vacíos
        $delete_query = "DELETE FROM palabras_kichwa WHERE 
                        (palabra_kichwa IS NULL OR palabra_kichwa = '') AND 
                        (traduccion_espanol IS NULL OR traduccion_espanol = '')";
        $stmt = $db->prepare($delete_query);
        $delete_result = $stmt->execute();
        
        if ($delete_result) {
            $deleted_rows = $stmt->rowCount();
            echo "<p style='color: green;'>✓ Se eliminaron $deleted_rows registros vacíos</p>";
        } else {
            echo "<p style='color: red;'>❌ Error al eliminar registros vacíos</p>";
        }
    } else {
        echo "<p>✓ No hay registros vacíos para eliminar</p>";
    }

    // Mostrar estado final
    $stmt = $db->prepare($count_query);
    $stmt->execute();
    $final_count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<p><strong>Registros después de limpiar:</strong> $final_count</p>";

    // Resetear el AUTO_INCREMENT
    $reset_query = "ALTER TABLE palabras_kichwa AUTO_INCREMENT = " . ($final_count + 1);
    $stmt = $db->prepare($reset_query);
    $reset_result = $stmt->execute();
    
    if ($reset_result) {
        echo "<p style='color: green;'>✓ AUTO_INCREMENT reseteado correctamente</p>";
    } else {
        echo "<p style='color: orange;'>⚠ No se pudo resetear AUTO_INCREMENT (no crítico)</p>";
    }

    // Mostrar algunas palabras válidas
    echo "<h3>Primeras 10 palabras válidas restantes:</h3>";
    $sample_query = "SELECT id, palabra_kichwa, traduccion_espanol, categoria FROM palabras_kichwa 
                     WHERE palabra_kichwa IS NOT NULL AND palabra_kichwa != '' 
                     ORDER BY id LIMIT 10";
    $stmt = $db->prepare($sample_query);
    $stmt->execute();
    
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Kichwa</th><th>Español</th><th>Categoría</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['palabra_kichwa']) . "</td>";
        echo "<td>" . htmlspecialchars($row['traduccion_espanol']) . "</td>";
        echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p style='color: green;'><strong>✅ Limpieza de base de datos completada exitosamente!</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>