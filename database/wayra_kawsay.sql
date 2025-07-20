DROP DATABASE IF EXISTS wayra_kawsay;
USE wayra_kawsay;

-- Tabla de Usuarios con roles. Es la tabla central.
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) COMMENT 'Número de teléfono del usuario',
    -- El rol define qué puede hacer el usuario en el sistema.
    rol ENUM('comunitario', 'artesano', 'admin') NOT NULL DEFAULT 'comunitario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Un usuario puede estar activo o inactivo (baneado).
    activo BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
);

-- Tabla para Artesanos, con una referencia a la tabla de usuarios.
-- Esto permite que un artesano pueda loguearse y gestionar su propio perfil.
CREATE TABLE artesanos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- FK: Un artesano ES un usuario.
    id_usuario INT NOT NULL UNIQUE,
    historia TEXT COMMENT 'Biografía o historia del artesano',
    especialidad VARCHAR(150),
    foto_perfil VARCHAR(255),
    telefono_contacto VARCHAR(20),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla para Productos. Bien estructurada en tu propuesta original.
CREATE TABLE productos_artesanales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- FK: Qué artesano hizo este producto.
    id_artesano INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    disponible BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_artesano) REFERENCES artesanos(id) ON DELETE CASCADE,
    INDEX idx_artesano (id_artesano),
    INDEX idx_disponible (disponible)
);

-- Tabla para la gastronomía (platos).
CREATE TABLE platos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    historia TEXT,
    imagen VARCHAR(255),
    video_preparacion_url VARCHAR(255),
    categoria ENUM('tradicional', 'carne', 'sopa', 'vegetariano', 'bebida', 'postre') DEFAULT 'tradicional',
    dificultad ENUM('facil', 'medio', 'dificil') DEFAULT 'medio',
    tiempo_preparacion INT COMMENT 'Tiempo en minutos',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria)
);

-- Tabla para el vocabulario. Bien estructurada en tu propuesta.
CREATE TABLE palabras_kichwa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    palabra_kichwa VARCHAR(255) NOT NULL,
    traduccion_espanol VARCHAR(255) NOT NULL,
    -- URL al archivo de audio.
    audio_url VARCHAR(255),
    categoria VARCHAR(100) COMMENT 'Categoría de la palabra (naturaleza, familia, etc.)',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_palabra (palabra_kichwa),
    INDEX idx_traduccion (traduccion_espanol)
);

-- Tabla central para todo el contenido dinámico: noticias, leyendas, etc.
CREATE TABLE contenido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- FK: Qué usuario comunitario subió el contenido.
    id_usuario_autor INT NOT NULL,
    tipo ENUM('noticia', 'historia', 'leyenda', 'baile', 'tradicion', 'evento') NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    cuerpo TEXT,
    url_multimedia VARCHAR(255) COMMENT 'URL para imagen o video asociado',
    -- Para moderación: un admin debe aprobar el contenido antes de que sea público.
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_aprobacion TIMESTAMP NULL,
    id_usuario_aprobador INT NULL,
    FOREIGN KEY (id_usuario_autor) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario_aprobador) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_autor (id_usuario_autor),
    INDEX idx_tipo (tipo),
    INDEX idx_estado (estado),
    INDEX idx_fecha (fecha_creacion)
);

-- Tabla para las rutas de senderismo y agroturismo.
CREATE TABLE rutas_turisticas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    distancia_km DECIMAL(5, 2),
    dificultad ENUM('baja', 'media', 'alta'),
    mapa_url VARCHAR(255) COMMENT 'URL a una imagen del mapa o a un servicio externo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_dificultad (dificultad)
);

-- Puntos de Interés para la gamificación en las rutas.
CREATE TABLE puntos_interes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- FK: A qué ruta pertenece este punto.
    id_ruta INT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    latitud DECIMAL(10, 8),
    longitud DECIMAL(11, 8),
    -- El contenido que se "desbloquea" al visitar el punto.
    contenido_desbloqueable_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ruta) REFERENCES rutas_turisticas(id) ON DELETE CASCADE,
    -- Se puede vincular a un contenido específico (una leyenda, un video).
    FOREIGN KEY (contenido_desbloqueable_id) REFERENCES contenido(id) ON DELETE SET NULL,
    INDEX idx_ruta (id_ruta),
    INDEX idx_coordenadas (latitud, longitud)
);

-- Tabla para Eventos y Festividades
CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME,
    ubicacion_texto VARCHAR(255),
    imagen VARCHAR(255),
    id_usuario_creador INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_creador) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_fecha_inicio (fecha_inicio),
    INDEX idx_creador (id_usuario_creador)
);

-- Tabla para manejar reservas (de guías, de restaurante, etc.)
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- FK: Quién hace la reserva.
    id_usuario_visitante INT NOT NULL,
    -- Tipo de servicio que se reserva.
    tipo_servicio ENUM('guia_turistico', 'restaurante') NOT NULL,
    -- ID del recurso reservado (ID del guía en tabla usuarios, o ID del restaurante si hubiera una tabla para ello).
    id_recurso INT NOT NULL,
    fecha_reserva DATETIME NOT NULL,
    numero_personas INT,
    estado ENUM('solicitada', 'confirmada', 'cancelada') DEFAULT 'solicitada',
    notas TEXT COMMENT 'Notas adicionales de la reserva',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario_visitante) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_visitante (id_usuario_visitante),
    INDEX idx_fecha_reserva (fecha_reserva),
    INDEX idx_estado (estado)
);

-- DATOS DE PRUEBA

-- Usuarios de prueba
INSERT INTO usuarios (nombre, email, password, telefono, rol, activo) VALUES
('Admin Sistema', 'admin@wayrakawsay.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+593987654321', 'admin', TRUE),
('María Quispe', 'maria.quispe@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+593987123456', 'artesano', TRUE),
('Carlos Mamani', 'carlos.mamani@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+593987789012', 'artesano', TRUE),
('Ana Condori', 'ana.condori@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+593987345678', 'artesano', TRUE),
('José Tandioy', 'jose.tandioy@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+593987901234', 'comunitario', TRUE),
('Lucia Calderón', 'lucia.calderon@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+593987567890', 'comunitario', TRUE);

-- Artesanos de prueba
INSERT INTO artesanos (id_usuario, historia, especialidad, foto_perfil, telefono_contacto) VALUES
(2, 'Maestra tejedora con más de 30 años de experiencia en textiles tradicionales andinos. Especialista en técnicas ancestrales de telar de cintura. Ha enseñado a más de 50 jóvenes las técnicas tradicionales.', 'Textiles Andinos', 'maria_quispe.jpg', '+593987123456'),
(3, 'Ceramista heredero de tradiciones alfareras precolombinas. Sus obras reflejan la cosmovisión andina en cada pieza. Utiliza técnicas de cocción en horno de leña y arcillas locales.', 'Cerámica Tradicional', 'carlos_mamani.jpg', '+593987789012'),
(4, 'Orfebre especializada en joyería tradicional andina. Trabaja con plata y piedras semipreciosas locales. Sus diseños se basan en iconografía inca y símbolos ancestrales.', 'Orfebrería Andina', 'ana_condori.jpg', '+593987345678');

-- Productos artesanales de prueba
INSERT INTO productos_artesanales (id_artesano, nombre, descripcion, imagen, disponible) VALUES
(1, 'Poncho Tradicional', 'Poncho tejido a mano con lana de alpaca, siguiendo patrones ancestrales de la región. Cada diseño cuenta una historia única.', 'poncho_tradicional.jpg', TRUE),
(1, 'Chullo Andino', 'Gorro tradicional con orejeras, tejido con técnicas milenarias. Perfecto para el clima andino.', 'chullo_andino.jpg', TRUE),
(1, 'Bufanda de Alpaca', 'Bufanda suave y cálida tejida con lana de alpaca natural. Diseños geométricos tradicionales.', 'bufanda_alpaca.jpg', TRUE),
(2, 'Vasija Ceremonial', 'Vasija de arcilla con diseños geométricos tradicionales, cocida en horno de leña. Ideal para ceremonias.', 'vasija_ceremonial.jpg', TRUE),
(2, 'Platos Decorativos', 'Set de platos con motivos andinos, perfectos para decoración o uso ceremonial.', 'platos_decorativos.jpg', TRUE),
(3, 'Collar de Plata', 'Collar artesanal con diseños inspirados en la iconografía inca. Plata 925 con acabados artesanales.', 'collar_plata.jpg', TRUE),
(3, 'Aretes Tradicionales', 'Aretes de plata con incrustaciones de turquesa andina. Diseño basado en símbolos ancestrales.', 'aretes_tradicionales.jpg', TRUE);

-- Platos tradicionales de prueba
INSERT INTO platos (nombre, descripcion, historia, imagen, categoria, dificultad, tiempo_preparacion) VALUES
('Cuy Asado', 'Plato tradicional andino preparado con cuy criado en casa. Se sirve con papas y salsa de ají.', 'El cuy ha sido parte de la alimentación andina desde tiempos precolombinos. Es considerado un plato festivo y ceremonial.', 'cuy_asado.jpg', 'tradicional', 'medio', 120),
('Locro de Papa', 'Sopa espesa de papas con queso fresco, cilantro y ají. Reconfortante y nutritiva.', 'Plato que representa la abundancia de la papa en los Andes. Cada familia tiene su receta secreta.', 'locro_papa.jpg', 'sopa', 'facil', 45),
('Colada Morada', 'Bebida tradicional preparada con maíz morado, frutas y especias. Se consume especialmente en noviembre.', 'Bebida ancestral que se prepara para el Día de los Difuntos, acompañada de guaguas de pan.', 'colada_morada.jpg', 'bebida', 'medio', 90),
('Hornado Pastuso', 'Cerdo horneado lentamente con especias andinas. Se sirve con mote, yuca y curtido.', 'Plato festivo que se prepara en ocasiones especiales. El hornado es una tradición que requiere paciencia y técnica.', 'hornado_pastuso.jpg', 'carne', 'dificil', 240);

-- Palabras Kichwa de prueba
INSERT INTO palabras_kichwa (palabra_kichwa, traduccion_espanol, categoria) VALUES
('Allí', 'Bueno/bien', 'expresiones'),
('Ama', 'No', 'expresiones'),
('Inti', 'Sol', 'naturaleza'),
('Killa', 'Luna', 'naturaleza'),
('Mama', 'Madre', 'familia'),
('Taita', 'Padre', 'familia'),
('Warmi', 'Mujer', 'persona'),
('Runa', 'Persona', 'persona'),
('Wasi', 'Casa', 'lugar'),
('Yakana', 'Agua', 'naturaleza'),
('Papa', 'Papa', 'alimentos'),
('Sara', 'Maíz', 'alimentos'),
('Urku', 'Cerro', 'naturaleza'),
('Pachamama', 'Madre Tierra', 'espiritualidad'),
('Wayra', 'Viento', 'naturaleza');

-- Contenido comunitario de prueba
INSERT INTO contenido (id_usuario_autor, tipo, titulo, cuerpo, estado) VALUES
(5, 'noticia', 'Minga Comunitaria este Sábado', 'Se convoca a todos los miembros de la comunidad para participar en la minga de limpieza del centro comunitario. La actividad iniciará a las 8:00 AM.', 'aprobado'),
(6, 'historia', 'La Leyenda del Volcán Imbabura', 'Cuenta la leyenda que el volcán Imbabura era un guerrero valiente que se enamoró de la hermosa Cotacachi...', 'aprobado'),
(5, 'evento', 'Feria de Productos Artesanales', 'Este fin de semana se realizará la feria mensual de productos artesanales en la plaza central. Habrá música, danza y comida tradicional.', 'pendiente');

-- Eventos de prueba
INSERT INTO eventos (nombre, descripcion, fecha_inicio, fecha_fin, ubicacion_texto, id_usuario_creador) VALUES
('Inti Raymi 2025', 'Celebración del solsticio de invierno, fiesta del sol. Danzas, música y rituales tradicionales.', '2024-06-21 09:00:00', '2024-06-21 18:00:00', 'Plaza Central de Caranqui', 1),
('Feria del Maíz', 'Celebración de la cosecha del maíz con platos tradicionales y ceremonias.', '2024-09-15 08:00:00', '2024-09-15 20:00:00', 'Centro Comunitario', 1),
('Festival de Música Andina', 'Encuentro de músicos y grupos de música tradicional andina.', '2024-08-10 19:00:00', '2024-08-10 23:00:00', 'Teatro Municipal', 1);

-- Rutas turísticas de prueba
INSERT INTO rutas_turisticas (nombre, descripcion, distancia_km, dificultad) VALUES
('Sendero al Volcán Imbabura', 'Caminata hacia la base del volcán Imbabura con vistas panorámicas de la provincia.', 15.5, 'alta'),
('Ruta de las Lagunas', 'Recorrido por las lagunas de Yahuarcocha y San Pablo, ideal para fotografía.', 8.2, 'media'),
('Camino del Agua', 'Sendero que sigue antiguas acequias y fuentes de agua natural.', 5.0, 'baja');