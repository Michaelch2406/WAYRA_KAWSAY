<?php
session_start();

include_once '../config/Conexion.php';
include_once '../models/Usuario.php';

// Verificar que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Conexion();
    $db = $database->getConnection();

    $usuario = new Usuario($db);

    $usuario->email = $_POST['email'];

    // Buscar el usuario por email
    if ($usuario->buscarPorEmail()) {
        // Verificar la contraseña
        if (password_verify($_POST['password'], $usuario->password)) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['usuario_nombre'] = $usuario->nombre;

            // Redirigir a la página de inicio
            header("Location: ../index.php");
            exit();
        } else {
            // Contraseña incorrecta
            header("Location: ../login.php?error=credenciales_invalidas");
            exit();
        }
    } else {
        // No se encontró el usuario
        header("Location: ../login.php?error=credenciales_invalidas");
        exit();
    }
} else {
    // Si no es una solicitud POST, redirigir a la página de inicio
    header("Location: ../index.php");
    exit();
}
?>
