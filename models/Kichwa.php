<?php
class Kichwa {
    private $conn;
    private $table_name = "palabras_kichwa";

    public $id;
    public $palabra_kichwa;
    public $traduccion_espanol;
    public $audio;

    public function __construct($db) {
        $this->conn = $db;
    }

    function read() {
        $query = "SELECT id, palabra_kichwa, traduccion_espanol, audio FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
