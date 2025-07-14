<?php
class Artesanias {
    private $conn;
    private $table_artesanos = "artesanos";
    private $table_productos = "productos_artesanales";

    public function __construct($db) {
        $this->conn = $db;
    }

    function read_artesanos() {
        $query = "SELECT id, nombre, descripcion, foto FROM " . $this->table_artesanos;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function read_productos_por_artesano($id_artesano) {
        $query = "SELECT id, nombre, descripcion, imagen FROM " . $this->table_productos . " WHERE id_artesano = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_artesano);
        $stmt->execute();
        return $stmt;
    }
}
?>
