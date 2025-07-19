<?php include_once 'config/global.php'; ?>

<!DOCTYPE html>
<html lang="<?php echo $lang_code; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texto['menu_cultura']; ?> - <?php echo NOMBRE_PROYECTO; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styles.css">
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
                        <a class="nav-link active" aria-current="page" href="cultura.php"><?php echo $texto['menu_cultura']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ubicacion.php"><?php echo $texto['menu_ubicacion']; ?></a>
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
        <h1><?php echo $texto['menu_cultura']; ?></h1>

        <div class="row">
            <div class="col-12">
                <h2>Videos del Inti Raymi</h2>
            </div>
            <div class="col-md-4">
                <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@joncarlosama/video/7516340227372272902" data-video-id="7516340227372272902" style="max-width: 605px;min-width: 325px;" > <section> <a target="_blank" title="@joncarlosama" href="https://www.tiktok.com/@joncarlosama?refer=embed">@joncarlosama</a> <p>inti raymi caranqui🇪🇨 <a title="fypシ゚" target="_blank" href="https://www.tiktok.com/tag/fyp%E3%82%9A%E3%82%9A?refer=embed">#fypシ゚</a> <a title="paratii" target="_blank" href="https://www.tiktok.com/tag/paratii?refer=embed">#paratii</a> <a title="diversion" target="_blank" href="https://www.tiktok.com/tag/diversion?refer=embed">#diversion</a></p> <a target="_blank" title="♬ sonido original - Joncarlosama" href="https://www.tiktok.com/music/sonido-original-7516340259845720837?refer=embed">♬ sonido original - Joncarlosama</a> </section> </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@liz.21_8/video/7516345123077131576" data-video-id="7516345123077131576" style="max-width: 605px;min-width: 325px;" > <section> <a target="_blank" title="@liz.21_8" href="https://www.tiktok.com/@liz.21_8?refer=embed">@liz.21_8</a> <p></p> <a target="_blank" title="♬ sonido original - 𝐂𝐀𝐑𝐀𝐍𝐐𝐔𝐈 𝐓𝐕" href="https://www.tiktok.com/music/sonido-original-7516345151512431366?refer=embed">♬ sonido original - 𝐂𝐀𝐑𝐀𝐍𝐐𝐔𝐈 𝐓𝐕</a> </section> </blockquote>
            </div>
            <div class="col-md-4">
                <blockquote class="tiktok-embed" cite="https://www.tiktok.com/@andreatefyarmas/video/7517754066802560261" data-video-id="7517754066802560261" style="max-width: 605px;min-width: 325px;" > <section> <a target="_blank" title="@andreatefyarmas" href="https://www.tiktok.com/@andreatefyarmas?refer=embed">@andreatefyarmas</a> <p>Fiestas de Caranqui</p> <a target="_blank" title="♬ sonido original - andreatefyarmas" href="https://www.tiktok.com/music/sonido-original-7517754093902990085?refer=embed">♬ sonido original - andreatefyarmas</a> </section> </blockquote>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h2>Galería de Fotos</h2>
                <div id="photo-gallery" class="row"></div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <h2>Próximos Eventos</h2>
                <ul class="list-group">
                    <li class="list-group-item">
                        <h5>Inti Raymi</h5>
                        <p><strong>Fecha:</strong> 21 de Junio</p>
                        <p>Celebración del solsticio de invierno, una de las festividades más importantes de la cultura andina.</p>
                    </li>
                    <li class="list-group-item">
                        <h5>Fiesta del Maíz</h5>
                        <p><strong>Fecha:</strong> 15 de Septiembre</p>
                        <p>Agradecimiento a la Pachamama por la cosecha del maíz.</p>
                    </li>
                    <li class="list-group-item">
                        <h5>Día de los Difuntos</h5>
                        <p><strong>Fecha:</strong> 2 de Noviembre</p>
                        <p>Tradición de honrar a los antepasados con ofrendas y visitas al cementerio.</p>
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted"><?php echo $texto['pie_pagina']; ?></span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script async src="https://www.tiktok.com/embed.js"></script>
    <script src="../js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const unsplashApiKey = 'TU_API_KEY_UNSPLASH'; // REEMPLAZA ESTO CON TU API KEY DE UNSPLASH
            const query = 'Andes culture';
            const url = `https://api.unsplash.com/photos/random?query=${query}&count=6&client_id=${unsplashApiKey}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const gallery = document.getElementById('photo-gallery');
                    data.forEach(photo => {
                        const col = document.createElement('div');
                        col.className = 'col-md-4 mb-4';
                        col.innerHTML = `<img src="${photo.urls.small}" class="img-fluid" alt="${photo.alt_description}">`;
                        gallery.appendChild(col);
                    });
                })
                .catch(error => {
                    console.error('Error al obtener las fotos:', error);
                    document.getElementById('photo-gallery').innerHTML = '<p>Ocurrió un error al cargar la galería de fotos.</p>';
                });
        });
    </script>
</body>
</html>
