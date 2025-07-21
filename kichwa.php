<?php
include_once 'config/global.php';
include_once 'config/Conexion.php';
include_once 'models/Kichwa.php';

$database = new Conexion();
$db = $database->getConnection();

$kichwa = new Kichwa($db);

// Parámetros de paginación
$rows_per_page = isset($_GET['rows']) ? (int)$_GET['rows'] : 10; // Default 10 filas
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

// Validar parámetros
if ($rows_per_page < 5) $rows_per_page = 5;
if ($rows_per_page > 100) $rows_per_page = 100;
if ($current_page < 1) $current_page = 1;

// Obtener datos paginados
$stmt = $kichwa->read($rows_per_page, $offset);
$total_words = $kichwa->getTotalCount();
$total_pages = ceil($total_words / $rows_per_page);

// Para las estadísticas (sin paginación)
$stats_stmt = $kichwa->read();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_kichwa']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    <meta name="description" content="Aprende el idioma Kichwa de Imbabura, Ecuador. Diccionario interactivo con pronunciación y cultura ancestral.">
    <meta name="keywords" content="Kichwa, Imbabura, Ecuador, idioma, cultura, Otavalo, Ibarra">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/kichwa.css">
    
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
                            <i class="fas fa-home"></i> <?php echo $texto['menu_inicio']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sabores.php">
                            <i class="fas fa-utensils"></i> <?php echo $texto['menu_sabores']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artesanias.php">
                            <i class="fas fa-palette"></i> <?php echo $texto['menu_artesanias']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="kichwa.php">
                            <i class="fas fa-language"></i> <?php echo $texto['menu_kichwa']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cultura.php">
                            <i class="fas fa-users"></i> <?php echo $texto['menu_cultura']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ubicacion.php">
                            <i class="fas fa-map-marker-alt"></i> <?php echo $texto['menu_ubicacion']; ?>
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
                                <?php if (isset($_SESSION['usuario_rol'])): ?>
                                    <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>
                                        <li><a class="dropdown-item" href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Panel Admin</a></li>
                                    <?php elseif ($_SESSION['usuario_rol'] === 'artesano'): ?>
                                        <li><a class="dropdown-item" href="panel/artesano.php"><i class="fas fa-palette"></i> Panel Artesano</a></li>
                                    <?php elseif ($_SESSION['usuario_rol'] === 'comunitario'): ?>
                                        <li><a class="dropdown-item" href="panel/comunitario.php"><i class="fas fa-users"></i> Panel Comunitario</a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> <?php echo $texto['cerrar_sesion']; ?></a></li>
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
    <section class="kichwa-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-language"></i>
                    <?php echo $texto['menu_kichwa']; ?>
                </h1>
                <p class="hero-subtitle">
                    Descubre la riqueza del idioma ancestral de los pueblos de Imbabura, Ecuador. Cada palabra lleva consigo siglos de sabiduría y tradición.
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
                    Todas
                </button>
                <button class="filter-btn" data-filter="search">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>
                <button class="filter-btn" data-filter="audio">
                    <i class="fas fa-volume-up"></i>
                    Con Audio
                </button>
                <button class="filter-btn" data-filter="culture">
                    <i class="fas fa-mountain"></i>
                    Cultura
                </button>
            </div>
        </div>
    </section>

    <!-- Contenido Principal -->
    <section class="kichwa-content">
        <div class="container">
            <!-- Barra de búsqueda -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="input-group search-container">
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar palabra en Kichwa o Español...">
                        <button class="btn btn-kichwa" type="button" onclick="searchWords()">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                            <i class="fas fa-times"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row g-4 mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 class="info-title"><?php echo $stmt->rowCount(); ?></h3>
                        <p class="info-description">Palabras en el diccionario</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-volume-up"></i>
                        </div>
                        <h3 class="info-title">Audio</h3>
                        <p class="info-description">Pronunciación auténtica</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h3 class="info-title">Imbabura</h3>
                        <p class="info-description">Tradición ancestral</p>
                    </div>
                </div>
            </div>

            <!-- Traductor Español - Kichwa -->
            <div class="translator-section mb-5">
                <div class="row">
                    <div class="col-12">
                        <div class="translator-card">
                            <div class="translator-header">
                                <h3><i class="fas fa-exchange-alt"></i> Traductor Español ↔ Kichwa</h3>
                                <p class="text-muted">Ingresa una palabra para buscar su traducción en nuestro diccionario</p>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="translation-input">
                                        <label class="form-label"><i class="fas fa-globe"></i> Español</label>
                                        <textarea class="form-control" id="spanishInput" rows="3" placeholder="Escribe una palabra en español..."></textarea>
                                        <div class="input-actions">
                                            <button class="btn btn-outline-secondary btn-sm" onclick="clearTranslation('spanish')">
                                                <i class="fas fa-eraser"></i> Limpiar
                                            </button>
                                            <button class="btn btn-primary btn-sm" onclick="translateToKichwa()">
                                                <i class="fas fa-arrow-right"></i> Traducir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <button class="btn btn-outline-primary btn-swap" onclick="swapTranslation()">
                                        <i class="fas fa-exchange-alt fa-2x"></i>
                                    </button>
                                </div>
                                <div class="col-md-5">
                                    <div class="translation-output">
                                        <label class="form-label"><i class="fas fa-language"></i> Kichwa</label>
                                        <textarea class="form-control" id="kichwaInput" rows="3" placeholder="Escribe una palabra en kichwa..."></textarea>
                                        <div class="input-actions">
                                            <button class="btn btn-outline-secondary btn-sm" onclick="clearTranslation('kichwa')">
                                                <i class="fas fa-eraser"></i> Limpiar
                                            </button>
                                            <button class="btn btn-primary btn-sm" onclick="translateToSpanish()">
                                                <i class="fas fa-arrow-left"></i> Traducir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="translation-results" id="translationResults" style="display: none;">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-search"></i> Resultados de búsqueda:</h6>
                                    <div id="translationContent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de palabras Kichwa -->
            <div class="kichwa-dictionary">
                <div class="table-responsive">
                    <table class="table kichwa-table">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <i class="fas fa-language me-2"></i>Kichwa
                                </th>
                                <th scope="col">
                                    <i class="fas fa-globe me-2"></i>Español
                                </th>
                                <th scope="col">
                                    <i class="fas fa-volume-up me-2"></i>Audio
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reiniciar el statement para la tabla
                            $stmt = $kichwa->read();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                            ?>
                                <tr class="kichwa-row">
                                    <td class="kichwa-word">
                                        <strong><?php echo htmlspecialchars($row['palabra_kichwa']); ?></strong>
                                    </td>
                                    <td class="spanish-translation">
                                        <?php echo htmlspecialchars($row['traduccion_espanol']); ?>
                                    </td>
                                    <td class="audio-cell">
                                        <?php if (!empty($row['audio'])): ?>
                                            <audio controls preload="none">
                                                <source src="audio/<?php echo htmlspecialchars($row['audio']); ?>" type="audio/mpeg">
                                                <source src="audio/<?php echo htmlspecialchars($row['audio']); ?>" type="audio/wav">
                                                Tu navegador no soporta el elemento de audio.
                                            </audio>
                                        <?php else: ?>
                                            <span class="text-muted">
                                                <i class="fas fa-volume-mute"></i> No disponible
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje cuando no hay resultados -->
                <div id="noResults" class="text-center py-5" style="display: none;">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No se encontraron resultados</h4>
                    <p class="text-muted">Intenta con otra palabra o revisa la ortografía</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Galería Cultural -->
    <section class="cultural-section">
        <div class="container">
            <h2 class="section-title">Cultura de Imbabura</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="cultural-card">
                        <div class="card-image-container">
                            <img src="images/kichwa_culture_1.jpg" alt="Cultura Kichwa Imbabura" loading="lazy">
                            <div class="card-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-eye fa-2x"></i>
                                    <p>Ver detalles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Tradiciones Ancestrales</h3>
                            <p class="card-description">Tradiciones ancestrales de Imbabura</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="cultural-card">
                        <div class="card-image-container">
                            <img src="images/kichwa_craft_1.jpg" alt="Artesanías Kichwa" loading="lazy">
                            <div class="card-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-eye fa-2x"></i>
                                    <p>Ver detalles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Artesanías Tradicionales</h3>
                            <p class="card-description">Artesanías tradicionales del mercado de Otavalo</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="cultural-card">
                        <div class="card-image-container">
                            <img src="images/imbabura_landscape_1.jpg" alt="Paisaje Imbabura" loading="lazy">
                            <div class="card-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-eye fa-2x"></i>
                                    <p>Ver detalles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Volcán Imbabura</h3>
                            <p class="card-description">Majestuoso volcán Imbabura</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="cultural-card">
                        <div class="card-image-container">
                            <img src="images/kichwa_craft_2.jpg" alt="Mercado Otavalo" loading="lazy">
                            <div class="card-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-eye fa-2x"></i>
                                    <p>Ver detalles</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Mercado Indígena</h3>
                            <p class="card-description">Colores vibrantes del mercado indígena</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de información adicional -->
    <section class="info-section">
        <div class="container">
            <h2 class="section-title">Nuestro Idioma</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="info-title">Idioma Ancestral</h3>
                        <p class="info-description">
                            El Kichwa es el idioma ancestral de los pueblos indígenas de Imbabura, Ecuador. Esta lengua milenaria es parte fundamental de la identidad cultural.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="info-title">Comunidades Vivas</h3>
                        <p class="info-description">
                            En Imbabura, el Kichwa se mantiene vivo a través de las tradiciones orales, ceremonias ancestrales y la vida cotidiana de las comunidades indígenas.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <h3 class="info-title">Cosmovisión Andina</h3>
                        <p class="info-description">
                            El volcán Imbabura, considerado sagrado por los pueblos originarios, domina el paisaje y es parte integral de la cosmovisión Kichwa.
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
                    <p>Preservando y compartiendo la riqueza lingüística y cultural de los Andes para las futuras generaciones.</p>
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
                    <h5>Idioma Kichwa</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Diccionario Interactivo</a></li>
                        <li><a href="#">Pronunciación</a></li>
                        <li><a href="#">Tradiciones Orales</a></li>
                        <li><a href="#">Cultura Ancestral</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-envelope"></i> kichwa@culturaandina.com</p>
                    <p><i class="fas fa-phone"></i> +593 (0)2 123-4567</p>
                    <p><i class="fas fa-map-marker-alt"></i> Imbabura, Ecuador</p>
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
    <script src="js/ultra-translation-test.js"></script>
    <script src="js/kichwa.js"></script>
    <script src="js/script.js"></script>
</body>
</html>

