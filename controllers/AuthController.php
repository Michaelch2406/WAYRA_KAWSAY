<?php
session_start();
include_once '../config/global.php';
include_once '../config/Conexion.php';
include_once '../models/Usuario.php';

class AuthController {
    private $db;
    private $usuario;

    public function __construct() {
        $database = new Conexion();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function registro() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        try {
            // Obtener datos del POST
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $rol = $_POST['rol'] ?? 'comunitario';

            // Validar que el rol sea válido
            $roles_permitidos = ['comunitario', 'artesano'];
            if (!in_array($rol, $roles_permitidos)) {
                $rol = 'comunitario';
            }

            // Asignar valores al objeto usuario
            $this->usuario->nombre = $nombre;
            $this->usuario->email = $email;
            $this->usuario->password = $password;
            $this->usuario->telefono = $telefono;
            $this->usuario->rol = $rol;

            // Validar datos
            $errores = $this->usuario->validateRegistration();
            if (!empty($errores)) {
                echo json_encode(['success' => false, 'message' => implode(', ', $errores)]);
                return;
            }

            // Verificar si el email ya existe
            if ($this->usuario->emailExists()) {
                echo json_encode(['success' => false, 'message' => 'El email ya está registrado']);
                return;
            }

            // Crear usuario
            if ($this->usuario->create()) {
                // Si es artesano, crear perfil básico
                if ($rol === 'artesano') {
                    $this->usuario->createArtesanoProfile(
                        'Perfil en construcción...', 
                        'Sin especialidad definida',
                        null,
                        $telefono
                    );
                }

                echo json_encode([
                    'success' => true, 
                    'message' => 'Usuario registrado exitosamente',
                    'redirect' => 'login.php'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al registrar usuario']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
    }

    public function login() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            return;
        }

        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Email y contraseña son requeridos']);
                return;
            }

            // Intentar autenticar
            if ($this->usuario->login($email, $password)) {
                // Establecer variables de sesión
                $_SESSION['usuario_id'] = $this->usuario->id;
                $_SESSION['usuario_nombre'] = $this->usuario->nombre;
                $_SESSION['usuario_email'] = $this->usuario->email;
                $_SESSION['usuario_rol'] = $this->usuario->rol;
                $_SESSION['logged_in'] = true;

                // Determinar redirección según el rol
                $redirect = 'index.php';
                switch ($this->usuario->rol) {
                    case 'admin':
                        $redirect = 'admin/dashboard.php';
                        break;
                    case 'artesano':
                        $redirect = 'panel/artesano.php';
                        break;
                    case 'comunitario':
                        $redirect = 'panel/comunitario.php';
                        break;
                }

                echo json_encode([
                    'success' => true, 
                    'message' => 'Inicio de sesión exitoso',
                    'redirect' => $redirect,
                    'user' => [
                        'id' => $this->usuario->id,
                        'nombre' => $this->usuario->nombre,
                        'rol' => $this->usuario->rol
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ../index.php');
        exit;
    }

    public function verificarSesion() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ../login.php');
            exit;
        }
    }

    public function verificarRol($roles_permitidos) {
        $this->verificarSesion();
        
        if (!in_array($_SESSION['usuario_rol'], $roles_permitidos)) {
            header('Location: ../index.php');
            exit;
        }
    }
}

// Manejar las acciones según el parámetro 'action'
if (isset($_GET['action'])) {
    $controller = new AuthController();
    
    switch ($_GET['action']) {
        case 'registro':
            $controller->registro();
            break;
        case 'login':
            $controller->login();
            break;
        case 'logout':
            $controller->logout();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
}
?>