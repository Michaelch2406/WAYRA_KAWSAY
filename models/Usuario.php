<?php
class Usuario {
    private $conn;
    private $table_name = "usuarios";

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $telefono;
    public $rol;
    public $fecha_registro;
    public $activo;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nuevo usuario
    function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET nombre=:nombre, email=:email, password=:password, telefono=:telefono, rol=:rol";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        // Bind de parámetros
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":rol", $this->rol);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Leer todos los usuarios
    function read() {
        $query = "SELECT u.id, u.nombre, u.email, u.telefono, u.rol, u.fecha_registro, u.activo,
                         a.historia, a.especialidad, a.foto_perfil
                  FROM " . $this->table_name . " u
                  LEFT JOIN artesanos a ON u.id = a.id_usuario
                  ORDER BY u.fecha_registro DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Leer un usuario por ID
    function readOne() {
        $query = "SELECT u.id, u.nombre, u.email, u.telefono, u.rol, u.fecha_registro, u.activo,
                         a.historia, a.especialidad, a.foto_perfil, a.telefono_contacto
                  FROM " . $this->table_name . " u
                  LEFT JOIN artesanos a ON u.id = a.id_usuario
                  WHERE u.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->telefono = $row['telefono'];
            $this->rol = $row['rol'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->activo = $row['activo'];
            return true;
        }
        return false;
    }

    // Autenticar usuario
    function login($email, $password) {
        $query = "SELECT id, nombre, email, password, rol, activo FROM " . $this->table_name . " 
                  WHERE email = :email AND activo = 1 LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row && password_verify($password, $row['password'])) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->rol = $row['rol'];
            return true;
        }
        return false;
    }

    // Verificar si el email ya existe
    function emailExists() {
        $query = "SELECT id, nombre, email, password, rol FROM " . $this->table_name . " 
                  WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->password = $row['password'];
            $this->rol = $row['rol'];
            return true;
        }
        return false;
    }

    // Actualizar usuario
    function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET nombre=:nombre, email=:email, telefono=:telefono";
        
        // Solo actualizar password si se proporciona uno nuevo
        if(!empty($this->password)) {
            $query .= ", password=:password";
        }
        
        $query .= " WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind de parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':id', $this->id);

        if(!empty($this->password)) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $this->password);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cambiar estado del usuario (activar/desactivar)
    function toggleActive() {
        $query = "UPDATE " . $this->table_name . " SET activo = :activo WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':activo', $this->activo);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar usuario
    function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Crear perfil de artesano
    function createArtesanoProfile($historia, $especialidad, $foto_perfil = null, $telefono_contacto = null) {
        if($this->rol !== 'artesano') {
            return false;
        }

        $query = "INSERT INTO artesanos (id_usuario, historia, especialidad, foto_perfil, telefono_contacto) 
                  VALUES (:id_usuario, :historia, :especialidad, :foto_perfil, :telefono_contacto)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_usuario', $this->id);
        $stmt->bindParam(':historia', $historia);
        $stmt->bindParam(':especialidad', $especialidad);
        $stmt->bindParam(':foto_perfil', $foto_perfil);
        $stmt->bindParam(':telefono_contacto', $telefono_contacto);

        return $stmt->execute();
    }

    // Obtener estadísticas de usuarios
    function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_usuarios,
                    SUM(CASE WHEN rol = 'admin' THEN 1 ELSE 0 END) as total_admins,
                    SUM(CASE WHEN rol = 'artesano' THEN 1 ELSE 0 END) as total_artesanos,
                    SUM(CASE WHEN rol = 'comunitario' THEN 1 ELSE 0 END) as total_comunitarios,
                    SUM(CASE WHEN activo = 1 THEN 1 ELSE 0 END) as total_activos
                  FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar usuarios
    function search($keywords) {
        $query = "SELECT u.id, u.nombre, u.email, u.telefono, u.rol, u.fecha_registro, u.activo
                  FROM " . $this->table_name . " u
                  WHERE u.nombre LIKE :keywords OR u.email LIKE :keywords
                  ORDER BY u.nombre ASC";

        $stmt = $this->conn->prepare($query);
        $keywords = "%{$keywords}%";
        $stmt->bindParam(':keywords', $keywords);
        $stmt->execute();

        return $stmt;
    }

    // Validar datos de registro
    function validateRegistration() {
        $errors = array();

        if(empty($this->nombre)) {
            $errors[] = "El nombre es requerido";
        }

        if(empty($this->email)) {
            $errors[] = "El email es requerido";
        } elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "El formato del email no es válido";
        }

        if(empty($this->password) || strlen($this->password) < 6) {
            $errors[] = "La contraseña debe tener al menos 6 caracteres";
        }

        if(!empty($this->telefono) && !preg_match('/^[\+\d\s\-\(\)]+$/', $this->telefono)) {
            $errors[] = "El formato del teléfono no es válido";
        }

        return $errors;
    }

    // Buscar usuario por email (método legacy)
    function buscarPorEmail() {
        $query = "SELECT id, nombre, password, rol FROM " . $this->table_name . "
                WHERE email = ? AND activo = 1 LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->password = $row['password'];
            $this->rol = $row['rol'];
            return true;
        }
        
        return false;
    }

    // Crear usuario (método legacy)
    function crear() {
        return $this->create();
    }
}
?>
