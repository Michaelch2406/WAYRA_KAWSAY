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

// Función para manejar uploads de archivos
function handleFileUpload($file, $type) {
    $allowedImages = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $allowedAudio = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];
    $maxSize = 10 * 1024 * 1024; // 10MB
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'El archivo es demasiado grande (máximo 10MB)'];
    }
    
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($type === 'image' && !in_array($fileExtension, $allowedImages)) {
        return ['success' => false, 'message' => 'Formato de imagen no permitido'];
    }
    
    if ($type === 'audio' && !in_array($fileExtension, $allowedAudio)) {
        return ['success' => false, 'message' => 'Formato de audio no permitido'];
    }
    
    // Generar nombre único
    $filename = uniqid() . '_' . time() . '.' . $fileExtension;
    $uploadDir = $type === 'image' ? '../images/' : '../audio/';
    $uploadPath = $uploadDir . $filename;
    
    // Crear directorio si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $uploadPath];
    } else {
        return ['success' => false, 'message' => 'Error al mover el archivo'];
    }
}

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
    
    // New CRUD operations for users management
    case 'create_user':
        createUser($db);
        break;
    case 'get_user':
        getUser($db);
        break;
    case 'update_user':
        updateUser($db);
        break;
    case 'update_plato':
        updatePlato($db);
        break;
    case 'update_palabra':
        updatePalabra($db);
        break;
    case 'update_evento':
        updateEvento($db);
        break;
    case 'update_ruta':
        updateRuta($db);
        break;
    case 'upload_file':
        uploadFile($db);
        break;
    case 'get_all_users':
        getAllUsers($db);
        break;
    case 'get_all_platos':
        getAllPlatos($db);
        break;
    case 'get_all_palabras':
        getAllPalabras($db);
        break;
    case 'get_all_eventos':
        getAllEventos($db);
        break;
    case 'get_all_rutas':
        getAllRutas($db);
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

        // Verificar si ya existe un plato con el mismo nombre
        $query_check = "SELECT id FROM platos WHERE nombre = :nombre";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':nombre', $nombre);
        $stmt_check->execute();
        $existing_plato = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($existing_plato) {
            // Si existe, actualizarlo en lugar de crear uno nuevo
            $_POST['id'] = $existing_plato['id'];
            return updatePlato($db);
        }

        // Manejar upload de imagen
        if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = handleFileUpload($_FILES['imagen_file'], 'image');
            if ($uploaded_file['success']) {
                $imagen = $uploaded_file['filename'];
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir imagen: ' . $uploaded_file['message']]);
                return;
            }
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
            echo json_encode(['success' => true, 'message' => 'Plato creado exitosamente', 'id' => $db->lastInsertId()]);
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

        // Verificar si ya existe una palabra con el mismo nombre
        $query_check = "SELECT id FROM palabras_kichwa WHERE palabra_kichwa = :palabra_kichwa";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':palabra_kichwa', $palabra_kichwa);
        $stmt_check->execute();
        $existing_palabra = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($existing_palabra) {
            // Si existe, actualizarla en lugar de crear una nueva
            $_POST['id'] = $existing_palabra['id'];
            return updatePalabra($db);
        }

        // Manejar upload de audio
        if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = handleFileUpload($_FILES['audio_file'], 'audio');
            if ($uploaded_file['success']) {
                $audio_url = $uploaded_file['filename'];
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir audio: ' . $uploaded_file['message']]);
                return;
            }
        }

        $query = "INSERT INTO palabras_kichwa (palabra_kichwa, traduccion_espanol, categoria, audio_url) 
                  VALUES (:palabra_kichwa, :traduccion_espanol, :categoria, :audio_url)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':palabra_kichwa', $palabra_kichwa);
        $stmt->bindParam(':traduccion_espanol', $traduccion_espanol);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':audio_url', $audio_url);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Palabra creada exitosamente', 'id' => $db->lastInsertId()]);
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

        // Verificar si ya existe un evento con el mismo nombre
        $query_check = "SELECT id FROM eventos WHERE nombre = :nombre";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':nombre', $nombre);
        $stmt_check->execute();
        $existing_evento = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($existing_evento) {
            // Si existe, actualizarlo en lugar de crear uno nuevo
            $_POST['id'] = $existing_evento['id'];
            return updateEvento($db);
        }

        // Manejar upload de imagen
        if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = handleFileUpload($_FILES['imagen_file'], 'image');
            if ($uploaded_file['success']) {
                $imagen = $uploaded_file['filename'];
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir imagen: ' . $uploaded_file['message']]);
                return;
            }
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
            echo json_encode(['success' => true, 'message' => 'Evento creado exitosamente', 'id' => $db->lastInsertId()]);
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

        // Verificar si ya existe una ruta con el mismo nombre
        $query_check = "SELECT id FROM rutas_turisticas WHERE nombre = :nombre";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':nombre', $nombre);
        $stmt_check->execute();
        $existing_ruta = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($existing_ruta) {
            // Si existe, actualizarla en lugar de crear una nueva
            $_POST['id'] = $existing_ruta['id'];
            return updateRuta($db);
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

// User Management Functions
function createUser($db) {
    try {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $rol = $_POST['rol'] ?? 'comunitario';
        
        if (!$nombre || !$email || !$password) {
            echo json_encode(['success' => false, 'message' => 'Nombre, email y contraseña son requeridos']);
            return;
        }
        
        // Verify email doesn't exist
        $query_check = "SELECT COUNT(*) as count FROM usuarios WHERE email = :email";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado']);
            return;
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $db->beginTransaction();
        
        try {
            $query = "INSERT INTO usuarios (nombre, email, password, telefono, rol, activo) 
                      VALUES (:nombre, :email, :password, :telefono, :rol, 1)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':rol', $rol);
            $stmt->execute();
            
            $user_id = $db->lastInsertId();
            
            // If role is artesano, create artisan profile
            if ($rol === 'artesano') {
                $query_artesano = "INSERT INTO artesanos (id_usuario, historia, especialidad) 
                                   VALUES (:user_id, 'Perfil en construcción...', 'Sin especialidad definida')";
                $stmt_artesano = $db->prepare($query_artesano);
                $stmt_artesano->bindParam(':user_id', $user_id);
                $stmt_artesano->execute();
            }
            
            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);
            
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getUser($db) {
    try {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            return;
        }
        
        $query = "SELECT id, nombre, email, telefono, rol, activo, fecha_registro FROM usuarios WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function updateUser($db) {
    try {
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $rol = $_POST['rol'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (!$id || !$nombre || !$email) {
            echo json_encode(['success' => false, 'message' => 'ID, nombre y email son requeridos']);
            return;
        }
        
        // Check if email is already used by another user
        $query_check = "SELECT COUNT(*) as count FROM usuarios WHERE email = :email AND id != :id";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':email', $email);
        $stmt_check->bindParam(':id', $id);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            echo json_encode(['success' => false, 'message' => 'El email ya está registrado por otro usuario']);
            return;
        }
        
        $db->beginTransaction();
        
        try {
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE usuarios SET nombre = :nombre, email = :email, telefono = :telefono, rol = :rol, password = :password WHERE id = :id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':password', $hashed_password);
            } else {
                $query = "UPDATE usuarios SET nombre = :nombre, email = :email, telefono = :telefono, rol = :rol WHERE id = :id";
                $stmt = $db->prepare($query);
            }
            
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            // If new role is artesano and no profile exists, create one
            if ($rol === 'artesano') {
                $query_check_artesano = "SELECT id FROM artesanos WHERE id_usuario = :user_id";
                $stmt_check_artesano = $db->prepare($query_check_artesano);
                $stmt_check_artesano->bindParam(':user_id', $id);
                $stmt_check_artesano->execute();
                
                if ($stmt_check_artesano->rowCount() === 0) {
                    $query_artesano = "INSERT INTO artesanos (id_usuario, historia, especialidad) 
                                       VALUES (:user_id, 'Perfil en construcción...', 'Sin especialidad definida')";
                    $stmt_artesano = $db->prepare($query_artesano);
                    $stmt_artesano->bindParam(':user_id', $id);
                    $stmt_artesano->execute();
                }
            }
            
            $db->commit();
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
            
        } catch (Exception $e) {
            $db->rollback();
            throw $e;
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getAllUsers($db) {
    try {
        $query = "SELECT id, nombre, email, telefono, rol, activo, fecha_registro FROM usuarios ORDER BY fecha_registro DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'users' => $users]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function updatePlato($db) {
    try {
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $historia = $_POST['historia'] ?? '';
        $imagen = $_POST['imagen'] ?? '';
        $video_preparacion_url = $_POST['video_preparacion_url'] ?? '';
        $categoria = $_POST['categoria'] ?? 'tradicional';
        $dificultad = $_POST['dificultad'] ?? 'medio';
        $tiempo_preparacion = $_POST['tiempo_preparacion'] ?? null;
        
        if (!$id || !$nombre || !$descripcion) {
            echo json_encode(['success' => false, 'message' => 'ID, nombre y descripción son requeridos']);
            return;
        }
        
        // Obtener imagen actual
        $query_current = "SELECT imagen FROM platos WHERE id = :id";
        $stmt_current = $db->prepare($query_current);
        $stmt_current->bindParam(':id', $id);
        $stmt_current->execute();
        $current_data = $stmt_current->fetch(PDO::FETCH_ASSOC);
        $current_image = $current_data['imagen'] ?? '';
        
        // Manejar upload de nueva imagen
        if (isset($_FILES['imagen_file']) && $_FILES['imagen_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = handleFileUpload($_FILES['imagen_file'], 'image');
            if ($uploaded_file['success']) {
                // Eliminar imagen anterior si existe y es diferente
                if ($current_image && $current_image !== $uploaded_file['filename'] && file_exists('../images/' . $current_image)) {
                    unlink('../images/' . $current_image);
                }
                $imagen = $uploaded_file['filename'];
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir imagen: ' . $uploaded_file['message']]);
                return;
            }
        } else if (empty($imagen)) {
            // Si no se subió archivo nuevo y no se especificó nombre, mantener imagen actual
            $imagen = $current_image;
        }
        
        $query = "UPDATE platos SET nombre = :nombre, descripcion = :descripcion, historia = :historia, 
                  imagen = :imagen, video_preparacion_url = :video_preparacion_url, categoria = :categoria, 
                  dificultad = :dificultad, tiempo_preparacion = :tiempo_preparacion WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':historia', $historia);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':video_preparacion_url', $video_preparacion_url);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':dificultad', $dificultad);
        $stmt->bindParam(':tiempo_preparacion', $tiempo_preparacion);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Plato actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el plato']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getAllPlatos($db) {
    try {
        $query = "SELECT * FROM platos ORDER BY fecha_creacion DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $platos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'platos' => $platos]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function updatePalabra($db) {
    try {
        $id = $_POST['id'] ?? 0;
        $palabra_kichwa = $_POST['palabra_kichwa'] ?? '';
        $traduccion_espanol = $_POST['traduccion_espanol'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $audio_url = $_POST['audio_url'] ?? '';
        
        if (!$id || !$palabra_kichwa || !$traduccion_espanol) {
            echo json_encode(['success' => false, 'message' => 'ID, palabra kichwa y traducción son requeridos']);
            return;
        }
        
        // Obtener audio actual
        $query_current = "SELECT audio_url FROM palabras_kichwa WHERE id = :id";
        $stmt_current = $db->prepare($query_current);
        $stmt_current->bindParam(':id', $id);
        $stmt_current->execute();
        $current_data = $stmt_current->fetch(PDO::FETCH_ASSOC);
        $current_audio = $current_data['audio_url'] ?? '';
        
        // Manejar upload de nuevo audio
        if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = handleFileUpload($_FILES['audio_file'], 'audio');
            if ($uploaded_file['success']) {
                // Eliminar audio anterior si existe y es diferente
                if ($current_audio && $current_audio !== $uploaded_file['filename'] && file_exists('../audio/' . $current_audio)) {
                    unlink('../audio/' . $current_audio);
                }
                $audio_url = $uploaded_file['filename'];
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al subir audio: ' . $uploaded_file['message']]);
                return;
            }
        } else if (empty($audio_url)) {
            // Si no se subió archivo nuevo y no se especificó nombre, mantener audio actual
            $audio_url = $current_audio;
        }
        
        $query = "UPDATE palabras_kichwa SET palabra_kichwa = :palabra_kichwa, traduccion_espanol = :traduccion_espanol, 
                  categoria = :categoria, audio_url = :audio_url WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':palabra_kichwa', $palabra_kichwa);
        $stmt->bindParam(':traduccion_espanol', $traduccion_espanol);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':audio_url', $audio_url);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Palabra actualizada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la palabra']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getAllPalabras($db) {
    try {
        $query = "SELECT * FROM palabras_kichwa ORDER BY fecha_creacion DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $palabras = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'palabras' => $palabras]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function updateEvento($db) {
    try {
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? null;
        $ubicacion_texto = $_POST['ubicacion_texto'] ?? '';
        $imagen = $_POST['imagen'] ?? '';
        
        if (!$id || !$nombre || !$descripcion || !$fecha_inicio) {
            echo json_encode(['success' => false, 'message' => 'ID, nombre, descripción y fecha de inicio son requeridos']);
            return;
        }
        
        $query = "UPDATE eventos SET nombre = :nombre, descripcion = :descripcion, fecha_inicio = :fecha_inicio, 
                  fecha_fin = :fecha_fin, ubicacion_texto = :ubicacion_texto, imagen = :imagen WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha_inicio', $fecha_inicio);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->bindParam(':ubicacion_texto', $ubicacion_texto);
        $stmt->bindParam(':imagen', $imagen);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Evento actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el evento']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getAllEventos($db) {
    try {
        $query = "SELECT e.*, u.nombre as creador_nombre FROM eventos e 
                  LEFT JOIN usuarios u ON e.id_usuario_creador = u.id 
                  ORDER BY e.fecha_inicio DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'eventos' => $eventos]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function updateRuta($db) {
    try {
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $distancia_km = $_POST['distancia_km'] ?? null;
        $dificultad = $_POST['dificultad'] ?? null;
        $mapa_url = $_POST['mapa_url'] ?? '';
        
        if (!$id || !$nombre || !$descripcion) {
            echo json_encode(['success' => false, 'message' => 'ID, nombre y descripción son requeridos']);
            return;
        }
        
        $query = "UPDATE rutas_turisticas SET nombre = :nombre, descripcion = :descripcion, 
                  distancia_km = :distancia_km, dificultad = :dificultad, mapa_url = :mapa_url WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':distancia_km', $distancia_km);
        $stmt->bindParam(':dificultad', $dificultad);
        $stmt->bindParam(':mapa_url', $mapa_url);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Ruta actualizada exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la ruta']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function getAllRutas($db) {
    try {
        $query = "SELECT * FROM rutas_turisticas ORDER BY fecha_creacion DESC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'rutas' => $rutas]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function uploadFile($db) {
    try {
        $uploadDir = '../uploads/';
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Error al subir el archivo']);
            return;
        }
        
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmpName = $file['tmp_name'];
        $fileType = $file['type'];
        
        // Get file extension
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Define allowed extensions based on file type
        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedAudioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'm4a'];
        
        $fileCategory = '';
        if (in_array($fileExtension, $allowedImageExtensions)) {
            $fileCategory = 'image';
            $uploadSubDir = $uploadDir . 'images/';
        } elseif (in_array($fileExtension, $allowedAudioExtensions)) {
            $fileCategory = 'audio';
            $uploadSubDir = $uploadDir . 'audio/';
        } else {
            echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido']);
            return;
        }
        
        // Create subdirectory if it doesn't exist
        if (!file_exists($uploadSubDir)) {
            mkdir($uploadSubDir, 0777, true);
        }
        
        // Check file size (10MB max)
        if ($fileSize > 10 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'El archivo es demasiado grande (máximo 10MB)']);
            return;
        }
        
        // Generate unique filename
        $newFileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadSubDir . $newFileName;
        
        if (move_uploaded_file($fileTmpName, $uploadPath)) {
            // Return relative path for database storage
            $relativePath = 'uploads/' . $fileCategory . 's/' . $newFileName;
            echo json_encode([
                'success' => true, 
                'message' => 'Archivo subido exitosamente',
                'filename' => $newFileName,
                'path' => $relativePath,
                'url' => '../' . $relativePath,
                'type' => $fileCategory
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al mover el archivo']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}
?>