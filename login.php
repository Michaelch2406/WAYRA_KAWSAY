<?php
include_once 'config/global.php';
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['titulo_login']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">

    <style>
        .auth-container {
            max-width: 500px;
            margin: 5rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .auth-title {
            color: var(--color-principal);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-label {
            color: var(--color-texto);
        }
        .btn-primary {
            background-color: var(--color-principal);
            border-color: var(--color-principal);
            width: 100%;
        }
        .btn-primary:hover {
            background-color: var(--color-acento);
            border-color: var(--color-acento);
        }
    </style>
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-mountain"></i>
                <?php echo NOMBRE_PROYECTO; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home"></i>
                            <?php echo $texto['menu_inicio']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sabores.php">
                            <i class="fas fa-utensils"></i>
                            <?php echo $texto['menu_sabores']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artesanias.php">
                            <i class="fas fa-palette"></i>
                            <?php echo $texto['menu_artesanias']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kichwa.php">
                            <i class="fas fa-language"></i>
                            <?php echo $texto['menu_kichwa']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cultura.php">
                            <i class="fas fa-users"></i>
                            <?php echo $texto['menu_cultura']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ubicacion.php">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo $texto['menu_ubicacion']; ?>
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <select class="form-select me-2" id="language-selector" style="width: auto;">
                        <option value="es" <?php if($lang_code == 'es') echo 'selected'; ?>>
                            🇪🇸 Español
                        </option>
                        <option value="qu" <?php if($lang_code == 'qu') echo 'selected'; ?>>
                            🏔️ Kichwa
                        </option>
                    </select>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <div class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="logout.php"><?php echo $texto['cerrar_sesion']; ?></a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2"><?php echo $texto['iniciar_sesion']; ?></a>
                        <a href="registro.php" class="btn btn-primary"><?php echo $texto['registrar']; ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="auth-container">
            <h2 class="auth-title"><?php echo $texto['bienvenido_de_vuelta']; ?></h2>
            <form action="controllers/login_controller.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label"><?php echo $texto['email_usuario']; ?></label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><?php echo $texto['contrasena_usuario']; ?></label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $texto['iniciar_sesion']; ?></button>
            </form>
            <div class="text-center mt-3">
                <p><?php echo $texto['no_tienes_cuenta']; ?> <a href="registro.php"><?php echo $texto['crea_una_aqui']; ?></a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2024 <?php echo NOMBRE_PROYECTO; ?>. <?php echo $texto['pie_pagina']; ?></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
