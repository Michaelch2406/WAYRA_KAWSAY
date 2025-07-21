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
    
    // Obtener parámetros de la URL
    $rows_per_page = isset($_GET['rows']) ? max(5, min(100, (int)$_GET['rows'])) : 10;
    $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    $offset = ($current_page - 1) * $rows_per_page;
    
    // Si hay término de búsqueda, usar búsqueda paginada
    if (!empty($search_term)) {
        $stmt = $kichwa->searchPaginated($search_term, $rows_per_page, $offset);
        $total_words = $kichwa->getSearchCount($search_term);
    } else {
        $stmt = $kichwa->read($rows_per_page, $offset);
        $total_words = $kichwa->getTotalCount();
    }
    
    $words = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $words[] = array(
            'id' => $row['id'],
            'palabra_kichwa' => $row['palabra_kichwa'],
            'traduccion_espanol' => $row['traduccion_espanol'],
            'audio' => $row['audio_url'] ?? '',
            'categoria' => $row['categoria'] ?? '',
            'fecha_creacion' => $row['fecha_creacion'] ?? ''
        );
    }
    
    $total_pages = ceil($total_words / $rows_per_page);
    
    // Generar información de paginación
    $pagination_info = array(
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'rows_per_page' => $rows_per_page,
        'total_words' => $total_words,
        'showing_from' => min(($current_page - 1) * $rows_per_page + 1, $total_words),
        'showing_to' => min($current_page * $rows_per_page, $total_words)
    );
    
    echo json_encode(array(
        'success' => true,
        'words' => $words,
        'pagination' => $pagination_info,
        'search_term' => $search_term
    ));
    
} catch (Exception $e) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Error del servidor: ' . $e->getMessage()
    ));
}
?>