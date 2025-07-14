<?php
// Definir constantes globales aquí
define("NOMBRE_PROYECTO", "Wayra Kawsay");

function cargar_idioma($lang = 'es') {
    $archivo = "languages/{$lang}.json";
    if (file_exists($archivo)) {
        $json = file_get_contents($archivo);
        return json_decode($json, true);
    } else {
        // Si el archivo no existe, cargar el idioma por defecto (español)
        $json = file_get_contents("languages/es.json");
        return json_decode($json, true);
    }
}

session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang_code = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'es';
$texto = cargar_idioma($lang_code);
?>
