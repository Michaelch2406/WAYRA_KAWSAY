<?php
class Sabores {
    private $conn;
    private $table_name = "sabores";

    public $id;
    public $nombre;
    public $descripcion;
    public $imagen;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id, nombre, descripcion, imagen FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
