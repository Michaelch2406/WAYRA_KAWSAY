# Wayra Kawsay - Plantilla de Aplicación Móvil

## Descripción del Proyecto

**Wayra Kawsay** (Viento de Cultura) es una plantilla de aplicación móvil diseñada específicamente para la comunidad indígena de Naranjito, ubicada en la parroquia Caranqui, cantón Ibarra, provincia de Imbabura, Ecuador. Esta aplicación busca preservar y promover la cultura Caranqui mediante una experiencia digital que impulse el turismo comunitario sostenible.

## Características Principales

### Diseño Minimalista e Intuitivo
- Interfaz limpia y moderna optimizada para dispositivos móviles
- Navegación fluida con scroll suave entre secciones
- Paleta de colores inspirada en la naturaleza andina
- Tipografía legible y accesible
- Diseño responsivo que se adapta a diferentes tamaños de pantalla

### Componentes Implementados

#### 1. Sabores Caranqui
- Restaurante temático "Kunu Tiyu"
- Menú interactivo con platos tradicionales
- Modal con información detallada de precios
- Integración de imágenes gastronómicas

#### 2. Galerías de Artesanías
- Perfiles de artesanos locales
- Galería de productos con imágenes
- Funcionalidad de compra directa (simulada)
- Información detallada de cada artesano

#### 3. Vocabulario Básico Kichwa
- Sistema de búsqueda en tiempo real
- Pronunciación simulada de palabras
- Interfaz bilingüe (Kichwa-Español)
- Diseño de tarjetas interactivas

#### 4. Agroturismo y Senderismo
- Tours al volcán Imbabura
- Experiencias agrícolas tradicionales
- Camping comunitario
- Sistema de reservas (simulado)

#### 5. Tradiciones y Cultura
- Galería de fotos comunitarias
- Videos de preparación gastronómica
- Leyendas ancestrales
- Danzas tradicionales
- Sistema de pestañas para organizar contenido

#### 6. Información Comunitaria
- Noticias actuales
- Calendario de eventos
- Información de contacto
- Sistema de reservas integrado

## Estructura de Archivos

```
wayra_kawsay_app/
├── index.html              # Archivo principal de la aplicación
├── css/
│   └── styles.css          # Estilos CSS completos
├── js/
│   └── script.js           # Funcionalidades JavaScript
├── assets/
│   ├── images/             # Imágenes de la aplicación
│   │   ├── naranjito_iglesia.jpg
│   │   ├── imbabura_volcan.jpg
│   │   ├── artesania_caranqui.jpg
│   │   └── gastronomia_andina.jpg
│   └── videos/             # Carpeta para videos (vacía)
└── sections/               # Carpeta para secciones adicionales (vacía)
```

## Tecnologías Utilizadas

- **HTML5**: Estructura semántica y accesible
- **CSS3**: Estilos modernos con Flexbox y Grid
- **JavaScript ES6+**: Funcionalidades interactivas
- **Font Awesome**: Iconografía
- **Google Fonts**: Tipografía Poppins

## Funcionalidades Implementadas

### Navegación
- Menú de navegación fijo con scroll suave
- Indicador de sección activa
- Menú móvil responsive
- Navegación por teclado accesible

### Interactividad
- Modales para información detallada
- Sistema de pestañas para contenido
- Búsqueda en tiempo real
- Efectos hover y transiciones suaves
- Animaciones de entrada para elementos

### Optimización Móvil
- Diseño mobile-first
- Touch-friendly (botones y enlaces grandes)
- Optimización de imágenes
- Carga rápida y eficiente

## Instrucciones de Uso

### Instalación
1. Descarga todos los archivos de la plantilla
2. Mantén la estructura de carpetas intacta
3. Abre `index.html` en un navegador web

### Personalización

#### Cambiar Imágenes
1. Reemplaza las imágenes en la carpeta `assets/images/`
2. Mantén los mismos nombres de archivo o actualiza las referencias en `index.html`

#### Modificar Contenido
1. Edita el archivo `index.html` para cambiar textos
2. Busca las secciones correspondientes por sus IDs:
   - `#inicio` - Sección hero
   - `#sabores` - Gastronomía
   - `#artesanias` - Artesanías
   - `#kichwa` - Vocabulario
   - `#turismo` - Agroturismo
   - `#tradiciones` - Cultura
   - `#comunidad` - Información

#### Personalizar Estilos
1. Modifica `css/styles.css` para cambiar colores, fuentes y diseño
2. Variables CSS principales:
   - Color primario: `#2c5530`
   - Color secundario: `#4a7c59`
   - Fuente principal: `'Poppins', sans-serif`

#### Agregar Funcionalidades
1. Edita `js/script.js` para nuevas características
2. Las funciones principales están documentadas en el código

### Integración de Contenido Real

#### Videos
1. Agrega archivos de video a `assets/videos/`
2. Actualiza las referencias en la sección de tradiciones
3. Reemplaza los placeholders de video con elementos `<video>`

#### Audio para Vocabulario
1. Crea archivos de audio para cada palabra Kichwa
2. Modifica la función `playAudio()` en `script.js`
3. Agrega los archivos de audio a una carpeta `assets/audio/`

#### Sistema de Reservas Real
1. Integra con un backend para manejar reservas
2. Modifica las funciones `handleReservation()` y `handleContactForm()`
3. Conecta con servicios de pago si es necesario

#### E-commerce para Artesanías
1. Integra con una plataforma de e-commerce
2. Modifica la función `addToCart()`
3. Agrega sistema de inventario y pagos

## Características Técnicas

### Rendimiento
- Imágenes optimizadas para web
- CSS y JavaScript minificados (en producción)
- Lazy loading para imágenes (implementable)
- Service Worker para PWA (base incluida)

### Accesibilidad
- Navegación por teclado
- Etiquetas ARIA apropiadas
- Contraste de colores adecuado
- Texto alternativo para imágenes

### SEO
- Estructura HTML semántica
- Meta tags apropiados
- Contenido estructurado
- URLs amigables (para implementación con backend)

## Próximos Pasos Recomendados

### Desarrollo Futuro
1. **Backend Integration**: Conectar con una base de datos para contenido dinámico
2. **CMS Integration**: Permitir que la comunidad actualice contenido fácilmente
3. **Multilingual Support**: Agregar soporte completo para Kichwa y otros idiomas
4. **PWA Features**: Implementar funcionalidades de aplicación web progresiva
5. **Analytics**: Integrar seguimiento de uso y métricas

### Contenido Adicional
1. **Más Vocabulario**: Expandir el diccionario Kichwa
2. **Recetas Detalladas**: Agregar instrucciones paso a paso
3. **Historia Comunitaria**: Sección dedicada a la historia de Naranjito
4. **Calendario Cultural**: Eventos y festividades anuales
5. **Mapa Interactivo**: Ubicaciones de interés en la comunidad

## Soporte y Mantenimiento

### Actualizaciones
- Revisar y actualizar contenido regularmente
- Mantener imágenes y videos actualizados
- Verificar funcionalidad en nuevos dispositivos y navegadores

### Backup
- Realizar copias de seguridad regulares
- Mantener versiones del código fuente
- Documentar cambios importantes

## Contacto y Créditos

Esta plantilla fue desarrollada específicamente para la comunidad indígena de Naranjito como parte del proyecto "Wayra Kawsay" (Viento de Cultura), con el objetivo de preservar y promover la rica cultura Caranqui a través de la tecnología digital.

**Desarrollado con amor y respeto por la cultura ancestral Caranqui** 🌱

