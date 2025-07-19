<?php include_once 'config/global.php'; ?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_ubicacion']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="Descubre la ubicación de Imbabura, Ecuador. Explora Ibarra, Naranjito y los principales puntos de interés de la provincia con mapas interactivos y información del clima.">
    <meta name="keywords" content="ubicación, Imbabura, Ecuador, Ibarra, Naranjito, mapas, clima, puntos de interés, turismo">
    <meta name="author" content="<?php echo NOMBRE_PROYECTO; ?>">
    
    <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="Ubicación de Imbabura - <?php echo NOMBRE_PROYECTO; ?>">
    <meta property="og:description" content="Explora la ubicación y puntos de interés de Imbabura, Ecuador con mapas interactivos">
    <meta property="og:image" content="images/imbabura_paisaje.jpg">
    <meta property="og:url" content="<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/ubicacion.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <!-- Navbar personalizado -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="fas fa-mountain me-2"></i>
                <?php echo NOMBRE_PROYECTO; ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>
                            <?php echo $texto['menu_inicio']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sabores.php">
                            <i class="fas fa-utensils me-1"></i>
                            <?php echo $texto['menu_sabores']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artesanias.php">
                            <i class="fas fa-palette me-1"></i>
                            <?php echo $texto['menu_artesanias']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kichwa.php">
                            <i class="fas fa-language me-1"></i>
                            <?php echo $texto['menu_kichwa']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cultura.php">
                            <i class="fas fa-theater-masks me-1"></i>
                            <?php echo $texto['menu_cultura']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="ubicacion.php">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <?php echo $texto['menu_ubicacion']; ?>
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <select class="form-select me-2" id="language-selector" onchange="changeLanguage(this.value)">
                        <option value="es" <?php if($lang_code == 'es') echo 'selected'; ?>>
                            <i class="fas fa-flag"></i> Español
                        </option>
                        <option value="qu" <?php if($lang_code == 'qu') echo 'selected'; ?>>
                            <i class="fas fa-flag"></i> Kichwa
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="location-pattern"></div>
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="fas fa-map-marker-alt me-3"></i>
                Ubicación de Imbabura
            </h1>
            <p class="hero-subtitle">
                Descubre la ubicación privilegiada de Imbabura en el corazón de los Andes ecuatorianos, donde se encuentran Ibarra, Naranjito y los más hermosos paisajes naturales del norte de Ecuador.
            </p>
            <button class="btn btn-ubicacion" onclick="smoothScrollTo('contenido-principal')">
                <i class="fas fa-arrow-down me-2"></i>
                Explorar Ubicación
            </button>
        </div>
    </section>

    <!-- Contenido Principal -->
    <main class="main-content" id="contenido-principal">
        <div class="container">
            
            <!-- Sección de Información Geográfica -->
            <section class="geo-info-section">
                <h2 class="section-title">
                    <i class="fas fa-globe-americas text-gradient me-2"></i>
                    Información Geográfica
                </h2>
                
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="geo-card">
                            <div class="icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <h4>Provincia de Imbabura</h4>
                            <p><strong>País:</strong> Ecuador</p>
                            <p><strong>Región:</strong> Sierra Norte</p>
                            <p><strong>Capital:</strong> Ibarra</p>
                            <p><strong>Superficie:</strong> 4,599 km²</p>
                            <p><strong>Población:</strong> ~476,257 habitantes</p>
                            <p class="coordinates">0°20'N, 78°07'W</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="geo-card">
                            <div class="icon">
                                <i class="fas fa-city"></i>
                            </div>
                            <h4>Ibarra - Ciudad Blanca</h4>
                            <p><strong>Altitud:</strong> 2,225 metros</p>
                            <p><strong>Clima:</strong> Templado seco</p>
                            <p><strong>Temperatura:</strong> 18°C promedio</p>
                            <p><strong>Fundación:</strong> 28 de septiembre de 1606</p>
                            <p><strong>Población:</strong> ~181,175 habitantes</p>
                            <p class="coordinates">0°21'N, 78°07'W</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="geo-card">
                            <div class="icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h4>Naranjito - Caranqui</h4>
                            <p><strong>Parroquia:</strong> Caranqui</p>
                            <p><strong>Altitud:</strong> 2,280 metros</p>
                            <p><strong>Clima:</strong> Templado andino</p>
                            <p><strong>Característica:</strong> Pueblo tradicional</p>
                            <p><strong>Actividad:</strong> Agricultura y turismo</p>
                            <p class="coordinates">0°19'42"N, 78°08'44"W</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección del Mapa Principal -->
            <section class="main-map-section">
                <h2 class="section-title">
                    <i class="fas fa-map text-gradient me-2"></i>
                    Mapa Principal de Imbabura
                </h2>
                <p class="text-center mb-4 lead">
                    Explora la ubicación de Imbabura y sus principales ciudades en este mapa interactivo. Puedes hacer zoom y navegar para conocer mejor la geografía de la región.
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
                                <h5><i class="fas fa-info-circle text-gradient me-2"></i>Información del Mapa</h5>
                                <p>Este mapa muestra la ubicación de Imbabura en Ecuador, incluyendo las principales ciudades, carreteras y puntos de referencia geográficos.</p>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-route text-gradient me-2"></i>Cómo Llegar</h5>
                                <p>Imbabura está conectada por la Panamericana Norte (E35) y se encuentra a 115 km al norte de Quito, aproximadamente 2 horas en vehículo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección de Puntos de Interés -->
            <section class="points-of-interest">
                <h2 class="section-title">
                    <i class="fas fa-map-pin text-gradient me-2"></i>
                    Puntos de Interés
                </h2>
                <p class="text-center mb-4 lead">
                    Descubre los lugares más emblemáticos de Imbabura. Cada punto incluye un mini-mapa interactivo, información detallada y enlaces directos a Google Maps.
                </p>
                
                <!-- Filtros de categorías -->
                <div class="text-center mb-4">
                    <div class="btn-group" role="group" aria-label="Filtros de puntos de interés">
                        <button type="button" class="btn btn-ubicacion" onclick="filterPOI('all')">
                            <i class="fas fa-list me-1"></i> Todos
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="filterPOI('natural')">
                            <i class="fas fa-leaf me-1"></i> Natural
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="filterPOI('cultural')">
                            <i class="fas fa-landmark me-1"></i> Cultural
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="filterPOI('adventure')">
                            <i class="fas fa-mountain me-1"></i> Aventura
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="filterPOI('traditional')">
                            <i class="fas fa-home me-1"></i> Tradicional
                        </button>
                    </div>
                </div>
                
                <div id="points-of-interest-grid" class="poi-grid">
                    <!-- Los puntos de interés se cargarán dinámicamente con JavaScript -->
                </div>
            </section>

            <!-- Sección del Clima -->
            <section class="weather-section">
                <div class="weather-content">
                    <h2 class="weather-title">
                        <i class="fas fa-cloud-sun me-3"></i>
                        Clima en Naranjito
                    </h2>
                    <p class="text-center mb-4">
                        Información meteorológica actualizada para Naranjito, Imbabura. El clima de la región es templado andino con temperaturas agradables durante todo el año.
                    </p>
                    
                    <div id="weather-info" class="weather-info">
                        <!-- La información del clima se cargará dinámicamente -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-light" onclick="loadWeatherData()">
                            <i class="fas fa-sync-alt me-2"></i>
                            Actualizar Clima
                        </button>
                    </div>
                </div>
            </section>

            <!-- Sección de Información Adicional -->
            <section class="additional-info mb-5">
                <h2 class="section-title">
                    <i class="fas fa-info-circle text-gradient me-2"></i>
                    Información Adicional
                </h2>
                
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="geo-card decorative-border">
                            <h4>
                                <i class="fas fa-road text-gradient me-2"></i>
                                Acceso y Transporte
                            </h4>
                            <p>
                                <strong>Desde Quito:</strong> 115 km por la Panamericana Norte (E35), aproximadamente 2 horas en vehículo.
                            </p>
                            <p>
                                <strong>Transporte público:</strong> Buses interprovinciales desde Terminal Carcelén en Quito.
                            </p>
                            <p>
                                <strong>Aeropuerto más cercano:</strong> Aeropuerto Internacional Mariscal Sucre (Quito).
                            </p>
                            <p>
                                <strong>Transporte local:</strong> Buses urbanos, taxis y camionetas en Ibarra.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="geo-card decorative-border">
                            <h4>
                                <i class="fas fa-calendar-alt text-gradient me-2"></i>
                                Mejor Época para Visitar
                            </h4>
                            <p>
                                <strong>Todo el año:</strong> Imbabura tiene un clima templado estable durante todo el año.
                            </p>
                            <p>
                                <strong>Época seca:</strong> Junio a septiembre, ideal para actividades al aire libre.
                            </p>
                            <p>
                                <strong>Festivales:</strong> Inti Raymi en junio, Fiesta del Maíz en septiembre.
                            </p>
                            <p>
                                <strong>Temperatura promedio:</strong> 18°C - 24°C durante el día.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="geo-card decorative-border">
                            <h4>
                                <i class="fas fa-mountain text-gradient me-2"></i>
                                Geografía y Relieve
                            </h4>
                            <p>
                                Imbabura se encuentra en la región interandina, entre las cordilleras Occidental y Oriental de los Andes ecuatorianos.
                            </p>
                            <p>
                                <strong>Volcanes principales:</strong> Imbabura (4,609 m), Cotacachi (4,944 m).
                            </p>
                            <p>
                                <strong>Lagunas:</strong> Yahuarcocha, San Pablo, Cuicocha.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="geo-card decorative-border">
                            <h4>
                                <i class="fas fa-users text-gradient me-2"></i>
                                Cultura y Población
                            </h4>
                            <p>
                                Imbabura es hogar de diversas etnias: mestizos, indígenas Otavalo, afroecuatorianos y comunidades Caranqui.
                            </p>
                            <p>
                                <strong>Idiomas:</strong> Español y Kichwa (lengua ancestral).
                            </p>
                            <p>
                                <strong>Actividades económicas:</strong> Turismo, agricultura, artesanías, comercio.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Footer personalizado -->
    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>
                            <i class="fas fa-mountain me-2"></i>
                            <?php echo NOMBRE_PROYECTO; ?>
                        </h5>
                        <p>Preservando y compartiendo la riqueza cultural de los Andes para las futuras generaciones.</p>
                        <div class="social-icons">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>
                            <i class="fas fa-compass me-2"></i>
                            Navegación
                        </h5>
                        <a href="index.php"><?php echo $texto['menu_inicio']; ?></a>
                        <a href="sabores.php"><?php echo $texto['menu_sabores']; ?></a>
                        <a href="artesanias.php"><?php echo $texto['menu_artesanias']; ?></a>
                        <a href="kichwa.php"><?php echo $texto['menu_kichwa']; ?></a>
                        <a href="cultura.php"><?php echo $texto['menu_cultura']; ?></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Ubicación
                        </h5>
                        <a href="#" onclick="smoothScrollTo('contenido-principal')">Información Geográfica</a>
                        <a href="#" onclick="smoothScrollTo('points-of-interest-grid')">Puntos de Interés</a>
                        <a href="#" onclick="filterPOI('natural')">Lugares Naturales</a>
                        <a href="#" onclick="loadWeatherData()">Clima Actual</a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>
                            <i class="fas fa-envelope me-2"></i>
                            Contacto
                        </h5>
                        <p>
                            <i class="fas fa-envelope me-2"></i>
                            info@culturaandina.com
                        </p>
                        <p>
                            <i class="fas fa-phone me-2"></i>
                            +593 (0)2 123-4567
                        </p>
                        <p>
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Ibarra, Imbabura, Ecuador
                        </p>
                        <p class="coordinates">0°21'N, 78°07'W</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 <?php echo NOMBRE_PROYECTO; ?>. Todos los derechos reservados. | Desarrollado con ❤️ para mostrar la belleza de Imbabura.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script src="js/ubicacion.js"></script>
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Place",
        "name": "Imbabura, Ecuador",
        "description": "Provincia de Imbabura en Ecuador, hogar de Ibarra y Naranjito",
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": 0.3283,
            "longitude": -78.1456
        },
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Ibarra",
            "addressRegion": "Imbabura",
            "addressCountry": "Ecuador"
        },
        "containedInPlace": {
            "@type": "Country",
            "name": "Ecuador"
        },
        "hasMap": "https://maps.app.goo.gl/wh6371obnzuUBxDu7"
    }
    </script>
</body>
</html>

