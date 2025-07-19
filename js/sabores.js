// JavaScript para funcionalidades específicas de la página de sabores

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
});

// Selector de idioma (heredado del index)
function initLanguageSelector() {
    const languageSelector = document.getElementById('language-selector');
    
    if (languageSelector) {
        languageSelector.addEventListener('change', function() {
            const selectedLang = this.value;
            showLoadingIndicator();
            
            setTimeout(() => {
                window.location.href = `sabores.php?lang=${selectedLang}`;
            }, 500);
        });
    }
}

// Efectos de scroll
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

// Sistema de filtros
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

// Función para filtrar tarjetas
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

// Animaciones de tarjetas
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
        
        // Click para abrir modal
        card.addEventListener('click', function() {
            const cardData = {
                title: this.querySelector('.card-title').textContent,
                description: this.querySelector('.card-description').textContent,
                image: this.querySelector('img').src,
                tags: Array.from(this.querySelectorAll('.tag')).map(tag => tag.textContent),
                rating: this.querySelector('.rating-text').textContent,
                difficulty: this.querySelectorAll('.difficulty-level.active').length,
                prepTime: this.querySelector('.prep-time').textContent
            };
            
            openModal(cardData);
        });
    });
}

// Sistema de modal
function initModal() {
    // Crear modal dinámicamente
    const modalHTML = `
        <div class="modal fade" id="saborModal" tabindex="-1" aria-labelledby="saborModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saborModalLabel">Detalles del Plato</h5>
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
                                <strong>Dificultad:</strong>
                                <div id="modalDifficulty" class="difficulty mt-2"></div>
                            </div>
                            <div class="col-md-6">
                                <strong>Tiempo de preparación:</strong>
                                <div id="modalPrepTime" class="prep-time mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary">Ver Receta Completa</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
}

// Abrir modal con datos
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
    
    // Rating
    document.getElementById('modalRating').innerHTML = `
        <div class="stars">★★★★★</div>
        <span class="rating-text">${data.rating}</span>
    `;
    
    // Dificultad
    const difficultyContainer = document.getElementById('modalDifficulty');
    difficultyContainer.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const level = document.createElement('div');
        level.className = `difficulty-level ${i <= data.difficulty ? 'active' : ''}`;
        difficultyContainer.appendChild(level);
    }
    
    // Tiempo de preparación
    document.getElementById('modalPrepTime').textContent = data.prepTime;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('saborModal'));
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
                <input type="text" class="form-control" id="searchInput" placeholder="Buscar platos...">
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

// Realizar búsqueda
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
            No se encontraron platos que coincidan con "${searchTerm}"
        `;
        
        const grid = document.querySelector('.sabores-grid .container');
        grid.insertBefore(message, grid.firstChild);
    } else if (searchTerm && count > 0) {
        const message = document.createElement('div');
        message.className = 'search-results-message alert alert-success text-center';
        message.innerHTML = `
            <i class="fas fa-check"></i>
            Se encontraron ${count} plato${count !== 1 ? 's' : ''} que coinciden con "${searchTerm}"
        `;
        
        const grid = document.querySelector('.sabores-grid .container');
        grid.insertBefore(message, grid.firstChild);
    }
}

// Animaciones al hacer scroll
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

// Función para mostrar notificaciones
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

// Efectos de parallax para el hero
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

// Inicializar parallax
initParallaxEffect();

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
`;
document.head.appendChild(additionalStyles);

