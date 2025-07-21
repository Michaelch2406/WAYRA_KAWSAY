<?php
session_start();
include_once '../config/global.php';
include_once '../config/Conexion.php';
include_once '../models/Usuario.php';

// Verificar que el usuario esté logueado y tenga rol artesano
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'artesano' && $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$database = new Conexion();
$db = $database->getConnection();

// Obtener información del artesano
$query_artesano = "SELECT a.*, u.nombre, u.email, u.telefono 
                   FROM artesanos a 
                   INNER JOIN usuarios u ON a.id_usuario = u.id 
                   WHERE a.id_usuario = :user_id";
$stmt_artesano = $db->prepare($query_artesano);
$stmt_artesano->bindParam(':user_id', $_SESSION['usuario_id']);
$stmt_artesano->execute();
$artesano_info = $stmt_artesano->fetch(PDO::FETCH_ASSOC);

// Obtener productos del artesano
$query_productos = "SELECT * FROM productos_artesanales WHERE id_artesano = :artesano_id ORDER BY fecha_creacion DESC";
$stmt_productos = $db->prepare($query_productos);
$stmt_productos->bindParam(':artesano_id', $artesano_info['id']);
$stmt_productos->execute();

// Obtener contenido del artesano
$query_contenido = "SELECT * FROM contenido WHERE id_usuario_autor = :user_id ORDER BY fecha_creacion DESC";
$stmt_contenido = $db->prepare($query_contenido);
$stmt_contenido->bindParam(':user_id', $_SESSION['usuario_id']);
$stmt_contenido->execute();

// Obtener estadísticas
$query_stats = "SELECT 
    COUNT(p.id) as total_productos,
    SUM(CASE WHEN p.disponible = 1 THEN 1 ELSE 0 END) as productos_disponibles,
    COUNT(c.id) as total_contenido,
    SUM(CASE WHEN c.estado = 'aprobado' THEN 1 ELSE 0 END) as contenido_aprobado
    FROM productos_artesanales p 
    LEFT JOIN contenido c ON c.id_usuario_autor = :user_id 
    WHERE p.id_artesano = :artesano_id";
$stmt_stats = $db->prepare($query_stats);
$stmt_stats->bindParam(':user_id', $_SESSION['usuario_id']);
$stmt_stats->bindParam(':artesano_id', $artesano_info['id']);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['panel_artesano'] ?? 'Panel Artesano'; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/styles.css">
    
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #f8f4e6, #ede0c8);
            color: #5d4e37;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-left: 4px solid #d2691e;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid #D2691E;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .product-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-3px);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        .sidebar {
            background: #f8f9fa;
            min-height: calc(100vh - 76px);
            padding: 1rem;
            border-right: 1px solid #dee2e6;
        }
        .nav-pills .nav-link {
            color: #495057;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link.active {
            background-color: #f4e4d0;
            color: #8b4513;
            border: 1px solid #d2691e;
        }
        .nav-pills .nav-link:hover {
            background-color: #f8f9fa;
            color: #D2691E;
        }
        .artisan-profile-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #D2691E;
        }
        .badge-status {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        .product-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <!-- Navegación mejorada -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-mountain"></i>
                <?php echo NOMBRE_PROYECTO; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home"></i> <?php echo $texto['menu_inicio']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../sabores.php">
                            <i class="fas fa-utensils"></i> <?php echo $texto['menu_sabores']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../artesanias.php">
                            <i class="fas fa-palette"></i> <?php echo $texto['menu_artesanias']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../kichwa.php">
                            <i class="fas fa-language"></i> <?php echo $texto['menu_kichwa']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cultura.php">
                            <i class="fas fa-users"></i> <?php echo $texto['menu_cultura']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../ubicacion.php">
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
                    <div class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-palette"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="artesano.php"><i class="fas fa-tachometer-alt"></i> Mi Panel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../index.php"><i class="fas fa-home"></i> <?php echo $texto['menu_inicio'] ?? 'Ir al sitio'; ?></a></li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i> <?php echo $texto['cerrar_sesion'] ?? 'Cerrar Sesión'; ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top: 76px;">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#dashboard" data-bs-toggle="pill">
                                <i class="fas fa-chart-bar"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#perfil-artesano" data-bs-toggle="pill">
                                <i class="fas fa-user-tie"></i> <?php echo $texto['perfil_artesano'] ?? 'Perfil Artesano'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#productos" data-bs-toggle="pill">
                                <i class="fas fa-box"></i> <?php echo $texto['mis_productos'] ?? 'Mis Productos'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#nuevo-producto" data-bs-toggle="pill">
                                <i class="fas fa-plus-circle"></i> <?php echo $texto['nuevo_producto'] ?? 'Nuevo Producto'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contenido" data-bs-toggle="pill">
                                <i class="fas fa-file-alt"></i> <?php echo $texto['mi_contenido'] ?? 'Mi Contenido'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#nuevo-contenido" data-bs-toggle="pill">
                                <i class="fas fa-pen"></i> <?php echo $texto['crear_contenido'] ?? 'Crear Contenido'; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <div class="dashboard-header">
                            <div class="container">
                                <h1><i class="fas fa-palette"></i> <?php echo $texto['panel_artesano'] ?? 'Panel Artesano'; ?></h1>
                                <p class="lead"><?php echo $texto['bienvenido_artesano'] ?? 'Bienvenido'; ?>, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>. <?php echo $texto['gestiona_productos'] ?? 'Gestiona tus productos y perfil artesanal desde aquí'; ?>.</p>
                            </div>
                        </div>

                        <!-- Perfil Rápido -->
                        <div class="artisan-profile-card">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <img src="../images/<?php echo $artesano_info['foto_perfil'] ?? 'default-avatar.jpg'; ?>" 
                                         alt="Foto de perfil" class="profile-image">
                                </div>
                                <div class="col-md-10">
                                    <h3><?php echo htmlspecialchars($artesano_info['nombre']); ?></h3>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-star"></i> <?php echo htmlspecialchars($artesano_info['especialidad'] ?? 'Sin especialidad definida'); ?>
                                    </p>
                                    <p class="mb-3"><?php echo htmlspecialchars($artesano_info['historia'] ?? 'Sin descripción disponible'); ?></p>
                                    <button class="btn btn-outline-primary" onclick="showTab('perfil-artesano')">
                                        <i class="fas fa-edit"></i> <?php echo $texto['editar_perfil'] ?? 'Editar Perfil'; ?>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-primary">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <h3><?php echo $stats['total_productos'] ?? 0; ?></h3>
                                    <p class="text-muted"><?php echo $texto['total_productos'] ?? 'Total Productos'; ?></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-success">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3><?php echo $stats['productos_disponibles'] ?? 0; ?></h3>
                                    <p class="text-muted"><?php echo $texto['disponibles'] ?? 'Disponibles'; ?></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-info">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h3><?php echo $stats['total_contenido'] ?? 0; ?></h3>
                                    <p class="text-muted"><?php echo $texto['contenido_total'] ?? 'Contenido Total'; ?></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-warning">
                                        <i class="fas fa-thumbs-up"></i>
                                    </div>
                                    <h3><?php echo $stats['contenido_aprobado'] ?? 0; ?></h3>
                                    <p class="text-muted"><?php echo $texto['contenido_aprobado'] ?? 'Contenido Aprobado'; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="stat-card">
                                    <h4><i class="fas fa-bolt"></i> <?php echo $texto['acciones_rapidas'] ?? 'Acciones Rápidas'; ?></h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <button class="btn btn-primary w-100 mb-2" onclick="showTab('nuevo-producto')">
                                                <i class="fas fa-plus"></i> <?php echo $texto['agregar_producto'] ?? 'Agregar Producto'; ?>
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-success w-100 mb-2" onclick="showTab('productos')">
                                                <i class="fas fa-box"></i> <?php echo $texto['ver_productos'] ?? 'Ver Productos'; ?>
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-info w-100 mb-2" onclick="showTab('nuevo-contenido')">
                                                <i class="fas fa-pen"></i> <?php echo $texto['crear_historia'] ?? 'Crear Historia'; ?>
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning w-100 mb-2" onclick="showTab('perfil-artesano')">
                                                <i class="fas fa-user-edit"></i> <?php echo $texto['editar_perfil'] ?? 'Editar Perfil'; ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Perfil Artesano Tab -->
                    <div class="tab-pane fade" id="perfil-artesano">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-user-tie"></i> <?php echo $texto['perfil_artesano'] ?? 'Perfil Artesano'; ?></h2>
                        </div>

                        <div class="stat-card">
                            <div id="profile-alert"></div>
                            
                            <form id="artesanoProfileForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">
                                                <i class="fas fa-user"></i> <?php echo $texto['nombre'] ?? 'Nombre'; ?>
                                            </label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                                   value="<?php echo htmlspecialchars($artesano_info['nombre']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="especialidad" class="form-label">
                                                <i class="fas fa-star"></i> <?php echo $texto['especialidad'] ?? 'Especialidad'; ?>
                                            </label>
                                            <input type="text" class="form-control" id="especialidad" name="especialidad" 
                                                   value="<?php echo htmlspecialchars($artesano_info['especialidad'] ?? ''); ?>" 
                                                   placeholder="Ej: Textiles Andinos, Cerámica Tradicional">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="historia" class="form-label">
                                        <i class="fas fa-book"></i> <?php echo $texto['historia_biografia'] ?? 'Historia/Biografía'; ?>
                                    </label>
                                    <textarea class="form-control" id="historia" name="historia" rows="4" 
                                              placeholder="Cuéntanos tu historia como artesano..."><?php echo htmlspecialchars($artesano_info['historia'] ?? ''); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefono_contacto" class="form-label">
                                                <i class="fas fa-phone"></i> <?php echo $texto['telefono_contacto'] ?? 'Teléfono de Contacto'; ?>
                                            </label>
                                            <input type="tel" class="form-control" id="telefono_contacto" name="telefono_contacto" 
                                                   value="<?php echo htmlspecialchars($artesano_info['telefono_contacto'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="foto_perfil" class="form-label">
                                                <i class="fas fa-camera"></i> <?php echo $texto['foto_perfil'] ?? 'Foto de Perfil'; ?>
                                            </label>
                                            <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*">
                                            <div class="form-text"><?php echo $texto['foto_actual'] ?? 'Foto actual'; ?>: <?php echo $artesano_info['foto_perfil'] ?? 'Sin foto'; ?></div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" id="btnUpdateArtesanoProfile">
                                    <i class="fas fa-save"></i>
                                    <span><?php echo $texto['actualizar_perfil'] ?? 'Actualizar Perfil'; ?></span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Productos Tab -->
                    <div class="tab-pane fade" id="productos">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-box"></i> <?php echo $texto['mis_productos'] ?? 'Mis Productos'; ?></h2>
                            <button class="btn btn-primary" onclick="showTab('nuevo-producto')">
                                <i class="fas fa-plus"></i> <?php echo $texto['nuevo_producto'] ?? 'Nuevo Producto'; ?>
                            </button>
                        </div>

                        <div class="row">
                            <?php while ($producto = $stmt_productos->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="product-card">
                                    <?php if($producto['imagen']): ?>
                                        <img src="../images/<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="product-image">
                                    <?php else: ?>
                                        <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h5><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                    <p class="text-muted"><?php echo substr(htmlspecialchars($producto['descripcion']), 0, 100) . '...'; ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge badge-status <?php echo $producto['disponible'] ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo $producto['disponible'] ? ($texto['disponible'] ?? 'Disponible') : ($texto['no_disponible'] ?? 'No Disponible'); ?>
                                        </span>
                                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($producto['fecha_creacion'])); ?></small>
                                    </div>
                                    
                                    <div class="product-actions">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editProduct(<?php echo $producto['id']; ?>)">
                                            <i class="fas fa-edit"></i> <?php echo $texto['editar'] ?? 'Editar'; ?>
                                        </button>
                                        <button class="btn btn-sm btn-outline-<?php echo $producto['disponible'] ? 'warning' : 'success'; ?>" 
                                                onclick="toggleProductStatus(<?php echo $producto['id']; ?>, <?php echo $producto['disponible'] ? 0 : 1; ?>)">
                                            <i class="fas fa-toggle-<?php echo $producto['disponible'] ? 'on' : 'off'; ?>"></i>
                                            <?php echo $producto['disponible'] ? ($texto['ocultar'] ?? 'Ocultar') : ($texto['mostrar'] ?? 'Mostrar'); ?>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(<?php echo $producto['id']; ?>)">
                                            <i class="fas fa-trash"></i> <?php echo $texto['eliminar'] ?? 'Eliminar'; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Nuevo Producto Tab -->
                    <div class="tab-pane fade" id="nuevo-producto">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-plus-circle"></i> <?php echo $texto['agregar_producto'] ?? 'Agregar Nuevo Producto'; ?></h2>
                        </div>

                        <div class="stat-card">
                            <div id="product-alert"></div>
                            
                            <form id="productForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="producto_nombre" class="form-label">
                                                <i class="fas fa-tag"></i> <?php echo $texto['nombre_producto'] ?? 'Nombre del Producto'; ?>
                                            </label>
                                            <input type="text" class="form-control" id="producto_nombre" name="nombre" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="producto_imagen" class="form-label">
                                                <i class="fas fa-image"></i> <?php echo $texto['imagen_producto'] ?? 'Imagen del Producto'; ?>
                                            </label>
                                            <input type="file" class="form-control" id="producto_imagen" name="imagen" accept="image/*">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="producto_descripcion" class="form-label">
                                        <i class="fas fa-file-alt"></i> <?php echo $texto['descripcion'] ?? 'Descripción'; ?>
                                    </label>
                                    <textarea class="form-control" id="producto_descripcion" name="descripcion" rows="4" required 
                                              placeholder="Describe tu producto: materiales, técnica, historia..."></textarea>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="producto_disponible" name="disponible" checked>
                                    <label class="form-check-label" for="producto_disponible">
                                        <?php echo $texto['producto_disponible'] ?? 'Producto disponible para la venta'; ?>
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary" id="btnSubmitProduct">
                                    <i class="fas fa-plus"></i>
                                    <span><?php echo $texto['agregar_producto'] ?? 'Agregar Producto'; ?></span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Contenido Tab -->
                    <div class="tab-pane fade" id="contenido">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-file-alt"></i> <?php echo $texto['mi_contenido'] ?? 'Mi Contenido Comunitario'; ?></h2>
                            <button class="btn btn-primary" onclick="showTab('nuevo-contenido')">
                                <i class="fas fa-plus"></i> <?php echo $texto['nuevo_contenido'] ?? 'Nuevo Contenido'; ?>
                            </button>
                        </div>

                        <div class="row">
                            <?php while ($contenido = $stmt_contenido->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="col-md-6">
                                <div class="stat-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5><?php echo htmlspecialchars($contenido['titulo']); ?></h5>
                                            <p class="text-muted">
                                                <i class="fas fa-tag"></i> <?php echo ucfirst($contenido['tipo']); ?>
                                                <span class="mx-2">|</span>
                                                <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($contenido['fecha_creacion'])); ?>
                                            </p>
                                        </div>
                                        <span class="badge bg-<?php 
                                            echo $contenido['estado'] === 'aprobado' ? 'success' : 
                                                ($contenido['estado'] === 'pendiente' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($contenido['estado']); ?>
                                        </span>
                                    </div>
                                    <p><?php echo substr(htmlspecialchars($contenido['cuerpo']), 0, 150) . '...'; ?></p>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editContent(<?php echo $contenido['id']; ?>)">
                                            <i class="fas fa-edit"></i> <?php echo $texto['editar'] ?? 'Editar'; ?>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteContent(<?php echo $contenido['id']; ?>)">
                                            <i class="fas fa-trash"></i> <?php echo $texto['eliminar'] ?? 'Eliminar'; ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Nuevo Contenido Tab -->
                    <div class="tab-pane fade" id="nuevo-contenido">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-pen"></i> <?php echo $texto['crear_contenido'] ?? 'Crear Contenido Comunitario'; ?></h2>
                        </div>

                        <div class="stat-card">
                            <div id="content-alert"></div>
                            
                            <form id="contentForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tipo" class="form-label">
                                                <i class="fas fa-tag"></i> <?php echo $texto['tipo_contenido'] ?? 'Tipo de Contenido'; ?>
                                            </label>
                                            <select class="form-select" id="tipo" name="tipo" required>
                                                <option value=""><?php echo $texto['selecciona_tipo'] ?? 'Selecciona un tipo'; ?></option>
                                                <option value="noticia"><?php echo $texto['noticia'] ?? 'Noticia/Anuncio'; ?></option>
                                                <option value="historia"><?php echo $texto['historia'] ?? 'Historia'; ?></option>
                                                <option value="leyenda"><?php echo $texto['leyenda'] ?? 'Leyenda'; ?></option>
                                                <option value="tradicion"><?php echo $texto['tradicion'] ?? 'Tradición'; ?></option>
                                                <option value="baile"><?php echo $texto['baile'] ?? 'Baile/Danza'; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="titulo" class="form-label">
                                                <i class="fas fa-heading"></i> <?php echo $texto['titulo'] ?? 'Título'; ?>
                                            </label>
                                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="cuerpo" class="form-label">
                                        <i class="fas fa-file-alt"></i> <?php echo $texto['contenido'] ?? 'Contenido'; ?>
                                    </label>
                                    <textarea class="form-control" id="cuerpo" name="cuerpo" rows="6" required 
                                              placeholder="Escribe aquí tu historia, tradición o noticia..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="url_multimedia" class="form-label">
                                        <i class="fas fa-image"></i> <?php echo $texto['url_multimedia'] ?? 'URL de Imagen/Video (opcional)'; ?>
                                    </label>
                                    <input type="url" class="form-control" id="url_multimedia" name="url_multimedia" 
                                           placeholder="https://ejemplo.com/imagen.jpg">
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong><?php echo $texto['nota'] ?? 'Nota'; ?>:</strong> <?php echo $texto['contenido_revision'] ?? 'Tu contenido será revisado por un administrador antes de ser publicado'; ?>.
                                </div>

                                <button type="submit" class="btn btn-primary" id="btnSubmitContent">
                                    <i class="fas fa-paper-plane"></i>
                                    <span><?php echo $texto['enviar_revision'] ?? 'Enviar para Revisión'; ?></span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/ultra-translation-system.js"></script>
    <script>
        function showTab(tabId) {
            // Remove active class from all tabs
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
            
            // Add active class to selected tab
            document.querySelector(`[href="#${tabId}"]`).classList.add('active');
            document.getElementById(tabId).classList.add('show', 'active');
        }

        // Handle language selector
        document.getElementById('language-selector').addEventListener('change', function() {
            const lang = this.value;
            window.location.href = window.location.pathname + '?lang=' + lang;
        });

        // Handle artesano profile form submission
        document.getElementById('artesanoProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const btn = document.getElementById('btnUpdateArtesanoProfile');
            const spinner = btn.querySelector('.spinner-border');
            
            spinner.classList.remove('d-none');
            btn.disabled = true;
            
            fetch('artesano-handler.php?action=update_profile', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('profile-alert', 'success', data.message);
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert('profile-alert', 'danger', data.message);
                }
            })
            .catch(error => {
                showAlert('profile-alert', 'danger', 'Error al procesar la solicitud');
            })
            .finally(() => {
                spinner.classList.add('d-none');
                btn.disabled = false;
            });
        });

        // Handle product form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const btn = document.getElementById('btnSubmitProduct');
            const spinner = btn.querySelector('.spinner-border');
            
            spinner.classList.remove('d-none');
            btn.disabled = true;
            
            fetch('artesano-handler.php?action=create_product', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('product-alert', 'success', data.message);
                    this.reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert('product-alert', 'danger', data.message);
                }
            })
            .catch(error => {
                showAlert('product-alert', 'danger', 'Error al procesar la solicitud');
            })
            .finally(() => {
                spinner.classList.add('d-none');
                btn.disabled = false;
            });
        });

        // Handle content form submission
        document.getElementById('contentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const btn = document.getElementById('btnSubmitContent');
            const spinner = btn.querySelector('.spinner-border');
            
            spinner.classList.remove('d-none');
            btn.disabled = true;
            
            fetch('content-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('content-alert', 'success', data.message);
                    this.reset();
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert('content-alert', 'danger', data.message);
                }
            })
            .catch(error => {
                showAlert('content-alert', 'danger', 'Error al procesar la solicitud');
            })
            .finally(() => {
                spinner.classList.add('d-none');
                btn.disabled = false;
            });
        });

        function showAlert(containerId, type, message) {
            const container = document.getElementById(containerId);
            container.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }

        function editProduct(id) {
            // Get product data and show edit modal
            fetch(`artesano-handler.php?action=get_product&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showEditProductModal(data.product);
                } else {
                    alert('Error al cargar el producto: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error de conexión: ' + error.message);
            });
        }

        function showEditProductModal(product) {
            // Create edit modal
            const modalHtml = `
                <div class="modal fade" id="editProductModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Producto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="editProductForm" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <input type="hidden" name="product_id" value="${product.id}">
                                    <div class="mb-3">
                                        <label class="form-label">Nombre del Producto</label>
                                        <input type="text" class="form-control" name="nombre" value="${product.nombre}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Descripción</label>
                                        <textarea class="form-control" name="descripcion" rows="3" required>${product.descripcion}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Imagen Actual</label>
                                        ${product.imagen ? `<br><img src="../images/${product.imagen}" alt="Producto" style="max-width: 100px; height: auto;">` : '<br><span class="text-muted">Sin imagen</span>'}
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nueva Imagen (opcional)</label>
                                        <input type="file" class="form-control" name="imagen" accept="image/*">
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="disponible" ${product.disponible ? 'checked' : ''}>
                                        <label class="form-check-label">
                                            Producto disponible
                                        </label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            const existingModal = document.getElementById('editProductModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Add modal to page
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Setup form submission
            document.getElementById('editProductForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateProduct(new FormData(this));
            });
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
            modal.show();
        }

        function updateProduct(formData) {
            fetch('artesano-handler.php?action=update_product', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto actualizado exitosamente');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error de conexión: ' + error.message);
            });
        }

        function toggleProductStatus(id, status) {
            fetch('artesano-handler.php?action=toggle_product', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${id}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error al actualizar el producto');
                }
            });
        }

        function deleteProduct(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                fetch('artesano-handler.php?action=delete_product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar el producto');
                    }
                });
            }
        }

        function editContent(id) {
            // Get content data and show edit modal
            fetch(`artesano-handler.php?action=get_content&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showEditContentModal(data.content);
                } else {
                    alert('Error al cargar el contenido: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error de conexión: ' + error.message);
            });
        }

        function showEditContentModal(content) {
            // Create edit modal
            const modalHtml = `
                <div class="modal fade" id="editContentModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Contenido</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="editContentForm">
                                <div class="modal-body">
                                    <input type="hidden" name="content_id" value="${content.id}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Tipo de Contenido</label>
                                                <select class="form-select" name="tipo" required>
                                                    <option value="noticia" ${content.tipo === 'noticia' ? 'selected' : ''}>Noticia</option>
                                                    <option value="historia" ${content.tipo === 'historia' ? 'selected' : ''}>Historia</option>
                                                    <option value="leyenda" ${content.tipo === 'leyenda' ? 'selected' : ''}>Leyenda</option>
                                                    <option value="tradicion" ${content.tipo === 'tradicion' ? 'selected' : ''}>Tradición</option>
                                                    <option value="baile" ${content.tipo === 'baile' ? 'selected' : ''}>Baile</option>
                                                    <option value="evento" ${content.tipo === 'evento' ? 'selected' : ''}>Evento</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Título</label>
                                                <input type="text" class="form-control" name="titulo" value="${content.titulo}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">URL Multimedia (opcional)</label>
                                                <input type="url" class="form-control" name="url_multimedia" value="${content.url_multimedia || ''}" placeholder="https://...">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contenido</label>
                                                <textarea class="form-control" name="cuerpo" rows="8" required>${content.cuerpo}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info">
                                        <small><i class="fas fa-info-circle"></i> El contenido editado volverá a estado pendiente para revisión.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Actualizar Contenido</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            const existingModal = document.getElementById('editContentModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Add modal to page
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            // Setup form submission
            document.getElementById('editContentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                updateContent(new FormData(this));
            });
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editContentModal'));
            modal.show();
        }

        function updateContent(formData) {
            fetch('artesano-handler.php?action=update_content', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Contenido actualizado exitosamente');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error de conexión: ' + error.message);
            });
        }

        function deleteContent(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este contenido?')) {
                fetch('artesano-handler.php?action=delete_content', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `content_id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Contenido eliminado exitosamente');
                        location.reload();
                    } else {
                        alert('Error al eliminar el contenido: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error de conexión: ' + error.message);
                });
            }
        }
    </script>
</body>
</html>