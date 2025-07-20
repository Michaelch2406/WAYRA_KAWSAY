<?php
include_once '../config/Conexion.php';
include_once '../models/Usuario.php';

// Verificar que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Conexion();
    $db = $database->getConnection();

    $usuario = new Usuario($db);

    // Asignar valores del formulario al objeto usuario
    $usuario->nombre = $_POST['nombre'];
    $usuario->email = $_POST['email'];
    $usuario->password = $_POST['password'];

    // Intentar crear el usuario
    if ($usuario->crear()) {
        // Redirigir al login con un mensaje de éxito
        header("Location: ../login.php?registro=exitoso");
        exit();
    } else {
        // Redirigir al registro con un mensaje de error
        header("Location: ../registro.php?error=email_existente");
        exit();
    }
} else {
    // Si no es una solicitud POST, redirigir a la página de inicio
    header("Location: ../index.php");
    exit();
}
?>
