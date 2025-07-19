// Kichwa Page JavaScript - Imbabura, Ecuador

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades
    initLanguageSelector();
    initTableAnimations();
    initAudioControls();
    initScrollAnimations();
    initResponsiveFeatures();
    
    console.log('Kichwa page initialized - Imbabura, Ecuador');
});

// Selector de idioma
function initLanguageSelector() {
    const languageSelector = document.getElementById('language-selector');
    
    if (languageSelector) {
        languageSelector.addEventListener('change', function() {
            const selectedLang = this.value;
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('lang', selectedLang);
            
            // Mostrar indicador de carga
            showLoadingIndicator();
            
            // Redirigir con el nuevo idioma
            window.location.href = currentUrl.toString();
        });
    }
}

// Animaciones de tabla
function initTableAnimations() {
    const tableRows = document.querySelectorAll('.kichwa-table tbody tr');
    
    tableRows.forEach((row, index) => {
        // Animación de entrada escalonada
        row.style.animationDelay = `${index * 0.1}s`;
        row.classList.add('fade-in-up');
        
        // Efecto hover mejorado
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.boxShadow = '0 5px 15px rgba(139, 69, 19, 0.2)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });
}

// Controles de audio mejorados
function initAudioControls() {
    const audioElements = document.querySelectorAll('audio');
    
    audioElements.forEach(audio => {
        // Personalizar controles de audio
        audio.addEventListener('loadstart', function() {
            this.parentElement.classList.add('loading-audio');
        });
        
        audio.addEventListener('canplay', function() {
            this.parentElement.classList.remove('loading-audio');
        });
        
        audio.addEventListener('play', function() {
            // Pausar otros audios cuando uno empiece a reproducir
            audioElements.forEach(otherAudio => {
                if (otherAudio !== this && !otherAudio.paused) {
                    otherAudio.pause();
                }
            });
            
            // Añadir efecto visual
            this.parentElement.classList.add('playing');
        });
        
        audio.addEventListener('pause', function() {
            this.parentElement.classList.remove('playing');
        });
        
        audio.addEventListener('ended', function() {
            this.parentElement.classList.remove('playing');
        });
        
        // Manejo de errores
        audio.addEventListener('error', function() {
            console.error('Error loading audio:', this.src);
            this.parentElement.innerHTML = '<span class="audio-error">Audio no disponible</span>';
        });
    });
}

// Animaciones de scroll
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                
                // Efecto especial para imágenes culturales
                if (entry.target.classList.contains('cultural-image')) {
                    entry.target.classList.add('sparkle');
                }
            }
        });
    }, observerOptions);
    
    // Observar elementos para animaciones
    const elementsToAnimate = document.querySelectorAll('.main-content, .cultural-image, .kichwa-table');
    elementsToAnimate.forEach(el => observer.observe(el));
}

// Características responsivas
function initResponsiveFeatures() {
    // Manejo del menú móvil
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            setTimeout(() => {
                if (navbarCollapse.classList.contains('show')) {
                    navbarCollapse.style.animation = 'slideDown 0.3s ease-out';
                } else {
                    navbarCollapse.style.animation = 'slideUp 0.3s ease-out';
                }
            }, 10);
        });
    }
    
    // Ajustar tabla en dispositivos móviles
    adjustTableForMobile();
    
    // Escuchar cambios de orientación
    window.addEventListener('orientationchange', function() {
        setTimeout(adjustTableForMobile, 500);
    });
    
    // Escuchar cambios de tamaño de ventana
    window.addEventListener('resize', debounce(adjustTableForMobile, 250));
}

// Ajustar tabla para móviles
function adjustTableForMobile() {
    const table = document.querySelector('.kichwa-table');
    const isMobile = window.innerWidth <= 768;
    
    if (table && isMobile) {
        // Hacer la tabla scrolleable horizontalmente en móviles
        table.style.overflowX = 'auto';
        table.style.whiteSpace = 'nowrap';
        
        // Ajustar el tamaño de fuente
        const cells = table.querySelectorAll('th, td');
        cells.forEach(cell => {
            cell.style.fontSize = window.innerWidth <= 576 ? '0.8rem' : '0.9rem';
        });
    } else if (table) {
        table.style.overflowX = 'visible';
        table.style.whiteSpace = 'normal';
    }
}

// Mostrar indicador de carga
function showLoadingIndicator() {
    const indicator = document.createElement('div');
    indicator.className = 'loading-overlay';
    indicator.innerHTML = `
        <div class="loading-content">
            <div class="loading"></div>
            <p>Cambiando idioma...</p>
        </div>
    `;
    
    // Estilos del indicador
    indicator.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(139, 69, 19, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: #F5F5DC;
        font-weight: bold;
    `;
    
    document.body.appendChild(indicator);
}

// Función debounce para optimizar eventos
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Funciones de utilidad para efectos especiales
function addSparkleEffect(element) {
    element.classList.add('sparkle');
    setTimeout(() => {
        element.classList.remove('sparkle');
    }, 3000);
}

// Manejo de errores globales
window.addEventListener('error', function(e) {
    console.error('Error en la página Kichwa:', e.error);
});

// Funciones adicionales para mejorar la experiencia
function highlightSearchTerm(term) {
    if (!term) return;
    
    const tableRows = document.querySelectorAll('.kichwa-table tbody tr');
    tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let found = false;
        
        cells.forEach(cell => {
            const text = cell.textContent.toLowerCase();
            if (text.includes(term.toLowerCase())) {
                cell.style.backgroundColor = 'rgba(218, 165, 32, 0.3)';
                found = true;
            } else {
                cell.style.backgroundColor = '';
            }
        });
        
        row.style.display = found ? '' : 'none';
    });
}

// Función para limpiar búsqueda
function clearSearch() {
    const tableRows = document.querySelectorAll('.kichwa-table tbody tr');
    tableRows.forEach(row => {
        row.style.display = '';
        const cells = row.querySelectorAll('td');
        cells.forEach(cell => {
            cell.style.backgroundColor = '';
        });
    });
}

// Exportar funciones para uso externo si es necesario
window.KichwaPage = {
    highlightSearchTerm,
    clearSearch,
    addSparkleEffect
};

