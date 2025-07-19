// JavaScript específico para la funcionalidad del navbar

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades del navbar
    initNavbarScrollEffects();
    initLanguageSelector();
    initMobileNavigation();
    initNavbarAnimations();
    initAccessibilityFeatures();
    initNavbarSearch();
});

// Efectos de scroll para el navbar
function initNavbarScrollEffects() {
    const navbar = document.querySelector('.navbar');
    let lastScrollTop = 0;
    let scrollTimeout;
    
    if (!navbar) return;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Limpiar timeout anterior
        clearTimeout(scrollTimeout);
        
        // Agregar clase scrolled cuando se hace scroll
        if (scrollTop > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        // Ocultar/mostrar navbar en scroll (solo en desktop)
        if (window.innerWidth > 991) {
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                // Scrolling down
                navbar.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up
                navbar.style.transform = 'translateY(0)';
            }
        }
        
        lastScrollTop = scrollTop;
        
        // Timeout para optimizar performance
        scrollTimeout = setTimeout(() => {
            // Código adicional si es necesario
        }, 100);
    });
}

// Selector de idioma
function initLanguageSelector() {
    const languageSelector = document.getElementById('language-selector');
    
    if (!languageSelector) return;
    
    // Agregar efecto de carga
    languageSelector.addEventListener('change', function() {
        const selectedLang = this.value;
        const navbar = document.querySelector('.navbar');
        
        // Mostrar indicador de carga
        navbar.classList.add('loading');
        showLoadingIndicator();
        
        // Simular delay para mejor UX
        setTimeout(() => {
            // Redirigir con el nuevo idioma
            const currentPage = window.location.pathname.split('/').pop() || 'index.php';
            window.location.href = `${currentPage}?lang=${selectedLang}`;
        }, 500);
    });
    
    // Efecto hover mejorado
    languageSelector.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-1px)';
    });
    
    languageSelector.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
}

// Navegación móvil mejorada
function initMobileNavigation() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    const navLinks = document.querySelectorAll('.nav-link');
    
    if (!navbarToggler || !navbarCollapse) return;
    
    // Mejorar el comportamiento del toggler
    navbarToggler.addEventListener('click', function() {
        // Agregar animación al icono
        this.classList.toggle('active');
        
        // Efecto de vibración sutil
        if (navigator.vibrate) {
            navigator.vibrate(50);
        }
    });
    
    // Cerrar menú al hacer clic en un enlace (móvil)
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 991) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    hide: true
                });
            }
        });
    });
    
    // Cerrar menú al hacer clic fuera (móvil)
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 991) {
            const isClickInsideNav = navbarCollapse.contains(event.target) || 
                                   navbarToggler.contains(event.target);
            
            if (!isClickInsideNav && navbarCollapse.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    hide: true
                });
            }
        }
    });
}

// Animaciones del navbar
function initNavbarAnimations() {
    const navLinks = document.querySelectorAll('.nav-link');
    const navbarBrand = document.querySelector('.navbar-brand');
    
    // Animaciones de entrada para los enlaces
    navLinks.forEach((link, index) => {
        link.style.animationDelay = `${index * 0.1}s`;
        link.classList.add('animate-in');
    });
    
    // Efectos hover mejorados para enlaces
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            // Efecto de escala en el icono
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1.2)';
            }
            
            // Efecto de brillo
            this.style.boxShadow = '0 4px 15px rgba(255, 255, 255, 0.2)';
        });
        
        link.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1)';
            }
            
            if (!this.classList.contains('active')) {
                this.style.boxShadow = '';
            }
        });
        
        // Efecto de click
        link.addEventListener('click', function(e) {
            // Crear efecto ripple
            createRippleEffect(this, e);
        });
    });
    
    // Animación especial para el brand
    if (navbarBrand) {
        navbarBrand.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Animación de pulso
            this.style.animation = 'pulse 0.6s ease-out';
            
            setTimeout(() => {
                this.style.animation = '';
                window.location.href = 'index.php';
            }, 300);
        });
    }
}

// Características de accesibilidad
function initAccessibilityFeatures() {
    const navLinks = document.querySelectorAll('.nav-link');
    const languageSelector = document.getElementById('language-selector');
    
    // Navegación por teclado mejorada
    navLinks.forEach((link, index) => {
        link.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
            
            // Navegación con flechas
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                const nextLink = navLinks[index + 1] || navLinks[0];
                nextLink.focus();
            }
            
            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                const prevLink = navLinks[index - 1] || navLinks[navLinks.length - 1];
                prevLink.focus();
            }
        });
    });
    
    // Mejorar accesibilidad del selector de idioma
    if (languageSelector) {
        languageSelector.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                this.dispatchEvent(new Event('change'));
            }
        });
    }
    
    // Anunciar cambios de página para lectores de pantalla
    const currentPage = document.querySelector('.nav-link.active');
    if (currentPage) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = `Página actual: ${currentPage.textContent.trim()}`;
        document.body.appendChild(announcement);
    }
}

// Funcionalidad de búsqueda en navbar (opcional)
function initNavbarSearch() {
    // Esta función puede expandirse para agregar una barra de búsqueda
    // Por ahora, implementa búsqueda rápida por teclado
    
    let searchBuffer = '';
    let searchTimeout;
    
    document.addEventListener('keydown', function(e) {
        // Solo activar si no hay elementos de input enfocados
        if (document.activeElement.tagName === 'INPUT' || 
            document.activeElement.tagName === 'TEXTAREA' ||
            document.activeElement.tagName === 'SELECT') {
            return;
        }
        
        // Búsqueda rápida por primera letra
        if (e.key.length === 1 && e.key.match(/[a-zA-Z]/)) {
            searchBuffer += e.key.toLowerCase();
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchBuffer = '';
            }, 1000);
            
            // Buscar enlace que coincida
            const navLinks = document.querySelectorAll('.nav-link');
            const matchingLink = Array.from(navLinks).find(link => {
                const text = link.textContent.trim().toLowerCase();
                return text.startsWith(searchBuffer);
            });
            
            if (matchingLink) {
                matchingLink.focus();
                // Efecto visual
                matchingLink.style.backgroundColor = 'rgba(255, 255, 255, 0.3)';
                setTimeout(() => {
                    matchingLink.style.backgroundColor = '';
                }, 500);
            }
        }
    });
}

// Función auxiliar para crear efecto ripple
function createRippleEffect(element, event) {
    const ripple = document.createElement('span');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s ease-out;
        pointer-events: none;
        z-index: 1000;
    `;
    
    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

// Indicador de carga
function showLoadingIndicator() {
    const indicator = document.createElement('div');
    indicator.id = 'navbar-loading-indicator';
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
            backdrop-filter: blur(5px);
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
                <div>Cambiando idioma...</div>
            </div>
        </div>
    `;
    
    document.body.appendChild(indicator);
    
    // Auto-remover después de 5 segundos (fallback)
    setTimeout(() => {
        const existingIndicator = document.getElementById('navbar-loading-indicator');
        if (existingIndicator) {
            existingIndicator.remove();
        }
    }, 5000);
}

// Función para actualizar el estado activo del navbar
function updateActiveNavLink() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        }
    });
}

// Función para manejar errores de navegación
function handleNavigationError(error) {
    console.error('Error de navegación:', error);
    
    // Mostrar mensaje de error amigable
    const errorMessage = document.createElement('div');
    errorMessage.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 1rem;
        border-radius: 5px;
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
    `;
    errorMessage.textContent = 'Error al cargar la página. Por favor, intenta nuevamente.';
    
    document.body.appendChild(errorMessage);
    
    setTimeout(() => {
        errorMessage.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            errorMessage.remove();
        }, 300);
    }, 3000);
}

// Inicializar estado activo al cargar
updateActiveNavLink();

// Agregar estilos CSS adicionales dinámicamente
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
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
    
    .animate-in {
        animation: fadeInDown 0.6s ease-out;
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .navbar-toggler.active .navbar-toggler-icon {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
    }
    
    .sr-only {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }
`;

document.head.appendChild(additionalStyles);

// Exportar funciones para uso externo si es necesario
window.NavbarUtils = {
    updateActiveNavLink,
    showLoadingIndicator,
    handleNavigationError
};

