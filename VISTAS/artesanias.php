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
    
    <?php include 'partials/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="artesanias-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <i class="fas fa-palette"></i>
                    <?php echo $texto['menu_artesanias']; ?>
                </h1>
                <p class="hero-subtitle">
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
                    Todos
                </button>
                <button class="category-btn" data-filter="textiles">
                    <i class="fas fa-tshirt"></i>
                    Textiles
                </button>
                <button class="category-btn" data-filter="ceramica">
                    <i class="fas fa-wine-glass"></i>
                    Cerámica
                </button>
                <button class="category-btn" data-filter="orfebreria">
                    <i class="fas fa-gem"></i>
                    Orfebrería
                </button>
                <button class="category-btn" data-filter="madera">
                    <i class="fas fa-tree"></i>
                    Madera
                </button>
                <button class="category-btn" data-filter="cuero">
                    <i class="fas fa-shoe-prints"></i>
                    Cuero
                </button>
            </div>
        </div>
    </section>

    <!-- Sección de artesanos -->
    <section class="artesanos-section">
        <div class="container">
            <h2 class="section-title">Nuestros Maestros Artesanos</h2>
            
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
                                <span class="artesano-specialty">Especialista en Artesanías</span>
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
    <?php include 'partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/artesanias.js"></script>
</body>
</html>

