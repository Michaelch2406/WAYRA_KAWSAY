<?php
session_start();
include_once '../config/global.php';
include_once '../config/Conexion.php';

// Verificar que el usuario esté logueado y tenga rol artesano
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

if ($_SESSION['usuario_rol'] !== 'artesano' && $_SESSION['usuario_rol'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

$database = new Conexion();
$db = $database->getConnection();

$action = $_GET['action'] ?? '';

switch($action) {
    case 'update_profile':
        updateArtesanoProfile($db);
        break;
    case 'create_product':
        createProduct($db);
        break;
    case 'toggle_product':
        toggleProductStatus($db);
        break;
    case 'delete_product':
        deleteProduct($db);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}

function updateArtesanoProfile($db) {
    try {
        $nombre = $_POST['nombre'] ?? '';
        $especialidad = $_POST['especialidad'] ?? '';
        $historia = $_POST['historia'] ?? '';
        $telefono_contacto = $_POST['telefono_contacto'] ?? '';
        
        // Obtener ID del artesano
        $query_artesano = "SELECT id FROM artesanos WHERE id_usuario = :user_id";
        $stmt = $db->prepare($query_artesano);
        $stmt->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt->execute();
        $artesano = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$artesano) {
            echo json_encode(['success' => false, 'message' => 'Perfil de artesano no encontrado']);
            return;
        }
        
        // Manejar upload de imagen
        $foto_perfil = null;
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['foto_perfil']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($filetype), $allowed)) {
                $newname = 'artesano_' . $_SESSION['usuario_id'] . '_' . time() . '.' . $filetype;
                $upload_path = '../images/' . $newname;
                
                if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $upload_path)) {
                    $foto_perfil = $newname;
                }
            }
        }
        
        // Actualizar tabla usuarios
        $query_user = "UPDATE usuarios SET nombre = :nombre WHERE id = :user_id";
        $stmt_user = $db->prepare($query_user);
        $stmt_user->bindParam(':nombre', $nombre);
        $stmt_user->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt_user->execute();
        
        // Actualizar tabla artesanos
        $query_artesano = "UPDATE artesanos SET especialidad = :especialidad, historia = :historia, telefono_contacto = :telefono_contacto";
        if ($foto_perfil) {
            $query_artesano .= ", foto_perfil = :foto_perfil";
        }
        $query_artesano .= " WHERE id = :artesano_id";
        
        $stmt_artesano = $db->prepare($query_artesano);
        $stmt_artesano->bindParam(':especialidad', $especialidad);
        $stmt_artesano->bindParam(':historia', $historia);
        $stmt_artesano->bindParam(':telefono_contacto', $telefono_contacto);
        $stmt_artesano->bindParam(':artesano_id', $artesano['id']);
        
        if ($foto_perfil) {
            $stmt_artesano->bindParam(':foto_perfil', $foto_perfil);
        }
        
        if ($stmt_artesano->execute()) {
            $_SESSION['usuario_nombre'] = $nombre;
            echo json_encode(['success' => true, 'message' => 'Perfil actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function createProduct($db) {
    try {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $disponible = isset($_POST['disponible']) ? 1 : 0;
        
        if (empty($nombre) || empty($descripcion)) {
            echo json_encode(['success' => false, 'message' => 'Nombre y descripción son requeridos']);
            return;
        }
        
        // Obtener ID del artesano
        $query_artesano = "SELECT id FROM artesanos WHERE id_usuario = :user_id";
        $stmt = $db->prepare($query_artesano);
        $stmt->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt->execute();
        $artesano = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$artesano) {
            echo json_encode(['success' => false, 'message' => 'Perfil de artesano no encontrado']);
            return;
        }
        
        // Manejar upload de imagen
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['imagen']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($filetype), $allowed)) {
                $newname = 'producto_' . $artesano['id'] . '_' . time() . '.' . $filetype;
                $upload_path = '../images/' . $newname;
                
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_path)) {
                    $imagen = $newname;
                }
            }
        }
        
        // Insertar producto
        $query = "INSERT INTO productos_artesanales (id_artesano, nombre, descripcion, imagen, disponible) 
                  VALUES (:id_artesano, :nombre, :descripcion, :imagen, :disponible)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_artesano', $artesano['id']);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':disponible', $disponible);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Producto agregado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al agregar el producto']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function toggleProductStatus($db) {
    try {
        $product_id = $_POST['product_id'] ?? 0;
        $status = $_POST['status'] ?? 0;
        
        // Verificar que el producto pertenece al artesano
        $query = "SELECT p.id FROM productos_artesanales p 
                  INNER JOIN artesanos a ON p.id_artesano = a.id 
                  WHERE p.id = :product_id AND a.id_usuario = :user_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
            return;
        }
        
        // Actualizar estado
        $query_update = "UPDATE productos_artesanales SET disponible = :status WHERE id = :product_id";
        $stmt_update = $db->prepare($query_update);
        $stmt_update->bindParam(':status', $status);
        $stmt_update->bindParam(':product_id', $product_id);
        
        if ($stmt_update->execute()) {
            echo json_encode(['success' => true, 'message' => 'Estado del producto actualizado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function deleteProduct($db) {
    try {
        $product_id = $_POST['product_id'] ?? 0;
        
        // Verificar que el producto pertenece al artesano
        $query = "SELECT p.id, p.imagen FROM productos_artesanales p 
                  INNER JOIN artesanos a ON p.id_artesano = a.id 
                  WHERE p.id = :product_id AND a.id_usuario = :user_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
            return;
        }
        
        // Eliminar imagen si existe
        if ($producto['imagen'] && file_exists('../images/' . $producto['imagen'])) {
            unlink('../images/' . $producto['imagen']);
        }
        
        // Eliminar producto
        $query_delete = "DELETE FROM productos_artesanales WHERE id = :product_id";
        $stmt_delete = $db->prepare($query_delete);
        $stmt_delete->bindParam(':product_id', $product_id);
        
        if ($stmt_delete->execute()) {
            echo json_encode(['success' => true, 'message' => 'Producto eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}
?>