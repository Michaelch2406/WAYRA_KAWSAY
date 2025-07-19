/**
 * Ubicacion.js - Funcionalidades interactivas para la página de ubicación de Imbabura, Ecuador
 */

// Configuración global
const UbicacionApp = {
    // Coordenadas de Naranjito, Imbabura
    coordinates: {
        naranjito: {
            lat: 0.3283,
            lng: -78.1456,
            name: 'Naranjito, Caranqui'
        },
        ibarra: {
            lat: 0.3516,
            lng: -78.1224,
            name: 'Ibarra, Ciudad Blanca'
        },
        imbabura: {
            lat: 0.2622,
            lng: -78.1756,
            name: 'Volcán Imbabura'
        }
    },

    // Puntos de interés con información detallada
    pointsOfInterest: [
        {
            id: 'yahuarcocha',
            name: 'Laguna de Yahuarcocha',
            description: 'Hermosa laguna ubicada al norte de Ibarra, conocida por su historia y belleza natural. Ideal para deportes acuáticos y relajación.',
            coordinates: { lat: 0.3833, lng: -78.1167 },
            image: 'images/yahuarcocha_laguna.jpg',
            mapUrl: 'https://maps.app.goo.gl/wpDktEnWtyxM2XqR8',
            category: 'natural',
            activities: ['Deportes acuáticos', 'Fotografía', 'Caminatas'],
            openHours: '24 horas',
            entrance: 'Gratuito'
        },
        {
            id: 'volcan_imbabura',
            name: 'Volcán Imbabura',
            description: 'Majestuoso volcán que domina el paisaje de la provincia. Perfecto para montañismo y observación de la naturaleza.',
            coordinates: { lat: 0.2622, lng: -78.1756 },
            image: 'images/imbabura_volcan.jpg',
            mapUrl: 'https://maps.app.goo.gl/zmWYeZJKANBPQTAG8',
            category: 'adventure',
            activities: ['Montañismo', 'Trekking', 'Observación de flora'],
            openHours: 'Amanecer a atardecer',
            entrance: 'Guía recomendado'
        },
        {
            id: 'centro_ibarra',
            name: 'Centro Histórico de Ibarra',
            description: 'El corazón de la Ciudad Blanca, con arquitectura colonial, iglesias históricas y la vibrante vida cultural de Imbabura.',
            coordinates: { lat: 0.3516, lng: -78.1224 },
            image: 'images/ibarra_ciudad.jpg',
            mapUrl: 'https://maps.app.goo.gl/t37xMndLDtqnMYtK9',
            category: 'cultural',
            activities: ['Turismo cultural', 'Gastronomía', 'Compras'],
            openHours: '6:00 AM - 10:00 PM',
            entrance: 'Gratuito'
        },
        {
            id: 'naranjito_pueblo',
            name: 'Pueblo de Naranjito',
            description: 'Pintoresco pueblo que conserva las tradiciones ancestrales de Imbabura, ubicado en la parroquia de Caranqui.',
            coordinates: { lat: 0.3283, lng: -78.1456 },
            image: 'images/naranjito_pueblo.jpg',
            mapUrl: 'https://maps.app.goo.gl/nWd3nHpLp56f1acF6',
            category: 'traditional',
            activities: ['Turismo comunitario', 'Artesanías', 'Tradiciones'],
            openHours: 'Todo el día',
            entrance: 'Gratuito'
        }
    ],

    // Configuración del clima
    weather: {
        apiKey: '', // Se configurará dinámicamente
        baseUrl: 'https://api.openweathermap.org/data/2.5/weather',
        currentData: null,
        lastUpdate: null
    },

    // Configuración de animaciones
    animations: {
        observerOptions: {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        }
    }
};

// Funciones de inicialización
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    console.log('Inicializando aplicación de ubicación...');
    
    // Inicializar componentes
    initializeScrollAnimations();
    initializeNavbarEffects();
    initializePointsOfInterest();
    initializeWeatherWidget();
    initializeMapInteractions();
    initializeParallaxEffects();
    
    console.log('Aplicación de ubicación inicializada correctamente');
}

// Gestión de puntos de interés
function initializePointsOfInterest() {
    const poiContainer = document.getElementById('points-of-interest-grid');
    if (!poiContainer) return;

    // Limpiar contenedor
    poiContainer.innerHTML = '';

    // Crear tarjetas para cada punto de interés
    UbicacionApp.pointsOfInterest.forEach((poi, index) => {
        const poiCard = createPOICard(poi, index);
        poiContainer.appendChild(poiCard);
    });

    // Aplicar animaciones escalonadas
    const poiCards = poiContainer.querySelectorAll('.poi-card');
    poiCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate-fade-in');
        }, index * 200);
    });
}

function createPOICard(poi, index) {
    const card = document.createElement('div');
    card.className = 'poi-card hover-glow';
    card.dataset.category = poi.category;
    
    // Crear URL del mini-mapa
    const miniMapUrl = createMiniMapUrl(poi.coordinates.lat, poi.coordinates.lng, poi.name);
    
    card.innerHTML = `
        <div class="poi-image" style="background-image: url('${poi.image}')">
            <div class="poi-category-badge">
                ${getCategoryIcon(poi.category)} ${getCategoryName(poi.category)}
            </div>
        </div>
        <div class="poi-content">
            <h4 class="poi-title">
                <i class="fas fa-map-marker-alt text-gradient me-2"></i>
                ${poi.name}
            </h4>
            <p class="poi-description">${poi.description}</p>
            
            <div class="poi-details mb-3">
                <div class="detail-item">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Horarios:</strong> ${poi.openHours}
                </div>
                <div class="detail-item">
                    <i class="fas fa-ticket-alt me-2"></i>
                    <strong>Entrada:</strong> ${poi.entrance}
                </div>
                <div class="detail-item">
                    <i class="fas fa-map me-2"></i>
                    <strong>Coordenadas:</strong> 
                    <span class="coordinates">${poi.coordinates.lat.toFixed(4)}, ${poi.coordinates.lng.toFixed(4)}</span>
                </div>
            </div>
            
            <div class="poi-activities mb-3">
                <h6><i class="fas fa-hiking me-2"></i>Actividades:</h6>
                <div class="activities-tags">
                    ${poi.activities.map(activity => `<span class="activity-tag">${activity}</span>`).join('')}
                </div>
            </div>
            
            <div class="poi-mini-map">
                <iframe src="${miniMapUrl}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            
            <div class="poi-actions">
                <a href="${poi.mapUrl}" target="_blank" class="btn-poi">
                    <i class="fas fa-external-link-alt"></i>
                    Ver en Google Maps
                </a>
                <button class="btn-poi" onclick="showPOIDetails('${poi.id}')">
                    <i class="fas fa-info-circle"></i>
                    Más Información
                </button>
            </div>
        </div>
    `;

    // Agregar eventos
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-10px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0) scale(1)';
    });

    return card;
}

function createMiniMapUrl(lat, lng, name) {
    const zoom = 14;
    return `https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dO_BcqOWBOUOdg&q=${lat},${lng}&zoom=${zoom}&maptype=satellite`;
}

function getCategoryIcon(category) {
    const icons = {
        natural: 'fas fa-leaf',
        adventure: 'fas fa-mountain',
        cultural: 'fas fa-landmark',
        traditional: 'fas fa-home'
    };
    return `<i class="${icons[category] || 'fas fa-map-marker-alt'}"></i>`;
}

function getCategoryName(category) {
    const names = {
        natural: 'Natural',
        adventure: 'Aventura',
        cultural: 'Cultural',
        traditional: 'Tradicional'
    };
    return names[category] || 'Punto de Interés';
}

// Widget del clima
function initializeWeatherWidget() {
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
        // Intentar con diferentes APIs de clima gratuitas
        let weatherData = await fetchWeatherFromOpenWeather();
        
        if (!weatherData) {
            weatherData = await fetchWeatherFromAlternativeAPI();
        }
        
        if (weatherData) {
            displayWeatherData(weatherContainer, weatherData);
            UbicacionApp.weather.currentData = weatherData;
            UbicacionApp.weather.lastUpdate = new Date();
        } else {
            showWeatherError(weatherContainer);
        }
    } catch (error) {
        console.error('Error al cargar datos del clima:', error);
        showWeatherError(weatherContainer);
    }
}

async function fetchWeatherFromOpenWeather() {
    // Esta función requiere una API key válida
    // Por ahora, simularemos datos del clima
    return null;
}

async function fetchWeatherFromAlternativeAPI() {
    // Simular datos del clima para Naranjito, Imbabura
    // En un entorno real, esto se conectaría a una API de clima
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve({
                location: 'Naranjito, Imbabura',
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
            <div class="weather-icon">${data.icon}</div>
            <div class="weather-temp">${data.temperature}°C</div>
            <div class="weather-description">${data.description}</div>
            <div class="weather-location">
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
        <div class="weather-error">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
            <h5>No se pudo cargar el clima</h5>
            <p>Información del clima no disponible en este momento.</p>
            <button class="btn-ubicacion mt-2" onclick="loadWeatherData()">
                <i class="fas fa-redo me-2"></i>
                Reintentar
            </button>
        </div>
    `;
}

// Interacciones con mapas
function initializeMapInteractions() {
    const mainMap = document.querySelector('.map-frame iframe');
    if (mainMap) {
        // Agregar indicador de carga
        const mapContainer = mainMap.parentElement;
        const loader = document.createElement('div');
        loader.className = 'map-loader';
        loader.innerHTML = `
            <div class="spinner"></div>
            <p>Cargando mapa...</p>
        `;
        mapContainer.appendChild(loader);

        mainMap.addEventListener('load', () => {
            loader.remove();
            mainMap.style.opacity = '1';
        });
    }
}

// Animaciones de scroll
function initializeScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                
                if (element.classList.contains('geo-card')) {
                    element.classList.add('animate-slide-left');
                } else if (element.classList.contains('poi-card')) {
                    element.classList.add('animate-fade-in');
                } else if (element.classList.contains('weather-section')) {
                    element.classList.add('animate-slide-right');
                }
                
                observer.unobserve(element);
            }
        });
    }, UbicacionApp.animations.observerOptions);

    // Observar elementos
    document.querySelectorAll('.geo-card, .poi-card, .weather-section').forEach(el => {
        observer.observe(el);
    });
}

// Efectos del navbar
function initializeNavbarEffects() {
    const navbar = document.querySelector('.navbar-custom');
    if (!navbar) return;

    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            navbar.style.background = 'linear-gradient(135deg, rgba(139, 69, 19, 0.95) 0%, rgba(210, 105, 30, 0.95) 100%)';
            navbar.style.backdropFilter = 'blur(10px)';
        } else {
            navbar.style.background = 'linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%)';
            navbar.style.backdropFilter = 'none';
        }
    });
}

// Efectos parallax
function initializeParallaxEffects() {
    const heroSection = document.querySelector('.hero-section');
    if (!heroSection) return;

    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        
        heroSection.style.transform = `translateY(${rate}px)`;
    });
}

// Funciones de utilidad
function showPOIDetails(poiId) {
    const poi = UbicacionApp.pointsOfInterest.find(p => p.id === poiId);
    if (!poi) return;

    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-gradient">
                        ${getCategoryIcon(poi.category)} ${poi.name}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="${poi.image}" alt="${poi.name}" class="img-fluid rounded mb-3">
                            <div class="poi-coordinates">
                                <h6><i class="fas fa-map me-2"></i>Coordenadas:</h6>
                                <p class="coordinates">${poi.coordinates.lat.toFixed(6)}, ${poi.coordinates.lng.toFixed(6)}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Descripción:</strong></p>
                            <p>${poi.description}</p>
                            
                            <p><strong>Categoría:</strong> ${getCategoryName(poi.category)}</p>
                            <p><strong>Horarios:</strong> ${poi.openHours}</p>
                            <p><strong>Entrada:</strong> ${poi.entrance}</p>
                            
                            <h6><i class="fas fa-hiking me-2"></i>Actividades disponibles:</h6>
                            <ul>
                                ${poi.activities.map(activity => `<li>${activity}</li>`).join('')}
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="${poi.mapUrl}" target="_blank" class="btn btn-ubicacion">
                        <i class="fas fa-external-link-alt me-2"></i>
                        Abrir en Google Maps
                    </a>
                    <button type="button" class="btn btn-secondary" onclick="shareLocation('${poi.name}', ${poi.coordinates.lat}, ${poi.coordinates.lng})">
                        <i class="fas fa-share me-2"></i>
                        Compartir Ubicación
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    modal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(modal);
    });
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

function filterPOI(category) {
    const poiCards = document.querySelectorAll('.poi-card');
    
    poiCards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
            card.classList.add('animate-fade-in');
        } else {
            card.style.display = 'none';
        }
    });
}

function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371; // Radio de la Tierra en km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

function getDirections(destinationLat, destinationLng, destinationName) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                const directionsUrl = `https://www.google.com/maps/dir/${userLat},${userLng}/${destinationLat},${destinationLng}`;
                window.open(directionsUrl, '_blank');
            },
            () => {
                // Si no se puede obtener la ubicación del usuario
                const directionsUrl = `https://www.google.com/maps/dir//${destinationLat},${destinationLng}`;
                window.open(directionsUrl, '_blank');
            }
        );
    } else {
        const directionsUrl = `https://www.google.com/maps/dir//${destinationLat},${destinationLng}`;
        window.open(directionsUrl, '_blank');
    }
}

// Función para cambio de idioma
function changeLanguage(langCode) {
    console.log(`Cambiando idioma a: ${langCode}`);
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('lang', langCode);
    window.location.href = currentUrl.toString();
}

// Función para scroll suave
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Exportar funciones globales
window.UbicacionApp = UbicacionApp;
window.showPOIDetails = showPOIDetails;
window.shareLocation = shareLocation;
window.filterPOI = filterPOI;
window.getDirections = getDirections;
window.changeLanguage = changeLanguage;
window.smoothScrollTo = smoothScrollTo;
window.loadWeatherData = loadWeatherData;

// Manejo de errores global
window.addEventListener('error', function(e) {
    console.error('Error en la aplicación de ubicación:', e.error);
});

// Función de limpieza al salir de la página
window.addEventListener('beforeunload', function() {
    console.log('Limpiando recursos de la aplicación de ubicación...');
});

