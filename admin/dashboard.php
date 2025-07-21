<?php
session_start();
include_once '../config/global.php';
include_once '../config/Conexion.php';
include_once '../models/Usuario.php';

// Verificar que el usuario esté logueado y tenga rol admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$database = new Conexion();
$db = $database->getConnection();

// Obtener estadísticas generales
$query_stats = "SELECT 
    (SELECT COUNT(*) FROM usuarios) as total_usuarios,
    (SELECT COUNT(*) FROM usuarios WHERE rol = 'comunitario') as total_comunitarios,
    (SELECT COUNT(*) FROM usuarios WHERE rol = 'artesano') as total_artesanos,
    (SELECT COUNT(*) FROM contenido WHERE estado = 'pendiente') as contenido_pendiente,
    (SELECT COUNT(*) FROM contenido WHERE estado = 'aprobado') as contenido_aprobado,
    (SELECT COUNT(*) FROM productos_artesanales) as total_productos,
    (SELECT COUNT(*) FROM eventos) as total_eventos,
    (SELECT COUNT(*) FROM palabras_kichwa) as total_palabras";

$stmt_stats = $db->prepare($query_stats);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

// Obtener contenido pendiente
$query_pendiente = "SELECT c.*, u.nombre as autor_nombre 
                    FROM contenido c 
                    INNER JOIN usuarios u ON c.id_usuario_autor = u.id 
                    WHERE c.estado = 'pendiente' 
                    ORDER BY c.fecha_creacion DESC 
                    LIMIT 10";
$stmt_pendiente = $db->prepare($query_pendiente);
$stmt_pendiente->execute();

// Obtener usuarios recientes
$query_usuarios = "SELECT * FROM usuarios ORDER BY fecha_registro DESC LIMIT 10";
$stmt_usuarios = $db->prepare($query_usuarios);
$stmt_usuarios->execute();

// Obtener platos para gestión de gastronomía
$query_platos = "SELECT * FROM platos ORDER BY fecha_creacion DESC";
$stmt_platos = $db->prepare($query_platos);
$stmt_platos->execute();

// Obtener palabras kichwa
$query_kichwa = "SELECT * FROM palabras_kichwa ORDER BY fecha_creacion DESC";
$stmt_kichwa = $db->prepare($query_kichwa);
$stmt_kichwa->execute();

// Obtener eventos
$query_eventos = "SELECT e.*, u.nombre as creador_nombre FROM eventos e 
                  LEFT JOIN usuarios u ON e.id_usuario_creador = u.id 
                  ORDER BY e.fecha_inicio DESC";
$stmt_eventos = $db->prepare($query_eventos);
$stmt_eventos->execute();

// Obtener rutas turísticas
$query_rutas = "SELECT * FROM rutas_turisticas ORDER BY fecha_creacion DESC";
$stmt_rutas = $db->prepare($query_rutas);
$stmt_rutas->execute();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['panel_admin'] ?? 'Panel Administrador'; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/styles.css">
    
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #fef8f8, #f8e8e8);
            color: #721c24;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-left: 4px solid #dc3545;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid #dc3545;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 2.5rem;
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
            background-color: #f8e8e8;
            color: #dc3545;
            border: 1px solid #dc3545;
        }
        .nav-pills .nav-link:hover {
            background-color: #f8f9fa;
            color: #dc3545;
        }
        .content-pending {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .user-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .badge-role {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.25rem;
        }
        .quick-stats {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
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
                            <i class="fas fa-home"></i>
                            <?php echo $texto['menu_inicio']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../sabores.php">
                            <i class="fas fa-utensils"></i>
                            <?php echo $texto['menu_sabores']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../artesanias.php">
                            <i class="fas fa-palette"></i>
                            <?php echo $texto['menu_artesanias']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../kichwa.php">
                            <i class="fas fa-language"></i>
                            <?php echo $texto['menu_kichwa']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cultura.php">
                            <i class="fas fa-users"></i>
                            <?php echo $texto['menu_cultura']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../ubicacion.php">
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
                    <div class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Panel Admin</a></li>
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
                                <i class="fas fa-chart-line"></i> <?php echo $texto['dashboard'] ?? 'Dashboard'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#usuarios" data-bs-toggle="pill">
                                <i class="fas fa-users"></i> <?php echo $texto['usuarios'] ?? 'Usuarios'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contenido" data-bs-toggle="pill">
                                <i class="fas fa-file-alt"></i> <?php echo $texto['moderacion'] ?? 'Moderación'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#gastronomia" data-bs-toggle="pill">
                                <i class="fas fa-utensils"></i> <?php echo $texto['gastronomia'] ?? 'Gastronomía'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#kichwa" data-bs-toggle="pill">
                                <i class="fas fa-language"></i> <?php echo $texto['kichwa'] ?? 'Kichwa'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#eventos" data-bs-toggle="pill">
                                <i class="fas fa-calendar"></i> <?php echo $texto['eventos'] ?? 'Eventos'; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#rutas" data-bs-toggle="pill">
                                <i class="fas fa-route"></i> <?php echo $texto['rutas'] ?? 'Rutas'; ?>
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
                                <h1><i class="fas fa-user-shield"></i> <?php echo $texto['panel_admin'] ?? 'Panel de Administración'; ?></h1>
                                <p class="lead"><?php echo $texto['bienvenido_admin'] ?? 'Bienvenido al panel de administración'; ?>, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>.</p>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="quick-stats">
                            <h4><i class="fas fa-chart-bar"></i> <?php echo $texto['resumen_general'] ?? 'Resumen General'; ?></h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-primary"><?php echo $stats['total_usuarios']; ?></h3>
                                        <p class="mb-0"><?php echo $texto['usuarios_totales'] ?? 'Usuarios Totales'; ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-warning"><?php echo $stats['contenido_pendiente']; ?></h3>
                                        <p class="mb-0"><?php echo $texto['contenido_pendiente'] ?? 'Contenido Pendiente'; ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-success"><?php echo $stats['total_productos']; ?></h3>
                                        <p class="mb-0"><?php echo $texto['productos_totales'] ?? 'Productos Totales'; ?></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h3 class="text-info"><?php echo $stats['total_palabras']; ?></h3>
                                        <p class="mb-0"><?php echo $texto['palabras_kichwa'] ?? 'Palabras Kichwa'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h3><?php echo $stats['total_comunitarios']; ?></h3>
                                    <p class="text-muted"><?php echo $texto['usuarios_comunitarios'] ?? 'Usuarios Comunitarios'; ?></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-success">
                                        <i class="fas fa-palette"></i>
                                    </div>
                                    <h3><?php echo $stats['total_artesanos']; ?></h3>
                                    <p class="text-muted"><?php echo $texto['artesanos'] ?? 'Artesanos'; ?></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-info">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3><?php echo $stats['contenido_aprobado']; ?></h3>
                                    <p class="text-muted"><?php echo $texto['contenido_aprobado'] ?? 'Contenido Aprobado'; ?></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-warning">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <h3><?php echo $stats['total_eventos']; ?></h3>
                                    <p class="text-muted"><?php echo $texto['eventos_totales'] ?? 'Eventos Totales'; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido Pendiente -->
                        <div class="row">
                            <div class="col-12">
                                <div class="stat-card">
                                    <h4><i class="fas fa-clock"></i> <?php echo $texto['contenido_pendiente_revision'] ?? 'Contenido Pendiente de Revisión'; ?></h4>
                                    <?php if ($stmt_pendiente->rowCount() > 0): ?>
                                        <div class="row">
                                            <?php while ($contenido = $stmt_pendiente->fetch(PDO::FETCH_ASSOC)): ?>
                                            <div class="col-md-6">
                                                <div class="stat-card content-pending">
                                                    <h6><?php echo htmlspecialchars($contenido['titulo']); ?></h6>
                                                    <p class="text-muted small">
                                                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($contenido['autor_nombre']); ?> 
                                                        | <i class="fas fa-tag"></i> <?php echo ucfirst($contenido['tipo']); ?>
                                                        | <i class="fas fa-calendar"></i> <?php echo date('d/m/Y', strtotime($contenido['fecha_creacion'])); ?>
                                                    </p>
                                                    <p><?php echo substr(htmlspecialchars($contenido['cuerpo']), 0, 100) . '...'; ?></p>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-success" onclick="approveContent(<?php echo $contenido['id']; ?>)">
                                                            <i class="fas fa-check"></i> <?php echo $texto['aprobar'] ?? 'Aprobar'; ?>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="rejectContent(<?php echo $contenido['id']; ?>)">
                                                            <i class="fas fa-times"></i> <?php echo $texto['rechazar'] ?? 'Rechazar'; ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted"><?php echo $texto['no_contenido_pendiente'] ?? 'No hay contenido pendiente de revisión'; ?>.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usuarios Tab -->
                    <div class="tab-pane fade" id="usuarios">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-users"></i> <?php echo $texto['gestion_usuarios'] ?? 'Gestión de Usuarios'; ?></h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                                <i class="fas fa-plus"></i> <?php echo $texto['nuevo_usuario'] ?? 'Nuevo Usuario'; ?>
                            </button>
                        </div>

                        <div class="user-table">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th><?php echo $texto['id'] ?? 'ID'; ?></th>
                                            <th><?php echo $texto['nombre'] ?? 'Nombre'; ?></th>
                                            <th><?php echo $texto['email'] ?? 'Email'; ?></th>
                                            <th><?php echo $texto['rol'] ?? 'Rol'; ?></th>
                                            <th><?php echo $texto['estado'] ?? 'Estado'; ?></th>
                                            <th><?php echo $texto['registro'] ?? 'Registro'; ?></th>
                                            <th><?php echo $texto['acciones'] ?? 'Acciones'; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($usuario = $stmt_usuarios->fetch(PDO::FETCH_ASSOC)): ?>
                                        <tr>
                                            <td><?php echo $usuario['id']; ?></td>
                                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                            <td>
                                                <span class="badge badge-role bg-<?php 
                                                    echo $usuario['rol'] === 'admin' ? 'danger' : 
                                                        ($usuario['rol'] === 'artesano' ? 'warning' : 'primary'); 
                                                ?>">
                                                    <?php echo ucfirst($usuario['rol']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $usuario['activo'] ? 'success' : 'secondary'; ?>">
                                                    <?php echo $usuario['activo'] ? ($texto['activo'] ?? 'Activo') : ($texto['inactivo'] ?? 'Inactivo'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editUser(<?php echo $usuario['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-<?php echo $usuario['activo'] ? 'warning' : 'success'; ?>" 
                                                            onclick="toggleUserStatus(<?php echo $usuario['id']; ?>, <?php echo $usuario['activo'] ? 0 : 1; ?>)">
                                                        <i class="fas fa-toggle-<?php echo $usuario['activo'] ? 'on' : 'off'; ?>"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Moderación de Contenido Tab -->
                    <div class="tab-pane fade" id="contenido">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-file-alt"></i> <?php echo $texto['moderacion_contenido'] ?? 'Moderación de Contenido'; ?></h2>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="stat-card">
                                    <div id="content-moderation-container">
                                        <p class="text-center"><?php echo $texto['cargando'] ?? 'Cargando contenido'; ?>...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Gastronomía -->
                    <div class="tab-pane fade" id="gastronomia">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-utensils"></i> <?php echo $texto['gestion_gastronomia'] ?? 'Gestión de Gastronomía'; ?></h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoPlato">
                                <i class="fas fa-plus"></i> Nuevo Plato
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="stat-card">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Imagen</th>
                                                    <th>Nombre</th>
                                                    <th>Categoría</th>
                                                    <th>Dificultad</th>
                                                    <th>Tiempo (min)</th>
                                                    <th>Fecha</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($plato = $stmt_platos->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($plato['imagen']): ?>
                                                            <img src="../images/<?php echo htmlspecialchars($plato['imagen']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($plato['nombre']); ?>" 
                                                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                <i class="fas fa-utensils text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($plato['nombre']); ?></strong>
                                                        <br><small class="text-muted"><?php echo substr(htmlspecialchars($plato['descripcion']), 0, 50); ?>...</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?php echo ucfirst($plato['categoria']); ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $plato['dificultad'] == 'facil' ? 'success' : ($plato['dificultad'] == 'medio' ? 'warning' : 'danger'); ?>">
                                                            <?php echo ucfirst($plato['dificultad']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo $plato['tiempo_preparacion']; ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($plato['fecha_creacion'])); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-primary" onclick="verPlato(<?php echo $plato['id']; ?>)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-warning" onclick="editarPlato(<?php echo $plato['id']; ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarPlato(<?php echo $plato['id']; ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Vocabulario Kichwa -->
                    <div class="tab-pane fade" id="kichwa">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-language"></i> <?php echo $texto['gestion_kichwa'] ?? 'Gestión de Vocabulario Kichwa'; ?></h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaPalabra">
                                <i class="fas fa-plus"></i> Nueva Palabra
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="stat-card">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Palabra Kichwa</th>
                                                    <th>Traducción Español</th>
                                                    <th>Categoría</th>
                                                    <th>Audio</th>
                                                    <th>Fecha</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($palabra = $stmt_kichwa->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td><strong><?php echo htmlspecialchars($palabra['palabra_kichwa']); ?></strong></td>
                                                    <td><?php echo htmlspecialchars($palabra['traduccion_espanol']); ?></td>
                                                    <td>
                                                        <?php if ($palabra['categoria']): ?>
                                                            <span class="badge bg-info"><?php echo htmlspecialchars($palabra['categoria']); ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin categoría</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($palabra['audio_url']): ?>
                                                            <button class="btn btn-sm btn-outline-success" onclick="reproducirAudio('<?php echo htmlspecialchars($palabra['audio_url']); ?>')">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin audio</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo date('d/m/Y', strtotime($palabra['fecha_creacion'])); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-warning" onclick="editarPalabra(<?php echo $palabra['id']; ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarPalabra(<?php echo $palabra['id']; ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Eventos -->
                    <div class="tab-pane fade" id="eventos">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-calendar"></i> <?php echo $texto['gestion_eventos'] ?? 'Gestión de Eventos'; ?></h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoEvento">
                                <i class="fas fa-plus"></i> Nuevo Evento
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="stat-card">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Imagen</th>
                                                    <th>Nombre</th>
                                                    <th>Fecha Inicio</th>
                                                    <th>Fecha Fin</th>
                                                    <th>Ubicación</th>
                                                    <th>Creador</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($evento = $stmt_eventos->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($evento['imagen']): ?>
                                                            <img src="../images/<?php echo htmlspecialchars($evento['imagen']); ?>" 
                                                                 alt="<?php echo htmlspecialchars($evento['nombre']); ?>" 
                                                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                <i class="fas fa-calendar text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($evento['nombre']); ?></strong>
                                                        <br><small class="text-muted"><?php echo substr(htmlspecialchars($evento['descripcion']), 0, 50); ?>...</small>
                                                    </td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($evento['fecha_inicio'])); ?></td>
                                                    <td>
                                                        <?php if ($evento['fecha_fin']): ?>
                                                            <?php echo date('d/m/Y H:i', strtotime($evento['fecha_fin'])); ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">No definida</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($evento['ubicacion_texto']); ?></td>
                                                    <td><?php echo htmlspecialchars($evento['creador_nombre'] ?? 'Sistema'); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-primary" onclick="verEvento(<?php echo $evento['id']; ?>)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-warning" onclick="editarEvento(<?php echo $evento['id']; ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarEvento(<?php echo $evento['id']; ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gestión de Rutas Turísticas -->
                    <div class="tab-pane fade" id="rutas">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-route"></i> <?php echo $texto['gestion_rutas'] ?? 'Gestión de Rutas Turísticas'; ?></h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaRuta">
                                <i class="fas fa-plus"></i> Nueva Ruta
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="stat-card">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Distancia (km)</th>
                                                    <th>Dificultad</th>
                                                    <th>Mapa</th>
                                                    <th>Fecha</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($ruta = $stmt_rutas->fetch(PDO::FETCH_ASSOC)): ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($ruta['nombre']); ?></strong>
                                                        <br><small class="text-muted"><?php echo substr(htmlspecialchars($ruta['descripcion']), 0, 80); ?>...</small>
                                                    </td>
                                                    <td><?php echo $ruta['distancia_km'] ? number_format($ruta['distancia_km'], 1) : 'No especificada'; ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $ruta['dificultad'] == 'baja' ? 'success' : ($ruta['dificultad'] == 'media' ? 'warning' : 'danger'); ?>">
                                                            <?php echo ucfirst($ruta['dificultad'] ?? 'No definida'); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($ruta['mapa_url']): ?>
                                                            <button class="btn btn-sm btn-outline-info" onclick="verMapa('<?php echo htmlspecialchars($ruta['mapa_url']); ?>')">
                                                                <i class="fas fa-map"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted">Sin mapa</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo date('d/m/Y', strtotime($ruta['fecha_creacion'])); ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-primary" onclick="verRuta(<?php echo $ruta['id']; ?>)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-warning" onclick="editarRuta(<?php echo $ruta['id']; ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" onclick="eliminarRuta(<?php echo $ruta['id']; ?>)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modales para CRUD operations -->
    
    <!-- Modal Nuevo Plato -->
    <div class="modal fade" id="modalNuevoPlato" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-utensils"></i> Nuevo Plato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formPlato">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Categoría *</label>
                                    <select class="form-select" name="categoria" required>
                                        <option value="">Seleccionar categoría</option>
                                        <option value="tradicional">Tradicional</option>
                                        <option value="carne">Carne</option>
                                        <option value="sopa">Sopa</option>
                                        <option value="vegetariano">Vegetariano</option>
                                        <option value="bebida">Bebida</option>
                                        <option value="postre">Postre</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dificultad *</label>
                                    <select class="form-select" name="dificultad" required>
                                        <option value="">Seleccionar dificultad</option>
                                        <option value="facil">Fácil</option>
                                        <option value="medio">Medio</option>
                                        <option value="dificil">Difícil</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tiempo de preparación (minutos)</label>
                                    <input type="number" class="form-control" name="tiempo_preparacion" min="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Descripción *</label>
                                    <textarea class="form-control" name="descripcion" rows="4" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Historia</label>
                                    <textarea class="form-control" name="historia" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Imagen</label>
                                    <input type="text" class="form-control" name="imagen" placeholder="nombre_imagen.jpg">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Video URL</label>
                                    <input type="url" class="form-control" name="video_preparacion_url">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Plato</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Palabra Kichwa -->
    <div class="modal fade" id="modalNuevaPalabra" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-language"></i> Nueva Palabra Kichwa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formPalabra">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Palabra en Kichwa *</label>
                            <input type="text" class="form-control" name="palabra_kichwa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Traducción en Español *</label>
                            <input type="text" class="form-control" name="traduccion_espanol" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <input type="text" class="form-control" name="categoria" placeholder="ej: familia, naturaleza, colores">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">URL del Audio</label>
                            <input type="text" class="form-control" name="audio_url" placeholder="archivo_audio.mp3">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Palabra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Evento -->
    <div class="modal fade" id="modalNuevoEvento" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar"></i> Nuevo Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEvento">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Evento *</label>
                                    <input type="text" class="form-control" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha y Hora de Inicio *</label>
                                    <input type="datetime-local" class="form-control" name="fecha_inicio" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha y Hora de Fin</label>
                                    <input type="datetime-local" class="form-control" name="fecha_fin">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ubicación</label>
                                    <input type="text" class="form-control" name="ubicacion_texto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Descripción *</label>
                                    <textarea class="form-control" name="descripcion" rows="6" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Imagen</label>
                                    <input type="text" class="form-control" name="imagen" placeholder="nombre_imagen.jpg">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Ruta -->
    <div class="modal fade" id="modalNuevaRuta" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-route"></i> Nueva Ruta Turística</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formRuta">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombre de la Ruta *</label>
                                    <input type="text" class="form-control" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Distancia (km)</label>
                                    <input type="number" step="0.1" class="form-control" name="distancia_km">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dificultad</label>
                                    <select class="form-select" name="dificultad">
                                        <option value="">Seleccionar dificultad</option>
                                        <option value="baja">Baja</option>
                                        <option value="media">Media</option>
                                        <option value="alta">Alta</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">URL del Mapa</label>
                                    <input type="text" class="form-control" name="mapa_url">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Descripción *</label>
                                    <textarea class="form-control" name="descripcion" rows="8" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Ruta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/ultra-translation-system.js"></script>
    <script>
        // Handle language selector
        document.getElementById('language-selector').addEventListener('change', function() {
            const lang = this.value;
            window.location.href = window.location.pathname + '?lang=' + lang;
        });

        function showTab(tabId) {
            // Remove active class from all tabs
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
            
            // Add active class to selected tab
            document.querySelector(`[href="#${tabId}"]`).classList.add('active');
            document.getElementById(tabId).classList.add('show', 'active');
        }

        function approveContent(contentId) {
            if (confirm('¿Aprobar este contenido?')) {
                fetch('admin-handler.php?action=approve_content', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `content_id=${contentId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }

        function rejectContent(contentId) {
            if (confirm('¿Rechazar este contenido?')) {
                fetch('admin-handler.php?action=reject_content', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `content_id=${contentId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
            }
        }

        function editUser(userId) {
            console.log('Edit user:', userId);
        }

        function toggleUserStatus(userId, status) {
            fetch('admin-handler.php?action=toggle_user', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `user_id=${userId}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }

        // Load content moderation when tab is shown
        document.querySelector('[href="#contenido"]').addEventListener('click', function() {
            loadContentModeration();
        });

        function loadContentModeration() {
            fetch('admin-handler.php?action=get_all_content')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayContentModeration(data.content);
                } else {
                    document.getElementById('content-moderation-container').innerHTML = 
                        '<p class="text-danger">Error al cargar el contenido</p>';
                }
            });
        }

        function displayContentModeration(content) {
            let html = '';
            if (content.length === 0) {
                html = '<p class="text-muted">No hay contenido para moderar</p>';
            } else {
                html = '<div class="row">';
                content.forEach(item => {
                    const statusClass = item.estado === 'aprobado' ? 'success' : 
                                       item.estado === 'pendiente' ? 'warning' : 'danger';
                    html += `
                        <div class="col-md-6 mb-3">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6>${item.titulo}</h6>
                                        <p class="text-muted small">
                                            <i class="fas fa-user"></i> ${item.autor_nombre} 
                                            | <i class="fas fa-tag"></i> ${item.tipo}
                                            | <i class="fas fa-calendar"></i> ${new Date(item.fecha_creacion).toLocaleDateString()}
                                        </p>
                                    </div>
                                    <span class="badge bg-${statusClass}">${item.estado}</span>
                                </div>
                                <p>${item.cuerpo.substring(0, 100)}...</p>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-success" onclick="approveContent(${item.id})">
                                        <i class="fas fa-check"></i> Aprobar
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectContent(${item.id})">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
            }
            document.getElementById('content-moderation-container').innerHTML = html;
        }

        // CRUD Functions for Gastronomy, Kichwa, Events, and Routes
        
        // Form submission handlers
        document.getElementById('formPlato').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm('plato', new FormData(this));
        });

        document.getElementById('formPalabra').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm('palabra', new FormData(this));
        });

        document.getElementById('formEvento').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm('evento', new FormData(this));
        });

        document.getElementById('formRuta').addEventListener('submit', function(e) {
            e.preventDefault();
            submitForm('ruta', new FormData(this));
        });

        function submitForm(type, formData) {
            const action = `create_${type}`;
            
            fetch('admin-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Guardado exitosamente!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo guardar'));
                }
            })
            .catch(error => {
                alert('Error de conexión: ' + error.message);
            });
        }

        // View functions
        function verPlato(id) {
            window.open(`../sabores.php#plato-${id}`, '_blank');
        }

        function verEvento(id) {
            alert('Vista detallada del evento (funcionalidad por implementar)');
        }

        function verRuta(id) {
            alert('Vista detallada de la ruta (funcionalidad por implementar)');
        }

        function verMapa(url) {
            window.open(url, '_blank');
        }

        // Edit functions
        function editarPlato(id) {
            // Load data and populate modal for editing
            fetch(`admin-handler.php?action=get_plato&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateForm('formPlato', data.plato);
                    document.getElementById('modalNuevoPlato').querySelector('.modal-title').innerHTML = '<i class="fas fa-utensils"></i> Editar Plato';
                    const modal = new bootstrap.Modal(document.getElementById('modalNuevoPlato'));
                    modal.show();
                }
            });
        }

        function editarPalabra(id) {
            fetch(`admin-handler.php?action=get_palabra&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateForm('formPalabra', data.palabra);
                    document.getElementById('modalNuevaPalabra').querySelector('.modal-title').innerHTML = '<i class="fas fa-language"></i> Editar Palabra';
                    const modal = new bootstrap.Modal(document.getElementById('modalNuevaPalabra'));
                    modal.show();
                }
            });
        }

        function editarEvento(id) {
            fetch(`admin-handler.php?action=get_evento&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateForm('formEvento', data.evento);
                    document.getElementById('modalNuevoEvento').querySelector('.modal-title').innerHTML = '<i class="fas fa-calendar"></i> Editar Evento';
                    const modal = new bootstrap.Modal(document.getElementById('modalNuevoEvento'));
                    modal.show();
                }
            });
        }

        function editarRuta(id) {
            fetch(`admin-handler.php?action=get_ruta&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateForm('formRuta', data.ruta);
                    document.getElementById('modalNuevaRuta').querySelector('.modal-title').innerHTML = '<i class="fas fa-route"></i> Editar Ruta';
                    const modal = new bootstrap.Modal(document.getElementById('modalNuevaRuta'));
                    modal.show();
                }
            });
        }

        // Delete functions
        function eliminarPlato(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este plato?')) {
                deleteItem('plato', id);
            }
        }

        function eliminarPalabra(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta palabra?')) {
                deleteItem('palabra', id);
            }
        }

        function eliminarEvento(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
                deleteItem('evento', id);
            }
        }

        function eliminarRuta(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta ruta?')) {
                deleteItem('ruta', id);
            }
        }

        function deleteItem(type, id) {
            fetch('admin-handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=delete_${type}&id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('¡Eliminado exitosamente!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo eliminar'));
                }
            });
        }

        // Helper function to populate form for editing
        function populateForm(formId, data) {
            const form = document.getElementById(formId);
            Object.keys(data).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'datetime-local') {
                        // Format datetime for input
                        const date = new Date(data[key]);
                        input.value = date.toISOString().slice(0, 16);
                    } else {
                        input.value = data[key] || '';
                    }
                }
            });
        }

        // Audio playback function
        function reproducirAudio(audioUrl) {
            const audio = new Audio(`../audio/${audioUrl}`);
            audio.play().catch(error => {
                alert('No se pudo reproducir el audio: ' + error.message);
            });
        }

        // Reset forms when modals are hidden
        document.getElementById('modalNuevoPlato').addEventListener('hidden.bs.modal', function() {
            document.getElementById('formPlato').reset();
            this.querySelector('.modal-title').innerHTML = '<i class="fas fa-utensils"></i> Nuevo Plato';
        });

        document.getElementById('modalNuevaPalabra').addEventListener('hidden.bs.modal', function() {
            document.getElementById('formPalabra').reset();
            this.querySelector('.modal-title').innerHTML = '<i class="fas fa-language"></i> Nueva Palabra Kichwa';
        });

        document.getElementById('modalNuevoEvento').addEventListener('hidden.bs.modal', function() {
            document.getElementById('formEvento').reset();
            this.querySelector('.modal-title').innerHTML = '<i class="fas fa-calendar"></i> Nuevo Evento';
        });

        document.getElementById('modalNuevaRuta').addEventListener('hidden.bs.modal', function() {
            document.getElementById('formRuta').reset();
            this.querySelector('.modal-title').innerHTML = '<i class="fas fa-route"></i> Nueva Ruta Turística';
        });
    </script>
</body>
</html>