<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

include_once '../config/Conexion.php';
include_once '../models/Kichwa.php';

try {
    $database = new Conexion();
    $db = $database->getConnection();
    
    $kichwa = new Kichwa($db);
    $stmt = $kichwa->read();
    
    $words = array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $words[] = array(
            'id' => $row['id'],
            'palabra_kichwa' => $row['palabra_kichwa'],
            'traduccion_espanol' => $row['traduccion_espanol'],
            'audio_url' => $row['audio_url'],
            'categoria' => $row['categoria'],
            'fecha_creacion' => $row['fecha_creacion']
        );
    }
    
    echo json_encode(array(
        'success' => true,
        'words' => $words,
        'total' => count($words)
    ));
    
} catch (Exception $e) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ));
}
?>