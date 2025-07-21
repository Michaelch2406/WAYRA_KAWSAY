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

$action = $_GET['action'] ?? $_POST['action'] ?? '';

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
    
    // New CRUD operations for gastronomy, kichwa, events, and routes
    case 'create_plato':
        createPlato($db);
        break;
    case 'get_plato':
        getPlato($db);
        break;
    case 'delete_plato':
        deletePlato($db);
        break;
    case 'create_palabra':
        createPalabra($db);
        break;
    case 'get_palabra':
        getPalabra($db);
        break;
    case 'delete_palabra':
        deletePalabra($db);
        break;
    case 'create_evento':
        createEvento($db);
        break;
    case 'get_evento':
        getEvento($db);
        break;
    case 'delete_evento':
        deleteEvento($db);
        break;
    case 'create_ruta':
        createRuta($db);
        break;
    case 'get_ruta':
        getRuta($db);
        break;
    case 'delete_ruta':
        deleteRuta($db);
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

// CRUD Functions for Gastronomy
function createPlato($db) {
    try {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $historia = $_POST['historia'] ?? '';
        $imagen = $_POST['imagen'] ?? '';
        $video_preparacion_url = $_POST['video_preparacion_url'] ?? '';
        $categoria = $_POST['categoria'] ?? 'tradicional';
        $dificultad = $_POST['dificultad'] ?? 'medio';
        $tiempo_preparacion = $_POST['tiempo_preparacion'] ?? null;
        
        if (!$nombre || !$descripcion) {
            echo json_encode(['success' => false, 'message' => 'Nombre y descripción son requeridos']);
            return;
        }
        
        $query = "INSERT INTO platos (nombre, descripcion, historia, imagen, video_preparacion_url, categoria, dificultad, tiempo_preparacion) 
                  VALUES (:nombre, :descripcion, :historia, :imagen, :video_preparacion_url, :categoria, :dificultad, :tiempo_preparacion)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':historia', $historia);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':video_preparacion_url', $video_preparacion_url);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':dificultad', $dificultad);
        $stmt->bindParam(':tiempo_preparacion', $tiempo_preparacion);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Plato creado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el plato']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getPlato($db) {
    try {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "SELECT * FROM platos WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $plato = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($plato) {
            echo json_encode(['success' => true, 'plato' => $plato]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Plato no encontrado']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function deletePlato($db) {
    try {
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "DELETE FROM platos WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Plato eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el plato']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

// CRUD Functions for Kichwa
function createPalabra($db) {
    try {
        $palabra_kichwa = $_POST['palabra_kichwa'] ?? '';
        $traduccion_espanol = $_POST['traduccion_espanol'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $audio_url = $_POST['audio_url'] ?? '';
        
        if (!$palabra_kichwa || !$traduccion_espanol) {
            echo json_encode(['success' => false, 'message' => 'Palabra kichwa y traducción son requeridas']);
            return;
        }
        
        $query = "INSERT INTO palabras_kichwa (palabra_kichwa, traduccion_espanol, categoria, audio_url) 
                  VALUES (:palabra_kichwa, :traduccion_espanol, :categoria, :audio_url)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':palabra_kichwa', $palabra_kichwa);
        $stmt->bindParam(':traduccion_espanol', $traduccion_espanol);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':audio_url', $audio_url);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Palabra creada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear la palabra']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getPalabra($db) {
    try {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "SELECT * FROM palabras_kichwa WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $palabra = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($palabra) {
            echo json_encode(['success' => true, 'palabra' => $palabra]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Palabra no encontrada']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function deletePalabra($db) {
    try {
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "DELETE FROM palabras_kichwa WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Palabra eliminada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la palabra']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

// CRUD Functions for Events
function createEvento($db) {
    try {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? null;
        $ubicacion_texto = $_POST['ubicacion_texto'] ?? '';
        $imagen = $_POST['imagen'] ?? '';
        $id_usuario_creador = $_SESSION['usuario_id'];
        
        if (!$nombre || !$descripcion || !$fecha_inicio) {
            echo json_encode(['success' => false, 'message' => 'Nombre, descripción y fecha de inicio son requeridos']);
            return;
        }
        
        $query = "INSERT INTO eventos (nombre, descripcion, fecha_inicio, fecha_fin, ubicacion_texto, imagen, id_usuario_creador) 
                  VALUES (:nombre, :descripcion, :fecha_inicio, :fecha_fin, :ubicacion_texto, :imagen, :id_usuario_creador)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->bindParam(':ubicacion_texto', $ubicacion_texto);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':id_usuario_creador', $id_usuario_creador);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Evento creado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el evento']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getEvento($db) {
    try {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "SELECT * FROM eventos WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($evento) {
            echo json_encode(['success' => true, 'evento' => $evento]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Evento no encontrado']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function deleteEvento($db) {
    try {
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "DELETE FROM eventos WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Evento eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el evento']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

// CRUD Functions for Routes
function createRuta($db) {
    try {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $distancia_km = $_POST['distancia_km'] ?? null;
        $dificultad = $_POST['dificultad'] ?? null;
        $mapa_url = $_POST['mapa_url'] ?? '';
        
        if (!$nombre || !$descripcion) {
            echo json_encode(['success' => false, 'message' => 'Nombre y descripción son requeridos']);
            return;
        }
        
        $query = "INSERT INTO rutas_turisticas (nombre, descripcion, distancia_km, dificultad, mapa_url) 
                  VALUES (:nombre, :descripcion, :distancia_km, :dificultad, :mapa_url)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':distancia_km', $distancia_km);
        $stmt->bindParam(':dificultad', $dificultad);
        $stmt->bindParam(':mapa_url', $mapa_url);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Ruta creada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear la ruta']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getRuta($db) {
    try {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "SELECT * FROM rutas_turisticas WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $ruta = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($ruta) {
            echo json_encode(['success' => true, 'ruta' => $ruta]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ruta no encontrada']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function deleteRuta($db) {
    try {
        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "DELETE FROM rutas_turisticas WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Ruta eliminada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la ruta']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}
?>