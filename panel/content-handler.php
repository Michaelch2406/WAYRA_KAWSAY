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

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch($action) {
    case 'create':
        createContent($db);
        break;
    case 'get_content':
        getContent($db);
        break;
    case 'update_content':
        updateContent($db);
        break;
    case 'delete_content':
        deleteContent($db);
        break;
    default:
        // Maintain backward compatibility
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            createContent($db);
        } else {
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
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

function getContent($db) {
    try {
        $content_id = $_GET['id'] ?? 0;
        
        if (!$content_id) {
            echo json_encode(['success' => false, 'message' => 'ID de contenido requerido']);
            return;
        }
        
        // Obtener contenido del usuario
        $query = "SELECT * FROM contenido WHERE id = :content_id AND id_usuario_autor = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':content_id', $content_id);
        $stmt->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt->execute();
        
        $content = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($content) {
            echo json_encode(['success' => true, 'content' => $content]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Contenido no encontrado']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function updateContent($db) {
    try {
        $content_id = $_POST['content_id'] ?? 0;
        $tipo = $_POST['tipo'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        $cuerpo = $_POST['cuerpo'] ?? '';
        $url_multimedia = $_POST['url_multimedia'] ?? null;
        
        if (!$content_id || empty($tipo) || empty($titulo) || empty($cuerpo)) {
            echo json_encode(['success' => false, 'message' => 'ID, tipo, título y contenido son requeridos']);
            return;
        }
        
        // Validar tipos permitidos
        $tipos_permitidos = ['noticia', 'historia', 'leyenda', 'tradicion', 'baile', 'evento'];
        if (!in_array($tipo, $tipos_permitidos)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de contenido no válido']);
            return;
        }
        
        // Verificar que el contenido pertenece al usuario
        $query_verify = "SELECT id FROM contenido WHERE id = :content_id AND id_usuario_autor = :user_id";
        $stmt_verify = $db->prepare($query_verify);
        $stmt_verify->bindParam(':content_id', $content_id);
        $stmt_verify->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt_verify->execute();
        
        if (!$stmt_verify->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Contenido no encontrado']);
            return;
        }
        
        // Actualizar contenido (regresa a estado pendiente)
        $query = "UPDATE contenido SET tipo = :tipo, titulo = :titulo, cuerpo = :cuerpo, url_multimedia = :url_multimedia, estado = 'pendiente' WHERE id = :content_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':cuerpo', $cuerpo);
        $stmt->bindParam(':url_multimedia', $url_multimedia);
        $stmt->bindParam(':content_id', $content_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Contenido actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el contenido']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function deleteContent($db) {
    try {
        $content_id = $_POST['content_id'] ?? 0;
        
        if (!$content_id) {
            echo json_encode(['success' => false, 'message' => 'ID de contenido requerido']);
            return;
        }
        
        // Verificar que el contenido pertenece al usuario
        $query_verify = "SELECT id FROM contenido WHERE id = :content_id AND id_usuario_autor = :user_id";
        $stmt_verify = $db->prepare($query_verify);
        $stmt_verify->bindParam(':content_id', $content_id);
        $stmt_verify->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt_verify->execute();
        
        if (!$stmt_verify->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Contenido no encontrado']);
            return;
        }
        
        // Eliminar contenido
        $query_delete = "DELETE FROM contenido WHERE id = :content_id";
        $stmt_delete = $db->prepare($query_delete);
        $stmt_delete->bindParam(':content_id', $content_id);
        
        if ($stmt_delete->execute()) {
            echo json_encode(['success' => true, 'message' => 'Contenido eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el contenido']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}
?>