<?php
session_start();
include_once '../config/global.php';
include_once '../config/Conexion.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$database = new Conexion();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    createContent($db);
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function createContent($db) {
    try {
        $tipo = $_POST['tipo'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $cuerpo = $_POST['cuerpo'] ?? '';
        $url_multimedia = $_POST['url_multimedia'] ?? null;
        
        // Validar campos requeridos
        if (empty($tipo) || empty($titulo) || empty($cuerpo)) {
            echo json_encode(['success' => false, 'message' => 'Tipo, título y contenido son requeridos']);
            return;
        }
        
        // Validar tipos permitidos
        $tipos_permitidos = ['noticia', 'historia', 'leyenda', 'tradicion', 'baile', 'evento'];
        if (!in_array($tipo, $tipos_permitidos)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de contenido no válido']);
            return;
        }
        
        // Validar URL multimedia si se proporciona
        if (!empty($url_multimedia) && !filter_var($url_multimedia, FILTER_VALIDATE_URL)) {
            echo json_encode(['success' => false, 'message' => 'URL de multimedia no válida']);
            return;
        }
        
        // Insertar contenido
        $query = "INSERT INTO contenido (id_usuario_autor, tipo, titulo, cuerpo, url_multimedia, estado) 
                  VALUES (:id_usuario_autor, :tipo, :titulo, :cuerpo, :url_multimedia, 'pendiente')";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_usuario_autor', $_SESSION['usuario_id']);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':cuerpo', $cuerpo);
        $stmt->bindParam(':url_multimedia', $url_multimedia);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Contenido enviado para revisión exitosamente. Será visible una vez que sea aprobado por un administrador.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el contenido']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}
?>