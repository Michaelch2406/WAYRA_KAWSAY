<?php include_once 'config/global.php'; ?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_cultura']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="Descubre la rica cultura de Imbabura, Ecuador. Tradiciones, festivales, artesanías y patrimonio cultural de Ibarra y Naranjito.">
    <meta name="keywords" content="cultura, Imbabura, Ecuador, tradiciones, festivales, artesanías, Ibarra, Naranjito, Inti Raymi">
    <meta name="author" content="<?php echo NOMBRE_PROYECTO; ?>">
    
    <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="Cultura de Imbabura - <?php echo NOMBRE_PROYECTO; ?>">
    <meta property="og:description" content="Explora las tradiciones milenarias y la riqueza cultural de Imbabura, Ecuador">
    <meta property="og:image" content="images/tradiciones_imbabura.jpg">
    <meta property="og:url" content="<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/cultura.css">
    
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
                        <a class="nav-link active" aria-current="page" href="cultura.php">
                            <i class="fas fa-theater-masks me-1"></i>
                            <?php echo $texto['menu_cultura']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ubicacion.php">
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
        <div class="cultural-pattern"></div>
        <div class="hero-content">
            <h1 class="hero-title">
                <i class="fas fa-theater-masks me-3"></i>
                Cultura de Imbabura
            </h1>
            <p class="hero-subtitle">
                Descubre el arte ancestral de nuestros maestros artesanos, donde cada pieza cuenta una historia milenaria de tradición y cultura en el corazón de los Andes ecuatorianos.
            </p>
            <button class="btn btn-cultura" onclick="smoothScrollTo('contenido-principal')">
                <i class="fas fa-arrow-down me-2"></i>
                Explorar Cultura
            </button>
        </div>
    </section>

    <!-- Contenido Principal -->
    <main class="main-content" id="contenido-principal">
        <div class="container">
            
            <!-- Sección de Introducción Cultural -->
            <section class="mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="culture-card">
                            <h3>
                                <i class="fas fa-seedling text-gradient me-2"></i>
                                Raíces Ancestrales
                            </h3>
                            <p>
                                La provincia de Imbabura, ubicada en el norte de Ecuador, es un territorio donde convergen múltiples culturas ancestrales. Desde los pueblos Caranqui hasta las comunidades Otavalo, cada grupo étnico ha contribuido a formar el rico tapiz cultural que caracteriza a esta región andina.
                            </p>
                            <p>
                                En Ibarra, la "Ciudad Blanca", y en las comunidades como Naranjito, las tradiciones se mantienen vivas a través de festivales, ceremonias y la práctica cotidiana de costumbres que han pasado de generación en generación.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="photo-item hover-glow">
                            <img src="images/tradiciones_imbabura.jpg" alt="Tradiciones de Imbabura" class="img-fluid rounded">
                            <div class="photo-overlay">
                                <h5>Tradiciones Vivas</h5>
                                <p>Las tradiciones de Imbabura se mantienen vivas en cada celebración</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección de Videos del Inti Raymi -->
            <section class="video-gallery mb-5">
                <h2 class="section-title">
                    <i class="fas fa-sun text-gradient me-2"></i>
                    Videos del Inti Raymi
                </h2>
                <p class="text-center mb-4 lead">
                    El Inti Raymi, o Fiesta del Sol, es una de las celebraciones más importantes de la cultura andina. Aquí puedes ver cómo se vive esta tradición milenaria en Caranqui, Imbabura.
                </p>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="video-container">
                            <h5 class="text-center mb-3">
                                <i class="fas fa-play-circle text-gradient me-2"></i>
                                Inti Raymi Caranqui
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
            </section>

            <!-- Sección de Galería de Fotos -->
            <section class="photo-gallery mb-5">
                <h2 class="section-title">
                    <i class="fas fa-images text-gradient me-2"></i>
                    Galería Cultural
                </h2>
                <p class="text-center mb-4 lead">
                    Una colección visual que captura la esencia de la cultura imbabureña, desde sus tradiciones ancestrales hasta sus expresiones artísticas contemporáneas.
                </p>
                
                <div id="photo-gallery" class="row">
                    <!-- Las fotos se cargarán dinámicamente con JavaScript -->
                </div>
            </section>

            <!-- Sección de Tradiciones y Costumbres -->
            <section class="mb-5">
                <h2 class="section-title">
                    <i class="fas fa-heart text-gradient me-2"></i>
                    Tradiciones y Costumbres
                </h2>
                
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="culture-card decorative-border">
                            <h4>
                                <i class="fas fa-music text-gradient me-2"></i>
                                Música Ancestral
                            </h4>
                            <p>
                                Los ritmos andinos resuenan en cada celebración. Instrumentos como la quena, el charango y el bombo acompañan las danzas tradicionales que narran historias de amor, trabajo y conexión con la Pachamama.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="culture-card decorative-border">
                            <h4>
                                <i class="fas fa-tshirt text-gradient me-2"></i>
                                Vestimenta Tradicional
                            </h4>
                            <p>
                                Los trajes típicos de Imbabura reflejan la identidad de cada comunidad. Los colores vibrantes, los bordados detallados y los accesorios como sombreros y joyas cuentan la historia de cada pueblo.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 mb-4">
                        <div class="culture-card decorative-border">
                            <h4>
                                <i class="fas fa-seedling text-gradient me-2"></i>
                                Rituales Sagrados
                            </h4>
                            <p>
                                Las ceremonias de agradecimiento a la Pachamama, los rituales de siembra y cosecha, y las limpias espirituales mantienen viva la conexión ancestral con la naturaleza y los espíritus protectores.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección de Próximos Eventos -->
            <section class="events-section">
                <div class="container">
                    <h2 class="section-title">
                        <i class="fas fa-calendar-alt text-gradient me-2"></i>
                        Calendario Cultural
                    </h2>
                    <p class="text-center mb-4 lead">
                        Descubre los eventos culturales más importantes de Imbabura y planifica tu visita para vivir estas experiencias únicas.
                    </p>
                    
                    <!-- Filtros de eventos -->
                    <div class="text-center mb-4">
                        <div class="btn-group" role="group" aria-label="Filtros de eventos">
                            <button type="button" class="btn btn-cultura" onclick="filterEvents('all')">
                                <i class="fas fa-list me-1"></i> Todos
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="filterEvents('festival')">
                                <i class="fas fa-star me-1"></i> Festivales
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="filterEvents('religious')">
                                <i class="fas fa-church me-1"></i> Religiosos
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="filterEvents('agricultural')">
                                <i class="fas fa-leaf me-1"></i> Agrícolas
                            </button>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="event-card" data-type="festival" onclick="showEventDetails('Inti Raymi')">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <i class="fas fa-sun fa-2x text-gradient"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5>Inti Raymi</h5>
                                        <p class="event-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            21 de Junio
                                        </p>
                                        <p class="event-description">
                                            Celebración del solsticio de invierno, una de las festividades más importantes de la cultura andina. Se realizan danzas, música y rituales de agradecimiento al sol.
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Caranqui, Ibarra
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-3">
                            <div class="event-card" data-type="agricultural" onclick="showEventDetails('Fiesta del Maíz')">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <i class="fas fa-corn fa-2x text-gradient"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5>Fiesta del Maíz</h5>
                                        <p class="event-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            15 de Septiembre
                                        </p>
                                        <p class="event-description">
                                            Agradecimiento a la Pachamama por la cosecha del maíz. Incluye preparación de platos tradicionales y ceremonias de bendición.
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Comunidades rurales de Imbabura
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-3">
                            <div class="event-card" data-type="traditional" onclick="showEventDetails('Día de los Difuntos')">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <i class="fas fa-candle-holder fa-2x text-gradient"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5>Día de los Difuntos</h5>
                                        <p class="event-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            2 de Noviembre
                                        </p>
                                        <p class="event-description">
                                            Tradición de honrar a los antepasados con ofrendas, visitas al cementerio y la preparación de la tradicional colada morada y guaguas de pan.
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Cementerios de Imbabura
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 mb-3">
                            <div class="event-card" data-type="festival" onclick="showEventDetails('Pawkar Raymi')">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <i class="fas fa-flower fa-2x text-gradient"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5>Pawkar Raymi</h5>
                                        <p class="event-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            21 de Marzo
                                        </p>
                                        <p class="event-description">
                                            Celebración del equinoccio de primavera y el florecimiento de la naturaleza. Festival de colores, música y danzas tradicionales.
                                        </p>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            Otavalo y comunidades aledañas
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección de Artesanías Culturales -->
            <section class="mb-5">
                <h2 class="section-title">
                    <i class="fas fa-palette text-gradient me-2"></i>
                    Artesanías y Expresiones Culturales
                </h2>
                
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4">
                        <div class="photo-item hover-glow">
                            <img src="images/artesanias_ecuador.jpg" alt="Artesanías de Ecuador" class="img-fluid rounded">
                            <div class="photo-overlay">
                                <h5>Artesanías Tradicionales</h5>
                                <p>Cada pieza cuenta una historia de tradición y maestría</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="culture-card">
                            <h3>
                                <i class="fas fa-hands text-gradient me-2"></i>
                                Maestros Artesanos
                            </h3>
                            <p>
                                Los artesanos de Imbabura han perfeccionado sus técnicas durante generaciones. Desde los famosos textiles de Otavalo hasta las cerámicas de La Esperanza, cada obra refleja la identidad cultural de su comunidad.
                            </p>
                            <p>
                                Las técnicas ancestrales se combinan con diseños contemporáneos, creando piezas únicas que mantienen viva la tradición mientras se adaptan a los tiempos modernos.
                            </p>
                            <button class="btn btn-cultura" onclick="window.location.href='artesanias.php'">
                                <i class="fas fa-arrow-right me-2"></i>
                                Explorar Artesanías
                            </button>
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
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5>
                            <i class="fas fa-theater-masks me-2"></i>
                            Cultura
                        </h5>
                        <a href="#" onclick="smoothScrollTo('contenido-principal')">Tradiciones</a>
                        <a href="ubicacion.php">Ubicación</a>
                        <a href="#" onclick="filterEvents('festival')">Festivales</a>
                        <a href="#" onclick="smoothScrollTo('photo-gallery')">Galería</a>
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
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 <?php echo NOMBRE_PROYECTO; ?>. Todos los derechos reservados. | Desarrollado con ❤️ para preservar nuestra cultura.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script async src="https://www.tiktok.com/embed.js"></script>
    <script src="js/script.js"></script>
    <script src="js/cultura.js"></script>
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CulturalEvent",
        "name": "Cultura de Imbabura",
        "description": "Descubre la rica cultura de Imbabura, Ecuador. Tradiciones, festivales y patrimonio cultural.",
        "location": {
            "@type": "Place",
            "name": "Imbabura, Ecuador",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Ibarra",
                "addressRegion": "Imbabura",
                "addressCountry": "Ecuador"
            }
        },
        "organizer": {
            "@type": "Organization",
            "name": "<?php echo NOMBRE_PROYECTO; ?>"
        }
    }
    </script>
</body>
</html>

