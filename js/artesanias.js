// JavaScript para funcionalidades específicas de la página de artesanías

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades
    initLanguageSelector();
    initScrollEffects();
    initNavbarEffects();
    initCategorySystem();
    initArtesanoAnimations();
    initProductoAnimations();
    initModal();
    initScrollToTop();
    initLazyLoading();
    initSearchFunctionality();
    initFilterAnimations();
});


// Efectos de scroll
function initScrollEffects() {
    if (!document.querySelector('.artesanias-hero')) {
        return;
    }

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
        
        // Efecto parallax en hero
        parallaxEffect();
    });
}

// Efectos de navegación
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

// Sistema de categorías/filtros
function initCategorySystem() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    const artesanoCards = document.querySelectorAll('.artesano-card');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            // Filtrar tarjetas de artesanos
            filterArtesanos(filterValue, artesanoCards);
            
            // Mostrar notificación
            showNotification(`Mostrando: ${this.textContent}`, 'info');
        });
    });
}

// Función para filtrar artesanos
function filterArtesanos(filterValue, cards) {
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

// Animaciones de tarjetas de artesanos
function initArtesanoAnimations() {
    const artesanoCards = document.querySelectorAll('.artesano-card');
    
    artesanoCards.forEach((card, index) => {
        // Animación de entrada escalonada
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 200);
        
        // Efectos hover eliminados para evitar transparencia molesta
        
        // Expandir/contraer productos
        const toggleBtn = card.querySelector('.toggle-productos');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const productosSection = card.querySelector('.productos-section');
                if (productosSection) {
                    productosSection.classList.toggle('expanded');
                    this.textContent = productosSection.classList.contains('expanded') ? 
                        'Ocultar Productos' : 'Ver Productos';
                }
            });
        }
    });
}

// Animaciones de productos
function initProductoAnimations() {
    const productoCards = document.querySelectorAll('.producto-card');
    
    productoCards.forEach((card, index) => {
        // Animación de entrada escalonada
        setTimeout(() => {
            card.classList.add('slide-in-right');
        }, index * 100);
        
        // Efectos hover eliminados para evitar transformaciones complejas
        
        // Click para abrir modal
        card.addEventListener('click', function() {
            const cardData = {
                title: this.querySelector('.producto-title').textContent,
                description: this.querySelector('.producto-description').textContent,
                image: this.querySelector('img').src,
                artesano: this.closest('.artesano-card').querySelector('.artesano-info h3').textContent,
                tags: Array.from(this.querySelectorAll('.producto-tag')).map(tag => tag.textContent)
            };
            
            openProductoModal(cardData);
        });
    });
}

// Sistema de modal para productos
function initModal() {
    // Crear modal dinámicamente
    const modalHTML = `
        <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productoModalLabel">Detalles del Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="" alt="" class="modal-image" id="modalImage">
                        <h4 id="modalTitle"></h4>
                        <p><strong>Artesano:</strong> <span id="modalArtesano"></span></p>
                        <div id="modalTags" class="producto-tags mb-3"></div>
                        <p id="modalDescription"></p>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Técnica:</strong>
                                <p id="modalTecnica">Técnica tradicional andina</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Materiales:</strong>
                                <p id="modalMateriales">Materiales naturales locales</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Contactar Artesano</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Abrir modal con datos del producto
function openProductoModal(data) {
    document.getElementById('modalTitle').textContent = data.title;
    document.getElementById('modalDescription').textContent = data.description;
    document.getElementById('modalImage').src = data.image;
    document.getElementById('modalImage').alt = data.title;
    document.getElementById('modalArtesano').textContent = data.artesano;
    
    // Tags
    const tagsContainer = document.getElementById('modalTags');
    tagsContainer.innerHTML = '';
    data.tags.forEach(tag => {
        const tagElement = document.createElement('span');
        tagElement.className = 'producto-tag';
        tagElement.textContent = tag;
        tagsContainer.appendChild(tagElement);
    });
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('productoModal'));
    modal.show();
}

// Botón scroll to top
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

// Lazy loading de imágenes
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

// Funcionalidad de búsqueda
function initSearchFunctionality() {
    // Crear barra de búsqueda
    const searchHTML = `
        <div class="search-container mb-4">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Buscar artesanos o productos...">
                <button class="btn btn-outline-primary" type="button" id="searchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    `;
    
    const categoriesSection = document.querySelector('.categories-section .container');
    if (categoriesSection) {
        categoriesSection.insertAdjacentHTML('afterbegin', searchHTML);
        
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

// Realizar búsqueda
function performSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const artesanoCards = document.querySelectorAll('.artesano-card');
    let visibleCount = 0;
    
    artesanoCards.forEach(card => {
        const artesanoName = card.querySelector('.artesano-info h3').textContent.toLowerCase();
        const artesanoDesc = card.querySelector('.artesano-info p').textContent.toLowerCase();
        const productos = Array.from(card.querySelectorAll('.producto-title')).map(p => p.textContent.toLowerCase());
        
        const matches = artesanoName.includes(searchTerm) || 
                       artesanoDesc.includes(searchTerm) || 
                       productos.some(producto => producto.includes(searchTerm));
        
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

// Mostrar resultados de búsqueda
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
            No se encontraron artesanos o productos que coincidan con "${searchTerm}"
        `;
        
        const section = document.querySelector('.artesanos-section .container');
        section.insertBefore(message, section.firstChild);
    } else if (searchTerm && count > 0) {
        const message = document.createElement('div');
        message.className = 'search-results-message alert alert-success text-center';
        message.innerHTML = `
            <i class="fas fa-check"></i>
            Se encontraron ${count} artesano${count !== 1 ? 's' : ''} que coinciden con "${searchTerm}"
        `;
        
        const section = document.querySelector('.artesanos-section .container');
        section.insertBefore(message, section.firstChild);
    }
}

// Animaciones de filtros
function initFilterAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.category-btn, .tecnica-card').forEach(el => {
        observer.observe(el);
    });
}

// Animaciones al hacer scroll
function animateOnScroll() {
    const elements = document.querySelectorAll('.artesano-card, .producto-card, .tecnica-card, .category-btn');
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('fade-in');
        }
    });
}

// Efecto parallax para el hero
function parallaxEffect() {
    const hero = document.querySelector('.artesanias-hero');
    
    if (hero) {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.3;
        hero.style.transform = `translateY(${rate}px)`;
    }
}

// Indicador de carga (heredado del index)
function showLoadingIndicator() {
    const indicator = document.createElement('div');
    indicator.innerHTML = `
        <div style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(160, 82, 45, 0.9);
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

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#8FBC8F' : type === 'error' ? '#dc3545' : '#A0522D'};
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

// Función para manejar errores de carga de imágenes
function handleImageErrors() {
    const images = document.querySelectorAll('img');
    
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPkltYWdlbiBubyBkaXNwb25pYmxlPC90ZXh0Pjwvc3ZnPg==';
            this.alt = 'Imagen no disponible';
        });
    });
}

// Inicializar manejo de errores de imágenes
handleImageErrors();

// Efectos especiales para artesanías
function initCraftEffects() {
    // Efectos de tejido eliminados para evitar transparencia
    
    // Efecto de calidad artesanal
    const productoCards = document.querySelectorAll('.producto-card');
    productoCards.forEach(card => {
        // Agregar indicador de calidad aleatoriamente
        if (Math.random() > 0.7) {
            const qualityIndicator = document.createElement('div');
            qualityIndicator.className = 'quality-indicator';
            qualityIndicator.innerHTML = '★';
            qualityIndicator.title = 'Calidad Premium';
            card.querySelector('.producto-image-container').appendChild(qualityIndicator);
        }
        
        // Agregar badge de hecho a mano
        const handmadeBadge = document.createElement('span');
        handmadeBadge.className = 'handmade-badge';
        handmadeBadge.textContent = 'Hecho a Mano';
        card.querySelector('.producto-body').appendChild(handmadeBadge);
    });
}

// Inicializar efectos especiales
initCraftEffects();

// Agregar estilos para las animaciones
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
    
    /* Efectos sparkle eliminados para evitar transparencia */
    
    .animate-in {
        animation: fadeInUp 0.6s ease-out;
    }
`;
document.head.appendChild(additionalStyles);

