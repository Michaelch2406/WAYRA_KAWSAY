<?php
include_once 'config/global.php';
include_once 'config/Conexion.php';

$database = new Conexion();
$db = $database->getConnection();

// Datos de ejemplo para demostración (en caso de que no haya datos en la BD)
$ubicaciones_ejemplo = [
    [
        'id' => 1,
        'nombre' => 'Laguna de Yahuarcocha',
        'descripcion' => 'Hermosa laguna sagrada ubicada al norte de Ibarra, conocida como "Lago de Sangre" en kichwa. Lugar perfecto para deportes acuáticos y contemplación.',
        'imagen' => 'yahuarcocha_laguna.jpg',
        'categoria' => 'natural',
        'latitud' => '0.3667',
        'longitud' => '-78.0833',
        'altitud' => '2190 m',
        'tipo' => 'Laguna',
        'actividades' => 'Kayak, pesca, caminata'
    ],
    [
        'id' => 2,
        'nombre' => 'Centro Histórico de Ibarra',
        'descripcion' => 'El corazón colonial de la Ciudad Blanca, con arquitectura republicana y calles empedradas que narran siglos de historia andina.',
        'imagen' => 'centro_ibarra.jpg',
        'categoria' => 'cultural',
        'latitud' => '0.3515',
        'longitud' => '-78.1224',
        'altitud' => '2225 m',
        'tipo' => 'Centro Histórico',
        'actividades' => 'Turismo cultural, gastronomía, compras'
    ],
    [
        'id' => 3,
        'nombre' => 'Volcán Imbabura',
        'descripcion' => 'Majestuoso volcán que da nombre a la provincia, considerado sagrado por los pueblos ancestrales. Ideal para montañismo y trekking.',
        'imagen' => 'imbabura_volcan.jpg',
        'categoria' => 'aventura',
        'latitud' => '0.2667',
        'longitud' => '-78.1833',
        'altitud' => '4609 m',
        'tipo' => 'Volcán',
        'actividades' => 'Montañismo, trekking, fotografía'
    ],
    [
        'id' => 4,
        'nombre' => 'Naranjito - Caranqui',
        'descripcion' => 'Pueblo tradicional en la parroquia de Caranqui, hogar de comunidades ancestrales que preservan las tradiciones andinas.',
        'imagen' => 'naranjito_caranqui.jpg',
        'categoria' => 'tradicional',
        'latitud' => '0.3283',
        'longitud' => '-78.1456',
        'altitud' => '2280 m',
        'tipo' => 'Pueblo Tradicional',
        'actividades' => 'Turismo comunitario, artesanías, gastronomía'
    ],
    [
        'id' => 5,
        'nombre' => 'Laguna de Cuicocha',
        'descripcion' => 'Laguna cratérica en el volcán Cotacachi, considerada sagrada. Sus aguas azul turquesa y las islas centrales crean un paisaje único.',
        'imagen' => 'cuicocha.jpg',
        'categoria' => 'natural',
        'latitud' => '0.3167',
        'longitud' => '-78.3667',
        'altitud' => '3068 m',
        'tipo' => 'Laguna Cratérica',
        'actividades' => 'Senderismo, fotografía, observación de aves'
    ],
    [
        'id' => 6,
        'nombre' => 'Mercado de Otavalo',
        'descripcion' => 'El mercado indígena más famoso de Sudamérica, donde se encuentran textiles, artesanías y productos tradicionales de los Andes.',
        'imagen' => 'mercado_otavalo.jpg',
        'categoria' => 'cultural',
        'latitud' => '0.2342',
        'longitud' => '-78.2619',
        'altitud' => '2532 m',
        'tipo' => 'Mercado Tradicional',
        'actividades' => 'Compras, turismo cultural, gastronomía'
    ]
];
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_ubicacion']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    <meta name="description" content="Descubre la ubicación privilegiada de Imbabura en los Andes ecuatorianos. Explora mapas interactivos, puntos de interés y la geografía de esta hermosa provincia.">
    <meta name="keywords" content="ubicación Imbabura, mapas Ecuador, geografía andina, puntos de interés, turismo Imbabura">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/ubicacion.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
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
                        <a class="nav-link active" aria-current="page" href="ubicacion.php">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo $texto['menu_ubicacion']; ?>
                        </a>
                    </li>
                </ul>
                <div class="d-flex">
                    <select class="form-select" id="language-selector">
                        <option value="es" <?php if($lang_code == 'es') echo 'selected'; ?>>
                            🇪🇸 Español
                        </option>
                        <option value="qu" <?php if($lang_code == 'qu') echo 'selected'; ?>>
                            🏔️ Kichwa
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="sabores-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo $texto['menu_ubicacion']; ?>
                </h1>
                <p class="hero-subtitle" data-translate="ubicacion_hero_subtitulo">
                    Descubre la ubicación privilegiada de Imbabura en el corazón de los Andes ecuatorianos, donde cada lugar cuenta una historia milenaria de geografía y cultura.
                </p>
            </div>
        </div>
    </section>

    <!-- Sección de filtros -->
    <section class="filters-section">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-th"></i>
                    <span data-translate="todos">Todos</span>
                </button>
                <button class="filter-btn" data-filter="natural">
                    <i class="fas fa-leaf"></i>
                    <span data-translate="natural">Natural</span>
                </button>
                <button class="filter-btn" data-filter="cultural">
                    <i class="fas fa-landmark"></i>
                    <span data-translate="cultural">Cultural</span>
                </button>
                <button class="filter-btn" data-filter="aventura">
                    <i class="fas fa-mountain"></i>
                    <span data-translate="aventura">Aventura</span>
                </button>
                <button class="filter-btn" data-filter="tradicional">
                    <i class="fas fa-home"></i>
                    <span data-translate="tradicional">Tradicional</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Grid de ubicaciones -->
    <section class="sabores-grid">
        <div class="container">
            <div class="row g-4">
                <?php
                    foreach ($ubicaciones_ejemplo as $ubicacion) {
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="sabor-card" data-category="<?php echo $ubicacion['categoria']; ?>">
                                <div class="card-image-container">
                                    <img src="images/<?php echo $ubicacion['imagen']; ?>" alt="<?php echo $ubicacion['nombre']; ?>" loading="lazy">
                                    <div class="card-overlay">
                                        <div class="overlay-content">
                                            <i class="fas fa-map fa-2x"></i>
                                            <p>Ver en mapa</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo $ubicacion['nombre']; ?></h3>
                                    <p class="card-description"><?php echo $ubicacion['descripcion']; ?></p>
                                    
                                    <div class="card-tags">
                                        <span class="tag">Imbabura</span>
                                        <span class="tag">Ecuador</span>
                                        <?php if ($ubicacion['categoria'] == 'natural') { ?>
                                            <span class="tag">Natural</span>
                                        <?php } elseif ($ubicacion['categoria'] == 'cultural') { ?>
                                            <span class="tag">Cultural</span>
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="card-rating">
                                        <div class="coordinates">
                                            <i class="fas fa-map-pin"></i>
                                            <span><?php echo $ubicacion['latitud']; ?>°N, <?php echo $ubicacion['longitud']; ?>°W</span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <div class="difficulty">
                                            <span>Altitud:</span>
                                            <div class="altitude-badge"><?php echo $ubicacion['altitud']; ?></div>
                                        </div>
                                        <div class="prep-time">
                                            <i class="fas fa-tag"></i>
                                            <?php echo $ubicacion['tipo']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </section>

    <!-- Sección del mapa principal -->
    <section class="main-map-section">
        <div class="container">
            <h2 class="section-title">Mapa Principal de Imbabura</h2>
            <p class="text-center mb-4 lead">
                Explora la ubicación de Imbabura y sus principales puntos de interés en este mapa interactivo. Navega y descubre la geografía de la región.
            </p>
            
            <div class="map-container">
                <div class="map-frame">
                    <iframe 
                        src="https://maps.app.goo.gl/wh6371obnzuUBxDu7"
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Mapa de Imbabura, Ecuador">
                    </iframe>
                </div>
                <div class="map-info mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-info-circle me-2"></i>Información del Mapa</h5>
                            <p>Este mapa muestra la ubicación de Imbabura en Ecuador, incluyendo las principales ciudades, carreteras y puntos de referencia geográficos.</p>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fas fa-route me-2"></i>Cómo Llegar</h5>
                            <p>Imbabura está conectada por la Panamericana Norte (E35) y se encuentra a 115 km al norte de Quito, aproximadamente 2 horas en vehículo.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de información adicional -->
    <section class="info-section">
        <div class="container">
            <h2 class="section-title" data-translate="geografia_imbabura">Geografía de Imbabura</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h3 class="info-title" data-translate="relieve_andino">Relieve Andino</h3>
                        <p class="info-description" data-translate="relieve_andino_descripcion">
                            Imbabura se encuentra en la región interandina, entre las cordilleras Occidental y Oriental, con volcanes, lagunas y valles únicos.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-thermometer-half"></i>
                        </div>
                        <h3 class="info-title" data-translate="clima_templado">Clima Templado</h3>
                        <p class="info-description" data-translate="clima_templado_descripcion">
                            Disfruta de un clima templado andino durante todo el año, con temperaturas promedio de 18°C y paisajes siempre verdes.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h3 class="info-title" data-translate="ubicacion_estrategica">Ubicación Estratégica</h3>
                        <p class="info-description" data-translate="ubicacion_estrategica_descripcion">
                            Situada en el norte de Ecuador, Imbabura es la puerta de entrada a Colombia y conecta la sierra con la costa y la amazonía.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección del clima -->
    <section class="weather-section">
        <div class="container">
            <h2 class="weather-title">
                <i class="fas fa-cloud-sun"></i>
                Clima en Imbabura
            </h2>
            <p class="text-center mb-4">
                Información meteorológica de la región. El clima de Imbabura es templado andino con temperaturas agradables durante todo el año.
            </p>
            
            <div id="weather-info" class="weather-info">
                <div class="weather-loader">
                    <div class="weather-spinner"></div>
                    <p>Cargando información del clima...</p>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <button class="btn btn-light" onclick="loadWeatherData()">
                    <i class="fas fa-sync-alt me-2"></i>
                    Actualizar Clima
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5><i class="fas fa-mountain"></i> <?php echo NOMBRE_PROYECTO; ?></h5>
                    <p>Preservando y compartiendo la riqueza geográfica y cultural de los Andes para las futuras generaciones.</p>
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
                    <h5>Ubicación</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Mapas Interactivos</a></li>
                        <li><a href="#">Puntos de Interés</a></li>
                        <li><a href="#">Información Geográfica</a></li>
                        <li><a href="#">Clima Regional</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-envelope"></i> ubicacion@culturaandina.com</p>
                    <p><i class="fas fa-phone"></i> +593 (0)2 123-4567</p>
                    <p><i class="fas fa-map-marker-alt"></i> Ibarra, Imbabura, Ecuador</p>
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
    <script src="js/ubicacion.js"></script>
</body>
</html>

