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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Custom Kichwa CSS -->
    <link rel="stylesheet" href="css/kichwa.css">
    
    <!-- Meta tags para SEO y redes sociales -->
    <meta name="description" content="Aprende el idioma Kichwa de Imbabura, Ecuador. Diccionario interactivo con pronunciación y cultura ancestral.">
    <meta name="keywords" content="Kichwa, Imbabura, Ecuador, idioma, cultura, Otavalo, Ibarra">
    <meta property="og:title" content="<?php echo $texto['menu_kichwa']; ?> - <?php echo NOMBRE_PROYECTO; ?>">
    <meta property="og:description" content="Descubre la riqueza del idioma Kichwa de Imbabura, Ecuador">
    <meta property="og:image" content="images/kichwa_culture_1.jpg">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-mountain me-2"></i>Wayra Kawsay
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i><?php echo $texto['menu_inicio']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sabores.php">
                            <i class="fas fa-utensils me-1"></i><?php echo $texto['menu_sabores']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artesanias.php">
                            <i class="fas fa-palette me-1"></i><?php echo $texto['menu_artesanias']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="kichwa.php">
                            <i class="fas fa-language me-1"></i><?php echo $texto['menu_kichwa']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cultura.php">
                            <i class="fas fa-users me-1"></i><?php echo $texto['menu_cultura']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ubicacion.php">
                            <i class="fas fa-map-marker-alt me-1"></i><?php echo $texto['menu_ubicacion']; ?>
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-language dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if($lang_code == 'es'): ?>
                                ES Español
                            <?php else: ?>
                                <i class="fas fa-mountain me-1"></i>Kichwa
                            <?php endif; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item <?php if($lang_code == 'es') echo 'active'; ?>" href="?lang=es">ES Español</a></li>
                            <li><a class="dropdown-item <?php if($lang_code == 'qu') echo 'active'; ?>" href="?lang=qu"><i class="fas fa-mountain me-1"></i>Kichwa</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sección Hero -->
    <section class="hero-section">
        <div class="container">
            <h1><?php echo $texto['menu_kichwa']; ?></h1>
            <p>Descubre la riqueza del idioma ancestral de los pueblos de Imbabura, Ecuador. 
               Cada palabra lleva consigo siglos de sabiduría y tradición.</p>
        </div>
    </section>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <div class="main-content">
            <!-- Barra de búsqueda -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="input-group">
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
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-book me-2"></i>
                                <?php echo $stmt->rowCount(); ?>
                            </h5>
                            <p class="card-text">Palabras en el diccionario</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title" style="color: var(--craft-primary);">
                                <i class="fas fa-volume-up me-2"></i>
                                Audio
                            </h5>
                            <p class="card-text">Pronunciación auténtica</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title" style="color: var(--craft-gold);">
                                <i class="fas fa-mountain me-2"></i>
                                Imbabura
                            </h5>
                            <p class="card-text">Tradición ancestral</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de palabras Kichwa -->
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
                            <tr>
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

        <!-- Galería Cultural -->
        <div class="main-content">
            <h2 class="text-center mb-4">
                <i class="fas fa-images me-2"></i>Cultura de Imbabura
            </h2>
            <div class="cultural-gallery">
                <div class="cultural-image">
                    <img src="images/kichwa_culture_1.jpg" alt="Cultura Kichwa Imbabura" loading="lazy">
                    <div class="caption">Tradiciones ancestrales de Imbabura</div>
                </div>
                <div class="cultural-image">
                    <img src="images/kichwa_craft_1.jpg" alt="Artesanías Kichwa" loading="lazy">
                    <div class="caption">Artesanías tradicionales del mercado de Otavalo</div>
                </div>
                <div class="cultural-image">
                    <img src="images/imbabura_landscape_1.jpg" alt="Paisaje Imbabura" loading="lazy">
                    <div class="caption">Majestuoso volcán Imbabura</div>
                </div>
                <div class="cultural-image">
                    <img src="images/kichwa_craft_2.jpg" alt="Mercado Otavalo" loading="lazy">
                    <div class="caption">Colores vibrantes del mercado indígena</div>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="main-content">
            <div class="row">
                <div class="col-md-6">
                    <h3><i class="fas fa-info-circle me-2"></i>Sobre el Kichwa</h3>
                    <p>El Kichwa es el idioma ancestral de los pueblos indígenas de Imbabura, Ecuador. 
                       Esta lengua milenaria es parte fundamental de la identidad cultural de comunidades 
                       como los Otavalos, Kayambis y Natabuelas.</p>
                    <p>En Imbabura, el Kichwa se mantiene vivo a través de las tradiciones orales, 
                       ceremonias ancestrales y la vida cotidiana de las comunidades indígenas.</p>
                </div>
                <div class="col-md-6">
                    <h3><i class="fas fa-map-marker-alt me-2"></i>Imbabura, Ecuador</h3>
                    <p>La provincia de Imbabura, conocida como la "Provincia de los Lagos", 
                       es hogar de una rica diversidad cultural y lingüística. Ibarra, su capital, 
                       y Otavalo, famoso por su mercado indígena, son centros importantes de la cultura Kichwa.</p>
                    <p>El volcán Imbabura, considerado sagrado por los pueblos originarios, 
                       domina el paisaje y es parte integral de la cosmovisión Kichwa.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-brand">
                        <h5><i class="fas fa-mountain me-2"></i>Wayra Kawsay</h5>
                        <p>Preservando y promoviendo las tradiciones artesanales de los Andes para las futuras generaciones.</p>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="footer-title">Navegación</h6>
                    <ul class="footer-links">
                        <li><a href="index.php"><?php echo $texto['menu_inicio']; ?></a></li>
                        <li><a href="sabores.php"><?php echo $texto['menu_sabores']; ?></a></li>
                        <li><a href="artesanias.php"><?php echo $texto['menu_artesanias']; ?></a></li>
                        <li><a href="kichwa.php"><?php echo $texto['menu_kichwa']; ?></a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="footer-title">Artesanías</h6>
                    <ul class="footer-links">
                        <li><a href="#">Textiles Tradicionales</a></li>
                        <li><a href="#">Cerámica Ancestral</a></li>
                        <li><a href="#">Orfebrería Andina</a></li>
                        <li><a href="#">Tallado en Madera</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="footer-title">Contacto</h6>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope me-2"></i>artesanias@culturaandina.com</li>
                        <li><i class="fas fa-phone me-2"></i>+593 (0)2 123-4567</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Quito, Ecuador</li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="footer-copyright">© 2024 Wayra Kawsay. © 2024 Wayra Kawsay. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    
    <!-- Custom Kichwa JS -->
    <script src="js/kichwa.js"></script>
    
    <!-- Funciones adicionales para búsqueda -->
    <script>
        function searchWords() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            if (searchTerm) {
                window.KichwaPage.highlightSearchTerm(searchTerm);
                checkNoResults();
            }
        }
        
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            window.KichwaPage.clearSearch();
            document.getElementById('noResults').style.display = 'none';
        }
        
        function checkNoResults() {
            const visibleRows = document.querySelectorAll('.kichwa-table tbody tr[style=""]');
            const noResultsDiv = document.getElementById('noResults');
            
            if (visibleRows.length === 0) {
                noResultsDiv.style.display = 'block';
            } else {
                noResultsDiv.style.display = 'none';
            }
        }
        
        // Búsqueda en tiempo real
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.trim();
            if (searchTerm.length > 2) {
                window.KichwaPage.highlightSearchTerm(searchTerm);
                checkNoResults();
            } else if (searchTerm.length === 0) {
                clearSearch();
            }
        });
        
        // Búsqueda con Enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchWords();
            }
        });
    </script>
</body>
</html>

