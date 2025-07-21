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
    (SELECT COUNT(*) FROM palabras_kichwa) as total_palabras,
    (SELECT COUNT(*) FROM platos) as total_platos,
    (SELECT COUNT(*) FROM rutas_turisticas) as total_rutas";

$stmt_stats = $db->prepare($query_stats);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);

// Obtener contenido pendiente
$query_pendiente = "SELECT c.*, u.nombre as autor_nombre 
                    FROM contenido c 
                    INNER JOIN usuarios u ON c.id_usuario_autor = u.id 
                    WHERE c.estado = 'pendiente' 
                    ORDER BY c.fecha_creacion DESC 
                    LIMIT 5";
$stmt_pendiente = $db->prepare($query_pendiente);
$stmt_pendiente->execute();
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - <?php echo NOMBRE_PROYECTO; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../css/styles.css">
    
    <style>
        :root {
            --admin-primary: #721c24;
            --admin-secondary: #8b2635;
            --admin-accent: #a73d47;
            --admin-light: #fef8f8;
            --admin-success: #28a745;
            --admin-warning: #ffc107;
            --admin-danger: #dc3545;
            --admin-info: #17a2b8;
        }

        body {
            background-color: var(--admin-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-admin {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)) !important;
            box-shadow: 0 2px 15px rgba(114, 28, 36, 0.2);
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--admin-light), #f8e8e8);
            color: var(--admin-primary);
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-left: 5px solid var(--admin-primary);
            border-radius: 0 10px 10px 0;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid var(--admin-primary);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .admin-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-top: 3px solid var(--admin-primary);
        }

        .admin-card h5 {
            color: var(--admin-primary);
            border-bottom: 2px solid var(--admin-light);
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .btn-admin {
            background: linear-gradient(45deg, var(--admin-primary), var(--admin-secondary));
            border: none;
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(114, 28, 36, 0.3);
            color: white;
        }

        .btn-admin-outline {
            border: 2px solid var(--admin-primary);
            color: var(--admin-primary);
            background: transparent;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-admin-outline:hover {
            background: var(--admin-primary);
            color: white;
            transform: translateY(-2px);
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(45deg, var(--admin-primary), var(--admin-secondary));
            border-radius: 25px;
        }

        .nav-pills .nav-link {
            color: var(--admin-primary);
            border-radius: 25px;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link:hover {
            background-color: var(--admin-light);
            color: var(--admin-primary);
        }

        .table th {
            background: linear-gradient(45deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            border: none;
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 500;
        }

        .modal-header {
            background: linear-gradient(45deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .modal-header .btn-close {
            filter: invert(1);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--admin-accent);
            box-shadow: 0 0 0 0.25rem rgba(167, 61, 71, 0.25);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--admin-primary) !important;
            border-color: var(--admin-primary) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--admin-light) !important;
            border-color: var(--admin-primary) !important;
            color: var(--admin-primary) !important;
        }

        .file-upload-area {
            border: 2px dashed var(--admin-accent);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            background-color: var(--admin-light);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-area:hover {
            background-color: #f0f0f0;
            border-color: var(--admin-primary);
        }

        .file-upload-area.dragover {
            background-color: #e8f5e8;
            border-color: var(--admin-success);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--admin-primary);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert-dismissible .btn-close {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
        }

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1rem 0;
                margin-bottom: 1rem;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .admin-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="loading-spinner"></div>
            <p class="mt-3 text-white">Cargando...</p>
        </div>
    </div>

    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-admin fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-shield-alt"></i> Panel Administrativo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="fas fa-home"></i> Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../logout.php">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid" style="margin-top: 80px;">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <h1><i class="fas fa-tachometer-alt"></i> Panel de Administración</h1>
                        <p class="lead mb-0">Gestiona todos los aspectos del sistema Wayra Kawsay</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="badge bg-success fs-6">
                            <i class="fas fa-clock"></i> <?php echo date('d/m/Y H:i'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="container mb-4">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users stat-icon text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-bold fs-4"><?php echo $stats['total_usuarios']; ?></div>
                                <div class="text-muted">Total Usuarios</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-language stat-icon text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-bold fs-4"><?php echo $stats['total_palabras']; ?></div>
                                <div class="text-muted">Palabras Kichwa</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-utensils stat-icon text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-bold fs-4"><?php echo $stats['total_platos']; ?></div>
                                <div class="text-muted">Platos Gastronómicos</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-alt stat-icon text-danger"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="fw-bold fs-4"><?php echo $stats['total_eventos']; ?></div>
                                <div class="text-muted">Eventos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="container">
            <!-- Nav pills para las secciones -->
            <ul class="nav nav-pills mb-4" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="users-tab" data-bs-toggle="pill" data-bs-target="#users" type="button">
                        <i class="fas fa-users"></i> Usuarios
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="gastronomia-tab" data-bs-toggle="pill" data-bs-target="#gastronomia" type="button">
                        <i class="fas fa-utensils"></i> Gastronomía
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="kichwa-tab" data-bs-toggle="pill" data-bs-target="#kichwa" type="button">
                        <i class="fas fa-language"></i> Vocabulario Kichwa
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="eventos-tab" data-bs-toggle="pill" data-bs-target="#eventos" type="button">
                        <i class="fas fa-calendar-alt"></i> Eventos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rutas-tab" data-bs-toggle="pill" data-bs-target="#rutas" type="button">
                        <i class="fas fa-route"></i> Rutas Turísticas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="content-tab" data-bs-toggle="pill" data-bs-target="#content" type="button">
                        <i class="fas fa-file-alt"></i> Contenido
                        <?php if ($stats['contenido_pendiente'] > 0): ?>
                            <span class="badge bg-danger ms-1"><?php echo $stats['contenido_pendiente']; ?></span>
                        <?php endif; ?>
                    </button>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="adminTabContent">
                <!-- Gestión de Usuarios -->
                <div class="tab-pane fade show active" id="users" role="tabpanel">
                    <div class="admin-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-users"></i> Gestión de Usuarios</h5>
                            <button class="btn btn-admin" onclick="crearUsuario()">
                                <i class="fas fa-plus"></i> Nuevo Usuario
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="usersTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Rol</th>
                                        <th>Estado</th>
                                        <th>Fecha Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Gestión de Gastronomía -->
                <div class="tab-pane fade" id="gastronomia" role="tabpanel">
                    <div class="admin-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-utensils"></i> Gestión de Gastronomía</h5>
                            <button class="btn btn-admin" onclick="crearPlato()">
                                <i class="fas fa-plus"></i> Nuevo Plato
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="platosTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Dificultad</th>
                                        <th>Tiempo (min)</th>
                                        <th>Imagen</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Gestión de Vocabulario Kichwa -->
                <div class="tab-pane fade" id="kichwa" role="tabpanel">
                    <div class="admin-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-language"></i> Gestión de Vocabulario Kichwa</h5>
                            <button class="btn btn-admin" onclick="crearPalabra()">
                                <i class="fas fa-plus"></i> Nueva Palabra
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="palabrasTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kichwa</th>
                                        <th>Español</th>
                                        <th>Categoría</th>
                                        <th>Audio</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Gestión de Eventos -->
                <div class="tab-pane fade" id="eventos" role="tabpanel">
                    <div class="admin-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-calendar-alt"></i> Gestión de Eventos</h5>
                            <button class="btn btn-admin" onclick="crearEvento()">
                                <i class="fas fa-plus"></i> Nuevo Evento
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="eventosTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Ubicación</th>
                                        <th>Creador</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Gestión de Rutas Turísticas -->
                <div class="tab-pane fade" id="rutas" role="tabpanel">
                    <div class="admin-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5><i class="fas fa-route"></i> Gestión de Rutas Turísticas</h5>
                            <button class="btn btn-admin" onclick="crearRuta()">
                                <i class="fas fa-plus"></i> Nueva Ruta
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="rutasTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Distancia (km)</th>
                                        <th>Dificultad</th>
                                        <th>Mapa</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Gestión de Contenido -->
                <div class="tab-pane fade" id="content" role="tabpanel">
                    <div class="admin-card">
                        <h5><i class="fas fa-file-alt"></i> Contenido Pendiente de Aprobación</h5>
                        <?php if ($stmt_pendiente->rowCount() > 0): ?>
                            <div class="row">
                                <?php while ($content = $stmt_pendiente->fetch(PDO::FETCH_ASSOC)): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong><?php echo htmlspecialchars($content['titulo']); ?></strong>
                                                <span class="badge bg-warning ms-2"><?php echo ucfirst($content['tipo']); ?></span>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"><?php echo substr(htmlspecialchars($content['cuerpo']), 0, 150) . '...'; ?></p>
                                                <small class="text-muted">Por: <?php echo htmlspecialchars($content['autor_nombre']); ?></small>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-success btn-sm" onclick="approveContent(<?php echo $content['id']; ?>)">
                                                    <i class="fas fa-check"></i> Aprobar
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="rejectContent(<?php echo $content['id']; ?>)">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No hay contenido pendiente de aprobación.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'modals.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <script>
        // Global variables
        let usersTable, platosTable, palabrasTable, eventosTable, rutasTable;

        // Initialize when document is ready
        $(document).ready(function() {
            // Initialize DataTables
            initializeDataTables();
            
            // Load initial data
            loadUsers();
            
            // Tab change handler
            $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
                const target = $(e.target).attr('data-bs-target');
                switch(target) {
                    case '#users':
                        if (!usersTable) loadUsers();
                        break;
                    case '#gastronomia':
                        if (!platosTable) loadPlatos();
                        break;
                    case '#kichwa':
                        if (!palabrasTable) loadPalabras();
                        break;
                    case '#eventos':
                        if (!eventosTable) loadEventos();
                        break;
                    case '#rutas':
                        if (!rutasTable) loadRutas();
                        break;
                }
            });
        });

        // Initialize DataTables
        function initializeDataTables() {
            const commonOptions = {
                responsive: true,
                pageLength: 10,
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-info btn-sm'
                    }
                ]
            };

            // Users Table
            usersTable = $('#usersTable').DataTable({
                ...commonOptions,
                columns: [
                    { data: 'id' },
                    { data: 'nombre' },
                    { data: 'email' },
                    { data: 'telefono' },
                    { 
                        data: 'rol',
                        render: function(data) {
                            const colors = { admin: 'danger', artesano: 'warning', comunitario: 'info' };
                            return `<span class="badge bg-${colors[data]}">${data}</span>`;
                        }
                    },
                    { 
                        data: 'activo',
                        render: function(data) {
                            return data ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>';
                        }
                    },
                    { data: 'fecha_registro' },
                    { 
                        data: null,
                        render: function(data) {
                            return `
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editarUsuario(${data.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-${data.activo ? 'warning' : 'success'}" onclick="toggleUsuario(${data.id}, ${!data.activo})">
                                        <i class="fas fa-${data.activo ? 'pause' : 'play'}"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarUsuario(${data.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });

            // Other tables initialization will be similar...
        }

        // Load functions
        function loadUsers() {
            showLoading();
            $.get('admin-handler.php?action=get_all_users')
                .done(function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        usersTable.clear().rows.add(data.users).draw();
                    } else {
                        showAlert('Error al cargar usuarios: ' + data.message, 'danger');
                    }
                })
                .fail(function() {
                    showAlert('Error de conexión al cargar usuarios', 'danger');
                })
                .always(function() {
                    hideLoading();
                });
        }

        // User management functions
        function openUserModal(id = null) {
            if (id) {
                // Edit mode
                $('#userModalTitle').text('Editar Usuario');
                $('#userId').val(id);
                loadUserData(id);
            } else {
                // Create mode
                $('#userModalTitle').text('Nuevo Usuario');
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#passwordGroup').show();
                $('#password').prop('required', true);
            }
        }

        function editUser(id) {
            openUserModal(id);
            $('#userModal').modal('show');
        }

        function loadUserData(id) {
            $.get('admin-handler.php?action=get_user&id=' + id)
                .done(function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        const user = data.user;
                        $('#nombre').val(user.nombre);
                        $('#email').val(user.email);
                        $('#telefono').val(user.telefono);
                        $('#rol').val(user.rol);
                        $('#passwordGroup').hide();
                        $('#password').prop('required', false);
                    }
                });
        }

        function saveUser() {
            const form = $('#userForm')[0];
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            const action = $('#userId').val() ? 'update_user' : 'create_user';
            formData.append('action', action);

            showLoading();
            $.ajax({
                url: 'admin-handler.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        $('#userModal').modal('hide');
                        showAlert(data.message, 'success');
                        loadUsers();
                    } else {
                        showAlert(data.message, 'danger');
                    }
                },
                error: function() {
                    showAlert('Error de conexión', 'danger');
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        function deleteUser(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
                showLoading();
                $.post('admin-handler.php', {
                    action: 'delete_user',
                    user_id: id
                })
                .done(function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        showAlert(data.message, 'success');
                        loadUsers();
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .fail(function() {
                    showAlert('Error de conexión', 'danger');
                })
                .always(function() {
                    hideLoading();
                });
            }
        }

        function toggleUser(id, status) {
            const action = status ? 'activar' : 'desactivar';
            if (confirm(`¿Estás seguro de que quieres ${action} este usuario?`)) {
                showLoading();
                $.post('admin-handler.php', {
                    action: 'toggle_user',
                    user_id: id,
                    status: status ? 1 : 0
                })
                .done(function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        showAlert(data.message, 'success');
                        loadUsers();
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .fail(function() {
                    showAlert('Error de conexión', 'danger');
                })
                .always(function() {
                    hideLoading();
                });
            }
        }

        // Utility functions
        function showLoading() {
            $('#loadingOverlay').show();
        }

        function hideLoading() {
            $('#loadingOverlay').hide();
        }

        function showAlert(message, type) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'}"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('#alertContainer').html(alertHtml);
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        }

        // File upload functions
        function setupFileUpload(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    uploadFile(file, function(response) {
                        if (response.success) {
                            if (response.type === 'image') {
                                preview.innerHTML = `<img src="${response.url}" alt="Preview" style="max-width: 200px; max-height: 150px;">`;
                            } else {
                                preview.innerHTML = `<i class="fas fa-file-audio"></i> ${response.filename}`;
                            }
                            // Store the path for form submission
                            $(input).data('uploaded-path', response.path);
                        } else {
                            showAlert('Error al subir archivo: ' + response.message, 'danger');
                        }
                    });
                }
            });
        }

        function uploadFile(file, callback) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('action', 'upload_file');

            showLoading();
            $.ajax({
                url: 'admin-handler.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const data = JSON.parse(response);
                    callback(data);
                },
                error: function() {
                    callback({success: false, message: 'Error de conexión'});
                },
                complete: function() {
                    hideLoading();
                }
            });
        }

        // Content management functions
        function approveContent(id) {
            if (confirm('¿Aprobar este contenido?')) {
                $.post('admin-handler.php', {
                    action: 'approve_content',
                    content_id: id
                })
                .done(function(response) {
                    const data = JSON.parse(response);
                    showAlert(data.message, data.success ? 'success' : 'danger');
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        function rejectContent(id) {
            if (confirm('¿Rechazar este contenido?')) {
                $.post('admin-handler.php', {
                    action: 'reject_content',
                    content_id: id
                })
                .done(function(response) {
                    const data = JSON.parse(response);
                    showAlert(data.message, data.success ? 'success' : 'danger');
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        // Funciones CRUD para Usuarios
        function editarUsuario(id) {
            $.get('admin-handler.php', {action: 'get_user', id: id}, function(data) {
                if(data.success) {
                    const user = data.user;
                    $('#modalUsuarioTitle').text('Editar Usuario');
                    $('#usuario_id').val(user.id);
                    $('#usuario_nombre').val(user.nombre);
                    $('#usuario_email').val(user.email);
                    $('#usuario_telefono').val(user.telefono);
                    $('#usuario_rol').val(user.rol);
                    $('#usuario_password').val(''); // Vacío para edición
                    
                    if(user.rol === 'artesano') {
                        $('#campos_artesano').show();
                        $('#artesano_historia').val(user.historia || '');
                        $('#artesano_especialidad').val(user.especialidad || '');
                        $('#artesano_telefono').val(user.telefono_contacto || '');
                    }
                    
                    $('#modalUsuario').modal('show');
                }
            }, 'json');
        }

        function crearUsuario() {
            $('#modalUsuarioTitle').text('Crear Usuario');
            $('#formUsuario')[0].reset();
            $('#usuario_id').val('');
            $('#campos_artesano').hide();
            $('#modalUsuario').modal('show');
        }

        function guardarUsuario() {
            const formData = new FormData($('#formUsuario')[0]);
            const isEdit = $('#usuario_id').val() !== '';
            formData.append('action', isEdit ? 'update_user' : 'create_user');
            
            $.ajax({
                url: 'admin-handler.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        $('#modalUsuario').modal('hide');
                        tablaUsuarios.ajax.reload();
                        showAlert('success', isEdit ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente');
                    } else {
                        showAlert('error', response.message);
                    }
                },
                dataType: 'json'
            });
        }

        function eliminarUsuario(id) {
            if (confirm('¿Estás seguro de eliminar este usuario?')) {
                $.post('admin-handler.php', {action: 'delete_user', user_id: id})
                .done(function(response) {
                    const data = JSON.parse(response);
                    showAlert(data.success ? 'success' : 'danger', data.message);
                    if (data.success) loadUsers();
                });
            }
        }

        function toggleUsuario(id, status) {
            $.post('admin-handler.php', {action: 'toggle_user', user_id: id, status: status ? 1 : 0})
            .done(function(response) {
                const data = JSON.parse(response);
                showAlert(data.success ? 'success' : 'danger', data.message);
                if (data.success) loadUsers();
            });
        }

        // Funciones para otras entidades (implementación básica)
        function loadPlatos() {
            showLoading();
            // TODO: Implementar carga de platos
            hideLoading();
        }

        function loadPalabras() {
            showLoading();
            // TODO: Implementar carga de palabras
            hideLoading();
        }

        function loadEventos() {
            showLoading();
            // TODO: Implementar carga de eventos
            hideLoading();
        }

        function loadRutas() {
            showLoading();
            // TODO: Implementar carga de rutas
            hideLoading();
        }

        // Funciones CRUD para Platos
        function crearPlato() {
            $('#modalPlatoTitle').text('Crear Plato');
            $('#formPlato')[0].reset();
            $('#plato_id').val('');
            $('#imagen_actual').empty();
            $('#modalPlato').modal('show');
        }

        function editarPlato(id) {
            // TODO: Implementar
        }

        function guardarPlato() {
            // TODO: Implementar
        }

        // Funciones CRUD para Palabras
        function crearPalabra() {
            $('#modalPalabraTitle').text('Crear Palabra');
            $('#formPalabra')[0].reset();
            $('#palabra_id').val('');
            $('#audio_actual').empty();
            $('#modalPalabra').modal('show');
        }

        function editarPalabra(id) {
            // TODO: Implementar
        }

        function guardarPalabra() {
            // TODO: Implementar
        }

        // Funciones CRUD para Eventos
        function crearEvento() {
            $('#modalEventoTitle').text('Crear Evento');
            $('#formEvento')[0].reset();
            $('#evento_id').val('');
            $('#imagen_evento_actual').empty();
            $('#modalEvento').modal('show');
        }

        function editarEvento(id) {
            // TODO: Implementar
        }

        function guardarEvento() {
            // TODO: Implementar
        }

        // Funciones CRUD para Rutas
        function crearRuta() {
            $('#modalRutaTitle').text('Crear Ruta');
            $('#formRuta')[0].reset();
            $('#ruta_id').val('');
            $('#imagen_ruta_actual').empty();
            $('#modalRuta').modal('show');
        }

        function editarRuta(id) {
            // TODO: Implementar
        }

        function guardarRuta() {
            // TODO: Implementar
        }

        // Funciones de vista detallada
        function verDetalleEvento(id) {
            // TODO: Implementar
        }

        function verDetalleRuta(id) {
            // TODO: Implementar
        }
    </script>
    
    <!-- Alert Container -->
    <div id="alertContainer" style="position: fixed; top: 100px; right: 20px; z-index: 9999; max-width: 400px;"></div>
</body>
</html>