<?php
include_once 'config/global.php';
?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['titulo_registro']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">

    <style>
        .auth-container {
            max-width: 500px;
            margin: 5rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .auth-title {
            color: var(--color-principal);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-label {
            color: var(--color-texto);
        }
        .btn-primary {
            background-color: var(--color-principal);
            border-color: var(--color-principal);
            width: 100%;
        }
        .btn-primary:hover {
            background-color: var(--color-acento);
            border-color: var(--color-acento);
        }
    </style>
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
                            <i class="fas fa-home"></i> <?php echo $texto['menu_inicio']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sabores.php">
                            <i class="fas fa-utensils"></i> <?php echo $texto['menu_sabores']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artesanias.php">
                            <i class="fas fa-palette"></i> <?php echo $texto['menu_artesanias']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kichwa.php">
                            <i class="fas fa-language"></i> <?php echo $texto['menu_kichwa']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cultura.php">
                            <i class="fas fa-users"></i> <?php echo $texto['menu_cultura']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ubicacion.php">
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
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <div class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="logout.php"><?php echo $texto['cerrar_sesion']; ?></a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2"><?php echo $texto['iniciar_sesion']; ?></a>
                        <a href="registro.php" class="btn btn-primary"><?php echo $texto['registrar']; ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="auth-container">
            <h2 class="auth-title">
                <i class="fas fa-user-plus"></i>
                <?php echo $texto['crear_cuenta']; ?>
            </h2>
            
            <!-- Alert para mensajes -->
            <div id="alert-container"></div>
            
            <form id="registroForm">
                <div class="mb-3">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-user"></i>
                        <?php echo $texto['nombre_usuario']; ?>
                    </label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        <?php echo $texto['email_usuario']; ?>
                    </label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="telefono" class="form-label">
                        <i class="fas fa-phone"></i>
                        Teléfono (opcional)
                    </label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="+593 999 999 999">
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        <?php echo $texto['contrasena_usuario']; ?>
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="form-text">La contraseña debe tener al menos 6 caracteres</div>
                </div>
                
                <div class="mb-3">
                    <label for="rol" class="form-label">
                        <i class="fas fa-users"></i>
                        Tipo de cuenta
                    </label>
                    <select class="form-select" id="rol" name="rol" required>
                        <option value="comunitario">Miembro de la Comunidad</option>
                        <option value="artesano">Artesano</option>
                    </select>
                    <div class="form-text">
                        <small>
                            <strong>Miembro de la Comunidad:</strong> Puede crear y gestionar contenido comunitario.<br>
                            <strong>Artesano:</strong> Puede gestionar productos y perfil artesanal.
                        </small>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" id="btnRegistrar">
                    <i class="fas fa-user-plus"></i>
                    <span><?php echo $texto['registrar']; ?></span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
            
            <div class="text-center mt-3">
                <p><?php echo $texto['ya_tienes_cuenta']; ?> <a href="login.php" class="text-decoration-none"><?php echo $texto['iniciar_sesion']; ?></a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h5><i class="fas fa-mountain"></i> <?php echo NOMBRE_PROYECTO; ?></h5>
                    <p>Preservando y compartiendo la riqueza cultural de los Andes para las futuras generaciones.</p>
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
                    <h5>Cultura</h5>
                    <ul class="list-unstyled">
                        <li><a href="cultura.php">Tradiciones</a></li>
                        <li><a href="ubicacion.php">Ubicación</a></li>
                        <li><a href="#">Historia</a></li>
                        <li><a href="#">Festivales</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-envelope"></i> info@culturaandina.com</p>
                    <p><i class="fas fa-phone"></i> +593 (0)2 123-4567</p>
                    <p><i class="fas fa-map-marker-alt"></i> Quito, Ecuador</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 <?php echo NOMBRE_PROYECTO; ?>. <?php echo $texto['pie_pagina']; ?></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/ultra-translation-system.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registroForm = document.getElementById('registroForm');
            const btnRegistrar = document.getElementById('btnRegistrar');
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            // Toggle password visibility
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });

            // Handle form submission
            registroForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const spinner = btnRegistrar.querySelector('.spinner-border');
                const buttonText = btnRegistrar.querySelector('span:first-of-type');
                
                // Show loading state
                spinner.classList.remove('d-none');
                btnRegistrar.disabled = true;
                buttonText.textContent = 'Registrando...';
                
                fetch('controllers/AuthController.php?action=registro', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => {
                            window.location.href = data.redirect || 'login.php';
                        }, 2000);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Error al procesar la solicitud');
                })
                .finally(() => {
                    // Hide loading state
                    spinner.classList.add('d-none');
                    btnRegistrar.disabled = false;
                    buttonText.textContent = '<?php echo $texto['registrar']; ?>';
                });
            });

            function showAlert(type, message) {
                const alertContainer = document.getElementById('alert-container');
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show`;
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                alertContainer.innerHTML = '';
                alertContainer.appendChild(alert);
                
                // Auto-hide success alerts
                if (type === 'success') {
                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                }
            }
        });
    </script>
</body>
</html>
