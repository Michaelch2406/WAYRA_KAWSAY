<?php
class Cultura {
    private $conn;
    private $table_name = "cultura";

    public $id;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $categoria;
    public $fecha;
    public $ubicacion;
    public $importancia;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        if (!$this->conn) {
            return false;
        }

        $query = "SELECT id, nombre, descripcion, imagen, categoria, fecha, ubicacion, importancia FROM " . $this->table_name;

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            // Si la tabla no existe, no hacemos nada.
            // La página mostrará los datos de ejemplo.
            return false;
        }
    }
}
?>
