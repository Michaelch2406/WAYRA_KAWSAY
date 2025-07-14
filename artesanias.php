<?php
include_once 'config/global.php';
include_once 'config/Conexion.php';
include_once 'models/Artesanias.php';

$database = new Conexion();
$db = $database->getConnection();

$artesania = new Artesanias($db);
$stmt_artesanos = $artesania->read_artesanos();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_artesanias']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
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
                        <a class="nav-link active" aria-current="page" href="artesanias.php"><?php echo $texto['menu_artesanias']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kichwa.php"><?php echo $texto['menu_kichwa']; ?></a>
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
        <h1><?php echo $texto['menu_artesanias']; ?></h1>

        <?php while ($row_artesano = $stmt_artesanos->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-2">
                                <img src="images/<?php echo $row_artesano['foto']; ?>" class="img-fluid rounded-start" alt="<?php echo $row_artesano['nombre']; ?>">
                            </div>
                            <div class="col-md-10">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row_artesano['nombre']; ?></h5>
                                    <p class="card-text"><?php echo $row_artesano['descripcion']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5>Productos de <?php echo $row_artesano['nombre']; ?></h5>
                    <div class="row">
                        <?php
                        $stmt_productos = $artesania->read_productos_por_artesano($row_artesano['id']);
                        while ($row_producto = $stmt_productos->fetch(PDO::FETCH_ASSOC)):
                        ?>
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="images/<?php echo $row_producto['imagen']; ?>" class="card-img-top" alt="<?php echo $row_producto['nombre']; ?>">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo $row_producto['nombre']; ?></h6>
                                        <p class="card-text"><?php echo $row_producto['descripcion']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
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
