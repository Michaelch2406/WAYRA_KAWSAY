<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo usuario
    function crear() {
        // Verificar si el email ya existe
        if ($this->emailExists()) {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . "
                SET
                    nombre=:nombre,
                    email=:email,
                    password=:password";

        $stmt = $this->conn->prepare($query);

        // Limpiar los datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Hashear la contraseña
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Vincular los valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Verificar si el email ya existe
    function emailExists() {
        $query = "SELECT id FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    // Buscar usuario por email
    function buscarPorEmail() {
        $query = "SELECT
                    id, nombre, password
                FROM
                    " . $this->table_name . "
                WHERE
                    email = ?
                LIMIT
                    0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->password = $row['password'];
            return true;
        }
        
        return false;
    }
}
?>
