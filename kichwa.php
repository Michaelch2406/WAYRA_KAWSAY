<?php
include_once 'config/global.php';
include_once 'config/Conexion.php';
include_once 'models/Kichwa.php';

$database = new Conexion();
$db = $database->getConnection();

$kichwa = new Kichwa($db);
$stmt = $kichwa->read();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_kichwa']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><?php echo NOMBRE_PROYECTO; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><?php echo $texto['menu_inicio']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sabores.php"><?php echo $texto['menu_sabores']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artesanias.php"><?php echo $texto['menu_artesanias']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="kichwa.php"><?php echo $texto['menu_kichwa']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cultura.php"><?php echo $texto['menu_cultura']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ubicacion.php"><?php echo $texto['menu_ubicacion']; ?></a>
                    </li>
                </ul>
                <div class="d-flex">
                    <select class="form-select" id="language-selector">
                        <option value="es" <?php if($lang_code == 'es') echo 'selected'; ?>>Español</option>
                        <option value="qu" <?php if($lang_code == 'qu') echo 'selected'; ?>>Kichwa</option>
                    </select>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <h1><?php echo $texto['menu_kichwa']; ?></h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Kichwa</th>
                    <th>Español</th>
                    <th>Audio</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['palabra_kichwa']; ?></td>
                        <td><?php echo $row['traduccion_espanol']; ?></td>
                        <td>
                            <?php if ($row['audio']): ?>
                                <audio controls>
                                    <source src="audio/<?php echo $row['audio']; ?>" type="audio/mpeg">
                                    Tu navegador no soporta el elemento de audio.
                                </audio>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted"><?php echo $texto['pie_pagina']; ?></span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>
</html>
