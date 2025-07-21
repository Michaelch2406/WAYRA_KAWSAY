<?php
class Kichwa {
    private $conn;
    private $table_name = "palabras_kichwa";

    public $id;
    public $palabra_kichwa;
    public $traduccion_espanol;
    public $audio_url;
    public $categoria;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read($limit = null, $offset = 0) {
        $query = "SELECT id, palabra_kichwa, traduccion_espanol, audio_url, categoria, fecha_creacion FROM " . $this->table_name . " ORDER BY palabra_kichwa ASC";
        
        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt;
    }

    function readByCategory($categoria) {
        $query = "SELECT id, palabra_kichwa, traduccion_espanol, audio_url, categoria, fecha_creacion FROM " . $this->table_name . " WHERE categoria = :categoria ORDER BY palabra_kichwa ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->execute();
        return $stmt;
    }

    function search($keywords) {
        $query = "SELECT id, palabra_kichwa, traduccion_espanol, audio_url, categoria, fecha_creacion FROM " . $this->table_name . " WHERE palabra_kichwa LIKE :keywords OR traduccion_espanol LIKE :keywords ORDER BY palabra_kichwa ASC";
        $stmt = $this->conn->prepare($query);
        $keywords = "%{$keywords}%";
        $stmt->bindParam(':keywords', $keywords);
        $stmt->execute();
        return $stmt;
    }

    function readOne() {
        $query = "SELECT id, palabra_kichwa, traduccion_espanol, audio_url, categoria, fecha_creacion FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->palabra_kichwa = $row['palabra_kichwa'];
            $this->traduccion_espanol = $row['traduccion_espanol'];
            $this->audio_url = $row['audio_url'];
            $this->categoria = $row['categoria'];
            return true;
        }
        return false;
    }

    function create() {
        $query = "INSERT INTO " . $this->table_name . " SET palabra_kichwa=:palabra_kichwa, traduccion_espanol=:traduccion_espanol, audio_url=:audio_url, categoria=:categoria";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->palabra_kichwa = htmlspecialchars(strip_tags($this->palabra_kichwa));
        $this->traduccion_espanol = htmlspecialchars(strip_tags($this->traduccion_espanol));
        $this->audio_url = htmlspecialchars(strip_tags($this->audio_url));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));

        // Bind parámetros
        $stmt->bindParam(":palabra_kichwa", $this->palabra_kichwa);
        $stmt->bindParam(":traduccion_espanol", $this->traduccion_espanol);
        $stmt->bindParam(":audio_url", $this->audio_url);
        $stmt->bindParam(":categoria", $this->categoria);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    function update() {
        $query = "UPDATE " . $this->table_name . " SET palabra_kichwa=:palabra_kichwa, traduccion_espanol=:traduccion_espanol, audio_url=:audio_url, categoria=:categoria WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->palabra_kichwa = htmlspecialchars(strip_tags($this->palabra_kichwa));
        $this->traduccion_espanol = htmlspecialchars(strip_tags($this->traduccion_espanol));
        $this->audio_url = htmlspecialchars(strip_tags($this->audio_url));
        $this->categoria = htmlspecialchars(strip_tags($this->categoria));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parámetros
        $stmt->bindParam(":palabra_kichwa", $this->palabra_kichwa);
        $stmt->bindParam(":traduccion_espanol", $this->traduccion_espanol);
        $stmt->bindParam(":audio_url", $this->audio_url);
        $stmt->bindParam(":categoria", $this->categoria);
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

    function getCategories() {
        $query = "SELECT DISTINCT categoria FROM " . $this->table_name . " WHERE categoria IS NOT NULL AND categoria != '' ORDER BY categoria ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_palabras,
                    COUNT(CASE WHEN audio_url IS NOT NULL AND audio_url != '' THEN 1 END) as con_audio,
                    COUNT(DISTINCT categoria) as total_categorias
                  FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>
