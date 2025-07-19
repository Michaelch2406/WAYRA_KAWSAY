<?php
// Este archivo contiene el código HTML para el pie de página (footer)
// Incluye las variables de texto y el nombre del proyecto desde global.php
include_once dirname(__DIR__, 3) . '/config/global.php';
?>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h5><i class="fas fa-mountain"></i> <?php echo NOMBRE_PROYECTO; ?></h5>
                <p>Preservando y compartiendo la riqueza cultural de los Andes para las futuras generaciones.</p>
                <div class="social-links">
                    <a href="#" class="me-3" title="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="me-3" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="me-3" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="me-3" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
            <div class="footer-section">
                <h5>Navegación</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php"><?php echo $texto["menu_inicio"]; ?></a></li>
                    <li><a href="sabores.php"><?php echo $texto["menu_sabores"]; ?></a></li>
                    <li><a href="artesanias.php"><?php echo $texto["menu_artesanias"]; ?></a></li>
                    <li><a href="kichwa.php"><?php echo $texto["menu_kichwa"]; ?></a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>Cultura</h5>
                <ul class="list-unstyled">
                    <li><a href="cultura.php"><?php echo $texto["menu_cultura"]; ?></a></li>
                    <li><a href="ubicacion.php"><?php echo $texto["menu_ubicacion"]; ?></a></li>
                    <li><a href="#">Historia</a></li>
                    <li><a href="#">Festivales</a></li>
                    <li><a href="#">Tradiciones</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h5>Contacto</h5>
                <p><i class="fas fa-envelope"></i> info@culturaandina.com</p>
                <p><i class="fas fa-phone"></i> +593 (0)2 123-4567</p>
                <p><i class="fas fa-map-marker-alt"></i> Quito, Ecuador</p>
                <p><i class="fas fa-globe"></i> www.culturaandina.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>&copy; 2024 <?php echo NOMBRE_PROYECTO; ?>. <?php echo $texto["pie_pagina"]; ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-links">
                        <a href="#" class="me-3">Política de Privacidad</a>
                        <a href="#" class="me-3">Términos de Uso</a>
                        <a href="#">Contacto</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

