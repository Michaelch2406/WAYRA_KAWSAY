// JavaScript para funcionalidades específicas de la página de ubicación - Siguiendo el patrón de sabores

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades
    initLanguageSelector();
    initScrollEffects();
    initNavbarEffects();
    initFilterSystem();
    initCardAnimations();
    initModal();
    initScrollToTop();
    initLazyLoading();
    initSearchFunctionality();
    initWeatherWidget();
    initMapInteractions();
});

// Selector de idioma (heredado del patrón sabores)
function initLanguageSelector() {
    const languageSelector = document.getElementById('language-selector');
    
    if (languageSelector) {
        languageSelector.addEventListener('change', function() {
            const selectedLang = this.value;
            showLoadingIndicator();
            
            setTimeout(() => {
                window.location.href = `ubicacion.php?lang=${selectedLang}`;
            }, 500);
        });
    }
}

// Efectos de scroll (heredado del patrón sabores)
function initScrollEffects() {
    const navbar = document.querySelector('.navbar');
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Efecto de transparencia en navbar
        if (scrollTop > 100) {
            navbar.style.backgroundColor = 'rgba(139, 69, 19, 0.95)';
            navbar.style.backdropFilter = 'blur(10px)';
        } else {
            navbar.style.backgroundColor = '';
            navbar.style.backdropFilter = '';
        }
        
        lastScrollTop = scrollTop;
        
        // Animaciones de elementos al hacer scroll
        animateOnScroll();
        
        // Mostrar/ocultar botón scroll to top
        toggleScrollToTop();
    });
}

// Efectos de navegación (heredado del patrón sabores)
function initNavbarEffects() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        link.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
}

// Sistema de filtros (adaptado para ubicaciones)
function initFilterSystem() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const saborCards = document.querySelectorAll('.sabor-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            // Filtrar tarjetas
            filterCards(filterValue, saborCards);
            
            // Mostrar notificación
            showNotification(`Mostrando: ${this.textContent}`, 'info');
        });
    });
}

// Función para filtrar tarjetas (heredada del patrón sabores)
function filterCards(filterValue, cards) {
    cards.forEach(card => {
        const cardCategory = card.getAttribute('data-category');
        
        if (filterValue === 'all' || cardCategory === filterValue) {
            card.style.display = 'block';
            card.style.animation = 'fadeInUp 0.5s ease-out';
        } else {
            card.style.display = 'none';
        }
    });
}

// Animaciones de tarjetas (adaptadas para ubicaciones)
function initCardAnimations() {
    const cards = document.querySelectorAll('.sabor-card');
    
    cards.forEach((card, index) => {
        // Animación de entrada escalonada
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
        
        // Efectos hover adicionales
        card.addEventListener('mouseenter', function() {
            this.classList.add('sparkle');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('sparkle');
        });
        
        // Click para abrir modal (adaptado para ubicaciones)
        card.addEventListener('click', function() {
            const cardData = {
                title: this.querySelector('.card-title').textContent,
                description: this.querySelector('.card-description').textContent,
                image: this.querySelector('img').src,
                tags: Array.from(this.querySelectorAll('.tag')).map(tag => tag.textContent),
                coordinates: this.querySelector('.coordinates span') ? this.querySelector('.coordinates span').textContent : 'No disponible',
                altitude: this.querySelector('.altitude-badge') ? this.querySelector('.altitude-badge').textContent : 'No disponible',
                locationType: this.querySelector('.prep-time') ? this.querySelector('.prep-time').textContent.replace(/.*\s/, '') : 'Lugar'
            };
            
            openModal(cardData);
        });
    });
}

// Sistema de modal (adaptado para ubicaciones)
function initModal() {
    // Crear modal dinámicamente
    const modalHTML = `
        <div class="modal fade" id="saborModal" tabindex="-1" aria-labelledby="saborModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saborModalLabel">Detalles de la Ubicación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="" alt="" class="modal-image" id="modalImage">
                        <h4 id="modalTitle"></h4>
                        <div id="modalTags" class="card-tags mb-3"></div>
                        <div id="modalRating" class="card-rating mb-3"></div>
                        <p id="modalDescription"></p>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Altitud:</strong>
                                <div id="modalDifficulty" class="difficulty mt-2"></div>
                            </div>
                            <div class="col-md-6">
                                <strong>Tipo de lugar:</strong>
                                <div id="modalPrepTime" class="prep-time mt-2"></div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div id="modalMiniMap" class="map-frame" style="height: 200px;">
                                <iframe src="" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="openInGoogleMaps()">Ver en Google Maps</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Abrir modal con datos (adaptado para ubicaciones)
function openModal(data) {
    document.getElementById('modalTitle').textContent = data.title;
    document.getElementById('modalDescription').textContent = data.description;
    document.getElementById('modalImage').src = data.image;
    document.getElementById('modalImage').alt = data.title;
    
    // Tags
    const tagsContainer = document.getElementById('modalTags');
    tagsContainer.innerHTML = '';
    data.tags.forEach(tag => {
        const tagElement = document.createElement('span');
        tagElement.className = 'tag';
        tagElement.textContent = tag;
        tagsContainer.appendChild(tagElement);
    });
    
    // Coordenadas (en lugar de rating)
    document.getElementById('modalRating').innerHTML = `
        <div class="coordinates">
            <i class="fas fa-map-pin"></i>
            <span>${data.coordinates}</span>
        </div>
    `;
    
    // Altitud (en lugar de dificultad)
    const difficultyContainer = document.getElementById('modalDifficulty');
    difficultyContainer.innerHTML = `<div class="altitude-badge">${data.altitude}</div>`;
    
    // Tipo de lugar (en lugar de tiempo de preparación)
    document.getElementById('modalPrepTime').textContent = data.locationType;
    
    // Mini mapa
    const coords = data.coordinates.split(',');
    if (coords.length === 2) {
        const lat = parseFloat(coords[0].replace('°N', ''));
        const lng = parseFloat(coords[1].replace('°W', '')) * -1;
        const mapUrl = `https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dO_BcqOWBOUOdg&q=${lat},${lng}&zoom=14`;
        document.querySelector('#modalMiniMap iframe').src = mapUrl;
    }
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('saborModal'));
    modal.show();
}

// Widget del clima (funcionalidad específica de ubicación)
function initWeatherWidget() {
    loadWeatherData();
    
    // Actualizar cada 10 minutos
    setInterval(loadWeatherData, 600000);
}

async function loadWeatherData() {
    const weatherContainer = document.getElementById('weather-info');
    if (!weatherContainer) return;

    // Mostrar loader
    showWeatherLoader(weatherContainer);

    try {
        // Simular datos del clima para Imbabura
        const weatherData = await simulateWeatherData();
        
        if (weatherData) {
            displayWeatherData(weatherContainer, weatherData);
        } else {
            showWeatherError(weatherContainer);
        }
    } catch (error) {
        console.error('Error al cargar datos del clima:', error);
        showWeatherError(weatherContainer);
    }
}

async function simulateWeatherData() {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve({
                location: 'Imbabura, Ecuador',
                temperature: Math.round(18 + Math.random() * 8), // 18-26°C típico para la región
                description: getRandomWeatherDescription(),
                humidity: Math.round(60 + Math.random() * 30), // 60-90%
                windSpeed: Math.round(5 + Math.random() * 10), // 5-15 km/h
                pressure: Math.round(1010 + Math.random() * 20), // 1010-1030 hPa
                visibility: Math.round(8 + Math.random() * 7), // 8-15 km
                uvIndex: Math.round(1 + Math.random() * 8), // 1-9
                icon: getWeatherIcon()
            });
        }, 1000);
    });
}

function getRandomWeatherDescription() {
    const descriptions = [
        'Parcialmente nublado',
        'Cielo despejado',
        'Nublado',
        'Lluvia ligera',
        'Soleado',
        'Bruma matutina'
    ];
    return descriptions[Math.floor(Math.random() * descriptions.length)];
}

function getWeatherIcon() {
    const icons = ['☀️', '⛅', '☁️', '🌦️', '🌤️', '🌫️'];
    return icons[Math.floor(Math.random() * icons.length)];
}

function showWeatherLoader(container) {
    container.innerHTML = `
        <div class="weather-loader">
            <div class="weather-spinner"></div>
            <p>Cargando información del clima...</p>
        </div>
    `;
}

function displayWeatherData(container, data) {
    container.innerHTML = `
        <div class="weather-current">
            <div class="weather-icon" style="font-size: 3rem; margin-bottom: 1rem;">${data.icon}</div>
            <div class="weather-temp">${data.temperature}°C</div>
            <div class="weather-description">${data.description}</div>
            <div class="weather-location" style="margin-top: 1rem; opacity: 0.9;">
                <i class="fas fa-map-marker-alt me-2"></i>
                ${data.location}
            </div>
        </div>
        
        <div class="weather-details">
            <div class="weather-detail">
                <div class="weather-detail-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="weather-detail-label">Humedad</div>
                <div class="weather-detail-value">${data.humidity}%</div>
            </div>
            
            <div class="weather-detail">
                <div class="weather-detail-icon">
                    <i class="fas fa-wind"></i>
                </div>
                <div class="weather-detail-label">Viento</div>
                <div class="weather-detail-value">${data.windSpeed} km/h</div>
            </div>
            
            <div class="weather-detail">
                <div class="weather-detail-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <div class="weather-detail-label">Presión</div>
                <div class="weather-detail-value">${data.pressure} hPa</div>
            </div>
            
            <div class="weather-detail">
                <div class="weather-detail-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="weather-detail-label">Visibilidad</div>
                <div class="weather-detail-value">${data.visibility} km</div>
            </div>
            
            <div class="weather-detail">
                <div class="weather-detail-icon">
                    <i class="fas fa-sun"></i>
                </div>
                <div class="weather-detail-label">Índice UV</div>
                <div class="weather-detail-value">${data.uvIndex}</div>
            </div>
            
            <div class="weather-detail">
                <div class="weather-detail-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="weather-detail-label">Actualizado</div>
                <div class="weather-detail-value">${new Date().toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</div>
            </div>
        </div>
    `;
}

function showWeatherError(container) {
    container.innerHTML = `
        <div class="weather-error text-center">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
            <h5>No se pudo cargar el clima</h5>
            <p>Información del clima no disponible en este momento.</p>
            <button class="btn btn-light mt-2" onclick="loadWeatherData()">
                <i class="fas fa-redo me-2"></i>
                Reintentar
            </button>
        </div>
    `;
}

// Interacciones con mapas (funcionalidad específica de ubicación)
function initMapInteractions() {
    const mainMap = document.querySelector('.map-frame iframe');
    if (mainMap) {
        // Agregar indicador de carga
        const mapContainer = mainMap.parentElement;
        const loader = document.createElement('div');
        loader.className = 'map-loader text-center';
        loader.innerHTML = `
            <div class="spinner-border text-primary mb-2" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p>Cargando mapa...</p>
        `;
        mapContainer.appendChild(loader);

        mainMap.addEventListener('load', () => {
            loader.remove();
            mainMap.style.opacity = '1';
        });
    }
}

// Botón scroll to top (heredado del patrón sabores)
function initScrollToTop() {
    const scrollButton = document.createElement('button');
    scrollButton.className = 'scroll-to-top';
    scrollButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    document.body.appendChild(scrollButton);
}

// Mostrar/ocultar botón scroll to top (heredado del patrón sabores)
function toggleScrollToTop() {
    const scrollButton = document.querySelector('.scroll-to-top');
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > 300) {
        scrollButton.classList.add('visible');
    } else {
        scrollButton.classList.remove('visible');
    }
}

// Lazy loading de imágenes (heredado del patrón sabores)
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('skeleton');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => {
        img.classList.add('skeleton');
        imageObserver.observe(img);
    });
}

// Funcionalidad de búsqueda (adaptada para ubicaciones)
function initSearchFunctionality() {
    // Crear barra de búsqueda
    const searchHTML = `
        <div class="search-container mb-4">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Buscar ubicaciones...">
                <button class="btn btn-outline-primary" type="button" id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    `;
    
    const filtersSection = document.querySelector('.filters-section .container');
    if (filtersSection) {
        filtersSection.insertAdjacentHTML('afterbegin', searchHTML);
        
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        
        searchInput.addEventListener('input', performSearch);
        searchButton.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
}

// Realizar búsqueda (adaptada para ubicaciones)
function performSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.sabor-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const description = card.querySelector('.card-description').textContent.toLowerCase();
        const tags = Array.from(card.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase());
        
        const matches = title.includes(searchTerm) || 
                       description.includes(searchTerm) || 
                       tags.some(tag => tag.includes(searchTerm));
        
        if (matches || searchTerm === '') {
            card.style.display = 'block';
            card.style.animation = 'fadeInUp 0.5s ease-out';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Mostrar mensaje si no hay resultados
    showSearchResults(visibleCount, searchTerm);
}

// Mostrar resultados de búsqueda (adaptada para ubicaciones)
function showSearchResults(count, searchTerm) {
    let existingMessage = document.querySelector('.search-results-message');
    if (existingMessage) {
        existingMessage.remove();
    }
    
    if (searchTerm && count === 0) {
        const message = document.createElement('div');
        message.className = 'search-results-message alert alert-info text-center';
        message.innerHTML = `
            <i class="fas fa-search"></i>
            No se encontraron ubicaciones que coincidan con "${searchTerm}"
        `;
        
        const grid = document.querySelector('.sabores-grid .container');
        grid.insertBefore(message, grid.firstChild);
    } else if (searchTerm && count > 0) {
        const message = document.createElement('div');
        message.className = 'search-results-message alert alert-success text-center';
        message.innerHTML = `
            <i class="fas fa-check"></i>
            Se encontraron ${count} ubicación${count !== 1 ? 'es' : ''} que coinciden con "${searchTerm}"
        `;
        
        const grid = document.querySelector('.sabores-grid .container');
        grid.insertBefore(message, grid.firstChild);
    }
}

// Animaciones al hacer scroll (heredado del patrón sabores)
function animateOnScroll() {
    const elements = document.querySelectorAll('.sabor-card, .info-card, .filter-btn');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('fade-in');
        }
    });
}

// Indicador de carga (heredado del patrón sabores)
function showLoadingIndicator() {
    const indicator = document.createElement('div');
    indicator.innerHTML = `
        <div style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(139, 69, 19, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            color: white;
            font-size: 1.2rem;
        ">
            <div style="text-align: center;">
                <div style="
                    width: 50px;
                    height: 50px;
                    border: 3px solid rgba(255,255,255,0.3);
                    border-top: 3px solid white;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin: 0 auto 1rem;
                "></div>
                Cambiando idioma...
            </div>
        </div>
    `;
    
    document.body.appendChild(indicator);
}

// Función para mostrar notificaciones (heredada del patrón sabores)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
        color: white;
        border-radius: 5px;
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Efectos de parallax para el hero (heredado del patrón sabores)
function initParallaxEffect() {
    const hero = document.querySelector('.sabores-hero');
    
    if (hero) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3;
            hero.style.transform = `translateY(${rate}px)`;
        });
    }
}

// Inicializar parallax (heredado del patrón sabores)
initParallaxEffect();

// Función para manejar errores de carga de imágenes (heredada del patrón sabores)
function handleImageErrors() {
    const images = document.querySelectorAll('img');
    
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiBubyBkaXNwb25pYmxlPC90ZXh0Pjwvc3ZnPg==';
            this.alt = 'Imagen no disponible';
        });
    });
}

// Funciones específicas para ubicación
function openInGoogleMaps() {
    const modal = document.getElementById('saborModal');
    const coordinates = modal.querySelector('#modalRating .coordinates span').textContent;
    const coords = coordinates.split(',');
    
    if (coords.length === 2) {
        const lat = parseFloat(coords[0].replace('°N', ''));
        const lng = parseFloat(coords[1].replace('°W', '')) * -1;
        const url = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
        window.open(url, '_blank');
    }
}

function shareLocation(name, lat, lng) {
    const url = `https://maps.google.com/?q=${lat},${lng}`;
    const text = `Descubre ${name} en Imbabura, Ecuador`;
    
    if (navigator.share) {
        navigator.share({
            title: name,
            text: text,
            url: url
        });
    } else {
        // Fallback para navegadores que no soportan Web Share API
        const shareUrl = encodeURIComponent(url);
        const shareText = encodeURIComponent(text);
        const twitterUrl = `https://twitter.com/intent/tweet?url=${shareUrl}&text=${shareText}`;
        window.open(twitterUrl, '_blank');
    }
}

function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Inicializar manejo de errores de imágenes (heredado del patrón sabores)
handleImageErrors();

// Agregar estilos para las animaciones (heredado del patrón sabores)
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(additionalStyles);

// Exportar funciones globales
window.openInGoogleMaps = openInGoogleMaps;
window.shareLocation = shareLocation;
window.smoothScrollTo = smoothScrollTo;
window.loadWeatherData = loadWeatherData;

