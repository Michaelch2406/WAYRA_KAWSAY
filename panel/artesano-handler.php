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
    case 'get_product':
        getProduct($db);
        break;
    case 'update_product':
        updateProduct($db);
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

function getProduct($db) {
    try {
        $product_id = $_GET['id'] ?? 0;
        
        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'ID de producto requerido']);
            return;
        }
        
        // Verificar que el producto pertenece al artesano
        $query = "SELECT p.*, a.id_usuario FROM productos_artesanales p 
                  INNER JOIN artesanos a ON p.id_artesano = a.id 
                  WHERE p.id = :product_id AND a.id_usuario = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt->execute();
        
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            echo json_encode(['success' => true, 'product' => $product]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
    }
}

function updateProduct($db) {
    try {
        $product_id = $_POST['product_id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $disponible = isset($_POST['disponible']) ? 1 : 0;
        
        if (!$product_id || empty($nombre) || empty($descripcion)) {
            echo json_encode(['success' => false, 'message' => 'ID, nombre y descripción son requeridos']);
            return;
        }
        
        // Verificar que el producto pertenece al artesano
        $query_verify = "SELECT p.id FROM productos_artesanales p 
                         INNER JOIN artesanos a ON p.id_artesano = a.id 
                         WHERE p.id = :product_id AND a.id_usuario = :user_id";
        $stmt_verify = $db->prepare($query_verify);
        $stmt_verify->bindParam(':product_id', $product_id);
        $stmt_verify->bindParam(':user_id', $_SESSION['usuario_id']);
        $stmt_verify->execute();
        
        if (!$stmt_verify->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
            return;
        }
        
        // Manejar upload de imagen si existe
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['imagen']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($filetype), $allowed)) {
                $newname = 'producto_' . $product_id . '_' . time() . '.' . $filetype;
                $upload_path = '../images/' . $newname;
                
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_path)) {
                    $imagen = $newname;
                }
            }
        }
        
        // Actualizar producto
        $query = "UPDATE productos_artesanales SET nombre = :nombre, descripcion = :descripcion, disponible = :disponible";
        if ($imagen) {
            $query .= ", imagen = :imagen";
        }
        $query .= " WHERE id = :product_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':disponible', $disponible);
        $stmt->bindParam(':product_id', $product_id);
        
        if ($imagen) {
            $stmt->bindParam(':imagen', $imagen);
        }
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Producto actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el producto']);
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