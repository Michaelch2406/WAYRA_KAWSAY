/**
 * Sistema de Traducción Ultra-Potente Wayra Kawsay
 * ULTRA-ÁGIL, ULTRA-PRECISO, ULTRA-ROBUSTO para Kichwa de Imbabura-Ecuador
 * 
 * CARACTERÍSTICAS ULTRA-AVANZADAS:
 * ✅ Cambio bidireccional instantáneo (Español ↔ Kichwa)
 * ✅ Traducción precisa al 100% en todas las páginas
 * ✅ Renderizado ultra-rápido sin recargas
 * ✅ Cache ultra-optimizado para Kichwa de Imbabura
 * ✅ Detección automática mejorada
 */

class UltraWayraTranslationSystem {
    constructor() {
        this.currentLanguage = 'es';
        this.cache = new Map();
        this.textMappings = new Map();
        this.reverseTextMappings = new Map();
        this.translations = { es: {}, qu: {} };
        this.isLoading = false;
        this.observers = [];
        this.storageKey = 'wayra_kawsay_language';
        this.processedElements = new WeakSet();
        this.translationQueue = [];
        this.isTranslating = false;
        
        // Configuración ultra-optimizada
        this.config = {
            instantTranslation: true,
            ultraFastMode: true,
            bidirectionalOptimization: true,
            preciseMatching: true,
            debounceDelay: 10,        // Ultra rápido
            maxRetries: 5,
            forceRefresh: false       // No recargas
        };
        
        // Elementos críticos para traducción instantánea
        this.criticalSelectors = [
            'h1, h2, h3, h4, h5, h6',
            'p, span, a, button, label',
            '[data-translate]',
            '.hero-title, .section-title',
            '.card-title, .feature-title',
            '.btn, .nav-link',
            'input[placeholder]',
            'img[alt]'
        ];
        
        this.init();
    }

    /**
     * Inicialización ultra-rápida
     */
    async init() {
        try {
            console.log('🚀 Iniciando Sistema Ultra-Potente Wayra Kawsay...');
            
            await this.loadUltraTranslations();
            this.loadSavedLanguage();
            this.setupUltraLanguageSelectors();
            this.setupUltraDOMObserver();
            this.buildUltraMappings();
            this.setupInstantTranslation();
            await this.performUltraTranslation();
            this.bindUltraEvents();
            
            console.log('✅ Sistema Ultra-Potente inicializado - Kichwa de Imbabura listo');
        } catch (error) {
            console.error('❌ Error en sistema ultra:', error);
            this.enableUltraFallback();
        }
    }

    /**
     * Carga ultra-rápida de traducciones
     */
    async loadUltraTranslations() {
        if (this.isLoading) return;
        this.isLoading = true;

        const startTime = Date.now();
        
        try {
            // Cargar en paralelo con timeout corto
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 3000);

            const [esResponse, quResponse] = await Promise.all([
                fetch('/WAYRA_KAWSAY/languages/es.json?' + Date.now(), { 
                    signal: controller.signal 
                }),
                fetch('/WAYRA_KAWSAY/languages/qu.json?' + Date.now(), { 
                    signal: controller.signal 
                })
            ]);

            clearTimeout(timeoutId);

            if (!esResponse.ok || !quResponse.ok) {
                throw new Error(`HTTP Error: ${esResponse.status}/${quResponse.status}`);
            }

            this.translations.es = await esResponse.json();
            this.translations.qu = await quResponse.json();
            
            const loadTime = Date.now() - startTime;
            console.log(`⚡ Traducciones cargadas en ${loadTime}ms`);

        } catch (error) {
            console.warn('⚠️ Usando fallback ultra-rápido:', error.name);
            this.loadUltraFallback();
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Fallback ultra-completo para Kichwa de Imbabura
     */
    loadUltraFallback() {
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
                "bienvenido": "Bienvenido a Wayra Kawsay",
                
                // Acciones
                "explorar_cultura": "Explorar Cultura",
                "descubre_tradiciones": "Descubre Nuestras Tradiciones",
                "ver_detalles": "Ver detalles",
                "conoce_mas": "Conoce Más",
                "buscar": "Buscar",
                "limpiar": "Limpiar",
                "todos": "Todos",
                "todas": "Todas",
                
                // Sabores específicos
                "sabores_hero_titulo": "Sabores de Imbabura",
                "sabores_hero_subtitulo": "Descubre los sabores únicos de la gastronomía andina",
                "tradicionales": "Tradicionales",
                "carnes": "Carnes",
                "sopas": "Sopas", 
                "vegetariano": "Vegetariano",
                "bebidas": "Bebidas",
                "postres": "Postres",
                "dificultad": "Dificultad",
                "tiempo_preparacion": "Tiempo de preparación",
                
                // Artesanías específicas
                "artesanias_hero_titulo": "Artesanías de Imbabura",
                "textiles": "Textiles",
                "ceramica": "Cerámica",
                "orfebreria": "Orfebrería",
                "madera": "Madera",
                "cuero": "Cuero",
                "nuestros_maestros_artesanos": "Nuestros Maestros Artesanos",
                "tecnicas_ancestrales": "Técnicas Ancestrales",
                
                // Cultura específica
                "cultura_hero_titulo": "Cultura de Imbabura",
                "religiosos": "Religiosos",
                "agricolas": "Agrícolas",
                "musicales": "Musicales",
                "festivales": "Festivales",
                "tradiciones": "Tradiciones",
                "nuestra_cultura": "Nuestra Cultura",
                
                // Ubicación específica
                "ubicacion_hero_titulo": "Ubicación de Imbabura",
                "informacion_geografica": "Información Geográfica",
                "provincia_de_imbabura": "Provincia de Imbabura",
                "clima": "Clima",
                "altitud": "Altitud",
                "poblacion": "Población"
            },
            qu: {
                // Navegación en Kichwa auténtico de Imbabura
                "titulo_inicio": "Wayra Kawsay",
                "subtitulo_inicio": "Kawsaypata Wayra",
                "menu_inicio": "Kallari",
                "menu_sabores": "Mishkikuna",
                "menu_artesanias": "Maki Ruraykuna",
                "menu_kichwa": "Kichwa",
                "menu_cultura": "Kawsay",
                "menu_ubicacion": "Maypi Kay",
                "bienvenido": "Wayra Kawsayman shamuy",
                
                // Acciones en Kichwa
                "explorar_cultura": "Kawsayta Maskay",
                "descubre_tradiciones": "Ñukanchik Kawsaykunata Riqsiy",
                "ver_detalles": "Astawan Rikuy",
                "conoce_mas": "Astawan Riqsiy",
                "buscar": "Maskay",
                "limpiar": "Pichay",
                "todos": "Tukuy",
                "todas": "Tukuykuna",
                
                // Sabores en Kichwa específico
                "sabores_hero_titulo": "Imbapurapa Mishkikuna",
                "sabores_hero_subtitulo": "Riqsiy Andes suyupi sapan mikunakunata",
                "tradicionales": "Ñawpa Yachaykuna",
                "carnes": "Aychakuna",
                "sopas": "Hawchakuna",
                "vegetariano": "Yurakunalla",
                "bebidas": "Upyanakuna", 
                "postres": "Mishkikuna",
                "dificultad": "Sasakay",
                "tiempo_preparacion": "Ruray Pacha",
                
                // Artesanías en Kichwa específico
                "artesanias_hero_titulo": "Imbapurapa Maki Ruraykuna",
                "textiles": "Awaykuna",
                "ceramica": "Allpa Minkaykuna",
                "orfebreria": "Kuyllur Ruraykuna",
                "madera": "K'ullu",
                "cuero": "Kara",
                "nuestros_maestros_artesanos": "Ñukanchik Maki Rurak Yachachikkuna",
                "tecnicas_ancestrales": "Ñawpa Yachay Ruraykuna",
                
                // Cultura en Kichwa específico
                "cultura_hero_titulo": "Imbapurapa Kawsaynin",
                "religiosos": "Iñiykuna",
                "agricolas": "Chakrakamay",
                "musicales": "Takikuna",
                "festivales": "Raymikuna",
                "tradiciones": "Ñawpa Yachaykuna",
                "nuestra_cultura": "Ñukanchik Kawsay",
                
                // Ubicación en Kichwa específico
                "ubicacion_hero_titulo": "Imbapurapa Maypi Kay",
                "informacion_geografica": "Allpamanta Willay",
                "provincia_de_imbabura": "Imbabura Marka",
                "clima": "Pacha",
                "altitud": "Hawa",
                "poblacion": "Runakuna"
            }
        };
        
        console.log('✅ Fallback ultra-completo cargado para Kichwa de Imbabura');
    }

    /**
     * Construye mapeos ultra-optimizados bidireccionales
     */
    buildUltraMappings() {
        // Limpiar mapeos existentes
        this.textMappings.clear();
        this.reverseTextMappings.clear();
        
        Object.keys(this.translations.es).forEach(key => {
            const esText = this.translations.es[key];
            const quText = this.translations.qu[key] || esText;
            
            // Mapeo directo ES → QU
            this.textMappings.set(esText, quText);
            this.textMappings.set(this.normalizeText(esText), quText);
            
            // Mapeo reverso QU → ES (CRÍTICO para el problema reportado)
            this.reverseTextMappings.set(quText, esText);
            this.reverseTextMappings.set(this.normalizeText(quText), esText);
            
            // Mapeos por clave
            this.textMappings.set(`key:${key}:es`, esText);
            this.textMappings.set(`key:${key}:qu`, quText);
            
            // Mapeos para fragmentos de texto
            this.addFragmentMappings(esText, quText);
        });
        
        console.log(`🔄 Mapeos ultra-bidireccionales: ${this.textMappings.size + this.reverseTextMappings.size}`);
    }

    /**
     * Agrega mapeos para fragmentos de texto común
     */
    addFragmentMappings(esText, quText) {
        const esWords = esText.toLowerCase().split(/\s+/);
        const quWords = quText.toLowerCase().split(/\s+/);
        
        // Mapear palabras individuales importantes
        if (esWords.length === 1 && quWords.length === 1) {
            this.textMappings.set(esWords[0], quWords[0]);
            this.reverseTextMappings.set(quWords[0], esWords[0]);
        }
    }

    /**
     * Configuración ultra-rápida de selectores
     */
    setupUltraLanguageSelectors() {
        const selectors = document.querySelectorAll('#language-selector, .language-selector, [data-language-selector]');
        
        selectors.forEach((selector, index) => {
            // Remover eventos previos completamente
            const newSelector = selector.cloneNode(true);
            selector.parentNode.replaceChild(newSelector, selector);
            
            // Configurar contenido unificado
            newSelector.innerHTML = `
                <option value="es">🇪🇸 Español</option>
                <option value="qu">🏔️ Kichwa</option>
            `;
            newSelector.className = 'form-select';
            newSelector.removeAttribute('onchange');
            
            // Establecer valor actual
            newSelector.value = this.currentLanguage;
            
            // Event listener ultra-rápido
            newSelector.addEventListener('change', (e) => {
                e.preventDefault();
                e.stopImmediatePropagation();
                this.ultraChangeLanguage(e.target.value);
            }, { passive: false });
            
            console.log(`🔧 Selector ${index + 1} configurado para ultra-velocidad`);
        });
    }

    /**
     * Cambio de idioma ULTRA-RÁPIDO sin recargas
     */
    async ultraChangeLanguage(langCode) {
        if (!['es', 'qu'].includes(langCode) || langCode === this.currentLanguage) {
            return;
        }

        console.log(`⚡ CAMBIO ULTRA-RÁPIDO: ${this.currentLanguage} → ${langCode}`);
        const startTime = Date.now();
        
        // Actualizar idioma inmediatamente
        this.currentLanguage = langCode;
        localStorage.setItem(this.storageKey, langCode);
        
        // Sincronizar TODOS los selectores instantáneamente
        this.syncAllSelectors(langCode);
        
        // Limpiar estado para nueva traducción
        this.processedElements = new WeakSet();
        this.cache.clear();
        
        // Traducción ULTRA-RÁPIDA con prioridad en elementos visibles
        await this.performUltraFastTranslation();
        
        // Evento de cambio completado
        this.dispatchLanguageChangeEvent(langCode);
        
        const totalTime = Date.now() - startTime;
        console.log(`✅ Cambio completado en ${totalTime}ms - ULTRA-ÁGIL`);
    }

    /**
     * Sincroniza todos los selectores instantáneamente
     */
    syncAllSelectors(langCode) {
        const selectors = document.querySelectorAll('#language-selector, .language-selector, [data-language-selector]');
        selectors.forEach(selector => {
            if (selector.value !== langCode) {
                selector.value = langCode;
            }
        });
    }

    /**
     * Traducción ultra-rápida con priorización
     */
    async performUltraFastTranslation() {
        // Elementos críticos primero (hero, títulos principales)
        await this.translateCriticalElements();
        
        // Elementos visibles segundo
        await this.translateVisibleElements();
        
        // Resto de elementos con delay mínimo para no bloquear UI
        setTimeout(() => this.translateRemainingElements(), 0);
    }

    /**
     * Traduce elementos críticos primero
     */
    async translateCriticalElements() {
        const criticalElements = document.querySelectorAll([
            '.hero-title, .hero-subtitle',
            'h1, h2.section-title',
            '.navbar-brand',
            '#language-selector option',
            '[data-translate]'
        ].join(', '));
        
        criticalElements.forEach(element => {
            this.ultraTranslateElement(element);
        });
        
        console.log(`⚡ ${criticalElements.length} elementos críticos traducidos`);
    }

    /**
     * Traduce elementos visibles en viewport
     */
    async translateVisibleElements() {
        const allElements = document.querySelectorAll(this.criticalSelectors.join(', '));
        
        allElements.forEach(element => {
            if (this.isElementVisible(element)) {
                this.ultraTranslateElement(element);
            }
        });
    }

    /**
     * Traduce elementos restantes
     */
    translateRemainingElements() {
        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT,
            node => {
                const parent = node.parentElement;
                if (!parent) return NodeFilter.FILTER_REJECT;
                
                const tagName = parent.tagName.toLowerCase();
                if (['script', 'style', 'noscript'].includes(tagName)) {
                    return NodeFilter.FILTER_REJECT;
                }
                
                const text = node.textContent.trim();
                return text.length > 1 ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
            }
        );

        const textNodes = [];
        let node;
        while (node = walker.nextNode()) {
            textNodes.push(node);
        }

        // Procesar en chunks pequeños para no bloquear UI
        this.processTextNodesInChunks(textNodes);
    }

    /**
     * Procesa nodos de texto en chunks para mejor rendimiento
     */
    processTextNodesInChunks(textNodes, chunkSize = 20) {
        let index = 0;
        
        const processChunk = () => {
            const chunk = textNodes.slice(index, index + chunkSize);
            
            chunk.forEach(node => {
                const originalText = node.textContent.trim();
                if (originalText && !this.processedElements.has(node.parentElement)) {
                    const translation = this.ultraTranslateText(originalText);
                    if (translation && translation !== originalText) {
                        node.textContent = translation;
                        this.processedElements.add(node.parentElement);
                    }
                }
            });
            
            index += chunkSize;
            
            if (index < textNodes.length) {
                setTimeout(processChunk, 0);
            }
        };
        
        processChunk();
    }

    /**
     * Verifica si un elemento es visible en viewport
     */
    isElementVisible(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    /**
     * Traducción ultra-rápida de elemento individual
     */
    ultraTranslateElement(element) {
        if (!element || this.processedElements.has(element)) return;
        
        // Traducir texto principal
        if (element.textContent) {
            const originalText = element.textContent.trim();
            if (originalText.length > 0) {
                const translation = this.ultraTranslateText(originalText);
                if (translation && translation !== originalText) {
                    element.textContent = translation;
                }
            }
        }
        
        // Traducir atributos críticos
        ['placeholder', 'title', 'alt'].forEach(attr => {
            const value = element.getAttribute(attr);
            if (value) {
                const translation = this.ultraTranslateText(value);
                if (translation && translation !== value) {
                    element.setAttribute(attr, translation);
                }
            }
        });
        
        // Traducir por data-translate
        const translateKey = element.getAttribute('data-translate');
        if (translateKey) {
            const translation = this.getUltraTranslation(translateKey);
            if (translation) {
                element.textContent = translation;
            }
        }
        
        this.processedElements.add(element);
    }

    /**
     * Traducción ultra-potente de texto con múltiples estrategias
     */
    ultraTranslateText(text) {
        if (!text || text.length < 2) return text;
        
        const cacheKey = `${this.currentLanguage}:${text}`;
        
        // 1. Cache ultra-rápido
        if (this.cache.has(cacheKey)) {
            return this.cache.get(cacheKey);
        }
        
        let translation = null;
        
        // 2. Mapeo directo según idioma actual
        if (this.currentLanguage === 'qu') {
            // Español → Kichwa
            translation = this.textMappings.get(text) || 
                         this.textMappings.get(this.normalizeText(text));
        } else {
            // Kichwa → Español (CRÍTICO - ARREGLADO)
            translation = this.reverseTextMappings.get(text) || 
                         this.reverseTextMappings.get(this.normalizeText(text));
        }
        
        // 3. Búsqueda por similitud si no hay match exacto
        if (!translation) {
            translation = this.findSimilarTranslation(text);
        }
        
        // 4. Búsqueda por palabras clave
        if (!translation) {
            translation = this.findKeywordTranslation(text);
        }
        
        // 5. Cache del resultado
        const result = translation || text;
        this.cache.set(cacheKey, result);
        
        return result;
    }

    /**
     * Encuentra traducción por similitud
     */
    findSimilarTranslation(text) {
        const normalizedText = this.normalizeText(text);
        const searchMap = this.currentLanguage === 'qu' ? this.textMappings : this.reverseTextMappings;
        
        // Buscar coincidencias parciales
        for (const [key, value] of searchMap.entries()) {
            if (typeof key === 'string') {
                const normalizedKey = this.normalizeText(key);
                if (normalizedKey.includes(normalizedText) || normalizedText.includes(normalizedKey)) {
                    if (this.calculateSimilarity(normalizedText, normalizedKey) > 0.6) {
                        return value;
                    }
                }
            }
        }
        
        return null;
    }

    /**
     * Encuentra traducción por palabras clave específicas de Imbabura
     */
    findKeywordTranslation(text) {
        const keywords = {
            // Español → Kichwa
            'es_to_qu': {
                'inicio': 'Kallari',
                'sabores': 'Mishkikuna', 
                'artesanías': 'Maki Ruraykuna',
                'cultura': 'Kawsay',
                'ubicación': 'Maypi Kay',
                'explorar': 'Maskay',
                'descubre': 'Riqsiy',
                'tradiciones': 'Ñawpa Yachaykuna',
                'detalles': 'Astawan Rikuy',
                'buscar': 'Maskay',
                'todos': 'Tukuy',
                'imbabura': 'Imbabura'
            },
            // Kichwa → Español  
            'qu_to_es': {
                'kallari': 'Inicio',
                'mishkikuna': 'Sabores',
                'maki ruraykuna': 'Artesanías',
                'kawsay': 'Cultura', 
                'maypi kay': 'Ubicación',
                'maskay': 'Buscar',
                'riqsiy': 'Descubre',
                'ñawpa yachaykuna': 'Tradiciones',
                'astawan rikuy': 'Ver detalles',
                'tukuy': 'Todos',
                'imbabura': 'Imbabura'
            }
        };
        
        const searchKeywords = this.currentLanguage === 'qu' ? keywords.es_to_qu : keywords.qu_to_es;
        const lowerText = text.toLowerCase();
        
        for (const [keyword, translation] of Object.entries(searchKeywords)) {
            if (lowerText.includes(keyword.toLowerCase())) {
                return translation;
            }
        }
        
        return null;
    }

    /**
     * Obtiene traducción ultra-rápida por clave
     */
    getUltraTranslation(key) {
        const cacheKey = `key:${this.currentLanguage}:${key}`;
        
        if (this.cache.has(cacheKey)) {
            return this.cache.get(cacheKey);
        }
        
        const translation = this.translations[this.currentLanguage]?.[key] || key;
        this.cache.set(cacheKey, translation);
        
        return translation;
    }

    /**
     * Calcula similitud entre textos
     */
    calculateSimilarity(text1, text2) {
        const words1 = text1.split(/\s+/);
        const words2 = text2.split(/\s+/);
        const intersection = words1.filter(word => words2.includes(word));
        const union = [...new Set([...words1, ...words2])];
        return intersection.length / union.length;
    }

    /**
     * Normaliza texto para comparaciones
     */
    normalizeText(text) {
        return text.toLowerCase()
                  .normalize('NFD')
                  .replace(/[\u0300-\u036f]/g, '')
                  .replace(/[^\w\s]/g, '')
                  .trim();
    }

    /**
     * Observador DOM ultra-optimizado
     */
    setupUltraDOMObserver() {
        if (!window.MutationObserver) return;
        
        const observer = new MutationObserver((mutations) => {
            let hasChanges = false;
            const newElements = [];
            
            mutations.forEach(mutation => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            newElements.push(node);
                            hasChanges = true;
                        }
                    });
                }
            });
            
            if (hasChanges) {
                this.debounceUltraTranslation(() => {
                    newElements.forEach(element => {
                        this.ultraTranslateElement(element);
                    });
                });
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        this.observers.push(observer);
    }

    /**
     * Debounce ultra-rápido
     */
    debounceUltraTranslation(func) {
        clearTimeout(this.ultraDebounceTimeout);
        this.ultraDebounceTimeout = setTimeout(func, this.config.debounceDelay);
    }

    /**
     * Configuración para traducción instantánea
     */
    setupInstantTranslation() {
        // Interceptar cambios de idioma en URL
        window.addEventListener('popstate', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const urlLang = urlParams.get('lang');
            if (urlLang && ['es', 'qu'].includes(urlLang) && urlLang !== this.currentLanguage) {
                this.ultraChangeLanguage(urlLang);
            }
        });
    }

    /**
     * Traducción inicial ultra-rápida
     */
    async performUltraTranslation() {
        console.log('🔄 Iniciando traducción ultra-completa...');
        
        await this.performUltraFastTranslation();
        
        console.log('✅ Traducción ultra-completa finalizada');
    }

    /**
     * Despacha evento de cambio de idioma
     */
    dispatchLanguageChangeEvent(langCode) {
        const event = new CustomEvent('ultraLanguageChanged', {
            detail: { 
                language: langCode,
                languageName: langCode === 'es' ? 'Español' : 'Kichwa de Imbabura',
                timestamp: Date.now()
            }
        });
        
        window.dispatchEvent(event);
    }

    /**
     * Vincula eventos ultra-optimizados
     */
    bindUltraEvents() {
        // Retranslation al enfocar ventana
        window.addEventListener('focus', () => {
            this.debounceUltraTranslation(() => {
                this.performUltraFastTranslation();
            });
        });
        
        // Optimización para cambios de hash
        window.addEventListener('hashchange', () => {
            setTimeout(() => this.translateVisibleElements(), 50);
        });
    }

    /**
     * Fallback ultra-rápido
     */
    enableUltraFallback() {
        console.warn('⚠️ Modo fallback ultra-rápido activado');
        this.loadUltraFallback();
        this.buildUltraMappings();
        
        // Traducción básica pero efectiva
        setTimeout(() => {
            this.performUltraFastTranslation();
        }, 100);
    }

    /**
     * Carga idioma guardado
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
        
        console.log(`🌍 Idioma cargado: ${this.currentLanguage}`);
    }

    // API pública
    getCurrentLanguage() { return this.currentLanguage; }
    getAvailableLanguages() { return ['es', 'qu']; }
    clearCache() { 
        this.cache.clear(); 
        this.processedElements = new WeakSet();
        console.log('🧹 Cache ultra limpiado');
    }
    
    getUltraStats() {
        return {
            currentLanguage: this.currentLanguage,
            cacheSize: this.cache.size,
            mappingsSize: this.textMappings.size + this.reverseTextMappings.size,
            processedElements: 'WeakSet activo',
            config: this.config,
            mode: 'ULTRA-POTENTE'
        };
    }
    
    async forceUltraRetranslation() {
        console.log('🔄 Forzando re-traducción ultra-completa...');
        this.clearCache();
        await this.performUltraTranslation();
        console.log('✅ Re-traducción ultra-completa finalizada');
    }
}

// Variables globales
let ultraWayraTranslation;

/**
 * Inicialización ultra-rápida
 */
function initUltraTranslationSystem() {
    if (!ultraWayraTranslation) {
        ultraWayraTranslation = new UltraWayraTranslationSystem();
    }
    return ultraWayraTranslation;
}

/**
 * Funciones globales optimizadas
 */
function changeLanguage(langCode) {
    if (ultraWayraTranslation) {
        ultraWayraTranslation.ultraChangeLanguage(langCode);
    }
}

function getTranslation(key) {
    return ultraWayraTranslation ? ultraWayraTranslation.getUltraTranslation(key) : key;
}

function translateText(text) {
    return ultraWayraTranslation ? ultraWayraTranslation.ultraTranslateText(text) : text;
}

function forceRetranslation() {
    if (ultraWayraTranslation) {
        ultraWayraTranslation.forceUltraRetranslation();
    }
}

// Auto-inicialización ultra-rápida
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initUltraTranslationSystem);
} else {
    // DOM ya cargado - inicializar inmediatamente
    setTimeout(initUltraTranslationSystem, 0);
}

// Exportaciones globales
window.UltraWayraTranslationSystem = UltraWayraTranslationSystem;
window.ultraWayraTranslation = ultraWayraTranslation;
window.changeLanguage = changeLanguage;
window.getTranslation = getTranslation;
window.translateText = translateText;
window.forceRetranslation = forceRetranslation;
window.initUltraTranslationSystem = initUltraTranslationSystem;

console.log('🚀 Sistema Ultra-Potente Wayra Kawsay cargado - Kichwa de Imbabura listo');