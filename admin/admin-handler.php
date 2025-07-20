<?php
session_start();
include_once '../config/global.php';
include_once '../config/Conexion.php';

// Verificar que el usuario esté logueado y tenga rol admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if ($_SESSION['usuario_rol'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

$database = new Conexion();
$db = $database->getConnection();

$action = $_GET['action'] ?? '';

switch($action) {
    case 'approve_content':
        approveContent($db);
        break;
    case 'reject_content':
        rejectContent($db);
        break;
    case 'toggle_user':
        toggleUserStatus($db);
        break;
    case 'get_all_content':
        getAllContent($db);
        break;
    case 'change_user_role':
        changeUserRole($db);
        break;
    case 'delete_user':
        deleteUser($db);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

function approveContent($db) {
    try {
        $content_id = $_POST['content_id'] ?? 0;
        
        if (!$content_id) {
            echo json_encode(['success' => false, 'message' => 'ID de contenido requerido']);
            return;
        }
        
        $query = "UPDATE contenido SET estado = 'aprobado', fecha_aprobacion = NOW(), id_usuario_aprobador = :admin_id WHERE id = :content_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':admin_id', $_SESSION['usuario_id']);
        $stmt->bindParam(':content_id', $content_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Contenido aprobado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al aprobar el contenido']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function rejectContent($db) {
    try {
        $content_id = $_POST['content_id'] ?? 0;
        
        if (!$content_id) {
            echo json_encode(['success' => false, 'message' => 'ID de contenido requerido']);
            return;
        }
        
        $query = "UPDATE contenido SET estado = 'rechazado', fecha_aprobacion = NOW(), id_usuario_aprobador = :admin_id WHERE id = :content_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':admin_id', $_SESSION['usuario_id']);
        $stmt->bindParam(':content_id', $content_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Contenido rechazado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al rechazar el contenido']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function toggleUserStatus($db) {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        $status = $_POST['status'] ?? 0;
        
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
            return;
        }
        
        // No permitir desactivar al admin actual
        if ($user_id == $_SESSION['usuario_id']) {
            echo json_encode(['success' => false, 'message' => 'No puedes desactivar tu propia cuenta']);
            return;
        }
        
        $query = "UPDATE usuarios SET activo = :status WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            $action = $status ? 'activado' : 'desactivado';
            echo json_encode(['success' => true, 'message' => "Usuario $action exitosamente"]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al cambiar el estado del usuario']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getAllContent($db) {
    try {
        $query = "SELECT c.*, u.nombre as autor_nombre 
                  FROM contenido c 
                  INNER JOIN usuarios u ON c.id_usuario_autor = u.id 
                  ORDER BY c.fecha_creacion DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $content = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'content' => $content]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function changeUserRole($db) {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        $new_role = $_POST['new_role'] ?? '';
        
        if (!$user_id || !$new_role) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario y rol requeridos']);
            return;
        }
        
        $allowed_roles = ['comunitario', 'artesano', 'admin'];
        if (!in_array($new_role, $allowed_roles)) {
            echo json_encode(['success' => false, 'message' => 'Rol no válido']);
            return;
        }
        
        // No permitir cambiar el rol del admin actual
        if ($user_id == $_SESSION['usuario_id']) {
            echo json_encode(['success' => false, 'message' => 'No puedes cambiar tu propio rol']);
            return;
        }
        
        $db->beginTransaction();
        
        try {
            // Actualizar rol del usuario
            $query = "UPDATE usuarios SET rol = :new_role WHERE id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':new_role', $new_role);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            
            // Si el nuevo rol es artesano, crear perfil de artesano si no existe
            if ($new_role === 'artesano') {
                $query_check = "SELECT id FROM artesanos WHERE id_usuario = :user_id";
                $stmt_check = $db->prepare($query_check);
                $stmt_check->bindParam(':user_id', $user_id);
                $stmt_check->execute();
                
                if ($stmt_check->rowCount() === 0) {
                    $query_artesano = "INSERT INTO artesanos (id_usuario, historia, especialidad) 
                                       VALUES (:user_id, 'Perfil en construcción...', 'Sin especialidad definida')";
                    $stmt_artesano = $db->prepare($query_artesano);
                    $stmt_artesano->bindParam(':user_id', $user_id);
                    $stmt_artesano->execute();
                }
            }
            
            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Rol de usuario actualizado exitosamente']);
            
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function deleteUser($db) {
    try {
        $user_id = $_POST['user_id'] ?? 0;
        
        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
            return;
        }
        
        // No permitir eliminar al admin actual
        if ($user_id == $_SESSION['usuario_id']) {
            echo json_encode(['success' => false, 'message' => 'No puedes eliminar tu propia cuenta']);
            return;
        }
        
        $query = "DELETE FROM usuarios WHERE id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}
?>