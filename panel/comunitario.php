<?php
session_start();
include_once '../config/global.php';
include_once '../config/Conexion.php';
include_once '../models/Usuario.php';

// Verificar que el usuario esté logueado y tenga rol comunitario
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'comunitario' && $_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$database = new Conexion();
$db = $database->getConnection();

// Obtener contenido del usuario
$query_contenido = "SELECT * FROM contenido WHERE id_usuario_autor = :user_id ORDER BY fecha_creacion DESC";
$stmt_contenido = $db->prepare($query_contenido);
$stmt_contenido->bindParam(':user_id', $_SESSION['usuario_id']);
$stmt_contenido->execute();

// Obtener estadísticas
$query_stats = "SELECT 
    COUNT(*) as total_contenido,
    SUM(CASE WHEN estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estado = 'rechazado' THEN 1 ELSE 0 END) as rechazados
    FROM contenido WHERE id_usuario_autor = :user_id";
$stmt_stats = $db->prepare($query_stats);
$stmt_stats->bindParam(':user_id', $_SESSION['usuario_id']);
$stmt_stats->execute();
$stats = $stmt_stats->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Comunitario - <?php echo NOMBRE_PROYECTO; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/styles.css">
    
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #f0f8f0, #e8f5e8);
            color: #2d5a3d;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-left: 4px solid #2e8b57;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
            border-left: 4px solid;
        }
        .content-card.aprobado { border-left-color: #28a745; }
        .content-card.pendiente { border-left-color: #ffc107; }
        .content-card.rechazado { border-left-color: #dc3545; }
        .sidebar {
            background: #f8f9fa;
            min-height: calc(100vh - 76px);
            padding: 1rem;
        }
        .nav-pills .nav-link {
            color: #495057;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        .nav-pills .nav-link.active {
            background-color: #e8f5e8;
            color: #2e8b57;
            border: 1px solid #2e8b57;
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
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="comunitario.php"><i class="fas fa-tachometer-alt"></i> Mi Panel</a></li>
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
                            <a class="nav-link" href="#contenido" data-bs-toggle="pill">
                                <i class="fas fa-file-alt"></i> Mi Contenido
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#nuevo-contenido" data-bs-toggle="pill">
                                <i class="fas fa-plus"></i> Nuevo Contenido
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#perfil" data-bs-toggle="pill">
                                <i class="fas fa-user-edit"></i> Mi Perfil
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
                                <h1><i class="fas fa-chart-bar"></i> Panel Comunitario</h1>
                                <p class="lead">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>. Gestiona tu contenido comunitario desde aquí.</p>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-primary">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h3><?php echo $stats['total_contenido'] ?? 0; ?></h3>
                                    <p class="text-muted">Total Contenido</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-success">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h3><?php echo $stats['aprobados'] ?? 0; ?></h3>
                                    <p class="text-muted">Aprobados</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h3><?php echo $stats['pendientes'] ?? 0; ?></h3>
                                    <p class="text-muted">Pendientes</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="stat-card text-center">
                                    <div class="stat-icon text-danger">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <h3><?php echo $stats['rechazados'] ?? 0; ?></h3>
                                    <p class="text-muted">Rechazados</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="stat-card">
                                    <h4><i class="fas fa-bolt"></i> Acciones Rápidas</h4>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <button class="btn btn-primary w-100 mb-2" onclick="showTab('nuevo-contenido')">
                                                <i class="fas fa-plus"></i> Crear Noticia
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-success w-100 mb-2" onclick="showTab('nuevo-contenido')">
                                                <i class="fas fa-book"></i> Compartir Historia
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-info w-100 mb-2" onclick="showTab('nuevo-contenido')">
                                                <i class="fas fa-calendar"></i> Crear Evento
                                            </button>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-warning w-100 mb-2" onclick="showTab('contenido')">
                                                <i class="fas fa-list"></i> Ver Mi Contenido
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mi Contenido Tab -->
                    <div class="tab-pane fade" id="contenido">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-file-alt"></i> Mi Contenido</h2>
                            <button class="btn btn-primary" onclick="showTab('nuevo-contenido')">
                                <i class="fas fa-plus"></i> Nuevo Contenido
                            </button>
                        </div>

                        <div class="row">
                            <?php while ($contenido = $stmt_contenido->fetch(PDO::FETCH_ASSOC)): ?>
                            <div class="col-md-6">
                                <div class="content-card <?php echo $contenido['estado']; ?>">
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
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewContent(<?php echo $contenido['id']; ?>)">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteContent(<?php echo $contenido['id']; ?>)">
                                            <i class="fas fa-trash"></i> Eliminar
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
                            <h2><i class="fas fa-plus"></i> Crear Nuevo Contenido</h2>
                        </div>

                        <div class="stat-card">
                            <div id="content-alert"></div>
                            
                            <form id="contentForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tipo" class="form-label">
                                                <i class="fas fa-tag"></i> Tipo de Contenido
                                            </label>
                                            <select class="form-select" id="tipo" name="tipo" required>
                                                <option value="">Selecciona un tipo</option>
                                                <option value="noticia">Noticia/Anuncio</option>
                                                <option value="historia">Historia</option>
                                                <option value="leyenda">Leyenda</option>
                                                <option value="tradicion">Tradición</option>
                                                <option value="evento">Evento</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="titulo" class="form-label">
                                                <i class="fas fa-heading"></i> Título
                                            </label>
                                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="cuerpo" class="form-label">
                                        <i class="fas fa-file-alt"></i> Contenido
                                    </label>
                                    <textarea class="form-control" id="cuerpo" name="cuerpo" rows="6" required 
                                              placeholder="Escribe aquí el contenido de tu publicación..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="url_multimedia" class="form-label">
                                        <i class="fas fa-image"></i> URL de Imagen/Video (opcional)
                                    </label>
                                    <input type="url" class="form-control" id="url_multimedia" name="url_multimedia" 
                                           placeholder="https://ejemplo.com/imagen.jpg">
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Nota:</strong> Tu contenido será revisado por un administrador antes de ser publicado.
                                </div>

                                <button type="submit" class="btn btn-primary" id="btnSubmitContent">
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Enviar para Revisión</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Perfil Tab -->
                    <div class="tab-pane fade" id="perfil">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h2><i class="fas fa-user-edit"></i> Mi Perfil</h2>
                        </div>

                        <div class="stat-card">
                            <div id="profile-alert"></div>
                            
                            <form id="profileForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">
                                                <i class="fas fa-user"></i> Nombre
                                            </label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                                   value="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                <i class="fas fa-envelope"></i> Email
                                            </label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?php echo htmlspecialchars($_SESSION['usuario_email']); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label">
                                        <i class="fas fa-phone"></i> Teléfono
                                    </label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono">
                                </div>

                                <hr>

                                <h5><i class="fas fa-lock"></i> Cambiar Contraseña</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary" id="btnUpdateProfile">
                                    <i class="fas fa-save"></i>
                                    <span>Actualizar Perfil</span>
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
    <script>
        function showTab(tabId) {
            // Remove active class from all tabs
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
            
            // Add active class to selected tab
            document.querySelector(`[href="#${tabId}"]`).classList.add('active');
            document.getElementById(tabId).classList.add('show', 'active');
        }

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

        // Handle profile form submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword && newPassword !== confirmPassword) {
                showAlert('profile-alert', 'danger', 'Las contraseñas no coinciden');
                return;
            }
            
            const formData = new FormData(this);
            const btn = document.getElementById('btnUpdateProfile');
            const spinner = btn.querySelector('.spinner-border');
            
            spinner.classList.remove('d-none');
            btn.disabled = true;
            
            fetch('profile-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('profile-alert', 'success', data.message);
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

        function showAlert(containerId, type, message) {
            const container = document.getElementById(containerId);
            container.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }

        function editContent(id) {
            fetch(`content-handler.php?action=get_content&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const content = data.content;
                        
                        // Create modal for editing
                        const modalHtml = `
                            <div class="modal fade" id="editContentModal" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Contenido</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editContentForm">
                                                <input type="hidden" id="edit_content_id" value="${content.id}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tipo de Contenido</label>
                                                            <select class="form-select" id="edit_tipo" name="tipo" required>
                                                                <option value="noticia" ${content.tipo === 'noticia' ? 'selected' : ''}>Noticia/Anuncio</option>
                                                                <option value="historia" ${content.tipo === 'historia' ? 'selected' : ''}>Historia</option>
                                                                <option value="leyenda" ${content.tipo === 'leyenda' ? 'selected' : ''}>Leyenda</option>
                                                                <option value="tradicion" ${content.tipo === 'tradicion' ? 'selected' : ''}>Tradición</option>
                                                                <option value="evento" ${content.tipo === 'evento' ? 'selected' : ''}>Evento</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Título</label>
                                                            <input type="text" class="form-control" id="edit_titulo" name="titulo" value="${content.titulo}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Contenido</label>
                                                    <textarea class="form-control" id="edit_cuerpo" name="cuerpo" rows="6" required>${content.cuerpo}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">URL de Multimedia (opcional)</label>
                                                    <input type="url" class="form-control" id="edit_url_multimedia" name="url_multimedia" value="${content.url_multimedia || ''}">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" onclick="updateContent()">Guardar Cambios</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Remove existing modal if any
                        const existingModal = document.getElementById('editContentModal');
                        if (existingModal) existingModal.remove();
                        
                        // Add modal to page
                        document.body.insertAdjacentHTML('beforeend', modalHtml);
                        
                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('editContentModal'));
                        modal.show();
                    } else {
                        alert('Error al cargar el contenido: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error al cargar el contenido');
                });
        }

        function updateContent() {
            const form = document.getElementById('editContentForm');
            const formData = new FormData(form);
            formData.append('action', 'update_content');
            formData.append('content_id', document.getElementById('edit_content_id').value);
            
            fetch('content-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Contenido actualizado exitosamente');
                    bootstrap.Modal.getInstance(document.getElementById('editContentModal')).hide();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error al actualizar el contenido');
            });
        }

        function viewContent(id) {
            fetch(`content-handler.php?action=get_content&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const content = data.content;
                        
                        // Create modal for viewing
                        const modalHtml = `
                            <div class="modal fade" id="viewContentModal" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Ver Contenido</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <strong>Tipo:</strong> <span class="badge bg-primary">${content.tipo}</span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Título:</strong> ${content.titulo}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Estado:</strong> <span class="badge bg-${content.estado === 'aprobado' ? 'success' : content.estado === 'pendiente' ? 'warning' : 'danger'}">${content.estado}</span>
                                            </div>
                                            <div class="mb-3">
                                                <strong>Contenido:</strong>
                                                <div class="border p-3 mt-2">${content.cuerpo}</div>
                                            </div>
                                            ${content.url_multimedia ? `
                                                <div class="mb-3">
                                                    <strong>Multimedia:</strong>
                                                    <div class="mt-2">
                                                        <a href="${content.url_multimedia}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-external-link-alt"></i> Ver enlace
                                                        </a>
                                                    </div>
                                                </div>
                                            ` : ''}
                                            <div class="mb-3">
                                                <strong>Fecha de creación:</strong> ${new Date(content.fecha_creacion).toLocaleDateString()}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-primary" onclick="editContent(${content.id})" data-bs-dismiss="modal">Editar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Remove existing modal if any
                        const existingModal = document.getElementById('viewContentModal');
                        if (existingModal) existingModal.remove();
                        
                        // Add modal to page
                        document.body.insertAdjacentHTML('beforeend', modalHtml);
                        
                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('viewContentModal'));
                        modal.show();
                    } else {
                        alert('Error al cargar el contenido: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error al cargar el contenido');
                });
        }

        function deleteContent(id) {
            if (confirm('¿Estás seguro de que deseas eliminar este contenido?')) {
                const formData = new FormData();
                formData.append('action', 'delete_content');
                formData.append('content_id', id);
                
                fetch('content-handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Contenido eliminado exitosamente');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error al eliminar el contenido');
                });
            }
        }
    </script>
</body>
</html>