<?php
include_once 'config/global.php';
include_once 'config/Conexion.php';
include_once 'models/Cultura.php';

$database = new Conexion();
$db = $database->getConnection();

$cultura = new Cultura($db);
$stmt = $cultura->read();

// Datos de ejemplo para demostración (en caso de que no haya datos en la BD)
$eventos_ejemplo = [
    [
        'id' => 1,
        'nombre' => 'Inti Raymi',
        'descripcion' => 'Celebración del solsticio de invierno, una de las festividades más importantes de la cultura andina. Se realizan danzas, música y rituales de agradecimiento al sol.',
        'imagen' => 'tradiciones_imbabura.jpg',
        'categoria' => 'festival',
        'fecha' => '21 de Junio',
        'ubicacion' => 'Caranqui, Ibarra',
        'importancia' => 5
    ],
    [
        'id' => 2,
        'nombre' => 'Fiesta del Maíz',
        'descripcion' => 'Agradecimiento a la Pachamama por la cosecha del maíz. Incluye preparación de platos tradicionales y ceremonias de bendición.',
        'imagen' => 'personajes_tradicionales.jpeg',
        'categoria' => 'agricultural',
        'fecha' => '15 de Septiembre',
        'ubicacion' => 'Comunidades rurales de Imbabura',
        'importancia' => 4
    ],
    [
        'id' => 3,
        'nombre' => 'Día de los Difuntos',
        'descripcion' => 'Tradición de honrar a los antepasados con ofrendas, visitas al cementerio y la preparación de la tradicional colada morada y guaguas de pan.',
        'imagen' => 'etnias_imbabura.jpg',
        'categoria' => 'traditional',
        'fecha' => '2 de Noviembre',
        'ubicacion' => 'Cementerios de Imbabura',
        'importancia' => 4
    ],
    [
        'id' => 4,
        'nombre' => 'Pawkar Raymi',
        'descripcion' => 'Celebración del equinoccio de primavera y el florecimiento de la naturaleza. Festival de colores, música y danzas tradicionales.',
        'imagen' => 'semana_santa.jpg',
        'categoria' => 'festival',
        'fecha' => '21 de Marzo',
        'ubicacion' => 'Otavalo y comunidades aledañas',
        'importancia' => 4
    ],
    [
        'id' => 5,
        'nombre' => 'Fiesta de San Juan',
        'descripcion' => 'Celebración que combina tradiciones católicas con rituales ancestrales andinos. Incluye danzas, música y ceremonias de purificación.',
        'imagen' => 'artesanias_ecuador.jpg',
        'categoria' => 'religious',
        'fecha' => '24 de Junio',
        'ubicacion' => 'Toda la provincia de Imbabura',
        'importancia' => 3
    ],
    [
        'id' => 6,
        'nombre' => 'Música Ancestral',
        'descripcion' => 'Los ritmos andinos resuenan en cada celebración. Instrumentos como la quena, el charango y el bombo acompañan las danzas tradicionales.',
        'imagen' => 'artesanias_coloridas.jpg',
        'categoria' => 'musical',
        'fecha' => 'Todo el año',
        'ubicacion' => 'Toda la región',
        'importancia' => 5
    ]
];
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_cultura']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    <meta name="description" content="Descubre la rica cultura de Imbabura, Ecuador. Tradiciones, festivales, artesanías y patrimonio cultural de Ibarra y Naranjito.">
    <meta name="keywords" content="cultura, Imbabura, Ecuador, tradiciones, festivales, artesanías, Ibarra, Naranjito, Inti Raymi">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/cultura.css">
    
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
                        <a class="nav-link active" aria-current="page" href="cultura.php">
                            <i class="fas fa-theater-masks"></i>
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

    <!-- Hero Section -->
    <section class="cultura-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-theater-masks"></i>
                    <span data-translate="cultura_hero_titulo">Cultura de Imbabura</span>
                </h1>
                <p class="hero-subtitle" data-translate="cultura_hero_subtitulo">
                    Descubre el arte ancestral de nuestros maestros artesanos, donde cada pieza cuenta una historia milenaria de tradición y cultura en el corazón de los Andes ecuatorianos.
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
                <button class="filter-btn" data-filter="festival">
                    <i class="fas fa-star"></i>
                    <span data-translate="festivales">Festivales</span>
                </button>
                <button class="filter-btn" data-filter="religious">
                    <i class="fas fa-church"></i>
                    <span data-translate="religiosos">Religiosos</span>
                </button>
                <button class="filter-btn" data-filter="agricultural">
                    <i class="fas fa-leaf"></i>
                    <span data-translate="agricolas">Agrícolas</span>
                </button>
                <button class="filter-btn" data-filter="traditional">
                    <i class="fas fa-heart"></i>
                    <span data-translate="tradicionales">Tradicionales</span>
                </button>
                <button class="filter-btn" data-filter="musical">
                    <i class="fas fa-music"></i>
                    <span data-translate="musicales">Musicales</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Grid de eventos culturales -->
    <section class="cultura-grid">
        <div class="container">
            <div class="row g-4">
                <?php 
                // Intentar obtener datos de la base de datos
                $has_db_data = false;
                if ($stmt) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $has_db_data = true;
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="cultura-card" data-category="<?php echo isset($row['categoria']) ? $row['categoria'] : 'festival'; ?>">
                                <div class="card-image-container">
                                    <img src="images/<?php echo $row['imagen']; ?>" alt="<?php echo $row['nombre']; ?>" loading="lazy">
                                    <div class="card-overlay">
                                        <div class="overlay-content">
                                            <i class="fas fa-eye fa-2x"></i>
                                            <p>Ver detalles</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo $row['nombre']; ?></h3>
                                    <p class="card-description"><?php echo $row['descripcion']; ?></p>
                                    
                                    <div class="card-tags">
                                        <span class="tag">Cultural</span>
                                        <span class="tag">Imbabura</span>
                                    </div>
                                    
                                    <div class="card-info">
                                        <div class="event-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo isset($row['fecha']) ? $row['fecha'] : 'Todo el año'; ?>
                                        </div>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?php echo isset($row['ubicacion']) ? $row['ubicacion'] : 'Imbabura'; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <div class="importance">
                                            <span>Importancia:</span>
                                            <?php 
                                            $importancia = isset($row['importancia']) ? $row['importancia'] : 4;
                                            for ($i = 1; $i <= 5; $i++) { ?>
                                                <div class="importance-level <?php echo $i <= $importancia ? 'active' : ''; ?>"></div>
                                            <?php } ?>
                                        </div>
                                        <div class="category-badge">
                                            <?php echo ucfirst(isset($row['categoria']) ? $row['categoria'] : 'festival'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                
                // Si no hay datos de la BD, usar datos de ejemplo
                if (!$has_db_data) {
                    foreach ($eventos_ejemplo as $evento) {
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="cultura-card" data-category="<?php echo $evento['categoria']; ?>">
                                <div class="card-image-container">
                                    <img src="images/<?php echo $evento['imagen']; ?>" alt="<?php echo $evento['nombre']; ?>" loading="lazy">
                                    <div class="card-overlay">
                                        <div class="overlay-content">
                                            <i class="fas fa-eye fa-2x"></i>
                                            <p>Ver detalles</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo $evento['nombre']; ?></h3>
                                    <p class="card-description"><?php echo $evento['descripcion']; ?></p>
                                    
                                    <div class="card-tags">
                                        <span class="tag">Cultural</span>
                                        <span class="tag">Imbabura</span>
                                        <?php if ($evento['categoria'] == 'festival') { ?>
                                            <span class="tag">Festival</span>
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="card-info">
                                        <div class="event-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo $evento['fecha']; ?>
                                        </div>
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?php echo $evento['ubicacion']; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <div class="importance">
                                            <span>Importancia:</span>
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <div class="importance-level <?php echo $i <= $evento['importancia'] ? 'active' : ''; ?>"></div>
                                            <?php } ?>
                                        </div>
                                        <div class="category-badge">
                                            <?php echo ucfirst($evento['categoria']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Sección de Videos del Inti Raymi -->
    <section class="video-gallery">
        <div class="container">
            <h2 class="section-title" data-translate="videos_del_inti_raymi">Videos del Inti Raymi</h2>
            <p class="text-center mb-4 lead" data-translate="videos_del_inti_raymi_descripcion">
                El Inti Raymi, o Fiesta del Sol, es una de las celebraciones más importantes de la cultura andina. Aquí puedes ver cómo se vive esta tradición milenaria en Caranqui, Imbabura.
            </p>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="video-container">
                        <h5 class="text-center mb-3">
                            <i class="fas fa-play-circle text-gradient me-2"></i>
                            <span data-translate="inti_raymi_caranqui">Inti Raymi Caranqui</span>
                        </h5>
                        <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@joncarlosama/video/7516340227372272902" data-video-id="7516340227372272902" style="max-width: 605px;min-width: 325px;">
                            <section>
                                <a target="_blank" title="@joncarlosama" href="https://www.tiktok.com/@joncarlosama?refer=embed">@joncarlosama</a>
                                <p>inti raymi caranqui🇪🇨 <a title="fypシ゚" target="_blank" href="https://www.tiktok.com/tag/fyp%E3%82%9A%E3%82%9A?refer=embed">#fypシ゚</a> <a title="paratii" target="_blank" href="https://www.tiktok.com/tag/paratii?refer=embed">#paratii</a> <a title="diversion" target="_blank" href="https://www.tiktok.com/tag/diversion?refer=embed">#diversion</a></p>
                                <a target="_blank" title="♬ sonido original - Joncarlosama" href="https://www.tiktok.com/music/sonido-original-7516340259845720837?refer=embed">♬ sonido original - Joncarlosama</a>
                            </section>
                        </blockquote>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="video-container">
                        <h5 class="text-center mb-3">
                            <i class="fas fa-play-circle text-gradient me-2"></i>
                            Celebración Tradicional
                        </h5>
                        <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@liz.21_8/video/7516345123077131576" data-video-id="7516345123077131576" style="max-width: 605px;min-width: 325px;">
                            <section>
                                <a target="_blank" title="@liz.21_8" href="https://www.tiktok.com/@liz.21_8?refer=embed">@liz.21_8</a>
                                <p></p>
                                <a target="_blank" title="♬ sonido original - 𝐂𝐀𝐑𝐀𝐍𝐐𝐔𝐈 𝐓𝐕" href="https://www.tiktok.com/music/sonido-original-7516345151512431366?refer=embed">♬ sonido original - 𝐂𝐀𝐑𝐀𝐍𝐐𝐔𝐈 𝐓𝐕</a>
                            </section>
                        </blockquote>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="video-container">
                        <h5 class="text-center mb-3">
                            <i class="fas fa-play-circle text-gradient me-2"></i>
                            Fiestas de Caranqui
                        </h5>
                        <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@andreatefyarmas/video/7517754066802560261" data-video-id="7517754066802560261" style="max-width: 605px;min-width: 325px;">
                            <section>
                                <a target="_blank" title="@andreatefyarmas" href="https://www.tiktok.com/@andreatefyarmas?refer=embed">@andreatefyarmas</a>
                                <p>Fiestas de Caranqui</p>
                                <a target="_blank" title="♬ sonido original - andreatefyarmas" href="https://www.tiktok.com/music/sonido-original-7517754093902990085?refer=embed">♬ sonido original - andreatefyarmas</a>
                            </section>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de información adicional -->
    <section class="info-section">
        <div class="container">
            <h2 class="section-title">Nuestra Cultura</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h3 class="info-title">Raíces Ancestrales</h3>
                        <p class="info-description">
                            La provincia de Imbabura es un territorio donde convergen múltiples culturas ancestrales, desde los pueblos Caranqui hasta las comunidades Otavalo.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <h3 class="info-title">Música y Danza</h3>
                        <p class="info-description">
                            Los ritmos andinos resuenan en cada celebración con instrumentos como la quena, el charango y el bombo que acompañan las danzas tradicionales.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="info-title">Tradiciones Vivas</h3>
                        <p class="info-description">
                            Las ceremonias de agradecimiento a la Pachamama, los rituales de siembra y cosecha mantienen viva la conexión ancestral con la naturaleza.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
                        <li><a href="#">Tradiciones</a></li>
                        <li><a href="#">Festivales</a></li>
                        <li><a href="#">Música Ancestral</a></li>
                        <li><a href="#">Rituales Sagrados</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-envelope"></i> cultura@culturaandina.com</p>
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
    <script async src="https://www.tiktok.com/embed.js"></script>
    <script src="js/ultra-translation-system.js"></script>
    <script src="js/ultra-translation-test.js"></script>
    <script src="js/cultura.js"></script>
    <script src="js/script.js"></script>
</body>
</html>

