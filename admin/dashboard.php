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
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
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
            background-color: #dc3545;
            color: white;
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
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-mountain"></i>
                <?php echo NOMBRE_PROYECTO; ?>
            </a>
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
                    <a href="#" class="nav-link dropdown-toggle text-white" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../index.php"><i class="fas fa-home"></i> <?php echo $texto['menu_inicio'] ?? 'Ir al sitio'; ?></a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i> <?php echo $texto['cerrar_sesion'] ?? 'Cerrar Sesión'; ?></a></li>
                    </ul>
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

                    <!-- Otras tabs pueden ir aquí -->
                    <div class="tab-pane fade" id="gastronomia">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-utensils"></i> <?php echo $texto['gestion_gastronomia'] ?? 'Gestión de Gastronomía'; ?></h2>
                        </div>
                        <p><?php echo $texto['funcion_desarrollo'] ?? 'Funcionalidad en desarrollo'; ?>...</p>
                    </div>

                    <div class="tab-pane fade" id="kichwa">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-language"></i> <?php echo $texto['gestion_kichwa'] ?? 'Gestión de Vocabulario Kichwa'; ?></h2>
                        </div>
                        <p><?php echo $texto['funcion_desarrollo'] ?? 'Funcionalidad en desarrollo'; ?>...</p>
                    </div>

                    <div class="tab-pane fade" id="eventos">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-calendar"></i> <?php echo $texto['gestion_eventos'] ?? 'Gestión de Eventos'; ?></h2>
                        </div>
                        <p><?php echo $texto['funcion_desarrollo'] ?? 'Funcionalidad en desarrollo'; ?>...</p>
                    </div>

                    <div class="tab-pane fade" id="rutas">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-route"></i> <?php echo $texto['gestion_rutas'] ?? 'Gestión de Rutas Turísticas'; ?></h2>
                        </div>
                        <p><?php echo $texto['funcion_desarrollo'] ?? 'Funcionalidad en desarrollo'; ?>...</p>
                    </div>
                </div>
            </main>
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
    </script>
</body>
</html>