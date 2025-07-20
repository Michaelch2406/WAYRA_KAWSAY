<?php
class Sabores {
    private $conn;
    private $table_name = "platos";

    public $id;
    public $nombre;
    public $descripcion;
    public $imagen;
    public $historia;
    public $categoria;
    public $dificultad;
    public $tiempo_preparacion;
    public $video_preparacion_url;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id, nombre, descripcion, historia, imagen, categoria, dificultad, tiempo_preparacion, video_preparacion_url FROM " . $this->table_name . " ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function readByCategory($categoria) {
        $query = "SELECT id, nombre, descripcion, historia, imagen, categoria, dificultad, tiempo_preparacion, video_preparacion_url FROM " . $this->table_name . " WHERE categoria = :categoria ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->execute();
        return $stmt;
    }

    function readOne() {
        $query = "SELECT id, nombre, descripcion, historia, imagen, categoria, dificultad, tiempo_preparacion, video_preparacion_url FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->historia = $row['historia'];
            $this->imagen = $row['imagen'];
            $this->categoria = $row['categoria'];
            $this->dificultad = $row['dificultad'];
            $this->tiempo_preparacion = $row['tiempo_preparacion'];
            $this->video_preparacion_url = $row['video_preparacion_url'];
            return true;
        }
        return false;
    }

    function search($keywords) {
        $query = "SELECT id, nombre, descripcion, historia, imagen, categoria, dificultad, tiempo_preparacion, video_preparacion_url FROM " . $this->table_name . " WHERE nombre LIKE :keywords OR descripcion LIKE :keywords ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $keywords = "%{$keywords}%";
        $stmt->bindParam(':keywords', $keywords);
        $stmt->execute();
        return $stmt;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, descripcion=:descripcion, historia=:historia, imagen=:imagen, categoria=:categoria, dificultad=:dificultad, tiempo_preparacion=:tiempo_preparacion, video_preparacion_url=:video_preparacion_url";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->historia = htmlspecialchars(strip_tags($this->historia));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->dificultad = htmlspecialchars(strip_tags($this->dificultad));
        $this->tiempo_preparacion = htmlspecialchars(strip_tags($this->tiempo_preparacion));
        $this->video_preparacion_url = htmlspecialchars(strip_tags($this->video_preparacion_url));

        // Bind parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":historia", $this->historia);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":dificultad", $this->dificultad);
        $stmt->bindParam(":tiempo_preparacion", $this->tiempo_preparacion);
        $stmt->bindParam(":video_preparacion_url", $this->video_preparacion_url);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre=:nombre, descripcion=:descripcion, historia=:historia, imagen=:imagen, categoria=:categoria, dificultad=:dificultad, tiempo_preparacion=:tiempo_preparacion, video_preparacion_url=:video_preparacion_url WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->historia = htmlspecialchars(strip_tags($this->historia));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->dificultad = htmlspecialchars(strip_tags($this->dificultad));
        $this->tiempo_preparacion = htmlspecialchars(strip_tags($this->tiempo_preparacion));
        $this->video_preparacion_url = htmlspecialchars(strip_tags($this->video_preparacion_url));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":historia", $this->historia);
        $stmt->bindParam(":imagen", $this->imagen);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":dificultad", $this->dificultad);
        $stmt->bindParam(":tiempo_preparacion", $this->tiempo_preparacion);
        $stmt->bindParam(":video_preparacion_url", $this->video_preparacion_url);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
