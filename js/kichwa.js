// JavaScript para funcionalidades específicas de la página de Kichwa - Adaptado del patrón de sabores

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades
    initLanguageSelector();
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
    
    console.log('Kichwa page initialized with sabores pattern - Imbabura, Ecuador');
});

// Selector de idioma (heredado del patrón de sabores)
function initLanguageSelector() {
    const languageSelector = document.getElementById('language-selector');
    
    if (languageSelector) {
        languageSelector.addEventListener('change', function() {
            const selectedLang = this.value;
            showLoadingIndicator();
            
            setTimeout(() => {
                window.location.href = `kichwa.php?lang=${selectedLang}`;
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
        
        // Ocultar/mostrar navbar en scroll
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            navbar.style.transform = 'translateY(-100%)';
        } else {
            navbar.style.transform = 'translateY(0)';
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

// Animaciones de tabla mejoradas
function initTableAnimations() {
    const tableRows = document.querySelectorAll('.kichwa-row');
    
    tableRows.forEach((row, index) => {
        // Animación de entrada escalonada
        setTimeout(() => {
            row.classList.add('fade-in');
        }, index * 50);
        
        // Efectos hover mejorados
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.boxShadow = '0 5px 15px rgba(139, 69, 19, 0.2)';
            this.classList.add('sparkle');
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
            this.classList.remove('sparkle');
        });
        
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

// Galería cultural mejorada
function initCulturalGallery() {
    const culturalCards = document.querySelectorAll('.cultural-card');
    
    culturalCards.forEach((card, index) => {
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

