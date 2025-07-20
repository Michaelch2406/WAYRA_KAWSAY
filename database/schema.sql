CREATE DATABASE IF NOT EXISTS wayra_kawsay;

USE wayra_kawsay;

CREATE TABLE sabores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(255)
);

CREATE TABLE artesanos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    foto VARCHAR(255)
);

CREATE TABLE productos_artesanales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_artesano INT,
    nombre VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    FOREIGN KEY (id_artesano) REFERENCES artesanos(id)
);

CREATE TABLE palabras_kichwa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    palabra_kichwa VARCHAR(255) NOT NULL,
    traduccion_espanol VARCHAR(255) NOT NULL,
    audio VARCHAR(255)
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Datos de ejemplo

INSERT INTO sabores (nombre, descripcion, imagen) VALUES
('Papas con cuero', 'Un plato tradicional de la sierra ecuatoriana, con papas en un guiso de maní y cuero de cerdo.', 'papas_cuero.jpg'),
('Fritada', 'Carne de cerdo frita en su propia grasa, servida con mote, choclo y encurtido.', 'fritada.jpg'),
('Quimbolitos', 'Pasteles de harina de maíz cocidos al vapor en hojas de achira, con un sabor dulce y suave.', 'quimbolitos.jpg');

INSERT INTO artesanos (nombre, descripcion, foto) VALUES
('María Tene', 'Artesana experta en bordados a mano, con más de 30 años de experiencia.', 'maria_tene.jpg'),
('José Lema', 'Tejedor de ponchos y fajas en telar de cintura, utilizando técnicas ancestrales.', 'jose_lema.jpg');

INSERT INTO productos_artesanales (id_artesano, nombre, descripcion, imagen) VALUES
(1, 'Blusa bordada', 'Blusa de algodón con bordados de flores y motivos andinos.', 'blusa_bordada.jpg'),
(1, 'Mantel de mesa', 'Mantel bordado a mano con diseños coloridos.', 'mantel_bordado.jpg'),
(2, 'Poncho de lana', 'Poncho tejido en telar de cintura con lana de oveja.', 'poncho_lana.jpg');

INSERT INTO palabras_kichwa (palabra_kichwa, traduccion_espanol, audio) VALUES
('Alli puncha', 'Buenos días', 'alli_puncha.mp3'),
('Alli chishi', 'Buenas tardes', 'alli_chishi.mp3'),
('Alli tuta', 'Buenas noches', 'alli_tuta.mp3'),
('Yupaichani', 'Gracias', 'yupaichani.mp3');
