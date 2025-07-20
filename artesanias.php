<?php
include_once 'config/global.php';
include_once 'config/Conexion.php';
include_once 'models/Artesanias.php';

$database = new Conexion();
$db = $database->getConnection();

$artesania = new Artesanias($db);
$stmt_artesanos = $artesania->read_artesanos();

// Datos de ejemplo para demostración (en caso de que no haya datos en la BD)
$artesanos_ejemplo = [
    [
        'id' => 1,
        'nombre' => 'María Quispe',
        'descripcion' => 'Maestra tejedora con más de 30 años de experiencia en textiles tradicionales andinos. Especialista en técnicas ancestrales de telar de cintura.',
        'foto' => 'textiles_andinos.jpg',
        'especialidad' => 'Textiles',
        'categoria' => 'textiles',
        'productos' => [
            [
                'nombre' => 'Poncho Tradicional',
                'descripcion' => 'Poncho tejido a mano con lana de alpaca, siguiendo patrones ancestrales de la región.',
                'imagen' => 'textiles_andinos.jpg'
            ],
            [
                'nombre' => 'Chullo Andino',
                'descripcion' => 'Gorro tradicional con orejeras, tejido con técnicas milenarias.',
                'imagen' => 'arte_andino.jpg'
            ]
        ]
    ],
    [
        'id' => 2,
        'nombre' => 'Carlos Mamani',
        'descripcion' => 'Ceramista heredero de tradiciones alfareras precolombinas. Sus obras reflejan la cosmovisión andina en cada pieza.',
        'foto' => 'ceramica_andina.jpg',
        'especialidad' => 'Cerámica',
        'categoria' => 'ceramica',
        'productos' => [
            [
                'nombre' => 'Vasija Ceremonial',
                'descripcion' => 'Vasija de arcilla con diseños geométricos tradicionales, cocida en horno de leña.',
                'imagen' => 'ceramica_andina.jpg'
            ],
            [
                'nombre' => 'Platos Decorativos',
                'descripcion' => 'Set de platos con motivos andinos, perfectos para decoración.',
                'imagen' => 'arte_andino.jpg'
            ]
        ]
    ],
    [
        'id' => 3,
        'nombre' => 'Ana Condori',
        'descripcion' => 'Orfebre especializada en joyería tradicional andina. Trabaja con plata y piedras semipreciosas locales.',
        'foto' => 'orfebreria_andina.jpg',
        'especialidad' => 'Orfebrería',
        'categoria' => 'orfebreria',
        'productos' => [
            [
                'nombre' => 'Collar de Plata',
                'descripcion' => 'Collar artesanal con diseños inspirados en la iconografía inca.',
                'imagen' => 'orfebreria_andina.jpg'
            ],
            [
                'nombre' => 'Aretes Tradicionales',
                'descripcion' => 'Aretes de plata con incrustaciones de turquesa andina.',
                'imagen' => 'arte_andino.jpg'
            ]
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_artesanias']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    <meta name="description" content="Descubre las artesanías tradicionales andinas. Conoce a nuestros maestros artesanos y sus técnicas ancestrales de textiles, cerámica y orfebrería.">
    <meta name="keywords" content="artesanías andinas, textiles tradicionales, cerámica andina, orfebrería, artesanos, técnicas ancestrales">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/artesanias.css">
    
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
                        <a class="nav-link active" aria-current="page" href="artesanias.php">
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
                <div class="d-flex">
                    <select class="form-select" id="language-selector">
                        <option value="es">
                            🇪🇸 Español
                        </option>
                        <option value="qu">
                            🏔️ Kichwa
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="artesanias-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-palette"></i>
                    <?php echo $texto['menu_artesanias']; ?>
                </h1>
                <p class="hero-subtitle" data-translate="artesanias_hero_subtitulo">
                    Descubre el arte ancestral de nuestros maestros artesanos, donde cada pieza cuenta una historia milenaria de tradición y cultura.
                </p>
            </div>
        </div>
    </section>

    <!-- Sección de categorías -->
    <section class="categories-section">
        <div class="container">
            <div class="category-buttons">
                <button class="category-btn active" data-filter="all">
                    <i class="fas fa-th"></i>
                    <span data-translate="todos">Todos</span>
                </button>
                <button class="category-btn" data-filter="textiles">
                    <i class="fas fa-tshirt"></i>
                    <span data-translate="textiles">Textiles</span>
                </button>
                <button class="category-btn" data-filter="ceramica">
                    <i class="fas fa-wine-glass"></i>
                    <span data-translate="ceramica">Cerámica</span>
                </button>
                <button class="category-btn" data-filter="orfebreria">
                    <i class="fas fa-gem"></i>
                    <span data-translate="orfebreria">Orfebrería</span>
                </button>
                <button class="category-btn" data-filter="madera">
                    <i class="fas fa-tree"></i>
                    <span data-translate="madera">Madera</span>
                </button>
                <button class="category-btn" data-filter="cuero">
                    <i class="fas fa-shoe-prints"></i>
                    <span data-translate="cuero">Cuero</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Sección de artesanos -->
    <section class="artesanos-section">
        <div class="container">
            <h2 class="section-title" data-translate="nuestros_maestros_artesanos">Nuestros Maestros Artesanos</h2>
            
            <?php 
            // Intentar obtener datos de la base de datos
            $has_db_data = false;
            if ($stmt_artesanos) {
                while ($row_artesano = $stmt_artesanos->fetch(PDO::FETCH_ASSOC)) {
                    $has_db_data = true;
                    ?>
                    <div class="artesano-card" data-category="<?php echo isset($row_artesano['categoria']) ? $row_artesano['categoria'] : 'general'; ?>">
                        <div class="artesano-header">
                            <img src="images/<?php echo $row_artesano['foto']; ?>" alt="<?php echo $row_artesano['nombre']; ?>" class="artesano-photo">
                            <div class="artesano-info">
                                <h3><?php echo $row_artesano['nombre']; ?></h3>
                                <p><?php echo $row_artesano['descripcion']; ?></p>
                                <span class="artesano-specialty" data-translate="especialista_en_artesanias">Especialista en Artesanías</span>
                            </div>
                        </div>
                        
                        <div class="productos-section">
                            <h4 class="productos-title">Productos de <?php echo $row_artesano['nombre']; ?></h4>
                            <div class="productos-grid">
                                <?php
                                $stmt_productos = $artesania->read_productos_por_artesano($row_artesano['id']);
                                while ($row_producto = $stmt_productos->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                    <div class="producto-card">
                                        <div class="producto-image-container">
                                            <img src="images/<?php echo $row_producto['imagen']; ?>" alt="<?php echo $row_producto['nombre']; ?>" loading="lazy">
                                            <div class="producto-overlay">
                                                <div class="overlay-content">
                                                    <i class="fas fa-eye fa-2x"></i>
                                                    <p>Ver detalles</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="producto-body">
                                            <h5 class="producto-title"><?php echo $row_producto['nombre']; ?></h5>
                                            <p class="producto-description"><?php echo $row_producto['descripcion']; ?></p>
                                            <div class="producto-tags">
                                                <span class="producto-tag">Artesanal</span>
                                                <span class="producto-tag">Tradicional</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            
            // Si no hay datos de la BD, usar datos de ejemplo
            if (!$has_db_data) {
                foreach ($artesanos_ejemplo as $artesano) {
                    ?>
                    <div class="artesano-card" data-category="<?php echo $artesano['categoria']; ?>">
                        <div class="artesano-header">
                            <img src="images/<?php echo $artesano['foto']; ?>" alt="<?php echo $artesano['nombre']; ?>" class="artesano-photo">
                            <div class="artesano-info">
                                <h3><?php echo $artesano['nombre']; ?></h3>
                                <p><?php echo $artesano['descripcion']; ?></p>
                                <span class="artesano-specialty"><?php echo $artesano['especialidad']; ?></span>
                            </div>
                        </div>
                        
                        <div class="productos-section">
                            <h4 class="productos-title">Productos de <?php echo $artesano['nombre']; ?></h4>
                            <div class="productos-grid">
                                <?php foreach ($artesano['productos'] as $producto) { ?>
                                    <div class="producto-card">
                                        <div class="producto-image-container">
                                            <img src="images/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" loading="lazy">
                                            <div class="producto-overlay">
                                                <div class="overlay-content">
                                                    <i class="fas fa-eye fa-2x"></i>
                                                    <p>Ver detalles</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="producto-body">
                                            <h5 class="producto-title"><?php echo $producto['nombre']; ?></h5>
                                            <p class="producto-description"><?php echo $producto['descripcion']; ?></p>
                                            <div class="producto-tags">
                                                <span class="producto-tag">Artesanal</span>
                                                <span class="producto-tag"><?php echo $artesano['especialidad']; ?></span>
                                                <span class="producto-tag">Tradicional</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </section>

    <!-- Sección de técnicas artesanales -->
    <section class="tecnicas-section">
        <div class="container">
            <h2 class="section-title text-white">Técnicas Ancestrales</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="tecnica-card">
                        <div class="tecnica-icon">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <h3 class="tecnica-title">Telar de Cintura</h3>
                        <p class="tecnica-description">
                            Técnica milenaria de tejido que utiliza un telar portátil amarrado a la cintura de la tejedora, permitiendo crear textiles con patrones complejos.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="tecnica-card">
                        <div class="tecnica-icon">
                            <i class="fas fa-wine-glass"></i>
                        </div>
                        <h3 class="tecnica-title">Alfarería Tradicional</h3>
                        <p class="tecnica-description">
                            Modelado a mano y cocción en hornos de leña siguiendo métodos precolombinos, creando piezas únicas con diseños ancestrales.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="tecnica-card">
                        <div class="tecnica-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <h3 class="tecnica-title">Orfebrería Andina</h3>
                        <p class="tecnica-description">
                            Trabajo artesanal de metales preciosos con técnicas heredadas de los maestros orfebres incas, creando joyas con simbolismo sagrado.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="tecnica-card">
                        <div class="tecnica-icon">
                            <i class="fas fa-tree"></i>
                        </div>
                        <h3 class="tecnica-title">Tallado en Madera</h3>
                        <p class="tecnica-description">
                            Escultura en maderas nativas utilizando herramientas tradicionales, creando máscaras ceremoniales y objetos rituales.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="tecnica-card">
                        <div class="tecnica-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h3 class="tecnica-title">Tintes Naturales</h3>
                        <p class="tecnica-description">
                            Uso de plantas, minerales e insectos locales para crear una paleta de colores vibrantes y duraderos en textiles y cerámicas.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="tecnica-card">
                        <div class="tecnica-icon">
                            <i class="fas fa-hands"></i>
                        </div>
                        <h3 class="tecnica-title">Transmisión Oral</h3>
                        <p class="tecnica-description">
                            Conocimientos pasados de generación en generación a través de la práctica y la enseñanza directa de maestro a aprendiz.
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
                    <p>Preservando y promoviendo las tradiciones artesanales de los Andes para las futuras generaciones.</p>
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
                    <h5>Artesanías</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Textiles Tradicionales</a></li>
                        <li><a href="#">Cerámica Ancestral</a></li>
                        <li><a href="#">Orfebrería Andina</a></li>
                        <li><a href="#">Tallado en Madera</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-envelope"></i> artesanias@culturaandina.com</p>
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
    <script src="js/artesanias.js"></script>
</body>
</html>

