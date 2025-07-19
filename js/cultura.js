/**
 * Cultura.js - Funcionalidades interactivas para la página de cultura de Imbabura, Ecuador
 */

// Configuración global
const CulturaApp = {
    // Configuración de la galería de fotos
    photoGallery: {
        photos: [
            {
                src: 'images/tradiciones_imbabura.jpg',
                title: 'Tradiciones de Imbabura',
                description: 'Las ricas tradiciones culturales de la provincia de Imbabura se mantienen vivas a través de las generaciones.'
            },
            {
                src: 'images/personajes_tradicionales.jpeg',
                title: 'Personajes Tradicionales',
                description: 'Los personajes tradicionales de Imbabura representan la diversidad cultural de la región.'
            },
            {
                src: 'images/etnias_imbabura.jpg',
                title: 'Etnias de Imbabura',
                description: 'La conjugación de diferentes etnias ha enriquecido la cultura de Imbabura.'
            },
            {
                src: 'images/semana_santa.jpg',
                title: 'Semana Santa en Imbabura',
                description: 'Las celebraciones de Semana Santa muestran la fusión de tradiciones ancestrales y católicas.'
            },
            {
                src: 'images/artesanias_ecuador.jpg',
                title: 'Artesanías Tradicionales',
                description: 'Las artesanías ecuatorianas reflejan siglos de tradición y maestría artesanal.'
            },
            {
                src: 'images/artesanias_coloridas.jpg',
                title: 'Artesanías Coloridas',
                description: 'Los colores vibrantes de las artesanías ecuatorianas representan la alegría de su pueblo.'
            }
        ],
        currentIndex: 0
    },

    // Configuración de eventos culturales
    events: [
        {
            name: 'Inti Raymi',
            date: '21 de Junio',
            description: 'Celebración del solsticio de invierno, una de las festividades más importantes de la cultura andina.',
            location: 'Caranqui, Ibarra',
            type: 'festival'
        },
        {
            name: 'Fiesta del Maíz',
            date: '15 de Septiembre',
            description: 'Agradecimiento a la Pachamama por la cosecha del maíz.',
            location: 'Comunidades rurales de Imbabura',
            type: 'agricultural'
        },
        {
            name: 'Día de los Difuntos',
            date: '2 de Noviembre',
            description: 'Tradición de honrar a los antepasados con ofrendas y visitas al cementerio.',
            location: 'Cementerios de Imbabura',
            type: 'traditional'
        },
        {
            name: 'Pawkar Raymi',
            date: '21 de Marzo',
            description: 'Celebración del equinoccio de primavera y el florecimiento de la naturaleza.',
            location: 'Otavalo y comunidades aledañas',
            type: 'festival'
        },
        {
            name: 'Fiesta de San Juan',
            date: '24 de Junio',
            description: 'Celebración que combina tradiciones católicas con rituales ancestrales andinos.',
            location: 'Toda la provincia de Imbabura',
            type: 'religious'
        }
    ],

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
    console.log('Inicializando aplicación de cultura...');
    
    // Inicializar componentes
    initializePhotoGallery();
    initializeScrollAnimations();
    initializeNavbarEffects();
    initializeEventCards();
    initializeParallaxEffects();
    initializeLazyLoading();
    
    console.log('Aplicación inicializada correctamente');
}

// Galería de fotos
function initializePhotoGallery() {
    const galleryContainer = document.getElementById('photo-gallery');
    if (!galleryContainer) return;

    // Mostrar loader
    showGalleryLoader(galleryContainer);

    // Simular carga de imágenes (reemplaza la API de Unsplash)
    setTimeout(() => {
        loadLocalPhotos(galleryContainer);
    }, 1000);
}

function showGalleryLoader(container) {
    container.innerHTML = `
        <div class="gallery-loader">
            <div class="spinner"></div>
            <span>Cargando galería cultural...</span>
        </div>
    `;
}

function loadLocalPhotos(container) {
    container.innerHTML = '';
    
    CulturaApp.photoGallery.photos.forEach((photo, index) => {
        const photoElement = createPhotoElement(photo, index);
        container.appendChild(photoElement);
    });

    // Aplicar animaciones escalonadas
    const photoItems = container.querySelectorAll('.photo-item');
    photoItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate-fade-in');
        }, index * 200);
    });
}

function createPhotoElement(photo, index) {
    const col = document.createElement('div');
    col.className = 'col-md-4 mb-4';
    
    col.innerHTML = `
        <div class="photo-item hover-glow" data-index="${index}">
            <img src="${photo.src}" alt="${photo.title}" class="img-fluid" loading="lazy">
            <div class="photo-overlay">
                <h5>${photo.title}</h5>
                <p>${photo.description}</p>
            </div>
        </div>
    `;

    // Agregar evento de click para modal
    col.addEventListener('click', () => openPhotoModal(index));
    
    return col;
}

function openPhotoModal(index) {
    const photo = CulturaApp.photoGallery.photos[index];
    
    // Crear modal dinámicamente
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-gradient">${photo.title}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="${photo.src}" alt="${photo.title}" class="img-fluid rounded">
                    <p class="mt-3">${photo.description}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cultura" onclick="sharePhoto('${photo.title}', '${photo.src}')">
                        Compartir
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Limpiar modal al cerrar
    modal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(modal);
    });
}

// Animaciones de scroll
function initializeScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                
                // Aplicar animación basada en la clase
                if (element.classList.contains('culture-card')) {
                    element.classList.add('animate-fade-in');
                } else if (element.classList.contains('event-card')) {
                    element.classList.add('animate-slide-left');
                } else if (element.classList.contains('video-container')) {
                    element.classList.add('animate-slide-right');
                }
                
                observer.unobserve(element);
            }
        });
    }, CulturaApp.animations.observerOptions);

    // Observar elementos
    document.querySelectorAll('.culture-card, .event-card, .video-container').forEach(el => {
        observer.observe(el);
    });
}

// Efectos del navbar
function initializeNavbarEffects() {
    const navbar = document.querySelector('.navbar-custom');
    if (!navbar) return;

    let lastScrollTop = 0;
    
    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Efecto de transparencia basado en scroll
        if (scrollTop > 100) {
            navbar.style.background = 'linear-gradient(135deg, rgba(139, 69, 19, 0.95) 0%, rgba(210, 105, 30, 0.95) 100%)';
            navbar.style.backdropFilter = 'blur(10px)';
        } else {
            navbar.style.background = 'linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%)';
            navbar.style.backdropFilter = 'none';
        }
        
        lastScrollTop = scrollTop;
    });
}

// Efectos en las tarjetas de eventos
function initializeEventCards() {
    const eventCards = document.querySelectorAll('.event-card');
    
    eventCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateX(15px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateX(0) scale(1)';
        });
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

// Carga lazy de imágenes
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.src; // Trigger load
                img.classList.add('animate-fade-in');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
}

// Funciones utilitarias
function sharePhoto(title, src) {
    if (navigator.share) {
        navigator.share({
            title: `Cultura de Imbabura - ${title}`,
            text: `Descubre la rica cultura de Imbabura: ${title}`,
            url: window.location.href
        });
    } else {
        // Fallback para navegadores que no soportan Web Share API
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(`Descubre la rica cultura de Imbabura: ${title}`);
        const shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${text}`;
        window.open(shareUrl, '_blank');
    }
}

function filterEvents(type) {
    const eventCards = document.querySelectorAll('.event-card');
    
    eventCards.forEach(card => {
        if (type === 'all' || card.dataset.type === type) {
            card.style.display = 'block';
            card.classList.add('animate-fade-in');
        } else {
            card.style.display = 'none';
        }
    });
}

// Función para cambio de idioma
function changeLanguage(langCode) {
    // Esta función se integraría con el sistema de idiomas existente
    console.log(`Cambiando idioma a: ${langCode}`);
    
    // Aquí se implementaría la lógica de cambio de idioma
    // Por ahora, solo recarga la página con el nuevo idioma
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('lang', langCode);
    window.location.href = currentUrl.toString();
}

// Función para mostrar información adicional de eventos
function showEventDetails(eventName) {
    const event = CulturaApp.events.find(e => e.name === eventName);
    if (!event) return;

    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-gradient">${event.name}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Fecha:</strong> ${event.date}</p>
                    <p><strong>Ubicación:</strong> ${event.location}</p>
                    <p><strong>Tipo:</strong> ${event.type}</p>
                    <p>${event.description}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cultura" onclick="addToCalendar('${event.name}', '${event.date}')">
                        Agregar al Calendario
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

function addToCalendar(eventName, eventDate) {
    // Crear evento de calendario
    const startDate = new Date(eventDate).toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    const endDate = new Date(new Date(eventDate).getTime() + 2 * 60 * 60 * 1000).toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
    
    const calendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(eventName)}&dates=${startDate}/${endDate}&details=${encodeURIComponent('Evento cultural de Imbabura')}&location=${encodeURIComponent('Imbabura, Ecuador')}`;
    
    window.open(calendarUrl, '_blank');
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

// Función para toggle del menú móvil
function toggleMobileMenu() {
    const navbarCollapse = document.querySelector('.navbar-collapse');
    if (navbarCollapse) {
        navbarCollapse.classList.toggle('show');
    }
}

// Función para búsqueda en el contenido
function searchContent(query) {
    const searchResults = [];
    const contentElements = document.querySelectorAll('h1, h2, h3, p');
    
    contentElements.forEach(element => {
        if (element.textContent.toLowerCase().includes(query.toLowerCase())) {
            searchResults.push({
                element: element,
                text: element.textContent,
                type: element.tagName
            });
        }
    });
    
    return searchResults;
}

// Exportar funciones globales
window.CulturaApp = CulturaApp;
window.sharePhoto = sharePhoto;
window.filterEvents = filterEvents;
window.changeLanguage = changeLanguage;
window.showEventDetails = showEventDetails;
window.addToCalendar = addToCalendar;
window.smoothScrollTo = smoothScrollTo;
window.toggleMobileMenu = toggleMobileMenu;
window.searchContent = searchContent;

// Manejo de errores global
window.addEventListener('error', function(e) {
    console.error('Error en la aplicación de cultura:', e.error);
});

// Función de limpieza al salir de la página
window.addEventListener('beforeunload', function() {
    // Limpiar observadores y eventos si es necesario
    console.log('Limpiando recursos de la aplicación...');
});

