<?php
include_once 'config/global.php';
include_once 'config/Conexion.php';
include_once 'models/Sabores.php';

$database = new Conexion();
$db = $database->getConnection();

$sabor = new Sabores($db);
$stmt = $sabor->read();

// Datos de ejemplo para demostración (en caso de que no haya datos en la BD)
$sabores_ejemplo = [
    [
        'id' => 1,
        'nombre' => 'Pachamanca',
        'descripcion' => 'Plato tradicional andino cocinado bajo tierra con piedras calientes. Una experiencia culinaria ancestral que combina carnes, papas y verduras.',
        'imagen' => 'pachamanca.jpg',
        'categoria' => 'tradicional',
        'dificultad' => 4,
        'tiempo_prep' => '4-6 horas',
        'rating' => '4.8/5'
    ],
    [
        'id' => 2,
        'nombre' => 'Cuy Asado',
        'descripcion' => 'Deliciosa preparación de cuy marinado con especias andinas y asado a la parrilla. Un manjar de la gastronomía serrana.',
        'imagen' => 'plato_andino_1.jpg',
        'categoria' => 'carnes',
        'dificultad' => 3,
        'tiempo_prep' => '2-3 horas',
        'rating' => '4.6/5'
    ],
    [
        'id' => 3,
        'nombre' => 'Locro de Papa',
        'descripcion' => 'Cremosa sopa de papas con queso fresco y hierbas aromáticas. Comfort food de los Andes que alimenta el alma.',
        'imagen' => 'platos_sierra.jpg',
        'categoria' => 'sopas',
        'dificultad' => 2,
        'tiempo_prep' => '45 min',
        'rating' => '4.7/5'
    ],
    [
        'id' => 4,
        'nombre' => 'Quinoa con Verduras',
        'descripcion' => 'Nutritivo plato con quinoa, el grano de oro de los Incas, acompañado de verduras frescas de la región.',
        'imagen' => 'gastronomia_andina.jpg',
        'categoria' => 'vegetariano',
        'dificultad' => 1,
        'tiempo_prep' => '30 min',
        'rating' => '4.5/5'
    ],
    [
        'id' => 5,
        'nombre' => 'Chicha de Jora',
        'descripcion' => 'Bebida ceremonial fermentada de maíz, preparada según tradiciones milenarias de los pueblos andinos.',
        'imagen' => 'cocina_peruana.jpg',
        'categoria' => 'bebidas',
        'dificultad' => 3,
        'tiempo_prep' => '7 días',
        'rating' => '4.4/5'
    ],
    [
        'id' => 6,
        'nombre' => 'Humitas',
        'descripcion' => 'Deliciosos tamales dulces de maíz envueltos en hojas de choclo. Perfectos para el desayuno o la merienda.',
        'imagen' => 'sabores_hero.jpg',
        'categoria' => 'postres',
        'dificultad' => 2,
        'tiempo_prep' => '1.5 horas',
        'rating' => '4.6/5'
    ]
];
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_sabores']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    <meta name="description" content="Descubre los sabores únicos de la gastronomía andina. Platos tradicionales, recetas ancestrales y la riqueza culinaria de los Andes.">
    <meta name="keywords" content="gastronomía andina, comida tradicional, sabores andinos, cocina ancestral, platos típicos">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/sabores.css">
    <link rel="stylesheet" href="VISTAS/partials/css/navbar.css">
    <link rel="stylesheet" href="VISTAS/partials/css/footer.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
    <!-- Navegación -->
    <header id="navbar-placeholder">
        <?php include 'VISTAS/partials/navbar.php'; ?>
    </header>

    <!-- Hero Section -->
    <section class="sabores-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-utensils"></i>
                    <?php echo $texto['menu_sabores']; ?>
                </h1>
                <p class="hero-subtitle">
                    Descubre los sabores únicos de la gastronomía andina, donde cada plato cuenta una historia milenaria de tradición y cultura.
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
                    Todos
                </button>
                <button class="filter-btn" data-filter="tradicional">
                    <i class="fas fa-fire"></i>
                    Tradicionales
                </button>
                <button class="filter-btn" data-filter="carnes">
                    <i class="fas fa-drumstick-bite"></i>
                    Carnes
                </button>
                <button class="filter-btn" data-filter="sopas">
                    <i class="fas fa-bowl-food"></i>
                    Sopas
                </button>
                <button class="filter-btn" data-filter="vegetariano">
                    <i class="fas fa-leaf"></i>
                    Vegetariano
                </button>
                <button class="filter-btn" data-filter="bebidas">
                    <i class="fas fa-glass-water"></i>
                    Bebidas
                </button>
                <button class="filter-btn" data-filter="postres">
                    <i class="fas fa-cake-candles"></i>
                    Postres
                </button>
            </div>
        </div>
    </section>

    <!-- Grid de sabores -->
    <section class="sabores-grid">
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
                            <div class="sabor-card" data-category="<?php echo isset($row['categoria']) ? $row['categoria'] : 'tradicional'; ?>">
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
                                        <span class="tag">Tradicional</span>
                                        <span class="tag">Andino</span>
                                    </div>
                                    
                                    <div class="card-rating">
                                        <div class="stars">★★★★★</div>
                                        <span class="rating-text">4.5/5 (120 reseñas)</span>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <div class="difficulty">
                                            <span>Dificultad:</span>
                                            <div class="difficulty-level active"></div>
                                            <div class="difficulty-level active"></div>
                                            <div class="difficulty-level active"></div>
                                            <div class="difficulty-level"></div>
                                            <div class="difficulty-level"></div>
                                        </div>
                                        <div class="prep-time">
                                            <i class="fas fa-clock"></i>
                                            2 horas
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
                    foreach ($sabores_ejemplo as $sabor) {
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="sabor-card" data-category="<?php echo $sabor['categoria']; ?>">
                                <div class="card-image-container">
                                    <img src="images/<?php echo $sabor['imagen']; ?>" alt="<?php echo $sabor['nombre']; ?>" loading="lazy">
                                    <div class="card-overlay">
                                        <div class="overlay-content">
                                            <i class="fas fa-eye fa-2x"></i>
                                            <p>Ver detalles</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo $sabor['nombre']; ?></h3>
                                    <p class="card-description"><?php echo $sabor['descripcion']; ?></p>
                                    
                                    <div class="card-tags">
                                        <span class="tag">Tradicional</span>
                                        <span class="tag">Andino</span>
                                        <?php if ($sabor['categoria'] == 'vegetariano') { ?>
                                            <span class="tag">Vegetariano</span>
                                        <?php } ?>
                                    </div>
                                    
                                    <div class="card-rating">
                                        <div class="stars">★★★★★</div>
                                        <span class="rating-text"><?php echo $sabor['rating']; ?> (120 reseñas)</span>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <div class="difficulty">
                                            <span>Dificultad:</span>
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <div class="difficulty-level <?php echo $i <= $sabor['dificultad'] ? 'active' : ''; ?>"></div>
                                            <?php } ?>
                                        </div>
                                        <div class="prep-time">
                                            <i class="fas fa-clock"></i>
                                            <?php echo $sabor['tiempo_prep']; ?>
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

    <!-- Sección de información adicional -->
    <section class="info-section">
        <div class="container">
            <h2 class="section-title">Nuestra Gastronomía</h2>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h3 class="info-title">Ingredientes Ancestrales</h3>
                        <p class="info-description">
                            Utilizamos ingredientes nativos como quinoa, papa, maíz y hierbas aromáticas que han alimentado a nuestros pueblos durante milenios.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <h3 class="info-title">Técnicas Tradicionales</h3>
                        <p class="info-description">
                            Preservamos métodos de cocción ancestrales como la pachamanca, el uso de piedras calientes y la fermentación natural.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="info-title">Cocina del Alma</h3>
                        <p class="info-description">
                            Cada plato está preparado con amor y respeto por la Pachamama, transmitiendo la esencia de nuestra cultura en cada bocado.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer-placeholder">
        <?php include 'VISTAS/partials/footer.php'; ?>
    </footer>
    <!-- Scripts -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/sabores.js"></script>
    <script src="VISTAS/partials/js/navbar.js"></script>
    <script src="VISTAS/partials/js/footer.js"></script>
</body>
</html>

