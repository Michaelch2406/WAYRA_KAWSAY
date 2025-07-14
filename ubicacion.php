<?php include_once 'config/global.php'; ?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_ubicacion']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><?php echo NOMBRE_PROYECTO; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><?php echo $texto['menu_inicio']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sabores.php"><?php echo $texto['menu_sabores']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artesanias.php"><?php echo $texto['menu_artesanias']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kichwa.php"><?php echo $texto['menu_kichwa']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cultura.php"><?php echo $texto['menu_cultura']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="ubicacion.php"><?php echo $texto['menu_ubicacion']; ?></a>
                    </li>
                </ul>
                <div class="d-flex">
                    <select class="form-select" id="language-selector">
                        <option value="es" <?php if($lang_code == 'es') echo 'selected'; ?>>Español</option>
                        <option value="qu" <?php if($lang_code == 'qu') echo 'selected'; ?>>Kichwa</option>
                    </select>
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        <h1><?php echo $texto['menu_ubicacion']; ?></h1>

        <div class="row">
            <div class="col-12">
                <h2>Mapa Principal</h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.789355176246!2d-78.1455556888881!3d0.3282999999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e2a3b3b3b3b3b3b%3A0x8e2a3b3b3b3b3b3b!2sNaranjito%2C%20Caranqui%2C%20Ecuador!5e0!3m2!1ses!2ses!4v1678886400000!5m2!1ses!2ses" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h2>Puntos de Interés</h2>
                <ul>
                    <li><a href="https://maps.app.goo.gl/wpDktEnWtyxM2XqR8" target="_blank">Punto de Interés 1</a></li>
                    <li><a href="https://maps.app.goo.gl/zmWYeZJKANBPQTAG8" target="_blank">Punto de Interés 2</a></li>
                    <li><a href="https://maps.app.goo.gl/t37xMndLDtqnMYtK9" target="_blank">Punto de Interés 3</a></li>
                    <li><a href="https://maps.app.goo.gl/nWd3nHpLp56f1acF6" target="_blank">Punto de Interés 4</a></li>
                </ul>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h2>Clima en Naranjito</h2>
                <div id="weather-info"></div>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted"><?php echo $texto['pie_pagina']; ?></span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const apiKey = 'TU_API_KEY'; // REEMPLAZA ESTO CON TU API KEY DE OPENWEATHERMAP
            const lat = 0.3283; // Latitud de Naranjito
            const lon = -78.1456; // Longitud de Naranjito
            const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=es`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.cod === 200) {
                        const weatherInfo = `
                            <p><strong>Temperatura:</strong> ${data.main.temp}°C</p>
                            <p><strong>Clima:</strong> ${data.weather[0].description}</p>
                            <p><strong>Humedad:</strong> ${data.main.humidity}%</p>
                            <p><strong>Viento:</strong> ${data.wind.speed} m/s</p>
                        `;
                        document.getElementById('weather-info').innerHTML = weatherInfo;
                    } else {
                        document.getElementById('weather-info').innerHTML = `<p>No se pudo obtener la información del clima. Por favor, verifica la API Key.</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error al obtener el clima:', error);
                    document.getElementById('weather-info').innerHTML = '<p>Ocurrió un error al cargar la información del clima.</p>';
                });
        });
    </script>
</body>
</html>
