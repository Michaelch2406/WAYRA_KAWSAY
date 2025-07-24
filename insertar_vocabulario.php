<?php
include_once __DIR__ . '/../config/Conexion.php';
include_once __DIR__ . '/../models/Kichwa.php';

function limpiar_palabra($palabra) {
    return trim(str_replace(['.', ',', ';', '¿', '?', '¡', '!'], '', $palabra));
}

function insertar_vocabulario($db, $archivo, $separador) {
    $kichwa = new Kichwa($db);
    $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $contador = 0;

    foreach ($lineas as $linea) {
        $partes = explode($separador, $linea);
        if (count($partes) >= 2) {
            $palabra_kichwa = limpiar_palabra($partes[0]);
            $traduccion_espanol = limpiar_palabra($partes[1]);

            if (!empty($palabra_kichwa) && !empty($traduccion_espanol)) {
                // Verificar si la palabra ya existe
                $query_check = "SELECT id FROM palabras_kichwa WHERE palabra_kichwa = :palabra_kichwa";
                $stmt_check = $db->prepare($query_check);
                $stmt_check->bindParam(':palabra_kichwa', $palabra_kichwa);
                $stmt_check->execute();
                $existing_palabra = $stmt_check->fetch(PDO::FETCH_ASSOC);

                if (!$existing_palabra) {
                    $kichwa->palabra_kichwa = $palabra_kichwa;
                    $kichwa->traduccion_espanol = $traduccion_espanol;
                    $kichwa->categoria = 'importado';
                    if ($kichwa->create()) {
                        $contador++;
                    }
                }
            }
        }
    }
    return $contador;
}


$database = new Conexion();
$db = $database->getConnection();

$total_insertadas = 0;

// Procesar FRASES COMUNES.txt
$total_insertadas += insertar_vocabulario($db, __DIR__ . '/../info_kichwa/FRASES COMUNES.txt', ' - ');

// Procesar LEXTN-Dea-142622-PUBCOM.txt
$total_insertadas += insertar_vocabulario($db, __DIR__ . '/../info_kichwa/LEXTN-Dea-142622-PUBCOM.txt', '.-');

// Procesar RK_diccionario_kichwa_castellano.txt
$total_insertadas += insertar_vocabulario($db, __DIR__ . '/../info_kichwa/RK_diccionario_kichwa_castellano.txt', ' - ');


echo "Se insertaron " . $total_insertadas . " nuevas palabras en el vocabulario Kichwa.";

?>
