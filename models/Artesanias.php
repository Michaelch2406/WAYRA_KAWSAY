<?php
class Artesanias {
    private $conn;
    private $table_artesanos = "artesanos";
    private $table_productos = "productos_artesanales";
    private $table_usuarios = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    function read_artesanos() {
        $query = "SELECT a.id, u.nombre, a.historia as descripcion, a.foto_perfil as foto, a.especialidad, a.telefono_contacto 
                  FROM " . $this->table_artesanos . " a 
                  INNER JOIN " . $this->table_usuarios . " u ON a.id_usuario = u.id 
                  WHERE u.activo = 1 
                  ORDER BY u.nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function read_productos_por_artesano($id_artesano) {
        $query = "SELECT id, nombre, descripcion, imagen, disponible, fecha_creacion 
                  FROM " . $this->table_productos . " 
                  WHERE id_artesano = ? 
                  ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_artesano);
        $stmt->execute();
        return $stmt;
    }

    function read_productos_disponibles() {
        $query = "SELECT p.id, p.nombre, p.descripcion, p.imagen, p.disponible, 
                         a.id as artesano_id, u.nombre as artesano_nombre, a.especialidad
                  FROM " . $this->table_productos . " p
                  INNER JOIN " . $this->table_artesanos . " a ON p.id_artesano = a.id
                  INNER JOIN " . $this->table_usuarios . " u ON a.id_usuario = u.id
                  WHERE p.disponible = 1 AND u.activo = 1
                  ORDER BY p.fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function read_artesano_by_id($id_artesano) {
        $query = "SELECT a.*, u.nombre, u.email, u.telefono
                  FROM " . $this->table_artesanos . " a 
                  INNER JOIN " . $this->table_usuarios . " u ON a.id_usuario = u.id 
                  WHERE a.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_artesano);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function search_artesanos($keywords) {
        $query = "SELECT a.id, u.nombre, a.historia as descripcion, a.foto_perfil as foto, a.especialidad 
                  FROM " . $this->table_artesanos . " a 
                  INNER JOIN " . $this->table_usuarios . " u ON a.id_usuario = u.id 
                  WHERE (u.nombre LIKE :keywords OR a.especialidad LIKE :keywords OR a.historia LIKE :keywords)
                  AND u.activo = 1 
                  ORDER BY u.nombre ASC";
        $stmt = $this->conn->prepare($query);
        $keywords = "%{$keywords}%";
        $stmt->bindParam(':keywords', $keywords);
        $stmt->execute();
        return $stmt;
    }

    function search_productos($keywords) {
        $query = "SELECT p.id, p.nombre, p.descripcion, p.imagen, p.disponible, 
                         a.id as artesano_id, u.nombre as artesano_nombre, a.especialidad
                  FROM " . $this->table_productos . " p
                  INNER JOIN " . $this->table_artesanos . " a ON p.id_artesano = a.id
                  INNER JOIN " . $this->table_usuarios . " u ON a.id_usuario = u.id
                  WHERE (p.nombre LIKE :keywords OR p.descripcion LIKE :keywords)
                  AND p.disponible = 1 AND u.activo = 1
                  ORDER BY p.fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $keywords = "%{$keywords}%";
        $stmt->bindParam(':keywords', $keywords);
        $stmt->execute();
        return $stmt;
    }

    function get_estadisticas() {
        $query = "SELECT 
                    COUNT(DISTINCT a.id) as total_artesanos,
                    COUNT(p.id) as total_productos,
                    COUNT(CASE WHEN p.disponible = 1 THEN p.id END) as productos_disponibles,
                    COUNT(DISTINCT a.especialidad) as especialidades_unicas
                  FROM " . $this->table_artesanos . " a
                  LEFT JOIN " . $this->table_productos . " p ON a.id = p.id_artesano
                  INNER JOIN " . $this->table_usuarios . " u ON a.id_usuario = u.id
                  WHERE u.activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
