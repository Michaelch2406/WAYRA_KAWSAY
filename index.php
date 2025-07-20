<?php include_once 'config/global.php'; ?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['titulo_inicio']; ?></title>
    <meta name="description" content="Descubre la rica cultura andina, sus tradiciones, sabores, artesanías y el idioma kichwa en un viaje fascinante por los Andes.">
    <meta name="keywords" content="cultura andina, kichwa, artesanías, gastronomía, tradiciones, Andes">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/index.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
    <!-- Navegación mejorada -->
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
                        <a class="nav-link active" aria-current="page" href="index.php">
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

    <!-- Hero Section mejorado -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo $texto['bienvenido']; ?></h1>
                <p class="hero-subtitle"><?php echo $texto['subtitulo_inicio']; ?></p>
                <a href="#features" class="hero-btn">
                    <i class="fas fa-compass"></i>
                    <span data-translate="explorar_cultura">Explorar Cultura</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Sección de características principales -->
    <section id="features" class="features-section">
        <div class="container">
            <h2 class="section-title" data-translate="descubre_tradiciones">Descubre Nuestras Tradiciones</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3 class="feature-title" data-translate="sabores_unicos">Sabores Únicos</h3>
                        <p class="feature-description" data-translate="sabores_descripcion">
                            Explora la rica gastronomía andina con ingredientes ancestrales y recetas tradicionales que han pasado de generación en generación.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h3 class="feature-title" data-translate="artesanias">Artesanías</h3>
                        <p class="feature-description" data-translate="artesanias_descripcion">
                            Descubre el arte textil, cerámico y de orfebrería que refleja la cosmovisión y habilidades ancestrales de nuestros pueblos.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-language"></i>
                        </div>
                        <h3 class="feature-title" data-translate="idioma_kichwa">Idioma Kichwa</h3>
                        <p class="feature-description" data-translate="idioma_kichwa_descripcion">
                            Aprende sobre la lengua ancestral que conecta a millones de personas en los Andes y preserva nuestra identidad cultural.
                        </p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h3 class="feature-title" data-translate="paisajes">Paisajes</h3>
                        <p class="feature-description" data-translate="paisajes_descripcion">
                            Conoce los majestuosos paisajes andinos que han moldeado nuestra cultura y forma de vida durante milenios.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería de imágenes -->
    <section class="gallery-section">
        <div class="container">
            <h2 class="section-title">Galería Cultural</h2>
            <div class="row g-4">
                <div class="col-lg-6 col-md-6">
                    <div class="gallery-item">
                        <img src="images/cultura_andina.jpg" alt="Cultura Andina" loading="lazy">
                        <div class="gallery-overlay">
                            <div class="gallery-text">
                                <h4 class="gallery-title">Patrimonio Cultural</h4>
                                <p>Miles de años de historia y tradición</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="gallery-item">
                        <img src="images/artesanias.jpg" alt="Artesanías Kichwa" loading="lazy">
                        <div class="gallery-overlay">
                            <div class="gallery-text">
                                <h4 class="gallery-title">Arte Ancestral</h4>
                                <p>Técnicas tradicionales preservadas</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="gallery-item">
                        <img src="images/comida_andina.jpg" alt="Gastronomía Andina" loading="lazy">
                        <div class="gallery-overlay">
                            <div class="gallery-text">
                                <h4 class="gallery-title">Sabores Ancestrales</h4>
                                <p>Ingredientes únicos de los Andes</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="gallery-item">
                        <img src="images/paisaje_andino.jpg" alt="Paisajes Andinos" loading="lazy">
                        <div class="gallery-overlay">
                            <div class="gallery-text">
                                <h4 class="gallery-title">Paisajes Sagrados</h4>
                                <p>La majestuosidad de los Andes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de información adicional -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title text-start">Nuestra Misión</h2>
                    <p class="lead">
                        Preservar y difundir la riqueza cultural de los pueblos andinos, promoviendo el respeto por nuestras tradiciones ancestrales y fomentando el intercambio cultural.
                    </p>
                    <p>
                        A través de este sitio web, queremos acercar a las personas de todo el mundo a la belleza y profundidad de la cultura andina, desde sus expresiones artísticas hasta su filosofía de vida en armonía con la naturaleza.
                    </p>
                    <a href="cultura.php" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-right"></i>
                        Conoce Más
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <img src="images/cultura_andina.jpg" alt="Cultura Andina" class="img-fluid rounded shadow" style="max-height: 400px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer mejorado -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5><i class="fas fa-mountain"></i> <?php echo NOMBRE_PROYECTO; ?></h5>
                    <p>Preservando y compartiendo la riqueza cultural de los Andes para las futuras generaciones.</p>
                    <div class="social-links">
                        <a href="#" class="me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="me-3"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h5>Navegación</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="sabores.php">Sabores</a></li>
                        <li><a href="artesanias.php">Artesanías</a></li>
                        <li><a href="kichwa.php">Kichwa</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Cultura</h5>
                    <ul class="list-unstyled">
                        <li><a href="cultura.php">Tradiciones</a></li>
                        <li><a href="ubicacion.php">Ubicación</a></li>
                        <li><a href="#">Historia</a></li>
                        <li><a href="#">Festivales</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-envelope"></i> info@culturaandina.com</p>
                    <p><i class="fas fa-phone"></i> +593 (0)2 123-4567</p>
                    <p><i class="fas fa-map-marker-alt"></i> Quito, Ecuador</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 <?php echo NOMBRE_PROYECTO; ?>. <?php echo $texto['pie_pagina']; ?></p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/ultra-translation-system.js"></script>
    <script src="js/safe-translation-fix.js"></script>
    <script src="js/ultra-translation-test.js"></script>
    <script src="js/index.js"></script>
</body>
</html>

