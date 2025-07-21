# Scripts de Vocabulario Kichwa

## extract_kichwa_vocabulary.php

Este script extrae vocabulario de los archivos de texto en la carpeta `info_kichwa/` y los procesa para el diccionario interactivo.

### Funcionalidad

1. **Extrae de múltiples fuentes:**
   - FRASES COMUNES.txt - Frases y expresiones comunes
   - RK_diccionario_kichwa_castellano.txt - Diccionario principal con definiciones
   - LEXTN-Dea-142622-PUBCOM.txt - Lexicón adicional

2. **Procesa diferentes tipos de datos:**
   - Palabras individuales con traducción
   - Frases completas
   - Definiciones con pronunciación
   - Ejemplos de uso

### Cómo ejecutar

```bash
cd scripts
php extract_kichwa_vocabulary.php
```

### Configuración de base de datos requerida

Asegúrate de que la tabla `palabras_kichwa` existe con esta estructura:

```sql
CREATE TABLE palabras_kichwa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    palabra_kichwa VARCHAR(255) NOT NULL,
    traduccion_espanol TEXT NOT NULL,
    pronunciacion VARCHAR(255) NULL,
    descripcion TEXT NULL,
    ejemplo TEXT NULL,
    tipo ENUM('word', 'phrase', 'lexicon') DEFAULT 'word',
    categoria VARCHAR(100) NULL,
    audio_url VARCHAR(255) NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_word (palabra_kichwa, traduccion_espanol)
);
```

### Características del procesamiento

- **Detección automática de idioma:** Usa patrones para identificar si un texto está en kichwa o español
- **Prevención de duplicados:** Usa INSERT IGNORE para evitar entradas duplicadas
- **Procesamiento por lotes:** Procesa archivos grandes en fragmentos para evitar problemas de memoria
- **Limpieza de datos:** Elimina caracteres especiales y normaliza el texto

### Resultados esperados

El script puede procesar:
- Hasta 2000 palabras del diccionario principal
- Hasta 1000 entradas del lexicón
- Todas las frases comunes disponibles

Total estimado: 3000+ entradas en el diccionario