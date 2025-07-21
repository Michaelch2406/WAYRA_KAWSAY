// JavaScript para funcionalidades específicas de la página de Kichwa - Adaptado del patrón de sabores

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades (removido initLanguageSelector para evitar conflictos)
    initScrollEffects();
    initNavbarEffects();
    initFilterSystem();
    initTableAnimations();
    initAudioControls();
    initSearchFunctionality();
    initCulturalGallery();
    initScrollToTop();
    initScrollAnimations();
    initResponsiveFeatures();
    
    console.log('Kichwa page initialized - Imbabura, Ecuador');
});

// Función para manejar el selector de idioma
function initLanguageSelector() {
    const languageSelector = document.getElementById('language-selector');
    
    if (languageSelector) {
        languageSelector.addEventListener('change', function() {
            const selectedLang = this.value;
            
            // Mostrar indicador de carga
            showLoadingIndicator();
            
            // Simular cambio de idioma (en una implementación real, esto redirigiría)
            setTimeout(() => {
                window.location.href = `?lang=${selectedLang}`;
            }, 500);
        });
    }
}


// Efectos de scroll (heredado del patrón de sabores)
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

// Efectos de navegación (heredado del patrón de sabores)
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

// Sistema de filtros adaptado para Kichwa
function initFilterSystem() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('.kichwa-row');
    const culturalCards = document.querySelectorAll('.cultural-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            // Filtrar contenido según el tipo
            filterKichwaContent(filterValue, tableRows, culturalCards);
            
            // Mostrar notificación
            showNotification(`Mostrando: ${this.textContent}`, 'info');
        });
    });
}

// Función para filtrar contenido de Kichwa
function filterKichwaContent(filterValue, tableRows, culturalCards) {
    switch(filterValue) {
        case 'all':
            showAllContent(tableRows, culturalCards);
            break;
        case 'search':
            focusSearchInput();
            break;
        case 'audio':
            filterByAudio(tableRows);
            break;
        case 'culture':
            showCulturalContent(tableRows, culturalCards);
            break;
        default:
            showAllContent(tableRows, culturalCards);
    }
}

// Mostrar todo el contenido
function showAllContent(tableRows, culturalCards) {
    tableRows.forEach(row => {
        row.style.display = '';
        row.style.animation = 'fadeInUp 0.5s ease-out';
    });
    
    document.querySelector('.kichwa-dictionary').style.display = 'block';
    document.querySelector('.cultural-section').style.display = 'block';
}

// Enfocar en la búsqueda
function focusSearchInput() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.focus();
        searchInput.style.borderColor = 'var(--color-food-warm)';
        setTimeout(() => {
            searchInput.style.borderColor = '';
        }, 2000);
    }
}

// Filtrar por audio disponible
function filterByAudio(tableRows) {
    tableRows.forEach(row => {
        const audioCell = row.querySelector('.audio-cell audio');
        if (audioCell) {
            row.style.display = '';
            row.style.animation = 'fadeInUp 0.5s ease-out';
        } else {
            row.style.display = 'none';
        }
    });
    
    document.querySelector('.cultural-section').style.display = 'none';
}

// Mostrar contenido cultural
function showCulturalContent(tableRows, culturalCards) {
    tableRows.forEach(row => {
        row.style.display = 'none';
    });
    
    document.querySelector('.kichwa-dictionary').style.display = 'none';
    document.querySelector('.cultural-section').style.display = 'block';
    
    // Animar tarjetas culturales
    culturalCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.animation = 'fadeInUp 0.5s ease-out';
        }, index * 100);
    });
}

// Animaciones de tabla simplificadas (sin efectos hover)
function initTableAnimations() {
    const tableRows = document.querySelectorAll('.kichwa-row');
    
    tableRows.forEach((row, index) => {
        // Asegurar visibilidad
        row.style.opacity = '1';
        row.style.visibility = 'visible';
        row.style.display = 'table-row';
        
        // Animación de entrada suave
        row.classList.add('fade-in');
        
        // Click para resaltar palabra
        row.addEventListener('click', function() {
            highlightWord(this);
        });
    });
}

// Resaltar palabra seleccionada
function highlightWord(row) {
    // Remover resaltado previo
    document.querySelectorAll('.kichwa-row').forEach(r => {
        r.classList.remove('highlighted');
    });
    
    // Agregar resaltado a la fila actual
    row.classList.add('highlighted');
    
    // Reproducir audio si está disponible
    const audio = row.querySelector('audio');
    if (audio) {
        audio.play().catch(e => console.log('Error playing audio:', e));
    }
    
    // Mostrar información adicional
    const kichwaWord = row.querySelector('.kichwa-word').textContent;
    const spanishTranslation = row.querySelector('.spanish-translation').textContent;
    
    showNotification(`${kichwaWord} - ${spanishTranslation}`, 'success');
}

// Controles de audio mejorados (funcionalidad específica de Kichwa)
function initAudioControls() {
    const audioElements = document.querySelectorAll('audio');
    
    audioElements.forEach(audio => {
        // Personalizar controles de audio
        audio.addEventListener('loadstart', function() {
            this.parentElement.classList.add('loading-audio');
        });
        
        audio.addEventListener('canplay', function() {
            this.parentElement.classList.remove('loading-audio');
            this.parentElement.classList.add('status-indicator', 'available');
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
            this.closest('.kichwa-row').classList.add('audio-playing');
        });
        
        audio.addEventListener('pause', function() {
            this.parentElement.classList.remove('playing');
            this.closest('.kichwa-row').classList.remove('audio-playing');
        });
        
        audio.addEventListener('ended', function() {
            this.parentElement.classList.remove('playing');
            this.closest('.kichwa-row').classList.remove('audio-playing');
        });
        
        // Manejo de errores
        audio.addEventListener('error', function() {
            console.error('Error loading audio:', this.src);
            this.parentElement.innerHTML = '<span class="audio-error"><i class="fas fa-volume-mute"></i> Audio no disponible</span>';
            this.parentElement.classList.add('status-indicator', 'unavailable');
        });
    });
}

// Funcionalidad de búsqueda mejorada (específica de Kichwa)
function initSearchFunctionality() {
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        // Búsqueda en tiempo real
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            if (searchTerm.length > 1) {
                performKichwaSearch(searchTerm);
            } else if (searchTerm.length === 0) {
                clearKichwaSearch();
            }
        });
        
        // Búsqueda con Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    performKichwaSearch(searchTerm);
                }
            }
        });
        
        // Autocompletado
        initAutoComplete(searchInput);
    }
}

// Realizar búsqueda en Kichwa
function performKichwaSearch(searchTerm) {
    const tableRows = document.querySelectorAll('.kichwa-row');
    let visibleCount = 0;
    
    tableRows.forEach(row => {
        const kichwaWord = row.querySelector('.kichwa-word').textContent.toLowerCase();
        const spanishTranslation = row.querySelector('.spanish-translation').textContent.toLowerCase();
        const searchTermLower = searchTerm.toLowerCase();
        
        const matches = kichwaWord.includes(searchTermLower) || 
                       spanishTranslation.includes(searchTermLower);
        
        if (matches) {
            row.style.display = '';
            row.style.animation = 'fadeInUp 0.5s ease-out';
            highlightSearchTerm(row, searchTerm);
            visibleCount++;
        } else {
            row.style.display = 'none';
            clearHighlight(row);
        }
    });
    
    // Mostrar/ocultar mensaje de resultados
    toggleNoResults(visibleCount, searchTerm);
    
    // Mostrar estadísticas de búsqueda
    showSearchStats(visibleCount, searchTerm);
}

// Limpiar búsqueda
function clearKichwaSearch() {
    const tableRows = document.querySelectorAll('.kichwa-row');
    tableRows.forEach(row => {
        row.style.display = '';
        clearHighlight(row);
    });
    
    document.getElementById('noResults').style.display = 'none';
    hideSearchStats();
}

// Resaltar términos de búsqueda
function highlightSearchTerm(row, searchTerm) {
    const kichwaCell = row.querySelector('.kichwa-word');
    const spanishCell = row.querySelector('.spanish-translation');
    
    [kichwaCell, spanishCell].forEach(cell => {
        const originalText = cell.textContent;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        const highlightedText = originalText.replace(regex, '<span class="search-highlight">$1</span>');
        
        if (highlightedText !== originalText) {
            cell.innerHTML = highlightedText;
        }
    });
}

// Limpiar resaltado
function clearHighlight(row) {
    const cells = row.querySelectorAll('.kichwa-word, .spanish-translation');
    cells.forEach(cell => {
        cell.innerHTML = cell.textContent;
    });
}

// Mostrar/ocultar mensaje de no resultados
function toggleNoResults(count, searchTerm) {
    const noResultsDiv = document.getElementById('noResults');
    
    if (count === 0 && searchTerm) {
        noResultsDiv.style.display = 'block';
        noResultsDiv.innerHTML = `
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No se encontraron resultados para "${searchTerm}"</h4>
            <p class="text-muted">Intenta con otra palabra o revisa la ortografía</p>
        `;
    } else {
        noResultsDiv.style.display = 'none';
    }
}

// Mostrar estadísticas de búsqueda
function showSearchStats(count, searchTerm) {
    let statsDiv = document.getElementById('searchStats');
    
    if (!statsDiv) {
        statsDiv = document.createElement('div');
        statsDiv.id = 'searchStats';
        statsDiv.className = 'alert alert-info text-center mt-3';
        document.querySelector('.kichwa-dictionary').insertBefore(statsDiv, document.querySelector('.table-responsive'));
    }
    
    if (searchTerm && count > 0) {
        statsDiv.style.display = 'block';
        statsDiv.innerHTML = `
            <i class="fas fa-check-circle"></i>
            Se encontraron <strong>${count}</strong> palabra${count !== 1 ? 's' : ''} que coinciden con "<strong>${searchTerm}</strong>"
        `;
    } else {
        statsDiv.style.display = 'none';
    }
}

// Ocultar estadísticas de búsqueda
function hideSearchStats() {
    const statsDiv = document.getElementById('searchStats');
    if (statsDiv) {
        statsDiv.style.display = 'none';
    }
}

// Autocompletado
function initAutoComplete(searchInput) {
    const words = [];
    document.querySelectorAll('.kichwa-word, .spanish-translation').forEach(cell => {
        words.push(cell.textContent.trim());
    });
    
    searchInput.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        if (value.length > 1) {
            const suggestions = words.filter(word => 
                word.toLowerCase().includes(value)
            ).slice(0, 5);
            
            showSuggestions(suggestions, this);
        } else {
            hideSuggestions();
        }
    });
}

// Mostrar sugerencias
function showSuggestions(suggestions, input) {
    hideSuggestions();
    
    if (suggestions.length === 0) return;
    
    const suggestionsList = document.createElement('div');
    suggestionsList.id = 'suggestions';
    suggestionsList.className = 'suggestions-list';
    suggestionsList.style.cssText = `
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--color-accent);
        border-radius: 0 0 10px 10px;
        box-shadow: var(--shadow-medium);
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
    `;
    
    suggestions.forEach(suggestion => {
        const item = document.createElement('div');
        item.className = 'suggestion-item';
        item.textContent = suggestion;
        item.style.cssText = `
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid rgba(139, 69, 19, 0.1);
            transition: background-color 0.2s;
        `;
        
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'var(--color-light)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
        
        item.addEventListener('click', function() {
            input.value = suggestion;
            performKichwaSearch(suggestion);
            hideSuggestions();
        });
        
        suggestionsList.appendChild(item);
    });
    
    input.parentElement.style.position = 'relative';
    input.parentElement.appendChild(suggestionsList);
}

// Ocultar sugerencias
function hideSuggestions() {
    const suggestions = document.getElementById('suggestions');
    if (suggestions) {
        suggestions.remove();
    }
}

// Galería cultural simplificada
function initCulturalGallery() {
    const culturalCards = document.querySelectorAll('.cultural-card');
    
    culturalCards.forEach((card, index) => {
        // Animación de entrada simple
        card.classList.add('fade-in');
        
        // Click para mostrar información
        card.addEventListener('click', function() {
            const title = this.querySelector('.card-title').textContent;
            const description = this.querySelector('.card-description').textContent;
            showCulturalInfo(title, description);
        });
    });
}

// Mostrar información cultural
function showCulturalInfo(title, description) {
    showNotification(`${title}: ${description}`, 'info');
}

// Botón scroll to top (heredado del patrón de sabores)
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

// Mostrar/ocultar botón scroll to top
function toggleScrollToTop() {
    const scrollButton = document.querySelector('.scroll-to-top');
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > 300) {
        scrollButton.classList.add('visible');
    } else {
        scrollButton.classList.remove('visible');
    }
}

// Animaciones de scroll (funcionalidad específica de Kichwa)
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                
                // Efecto especial para elementos culturales
                if (entry.target.classList.contains('cultural-card') || 
                    entry.target.classList.contains('info-card')) {
                    entry.target.classList.add('sparkle');
                }
            }
        });
    }, observerOptions);
    
    // Observar elementos para animaciones
    const elementsToAnimate = document.querySelectorAll('.kichwa-dictionary, .cultural-card, .info-card');
    elementsToAnimate.forEach(el => observer.observe(el));
}

// Características responsivas (funcionalidad específica de Kichwa)
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

// Animaciones al hacer scroll (heredado del patrón de sabores)
function animateOnScroll() {
    const elements = document.querySelectorAll('.kichwa-row, .cultural-card, .info-card, .filter-btn');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('fade-in');
        }
    });
}

// Indicador de carga (heredado del patrón de sabores)
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

// Función para mostrar notificaciones (heredado del patrón de sabores)
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
        max-width: 300px;
        word-wrap: break-word;
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
    }, 4000);
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

// Exportar funciones para uso externo
window.KichwaPage = {
    highlightSearchTerm: performKichwaSearch,
    clearSearch: clearKichwaSearch,
    addSparkleEffect,
    showNotification
};

// Agregar estilos para las animaciones (heredado del patrón de sabores)
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
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .highlighted {
        background-color: rgba(255, 107, 53, 0.2) !important;
        border-left: 4px solid var(--color-food-warm) !important;
    }
    
    .audio-playing {
        background-color: rgba(255, 107, 53, 0.1) !important;
    }
    
    .fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }
    
    /* Suggestion dropdown styles */
    .suggestions-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        max-height: 250px;
        overflow-y: auto;
        display: none;
    }
    
    .suggestion-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }
    
    .suggestion-item:hover {
        background-color: #f8f9fa;
    }
    
    .suggestion-item:last-child {
        border-bottom: none;
    }
    
    .suggestion-primary {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }
    
    .suggestion-secondary {
        font-size: 0.85rem;
        color: #666;
        margin-top: 2px;
    }
    
    /* Pagination styles */
    .pagination .page-link {
        color: #8b4513;
        border-color: #ddd;
    }
    
    .pagination .page-link:hover {
        background-color: #f8f5f0;
        border-color: #8b4513;
        color: #5a2d0c;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #8b4513;
        border-color: #8b4513;
    }
    
    .pagination .page-item.active .page-link:hover {
        background-color: #5a2d0c;
        border-color: #5a2d0c;
    }
`;
document.head.appendChild(additionalStyles);

// Funciones globales para compatibilidad con el HTML
function searchWords() {
    const searchTerm = document.getElementById('searchInput').value.trim();
    if (searchTerm) {
        performKichwaSearch(searchTerm);
    }
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    clearKichwaSearch();
}

// Ocultar sugerencias al hacer click fuera
document.addEventListener('click', function(e) {
    if (!e.target.closest('.search-container')) {
        hideSuggestions();
    }
});

// Inicializar efectos de parallax para el hero
function initParallaxEffect() {
    const hero = document.querySelector('.kichwa-hero');
    
    if (hero) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3;
            hero.style.transform = `translateY(${rate}px)`;
        });
    }
}

// Inicializar parallax
initParallaxEffect();

// Translation functionality
let translationData = [];

// Load translation data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTranslationData();
});

function loadTranslationData() {
    fetch('api/kichwa-words.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                translationData = data.words;
                console.log(`Loaded ${data.total} Kichwa words for translation`);
            } else {
                console.error('Error loading translation data:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading translation data:', error);
        });
}

function translateToKichwa() {
    const spanishText = document.getElementById('spanishInput').value.trim().toLowerCase();
    
    if (!spanishText) {
        alert('Por favor, ingresa una palabra en español para traducir.');
        return;
    }
    
    const results = translationData.filter(word => 
        word.traduccion_espanol.toLowerCase().includes(spanishText) ||
        word.traduccion_espanol.toLowerCase() === spanishText
    );
    
    displayTranslationResults(results, spanishText, 'spanish');
}

function translateToSpanish() {
    const kichwaText = document.getElementById('kichwaInput').value.trim().toLowerCase();
    
    if (!kichwaText) {
        alert('Por favor, ingresa una palabra en kichwa para traducir.');
        return;
    }
    
    const results = translationData.filter(word => 
        word.palabra_kichwa.toLowerCase().includes(kichwaText) ||
        word.palabra_kichwa.toLowerCase() === kichwaText
    );
    
    displayTranslationResults(results, kichwaText, 'kichwa');
}

function displayTranslationResults(results, searchTerm, sourceLanguage) {
    const resultsContainer = document.getElementById('translationResults');
    const contentContainer = document.getElementById('translationContent');
    
    if (results.length === 0) {
        contentContainer.innerHTML = `
            <div class="no-translation-found">
                <i class="fas fa-search fa-2x mb-3"></i>
                <h5>No se encontraron traducciones exactas</h5>
                <p>No se encontraron traducciones para "${searchTerm}"</p>
                ${getSuggestions(searchTerm, sourceLanguage)}
            </div>
        `;
    } else {
        let html = '<div class="translation-matches">';
        results.forEach(word => {
            html += `
                <div class="translation-match">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kichwa-word">${word.palabra_kichwa}</div>
                            <div class="spanish-word">${word.traduccion_espanol}</div>
                            ${word.categoria ? `<small class="text-muted"><i class="fas fa-tag"></i> ${word.categoria}</small>` : ''}
                        </div>
                        <div>
                            ${word.audio_url ? 
                                `<button class="audio-btn" onclick="playTranslationAudio('${word.audio_url}')" title="Reproducir pronunciación">
                                    <i class="fas fa-volume-up"></i>
                                </button>` : 
                                '<span class="text-muted"><i class="fas fa-volume-mute"></i></span>'
                            }
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        contentContainer.innerHTML = html;
    }
    
    resultsContainer.style.display = 'block';
}

function getSuggestions(searchTerm, sourceLanguage) {
    const suggestions = translationData.filter(word => {
        if (sourceLanguage === 'spanish') {
            return word.traduccion_espanol.toLowerCase().includes(searchTerm.substring(0, 3));
        } else {
            return word.palabra_kichwa.toLowerCase().includes(searchTerm.substring(0, 3));
        }
    }).slice(0, 5);
    
    if (suggestions.length === 0) {
        return '';
    }
    
    let html = `
        <div class="translation-suggestion">
            <div class="suggestion-text">¿Quizás quisiste decir?</div>
            <div class="suggestion-words">
    `;
    
    suggestions.forEach(word => {
        const displayWord = sourceLanguage === 'spanish' ? word.traduccion_espanol : word.palabra_kichwa;
        html += `<span class="suggestion-word" onclick="applySuggestion('${displayWord}', '${sourceLanguage}')">${displayWord}</span>`;
    });
    
    html += '</div></div>';
    return html;
}

function applySuggestion(word, sourceLanguage) {
    if (sourceLanguage === 'spanish') {
        document.getElementById('spanishInput').value = word;
        translateToKichwa();
    } else {
        document.getElementById('kichwaInput').value = word;
        translateToSpanish();
    }
}

function clearTranslation(language) {
    if (language === 'spanish') {
        document.getElementById('spanishInput').value = '';
    } else {
        document.getElementById('kichwaInput').value = '';
    }
    
    // Hide results
    document.getElementById('translationResults').style.display = 'none';
}

function swapTranslation() {
    const spanishInput = document.getElementById('spanishInput');
    const kichwaInput = document.getElementById('kichwaInput');
    
    const spanishText = spanishInput.value;
    const kichwaText = kichwaInput.value;
    
    spanishInput.value = kichwaText;
    kichwaInput.value = spanishText;
    
    // Hide previous results
    document.getElementById('translationResults').style.display = 'none';
}

function playTranslationAudio(audioUrl) {
    const audio = new Audio(`audio/${audioUrl}`);
    audio.play().catch(error => {
        console.error('Error playing audio:', error);
        alert('No se pudo reproducir el audio.');
    });
}

// Pagination functions
function changeRowsPerPage() {
    const rowsSelector = document.getElementById('rowsSelector');
    const selectedRows = rowsSelector.value;
    const urlParams = new URLSearchParams(window.location.search);
    
    // Update URL with new rows parameter and reset to page 1
    urlParams.set('rows', selectedRows);
    urlParams.set('page', '1');
    
    // Redirect to updated URL
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

// Enhanced suggestion functionality for translation inputs
function initTranslationSuggestions() {
    const spanishInput = document.getElementById('spanishInput');
    const kichwaInput = document.getElementById('kichwaInput');
    
    if (spanishInput) {
        let spanishTimeout;
        spanishInput.addEventListener('input', function() {
            clearTimeout(spanishTimeout);
            spanishTimeout = setTimeout(() => {
                showTranslationSuggestions(this.value, 'spanish');
            }, 300);
        });
        
        spanishInput.addEventListener('focus', function() {
            if (this.value.trim()) {
                showTranslationSuggestions(this.value, 'spanish');
            }
        });
    }
    
    if (kichwaInput) {
        let kichwaTimeout;
        kichwaInput.addEventListener('input', function() {
            clearTimeout(kichwaTimeout);
            kichwaTimeout = setTimeout(() => {
                showTranslationSuggestions(this.value, 'kichwa');
            }, 300);
        });
        
        kichwaInput.addEventListener('focus', function() {
            if (this.value.trim()) {
                showTranslationSuggestions(this.value, 'kichwa');
            }
        });
    }
}

function showTranslationSuggestions(value, language) {
    if (!value || value.length < 2) {
        hideTranslationSuggestions(language);
        return;
    }
    
    const suggestions = getFilteredSuggestions(value, language);
    const containerId = language === 'spanish' ? 'spanishSuggestions' : 'kichwaSuggestions';
    const container = document.getElementById(containerId);
    
    if (!container) return;
    
    if (suggestions.length === 0) {
        hideTranslationSuggestions(language);
        return;
    }
    
    let html = '';
    suggestions.forEach(suggestion => {
        const displayText = language === 'spanish' ? suggestion.traduccion_espanol : suggestion.palabra_kichwa;
        const secondaryText = language === 'spanish' ? suggestion.palabra_kichwa : suggestion.traduccion_espanol;
        
        html += `
            <div class="suggestion-item" onclick="selectTranslationSuggestion('${displayText}', '${language}')">
                <div class="suggestion-primary">${displayText}</div>
                <div class="suggestion-secondary">${secondaryText}</div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    container.style.display = 'block';
}

function getFilteredSuggestions(value, language) {
    const lowerValue = value.toLowerCase();
    return translationData.filter(word => {
        if (language === 'spanish') {
            return word.traduccion_espanol.toLowerCase().includes(lowerValue);
        } else {
            return word.palabra_kichwa.toLowerCase().includes(lowerValue);
        }
    }).slice(0, 8);
}

function selectTranslationSuggestion(text, language) {
    const inputId = language === 'spanish' ? 'spanishInput' : 'kichwaInput';
    document.getElementById(inputId).value = text;
    hideTranslationSuggestions(language);
    
    // Auto-translate
    if (language === 'spanish') {
        translateToKichwa();
    } else {
        translateToSpanish();
    }
}

function hideTranslationSuggestions(language) {
    const containerId = language === 'spanish' ? 'spanishSuggestions' : 'kichwaSuggestions';
    const container = document.getElementById(containerId);
    if (container) {
        container.style.display = 'none';
    }
}

// Hide suggestions when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.translation-input') && !e.target.closest('.translation-output')) {
        hideTranslationSuggestions('spanish');
        hideTranslationSuggestions('kichwa');
    }
});

// Add Enter key support for translation inputs
document.addEventListener('DOMContentLoaded', function() {
    const spanishInput = document.getElementById('spanishInput');
    const kichwaInput = document.getElementById('kichwaInput');
    
    if (spanishInput) {
        spanishInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                translateToKichwa();
            }
        });
    }
    
    if (kichwaInput) {
        kichwaInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                translateToSpanish();
            }
        });
    }
    
    // Initialize translation suggestions
    initTranslationSuggestions();
});

