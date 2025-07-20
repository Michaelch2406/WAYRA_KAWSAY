/**
 * Sistema de Traducción Wayra Kawsay
 * Sistema robusto de traducción Español-Kichwa optimizado para la cultura andina
 * 
 * Características:
 * - Cache inteligente para optimización de rendimiento
 * - Prevención de duplicaciones y conflictos
 * - Traducción automática de contenido dinámico
 * - Persistencia de preferencias de idioma
 * - Integración universal en todas las páginas
 */

class WayraTranslationSystem {
    constructor() {
        this.currentLanguage = 'es';
        this.cache = new Map();
        this.translations = {
            es: {},
            qu: {}
        };
        this.isLoading = false;
        this.observers = [];
        this.storageKey = 'wayra_kawsay_language';
        
        this.init();
    }

    /**
     * Inicialización del sistema de traducción
     */
    async init() {
        try {
            await this.loadTranslations();
            this.loadSavedLanguage();
            this.setupLanguageSelectors();
            this.setupDOMObserver();
            this.translatePage();
            this.bindEvents();
            
            console.log('Sistema de traducción Wayra Kawsay inicializado correctamente');
        } catch (error) {
            console.error('Error al inicializar el sistema de traducción:', error);
        }
    }

    /**
     * Carga las traducciones desde los archivos JSON
     */
    async loadTranslations() {
        if (this.isLoading) return;
        this.isLoading = true;

        try {
            const [esResponse, quResponse] = await Promise.all([
                fetch('/WAYRA_KAWSAY/languages/es.json'),
                fetch('/WAYRA_KAWSAY/languages/qu.json')
            ]);

            if (!esResponse.ok || !quResponse.ok) {
                throw new Error('Error al cargar archivos de traducción');
            }

            this.translations.es = await esResponse.json();
            this.translations.qu = await quResponse.json();

        } catch (error) {
            console.error('Error cargando traducciones:', error);
            // Fallback: usar traducciones mínimas hardcodeadas
            this.loadFallbackTranslations();
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Traducciones de fallback en caso de error
     */
    loadFallbackTranslations() {
        this.translations = {
            es: {
                "titulo_inicio": "Wayra Kawsay",
                "menu_inicio": "Inicio",
                "menu_sabores": "Sabores",
                "menu_artesanias": "Artesanías",
                "menu_kichwa": "Kichwa",
                "menu_cultura": "Cultura",
                "menu_ubicacion": "Ubicación"
            },
            qu: {
                "titulo_inicio": "Wayra Kawsay",
                "menu_inicio": "Kallari",
                "menu_sabores": "Mishkikuna",
                "menu_artesanias": "Maki Ruraykuna",
                "menu_kichwa": "Kichwa",
                "menu_cultura": "Kawsay",
                "menu_ubicacion": "Maypi Kay"
            }
        };
    }

    /**
     * Carga el idioma guardado en localStorage
     */
    loadSavedLanguage() {
        const saved = localStorage.getItem(this.storageKey);
        const urlParams = new URLSearchParams(window.location.search);
        const urlLang = urlParams.get('lang');
        
        if (urlLang && ['es', 'qu'].includes(urlLang)) {
            this.currentLanguage = urlLang;
            localStorage.setItem(this.storageKey, urlLang);
        } else if (saved && ['es', 'qu'].includes(saved)) {
            this.currentLanguage = saved;
        }
    }

    /**
     * Configura todos los selectores de idioma de manera unificada
     */
    setupLanguageSelectors() {
        const selectors = document.querySelectorAll('#language-selector');
        
        selectors.forEach(selector => {
            // Limpiar eventos previos
            const newSelector = selector.cloneNode(true);
            selector.parentNode.replaceChild(newSelector, selector);
            
            // Configurar contenido estándar
            this.setupSelectorContent(newSelector);
            
            // Establecer valor actual
            newSelector.value = this.currentLanguage;
            
            // Agregar event listener
            newSelector.addEventListener('change', (e) => {
                this.changeLanguage(e.target.value);
            });
        });
    }

    /**
     * Configura el contenido estándar del selector
     */
    setupSelectorContent(selector) {
        selector.innerHTML = `
            <option value="es">🇪🇸 Español</option>
            <option value="qu">🏔️ Kichwa</option>
        `;
        selector.className = 'form-select';
    }

    /**
     * Configura el observador de cambios en el DOM
     */
    setupDOMObserver() {
        if (window.MutationObserver) {
            const observer = new MutationObserver((mutations) => {
                let shouldTranslate = false;
                
                mutations.forEach(mutation => {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach(node => {
                            if (node.nodeType === Node.ELEMENT_NODE) {
                                if (node.hasAttribute && node.hasAttribute('data-translate')) {
                                    shouldTranslate = true;
                                } else if (node.querySelector && node.querySelector('[data-translate]')) {
                                    shouldTranslate = true;
                                }
                            }
                        });
                    }
                });
                
                if (shouldTranslate) {
                    this.translateNewContent();
                }
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }

    /**
     * Traduce contenido nuevo agregado dinámicamente
     */
    translateNewContent() {
        clearTimeout(this.translateTimeout);
        this.translateTimeout = setTimeout(() => {
            this.translatePage();
        }, 100);
    }

    /**
     * Cambia el idioma del sitio
     */
    changeLanguage(langCode) {
        if (!['es', 'qu'].includes(langCode) || langCode === this.currentLanguage) {
            return;
        }

        this.currentLanguage = langCode;
        localStorage.setItem(this.storageKey, langCode);
        
        // Actualizar todos los selectores
        document.querySelectorAll('#language-selector').forEach(selector => {
            selector.value = langCode;
        });
        
        // Traducir página
        this.translatePage();
        
        // Disparar evento personalizado
        window.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { language: langCode }
        }));
        
        console.log(`Idioma cambiado a: ${langCode === 'es' ? 'Español' : 'Kichwa'}`);
    }

    /**
     * Traduce toda la página
     */
    translatePage() {
        const elements = document.querySelectorAll('[data-translate]');
        
        elements.forEach(element => {
            const key = element.getAttribute('data-translate');
            const translation = this.getTranslation(key);
            
            if (translation && translation !== element.textContent.trim()) {
                element.textContent = translation;
            }
        });

        // Traducir placeholders
        const placeholderElements = document.querySelectorAll('[data-translate-placeholder]');
        placeholderElements.forEach(element => {
            const key = element.getAttribute('data-translate-placeholder');
            const translation = this.getTranslation(key);
            
            if (translation) {
                element.placeholder = translation;
            }
        });

        // Traducir títulos
        const titleElements = document.querySelectorAll('[data-translate-title]');
        titleElements.forEach(element => {
            const key = element.getAttribute('data-translate-title');
            const translation = this.getTranslation(key);
            
            if (translation) {
                element.title = translation;
            }
        });
    }

    /**
     * Obtiene una traducción por clave con cache
     */
    getTranslation(key) {
        const cacheKey = `${this.currentLanguage}:${key}`;
        
        if (this.cache.has(cacheKey)) {
            return this.cache.get(cacheKey);
        }

        const translation = this.translations[this.currentLanguage]?.[key] || key;
        this.cache.set(cacheKey, translation);
        
        return translation;
    }

    /**
     * Agrega nuevas traducciones dinámicamente
     */
    addTranslations(langCode, translations) {
        if (!this.translations[langCode]) {
            this.translations[langCode] = {};
        }
        
        Object.assign(this.translations[langCode], translations);
        this.cache.clear(); // Limpiar cache para reflejar cambios
    }

    /**
     * Vincula eventos globales
     */
    bindEvents() {
        // Manejar navegación SPA si existe
        window.addEventListener('popstate', () => {
            this.loadSavedLanguage();
            this.translatePage();
        });

        // Manejar carga de nuevo contenido
        document.addEventListener('DOMContentLoaded', () => {
            this.translatePage();
        });
    }

    /**
     * Utilidades para desarrolladores
     */
    getCurrentLanguage() {
        return this.currentLanguage;
    }

    getAvailableLanguages() {
        return ['es', 'qu'];
    }

    clearCache() {
        this.cache.clear();
        console.log('Cache de traducciones limpiado');
    }

    getStats() {
        return {
            currentLanguage: this.currentLanguage,
            cacheSize: this.cache.size,
            translationsLoaded: {
                es: Object.keys(this.translations.es || {}).length,
                qu: Object.keys(this.translations.qu || {}).length
            }
        };
    }
}

// Inicialización automática del sistema
let wayraTranslation;

function initTranslationSystem() {
    if (!wayraTranslation) {
        wayraTranslation = new WayraTranslationSystem();
    }
    return wayraTranslation;
}

// Funciones globales para compatibilidad
function changeLanguage(langCode) {
    if (wayraTranslation) {
        wayraTranslation.changeLanguage(langCode);
    }
}

function getTranslation(key) {
    return wayraTranslation ? wayraTranslation.getTranslation(key) : key;
}

// Auto-inicialización
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTranslationSystem);
} else {
    initTranslationSystem();
}

// Exportar para uso global
window.WayraTranslationSystem = WayraTranslationSystem;
window.wayraTranslation = wayraTranslation;
window.changeLanguage = changeLanguage;
window.getTranslation = getTranslation;
window.initTranslationSystem = initTranslationSystem;