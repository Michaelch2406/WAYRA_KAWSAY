<?php
// Este archivo contiene el código HTML para la barra de navegación (navbar)
// Incluye las variables de texto y el nombre del proyecto desde global.php
include_once dirname(__DIR__, 3) . 
'/config/global.php';
?>

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
                    <a class="nav-link <?php echo (basename($_SERVER["PHP_SELF"]) == "index.php") ? "active" : ""; ?>" aria-current="page" href="index.php">
                        <i class="fas fa-home"></i>
                        <?php echo $texto["menu_inicio"]; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER["PHP_SELF"]) == "sabores.php") ? "active" : ""; ?>" href="sabores.php">
                        <i class="fas fa-utensils"></i>
                        <?php echo $texto["menu_sabores"]; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER["PHP_SELF"]) == "artesanias.php") ? "active" : ""; ?>" href="artesanias.php">
                        <i class="fas fa-palette"></i>
                        <?php echo $texto["menu_artesanias"]; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER["PHP_SELF"]) == "kichwa.php") ? "active" : ""; ?>" href="kichwa.php">
                        <i class="fas fa-language"></i>
                        <?php echo $texto["menu_kichwa"]; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER["PHP_SELF"]) == "cultura.php") ? "active" : ""; ?>" href="cultura.php">
                        <i class="fas fa-users"></i>
                        <?php echo $texto["menu_cultura"]; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER["PHP_SELF"]) == "ubicacion.php") ? "active" : ""; ?>" href="ubicacion.php">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo $texto["menu_ubicacion"]; ?>
                    </a>
                </li>
            </ul>
            <div class="d-flex">
                <select class="form-select" id="language-selector">
                    <option value="es" <?php if($lang_code == "es") echo "selected"; ?>>
                        🇪🇸 Español
                    </option>
                    <option value="qu" <?php if($lang_code == "qu") echo "selected"; ?>>
                        🏔️ Kichwa
                    </option>
                </select>
            </div>
        </div>
    </div>
</nav>


