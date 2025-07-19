// JavaScript específico para la funcionalidad del footer

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades del footer
    initFooterAnimations();
    initSocialLinks();
    initFooterLinks();
    initContactInfo();
    initFooterObserver();
    initFooterAccessibility();
});

// Animaciones del footer
function initFooterAnimations() {
    const footer = document.querySelector('.footer');
    const footerSections = document.querySelectorAll('.footer-section');
    
    if (!footer) return;
    
    // Observador de intersección para animaciones
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                
                // Animar secciones con delay
                footerSections.forEach((section, index) => {
                    setTimeout(() => {
                        section.style.opacity = '1';
                        section.style.transform = 'translateY(0)';
                    }, index * 150);
                });
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    observer.observe(footer);
    
    // Preparar secciones para animación
    footerSections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(30px)';
        section.style.transition = 'all 0.6s ease-out';
    });
}

// Enlaces sociales interactivos
function initSocialLinks() {
    const socialLinks = document.querySelectorAll('.social-links a');
    
    socialLinks.forEach(link => {
        // Efecto hover mejorado
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.1)';
            this.style.boxShadow = '0 6px 20px rgba(0, 0, 0, 0.3)';
            
            // Efecto de rotación en el icono
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1.2) rotate(10deg)';
            }
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '';
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
        });
        
        // Efecto de click
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Crear efecto ripple
            createRippleEffect(this, e);
            
            // Simular apertura de red social
            const platform = this.title || 'Red Social';
            showSocialNotification(platform);
            
            // Aquí se podría agregar la lógica real para abrir las redes sociales
            setTimeout(() => {
                // window.open(this.href, '_blank');
            }, 300);
        });
        
        // Efecto de focus para accesibilidad
        link.addEventListener('focus', function() {
            this.style.outline = '2px solid rgba(255, 255, 255, 0.8)';
            this.style.outlineOffset = '3px';
        });
        
        link.addEventListener('blur', function() {
            this.style.outline = '';
            this.style.outlineOffset = '';
        });
    });
}

// Enlaces del footer
function initFooterLinks() {
    const footerLinks = document.querySelectorAll('.footer-section a:not(.social-links a)');
    
    footerLinks.forEach(link => {
        // Efecto hover mejorado
        link.addEventListener('mouseenter', function() {
            this.style.paddingLeft = '10px';
            this.style.color = '#FFFFFF';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.paddingLeft = '0';
            this.style.color = '';
        });
        
        // Efecto de click con animación
        link.addEventListener('click', function(e) {
            // Si es un enlace interno, agregar efecto de transición
            const href = this.getAttribute('href');
            if (href && !href.startsWith('http') && !href.startsWith('#')) {
                e.preventDefault();
                
                // Efecto de carga
                this.style.opacity = '0.7';
                this.innerHTML += ' <i class="fas fa-spinner fa-spin"></i>';
                
                setTimeout(() => {
                    window.location.href = href;
                }, 500);
            }
        });
    });
    
    // Enlaces de la parte inferior
    const bottomLinks = document.querySelectorAll('.footer-links a');
    bottomLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Mostrar modal de información (simulado)
            const linkText = this.textContent;
            showInfoModal(linkText);
        });
    });
}

// Información de contacto interactiva
function initContactInfo() {
    const contactItems = document.querySelectorAll('.footer-section p');
    
    contactItems.forEach(item => {
        const text = item.textContent;
        
        // Detectar email
        if (text.includes('@')) {
            item.style.cursor = 'pointer';
            item.addEventListener('click', function() {
                const email = text.match(/[\w.-]+@[\w.-]+\.\w+/)[0];
                copyToClipboard(email);
                showNotification('Email copiado al portapapeles', 'success');
            });
        }
        
        // Detectar teléfono
        if (text.includes('+') || text.match(/\d{3}-\d{4}/)) {
            item.style.cursor = 'pointer';
            item.addEventListener('click', function() {
                const phone = text.match(/[\+\d\s\(\)-]+/)[0];
                copyToClipboard(phone);
                showNotification('Teléfono copiado al portapapeles', 'success');
            });
        }
        
        // Efecto hover para elementos clickeables
        if (item.style.cursor === 'pointer') {
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                this.style.padding = '0.5rem';
                this.style.borderRadius = '5px';
                this.style.transform = 'translateX(5px)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
                this.style.padding = '';
                this.style.borderRadius = '';
                this.style.transform = '';
            });
        }
    });
}

// Observador del footer para efectos especiales
function initFooterObserver() {
    const footer = document.querySelector('.footer');
    if (!footer) return;
    
    // Efecto parallax sutil
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const footerTop = footer.offsetTop;
        const windowHeight = window.innerHeight;
        
        if (scrolled + windowHeight > footerTop) {
            const parallaxValue = (scrolled + windowHeight - footerTop) * 0.1;
            footer.style.transform = `translateY(-${parallaxValue}px)`;
        }
    });
    
    // Efecto de partículas en el fondo (opcional)
    createFooterParticles();
}

// Accesibilidad del footer
function initFooterAccessibility() {
    const footerSections = document.querySelectorAll('.footer-section');
    
    // Agregar landmarks ARIA
    const footer = document.querySelector('.footer');
    if (footer) {
        footer.setAttribute('role', 'contentinfo');
        footer.setAttribute('aria-label', 'Información del sitio web');
    }
    
    // Mejorar navegación por teclado
    const focusableElements = footer.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
    
    focusableElements.forEach((element, index) => {
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                // Lógica adicional para navegación por teclado si es necesaria
            }
        });
    });
    
    // Agregar skip link para el footer
    const skipLink = document.createElement('a');
    skipLink.href = '#main-content';
    skipLink.textContent = 'Saltar al contenido principal';
    skipLink.className = 'skip-link';
    skipLink.style.cssText = `
        position: absolute;
        top: -40px;
        left: 6px;
        background: #000;
        color: #fff;
        padding: 8px;
        text-decoration: none;
        border-radius: 3px;
        z-index: 1000;
        transition: top 0.3s;
    `;
    
    skipLink.addEventListener('focus', function() {
        this.style.top = '6px';
    });
    
    skipLink.addEventListener('blur', function() {
        this.style.top = '-40px';
    });
    
    footer.insertBefore(skipLink, footer.firstChild);
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

// Notificación para redes sociales
function showSocialNotification(platform) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: linear-gradient(135deg, #8B4513, #D2691E);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        z-index: 1000;
        animation: slideInUp 0.3s ease-out;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    `;
    notification.innerHTML = `
        <i class="fas fa-info-circle"></i>
        Próximamente: Síguenos en ${platform}
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutDown 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Modal de información
function showInfoModal(title) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        animation: fadeIn 0.3s ease-out;
    `;
    
    modal.innerHTML = `
        <div style="
            background: white;
            padding: 2rem;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            animation: scaleIn 0.3s ease-out;
        ">
            <h3 style="color: #8B4513; margin-bottom: 1rem;">${title}</h3>
            <p style="color: #666; margin-bottom: 1.5rem;">
                Esta sección estará disponible próximamente. 
                Estamos trabajando para brindarte la mejor experiencia.
            </p>
            <button onclick="this.closest('.modal').remove()" style="
                background: linear-gradient(135deg, #8B4513, #D2691E);
                color: white;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 25px;
                cursor: pointer;
                font-weight: 500;
                transition: transform 0.3s ease;
            " onmouseover="this.style.transform='scale(1.05)'" 
               onmouseout="this.style.transform='scale(1)'">
                Entendido
            </button>
        </div>
    `;
    
    modal.className = 'modal';
    document.body.appendChild(modal);
    
    // Cerrar con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modal.remove();
        }
    });
    
    // Cerrar al hacer clic fuera
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

// Función para copiar al portapapeles
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
    } else {
        // Fallback para navegadores más antiguos
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }
}

// Notificaciones generales
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        info: '#17a2b8',
        warning: '#ffc107'
    };
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type] || colors.info};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 5px;
        z-index: 1000;
        animation: slideInRight 0.3s ease-out;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Crear partículas en el fondo del footer (efecto decorativo)
function createFooterParticles() {
    const footer = document.querySelector('.footer');
    if (!footer) return;
    
    const particlesContainer = document.createElement('div');
    particlesContainer.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;
        z-index: 1;
    `;
    
    footer.style.position = 'relative';
    footer.appendChild(particlesContainer);
    
    // Crear partículas
    for (let i = 0; i < 20; i++) {
        const particle = document.createElement('div');
        particle.style.cssText = `
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float ${3 + Math.random() * 4}s ease-in-out infinite;
            animation-delay: ${Math.random() * 2}s;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
        `;
        
        particlesContainer.appendChild(particle);
    }
}

// Agregar estilos CSS adicionales
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
    @keyframes slideInUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutDown {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(100%);
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
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes scaleIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
            opacity: 0.3;
        }
        50% {
            transform: translateY(-20px);
            opacity: 0.8;
        }
    }
    
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .skip-link:focus {
        top: 6px !important;
    }
`;

document.head.appendChild(additionalStyles);

// Exportar funciones para uso externo
window.FooterUtils = {
    showNotification,
    showInfoModal,
    copyToClipboard
};

