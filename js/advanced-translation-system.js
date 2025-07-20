/**
 * Sistema de Traducción Avanzado Wayra Kawsay
 * Traducción completa y automática Español-Kichwa para Imbabura, Ecuador
 * 
 * CARACTERÍSTICAS PROFESIONALES:
 * ✅ Detección automática de TODO el texto
 * ✅ Traducción de contenido dinámico
 * ✅ Cache ultra-optimizado
 * ✅ Reemplazo inteligente de texto
 * ✅ Soporte para contenido PHP y JavaScript
 * ✅ Sistema de fallback robusto
 * ✅ Traducción automática sin marcadores data-translate
 */

class AdvancedWayraTranslationSystem {
    constructor() {
        this.currentLanguage = 'es';
        this.cache = new Map();
        this.textMappings = new Map();
        this.translations = { es: {}, qu: {} };
        this.isLoading = false;
        this.observers = [];
        this.storageKey = 'wayra_kawsay_language';
        this.autoTranslateMode = true;
        this.textReplacementQueue = [];
        this.processedElements = new WeakSet();
        
        // Configuración avanzada
        this.config = {
            enableFuzzyMatching: true,
            enableAutoDetection: true,
            enableDynamicContent: true,
            enableJavaScriptTranslation: true,
            cacheTimeout: 300000, // 5 minutos
            debounceDelay: 100,
            maxRetries: 3
        };
        
        this.init();
    }

    /**
     * Inicialización del sistema avanzado
     */
    async init() {
        try {
            console.log('🚀 Iniciando Sistema de Traducción Avanzado Wayra Kawsay...');
            
            await this.loadAdvancedTranslations();
            this.loadSavedLanguage();
            this.setupAdvancedLanguageSelectors();
            this.setupAdvancedDOMObserver();
            this.setupTextMappings();
            this.setupJavaScriptInterception();
            await this.performCompleteTranslation();
            this.bindAdvancedEvents();
            
            console.log('✅ Sistema de traducción avanzado inicializado exitosamente');
        } catch (error) {
            console.error('❌ Error al inicializar el sistema de traducción avanzado:', error);
            this.enableFallbackMode();
        }
    }

    /**
     * Carga traducciones con sistema avanzado de fallback
     */
    async loadAdvancedTranslations() {
        if (this.isLoading) return;
        this.isLoading = true;

        let retries = 0;
        while (retries < this.config.maxRetries) {
            try {
                const [esResponse, quResponse] = await Promise.all([
                    fetch('/WAYRA_KAWSAY/languages/es.json?' + Date.now()),
                    fetch('/WAYRA_KAWSAY/languages/qu.json?' + Date.now())
                ]);

                if (!esResponse.ok || !quResponse.ok) {
                    throw new Error(`Error HTTP: ${esResponse.status} / ${quResponse.status}`);
                }

                this.translations.es = await esResponse.json();
                this.translations.qu = await quResponse.json();
                
                this.buildAdvancedMappings();
                break;

            } catch (error) {
                retries++;
                console.warn(`⚠️ Intento ${retries}/${this.config.maxRetries} fallido:`, error);
                
                if (retries >= this.config.maxRetries) {
                    console.error('❌ No se pudieron cargar las traducciones después de varios intentos');
                    this.loadComprehensiveFallback();
                }
                
                await new Promise(resolve => setTimeout(resolve, 1000 * retries));
            }
        }
        
        this.isLoading = false;
    }

    /**
     * Construye mapeos avanzados para traducción automática
     */
    buildAdvancedMappings() {
        // Crear mapeos bidireccionales
        Object.keys(this.translations.es).forEach(key => {
            const esText = this.translations.es[key];
            const quText = this.translations.qu[key] || esText;
            
            // Mapeo exacto
            this.textMappings.set(esText, quText);
            this.textMappings.set(quText, esText);
            
            // Mapeo por clave
            this.textMappings.set(key, { es: esText, qu: quText });
            
            // Mapeos de variantes (sin acentos, mayúsculas/minúsculas)
            this.textMappings.set(this.normalizeText(esText), quText);
            this.textMappings.set(this.normalizeText(quText), esText);
        });
        
        console.log(`📚 Construidos ${this.textMappings.size} mapeos de traducción`);
    }

    /**
     * Normaliza texto para búsqueda fuzzy
     */
    normalizeText(text) {
        return text.toLowerCase()
                  .normalize('NFD')
                  .replace(/[\u0300-\u036f]/g, '')
                  .replace(/[^\w\s]/g, '')
                  .trim();
    }

    /**
     * Traducciones de fallback más completas
     */
    loadComprehensiveFallback() {
        this.translations = {
            es: {
                // Navegación
                "titulo_inicio": "Wayra Kawsay",
                "subtitulo_inicio": "Viento de Cultura",
                "menu_inicio": "Inicio",
                "menu_sabores": "Sabores",
                "menu_artesanias": "Artesanías",
                "menu_kichwa": "Kichwa",
                "menu_cultura": "Cultura",
                "menu_ubicacion": "Ubicación",
                
                // Contenido principal
                "bienvenido": "Bienvenido a Wayra Kawsay",
                "explorar_cultura": "Explorar Cultura",
                "descubre_tradiciones": "Descubre Nuestras Tradiciones",
                "sabores_unicos": "Sabores Únicos",
                "artesanias": "Artesanías",
                "idioma_kichwa": "Idioma Kichwa",
                "paisajes": "Paisajes",
                
                // Botones y acciones
                "ver_detalles": "Ver detalles",
                "conoce_mas": "Conoce Más",
                "buscar": "Buscar",
                "limpiar": "Limpiar",
                "cerrar": "Cerrar",
                "todos": "Todos",
                "todas": "Todas",
                
                // Footer
                "pie_pagina": "© 2024 Wayra Kawsay. Todos los derechos reservados.",
                "navegacion": "Navegación",
                "contacto": "Contacto",
                
                // JavaScript messages
                "cambiando_idioma": "Cambiando idioma...",
                "no_disponible": "No disponible",
                "imagen_no_disponible": "Imagen no disponible",
                "audio_no_disponible": "Audio no disponible"
            },
            qu: {
                // Navegación
                "titulo_inicio": "Wayra Kawsay",
                "subtitulo_inicio": "Kawsaypata Wayra",
                "menu_inicio": "Kallari",
                "menu_sabores": "Mishkikuna",
                "menu_artesanias": "Maki Ruraykuna",
                "menu_kichwa": "Kichwa",
                "menu_cultura": "Kawsay",
                "menu_ubicacion": "Maypi Kay",
                
                // Contenido principal
                "bienvenido": "Wayra Kawsayman shamuy",
                "explorar_cultura": "Kawsayta Maskay",
                "descubre_tradiciones": "Ñukanchik Kawsaykunata Riqsiy",
                "sabores_unicos": "Sapan Mishkikuna",
                "artesanias": "Maki Ruraykuna",
                "idioma_kichwa": "Kichwa Shimi",
                "paisajes": "Pampakuna",
                
                // Botones y acciones
                "ver_detalles": "Astawan Rikuy",
                "conoce_mas": "Astawan Riqsiy",
                "buscar": "Maskay",
                "limpiar": "Pichay",
                "cerrar": "Wisay",
                "todos": "Tukuy",
                "todas": "Tukuykuna",
                
                // Footer
                "pie_pagina": "© 2024 Wayra Kawsay. Tukuy hayñikuna kamachisqa.",
                "navegacion": "Wamp'una",
                "contacto": "Tinkuna",
                
                // JavaScript messages
                "cambiando_idioma": "Shimita tikrachispa...",
                "no_disponible": "Mana tiyan",
                "imagen_no_disponible": "Siq'i mana tiyan",
                "audio_no_disponible": "Uyarina mana tiyan"
            }
        };
        
        this.buildAdvancedMappings();
    }

    /**
     * Configuración avanzada de selectores de idioma
     */
    setupAdvancedLanguageSelectors() {
        const selectors = document.querySelectorAll('#language-selector, .language-selector, [data-language-selector]');
        
        selectors.forEach(selector => {
            // Limpiar eventos previos completamente
            const newSelector = selector.cloneNode(true);
            selector.parentNode.replaceChild(newSelector, selector);
            
            // Configurar contenido estándar
            this.setupSelectorContent(newSelector);
            
            // Establecer valor actual
            newSelector.value = this.currentLanguage;
            
            // Agregar event listener robusto
            newSelector.addEventListener('change', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.changeLanguage(e.target.value);
            });
            
            // Prevenir eventos duplicados
            newSelector.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
        
        console.log(`🔧 Configurados ${selectors.length} selectores de idioma`);
    }

    /**
     * Configuración del contenido del selector
     */
    setupSelectorContent(selector) {
        selector.innerHTML = `
            <option value="es">🇪🇸 Español</option>
            <option value="qu">🏔️ Kichwa</option>
        `;
        selector.className = 'form-select';
        selector.removeAttribute('onchange');
    }

    /**
     * Observador DOM avanzado con detección profunda
     */
    setupAdvancedDOMObserver() {
        if (!window.MutationObserver) return;
        
        const observer = new MutationObserver((mutations) => {
            let shouldTranslate = false;
            let newElements = [];
            
            mutations.forEach(mutation => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            newElements.push(node);
                            shouldTranslate = true;
                        }
                    });
                } else if (mutation.type === 'characterData') {
                    shouldTranslate = true;
                }
            });
            
            if (shouldTranslate) {
                this.debounceTranslation(() => {
                    this.translateNewElements(newElements);
                });
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
            characterData: true,
            characterDataOldValue: true
        });
        
        this.observers.push(observer);
    }

    /**
     * Traducción de nuevos elementos con procesamiento inteligente
     */
    translateNewElements(elements) {
        elements.forEach(element => {
            if (!this.processedElements.has(element)) {
                this.translateElement(element);
                this.processedElements.add(element);
            }
        });
    }

    /**
     * Debounce para optimizar rendimiento
     */
    debounceTranslation(func) {
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(func, this.config.debounceDelay);
    }

    /**
     * Configuración de mapeos de texto automático
     */
    setupTextMappings() {
        // Interceptar cambios de texto en elementos
        const originalTextContent = Object.getOwnPropertyDescriptor(Node.prototype, 'textContent');
        const originalInnerHTML = Object.getOwnPropertyDescriptor(Element.prototype, 'innerHTML');
        
        if (originalTextContent) {
            Object.defineProperty(Node.prototype, 'textContent', {
                get: originalTextContent.get,
                set: function(value) {
                    const result = originalTextContent.set.call(this, value);
                    if (wayraAdvancedTranslation?.autoTranslateMode && this.nodeType === Node.ELEMENT_NODE) {
                        wayraAdvancedTranslation.debounceTranslation(() => {
                            wayraAdvancedTranslation.translateElement(this);
                        });
                    }
                    return result;
                }
            });
        }
    }

    /**
     * Interceptación de JavaScript para traducir texto dinámico
     */
    setupJavaScriptInterception() {
        if (!this.config.enableJavaScriptTranslation) return;
        
        // Interceptar console.log para traducir mensajes
        const originalConsoleLog = console.log;
        console.log = (...args) => {
            const translatedArgs = args.map(arg => {
                if (typeof arg === 'string') {
                    return this.translateText(arg) || arg;
                }
                return arg;
            });
            return originalConsoleLog.apply(console, translatedArgs);
        };
        
        // Interceptar alert para traducir mensajes
        const originalAlert = window.alert;
        window.alert = (message) => {
            const translatedMessage = this.translateText(message) || message;
            return originalAlert.call(window, translatedMessage);
        };
    }

    /**
     * Realiza traducción completa de TODA la página
     */
    async performCompleteTranslation() {
        console.log('🔄 Iniciando traducción completa de la página...');
        
        // 1. Traducir elementos con data-translate
        await this.translateMarkedElements();
        
        // 2. Traducir todo el texto visible automáticamente
        await this.translateAllVisibleText();
        
        // 3. Traducir placeholders y atributos
        await this.translateAttributes();
        
        // 4. Traducir contenido JavaScript
        await this.translateJavaScriptContent();
        
        // 5. Actualizar título de la página
        this.translatePageTitle();
        
        console.log('✅ Traducción completa finalizada');
    }

    /**
     * Traduce elementos marcados con data-translate
     */
    async translateMarkedElements() {
        const elements = document.querySelectorAll('[data-translate]');
        
        elements.forEach(element => {
            const key = element.getAttribute('data-translate');
            const translation = this.getTranslation(key);
            
            if (translation && translation !== element.textContent.trim()) {
                element.textContent = translation;
                this.processedElements.add(element);
            }
        });
        
        console.log(`🏷️ Traducidos ${elements.length} elementos marcados`);
    }

    /**
     * Traduce automáticamente TODO el texto visible
     */
    async translateAllVisibleText() {
        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT,
            {
                acceptNode: (node) => {
                    // Filtrar nodos de script, style, etc.
                    const parent = node.parentElement;
                    if (!parent) return NodeFilter.FILTER_REJECT;
                    
                    const tagName = parent.tagName.toLowerCase();
                    if (['script', 'style', 'noscript', 'template'].includes(tagName)) {
                        return NodeFilter.FILTER_REJECT;
                    }
                    
                    // Solo nodos con texto significativo
                    const text = node.textContent.trim();
                    if (text.length < 2) return NodeFilter.FILTER_REJECT;
                    
                    return NodeFilter.FILTER_ACCEPT;
                }
            }
        );
        
        const textNodes = [];
        let currentNode;
        
        while (currentNode = walker.nextNode()) {
            textNodes.push(currentNode);
        }
        
        // Procesar nodos de texto en batches para mejor rendimiento
        for (let i = 0; i < textNodes.length; i += 50) {
            const batch = textNodes.slice(i, i + 50);
            await this.processBatch(batch);
            
            // Permitir que el navegador respire
            await new Promise(resolve => setTimeout(resolve, 0));
        }
        
        console.log(`📝 Procesados ${textNodes.length} nodos de texto`);
    }

    /**
     * Procesa un lote de nodos de texto
     */
    async processBatch(nodes) {
        nodes.forEach(node => {
            const originalText = node.textContent.trim();
            if (originalText && !this.processedElements.has(node.parentElement)) {
                const translation = this.translateTextAdvanced(originalText);
                if (translation && translation !== originalText) {
                    node.textContent = translation;
                    this.processedElements.add(node.parentElement);
                }
            }
        });
    }

    /**
     * Traducción avanzada de texto con múltiples estrategias
     */
    translateTextAdvanced(text) {
        if (!text || text.length < 2) return text;
        
        // 1. Búsqueda exacta
        let translation = this.textMappings.get(text);
        if (translation) {
            return this.currentLanguage === 'qu' ? translation : 
                   this.textMappings.get(translation);
        }
        
        // 2. Búsqueda normalizada
        const normalized = this.normalizeText(text);
        translation = this.textMappings.get(normalized);
        if (translation) return translation;
        
        // 3. Búsqueda fuzzy por palabras clave
        if (this.config.enableFuzzyMatching) {
            translation = this.fuzzyTranslate(text);
            if (translation) return translation;
        }
        
        // 4. Búsqueda por patrones comunes
        translation = this.patternTranslate(text);
        if (translation) return translation;
        
        return null;
    }

    /**
     * Traducción fuzzy inteligente
     */
    fuzzyTranslate(text) {
        const words = text.toLowerCase().split(/\s+/);
        let bestMatch = null;
        let bestScore = 0;
        
        for (const [key, value] of this.textMappings.entries()) {
            if (typeof key === 'string' && key.length > 3) {
                const keyWords = key.toLowerCase().split(/\s+/);
                const score = this.calculateSimilarity(words, keyWords);
                
                if (score > 0.7 && score > bestScore) {
                    bestScore = score;
                    bestMatch = this.currentLanguage === 'qu' ? value : 
                               this.textMappings.get(value);
                }
            }
        }
        
        return bestMatch;
    }

    /**
     * Calcula similitud entre arrays de palabras
     */
    calculateSimilarity(words1, words2) {
        const intersection = words1.filter(word => words2.includes(word));
        const union = [...new Set([...words1, ...words2])];
        return intersection.length / union.length;
    }

    /**
     * Traducción por patrones comunes
     */
    patternTranslate(text) {
        const patterns = {
            es: {
                'Ver más': 'Ver detalles',
                'Leer más': 'Ver detalles',
                'Mostrar': 'Ver detalles',
                'Ocultar': 'Cerrar',
                'Buscar en': 'Buscar',
                'Filtrar por': 'Todos'
            },
            qu: {
                'Astawan rikuy': 'Astawan Rikuy',
                'Ñawiriyta': 'Astawan Rikuy',
                'Riksichiy': 'Astawan Rikuy',
                'Pakay': 'Wisay',
                'Maskay': 'Maskay',
                'Suyana': 'Tukuy'
            }
        };
        
        const currentPatterns = patterns[this.currentLanguage] || {};
        
        for (const [pattern, replacement] of Object.entries(currentPatterns)) {
            if (text.toLowerCase().includes(pattern.toLowerCase())) {
                return this.getTranslation(replacement) || replacement;
            }
        }
        
        return null;
    }

    /**
     * Traduce atributos (placeholders, titles, alt, etc.)
     */
    async translateAttributes() {
        // Placeholders
        const placeholderElements = document.querySelectorAll('[placeholder], [data-translate-placeholder]');
        placeholderElements.forEach(element => {
            const key = element.getAttribute('data-translate-placeholder');
            if (key) {
                const translation = this.getTranslation(key);
                if (translation) element.placeholder = translation;
            } else {
                const placeholder = element.placeholder;
                const translation = this.translateTextAdvanced(placeholder);
                if (translation) element.placeholder = translation;
            }
        });
        
        // Títulos
        const titleElements = document.querySelectorAll('[title], [data-translate-title]');
        titleElements.forEach(element => {
            const key = element.getAttribute('data-translate-title');
            if (key) {
                const translation = this.getTranslation(key);
                if (translation) element.title = translation;
            } else {
                const title = element.title;
                const translation = this.translateTextAdvanced(title);
                if (translation) element.title = translation;
            }
        });
        
        // Alt text
        const imgElements = document.querySelectorAll('img[alt]');
        imgElements.forEach(img => {
            const alt = img.alt;
            const translation = this.translateTextAdvanced(alt);
            if (translation) img.alt = translation;
        });
        
        console.log('🏷️ Atributos traducidos completamente');
    }

    /**
     * Traduce contenido generado por JavaScript
     */
    async translateJavaScriptContent() {
        // Interceptar setTimeout y setInterval para traducir contenido dinámico
        const originalSetTimeout = window.setTimeout;
        window.setTimeout = (callback, delay, ...args) => {
            const wrappedCallback = () => {
                const result = callback.apply(this, args);
                this.debounceTranslation(() => this.translateAllVisibleText());
                return result;
            };
            return originalSetTimeout(wrappedCallback, delay);
        };
        
        // Interceptar fetch para retranslate después de cargar contenido
        const originalFetch = window.fetch;
        window.fetch = async (...args) => {
            const response = await originalFetch.apply(this, args);
            
            // Si es una respuesta exitosa, programar retranslation
            if (response.ok) {
                setTimeout(() => {
                    this.debounceTranslation(() => this.translateAllVisibleText());
                }, 100);
            }
            
            return response;
        };
    }

    /**
     * Traduce el título de la página
     */
    translatePageTitle() {
        const title = document.title;
        const translation = this.translateTextAdvanced(title);
        if (translation && translation !== title) {
            document.title = translation;
        }
    }

    /**
     * Traduce un elemento específico completamente
     */
    translateElement(element) {
        if (!element || this.processedElements.has(element)) return;
        
        // Traducir texto del elemento
        if (element.childNodes) {
            element.childNodes.forEach(node => {
                if (node.nodeType === Node.TEXT_NODE) {
                    const text = node.textContent.trim();
                    if (text.length > 1) {
                        const translation = this.translateTextAdvanced(text);
                        if (translation && translation !== text) {
                            node.textContent = translation;
                        }
                    }
                }
            });
        }
        
        // Traducir atributos
        ['placeholder', 'title', 'alt'].forEach(attr => {
            const value = element.getAttribute(attr);
            if (value) {
                const translation = this.translateTextAdvanced(value);
                if (translation && translation !== value) {
                    element.setAttribute(attr, translation);
                }
            }
        });
        
        this.processedElements.add(element);
    }

    /**
     * Cambia el idioma con retranslation completa
     */
    async changeLanguage(langCode) {
        if (!['es', 'qu'].includes(langCode) || langCode === this.currentLanguage) {
            return;
        }

        console.log(`🔄 Cambiando idioma de ${this.currentLanguage} a ${langCode}...`);
        
        this.currentLanguage = langCode;
        localStorage.setItem(this.storageKey, langCode);
        
        // Limpiar cache y elementos procesados
        this.cache.clear();
        this.processedElements = new WeakSet();
        
        // Actualizar todos los selectores
        document.querySelectorAll('#language-selector, .language-selector, [data-language-selector]').forEach(selector => {
            selector.value = langCode;
        });
        
        // Realizar traducción completa
        await this.performCompleteTranslation();
        
        // Disparar evento personalizado
        window.dispatchEvent(new CustomEvent('languageChanged', {
            detail: { 
                language: langCode,
                languageName: langCode === 'es' ? 'Español' : 'Kichwa'
            }
        }));
        
        console.log(`✅ Idioma cambiado exitosamente a ${langCode === 'es' ? 'Español' : 'Kichwa'}`);
    }

    /**
     * Obtiene traducción con cache optimizado
     */
    getTranslation(key) {
        const cacheKey = `${this.currentLanguage}:${key}`;
        
        if (this.cache.has(cacheKey)) {
            return this.cache.get(cacheKey);
        }

        const translation = this.translations[this.currentLanguage]?.[key] || key;
        this.cache.set(cacheKey, translation);
        
        // Limpiar cache automáticamente
        if (this.cache.size > 1000) {
            const firstKey = this.cache.keys().next().value;
            this.cache.delete(firstKey);
        }
        
        return translation;
    }

    /**
     * Traduce texto directamente
     */
    translateText(text) {
        return this.translateTextAdvanced(text);
    }

    /**
     * Habilita modo fallback en caso de errores críticos
     */
    enableFallbackMode() {
        console.warn('⚠️ Habilitando modo fallback...');
        this.loadComprehensiveFallback();
        this.autoTranslateMode = false;
        
        // Traducción básica de elementos marcados
        const elements = document.querySelectorAll('[data-translate]');
        elements.forEach(element => {
            const key = element.getAttribute('data-translate');
            const translation = this.getTranslation(key);
            if (translation) {
                element.textContent = translation;
            }
        });
    }

    /**
     * Vincula eventos avanzados
     */
    bindAdvancedEvents() {
        // Manejar navegación SPA
        window.addEventListener('popstate', () => {
            this.loadSavedLanguage();
            setTimeout(() => this.performCompleteTranslation(), 100);
        });

        // Manejar carga de contenido dinámico
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => this.performCompleteTranslation(), 100);
        });
        
        // Manejar cambios de hash
        window.addEventListener('hashchange', () => {
            setTimeout(() => this.translateAllVisibleText(), 200);
        });
        
        // Manejar resize para contenido responsive
        window.addEventListener('resize', this.debounce(() => {
            this.translateAllVisibleText();
        }, 300));
    }

    /**
     * Función debounce genérica
     */
    debounce(func, wait) {
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

    /**
     * Carga idioma guardado con validación
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
     * API de utilidades para desarrolladores
     */
    getCurrentLanguage() {
        return this.currentLanguage;
    }

    getAvailableLanguages() {
        return ['es', 'qu'];
    }

    clearCache() {
        this.cache.clear();
        this.processedElements = new WeakSet();
        console.log('🧹 Cache limpiado completamente');
    }

    getAdvancedStats() {
        return {
            currentLanguage: this.currentLanguage,
            cacheSize: this.cache.size,
            mappingsSize: this.textMappings.size,
            processedElements: this.processedElements ? 'WeakSet activo' : 'No disponible',
            translationsLoaded: {
                es: Object.keys(this.translations.es || {}).length,
                qu: Object.keys(this.translations.qu || {}).length
            },
            observersActive: this.observers.length,
            autoTranslateMode: this.autoTranslateMode,
            config: this.config
        };
    }

    /**
     * Fuerza retranslation completa
     */
    async forceCompleteRetranslation() {
        console.log('🔄 Forzando retranslation completa...');
        this.clearCache();
        await this.performCompleteTranslation();
        console.log('✅ Retranslation completa finalizada');
    }

    /**
     * Agrega nuevas traducciones dinámicamente
     */
    addTranslations(langCode, translations) {
        if (!this.translations[langCode]) {
            this.translations[langCode] = {};
        }
        
        Object.assign(this.translations[langCode], translations);
        this.buildAdvancedMappings();
        this.cache.clear();
        
        console.log(`➕ Agregadas ${Object.keys(translations).length} traducciones para ${langCode}`);
    }
}

// Variables globales
let wayraAdvancedTranslation;

/**
 * Inicialización automática del sistema avanzado
 */
function initAdvancedTranslationSystem() {
    if (!wayraAdvancedTranslation) {
        wayraAdvancedTranslation = new AdvancedWayraTranslationSystem();
    }
    return wayraAdvancedTranslation;
}

/**
 * Funciones globales para compatibilidad
 */
function changeLanguage(langCode) {
    if (wayraAdvancedTranslation) {
        wayraAdvancedTranslation.changeLanguage(langCode);
    }
}

function getTranslation(key) {
    return wayraAdvancedTranslation ? wayraAdvancedTranslation.getTranslation(key) : key;
}

function translateText(text) {
    return wayraAdvancedTranslation ? wayraAdvancedTranslation.translateText(text) : text;
}

function forceRetranslation() {
    if (wayraAdvancedTranslation) {
        wayraAdvancedTranslation.forceCompleteRetranslation();
    }
}

// Auto-inicialización inteligente
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAdvancedTranslationSystem);
} else {
    // Si el DOM ya está cargado, inicializar inmediatamente
    setTimeout(initAdvancedTranslationSystem, 0);
}

// Exportar para uso global
window.AdvancedWayraTranslationSystem = AdvancedWayraTranslationSystem;
window.wayraAdvancedTranslation = wayraAdvancedTranslation;
window.changeLanguage = changeLanguage;
window.getTranslation = getTranslation;
window.translateText = translateText;
window.forceRetranslation = forceRetranslation;
window.initAdvancedTranslationSystem = initAdvancedTranslationSystem;

console.log('🚀 Sistema de Traducción Avanzado Wayra Kawsay cargado');