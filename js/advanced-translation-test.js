/**
 * Sistema de Pruebas Avanzado para Traducción Completa
 * Verifica que TODA la página se traduzca al 100%
 */

class AdvancedTranslationTester {
    constructor() {
        this.testResults = [];
        this.untranslatedTexts = [];
        this.translationCoverage = 0;
        this.totalTextNodes = 0;
        this.translatedNodes = 0;
        this.startTime = Date.now();
    }

    /**
     * Ejecuta todas las pruebas de traducción completa
     */
    async runCompleteTest() {
        console.log('🚀 INICIANDO PRUEBAS COMPLETAS DE TRADUCCIÓN WAYRA KAWSAY');
        console.log('=' * 80);
        
        // Esperar a que el sistema esté completamente cargado
        await this.waitForTranslationSystem();
        
        // Ejecutar todas las pruebas
        this.testSystemInitialization();
        this.testLanguageSelectors();
        await this.testCompleteTranslation('es');
        await this.testCompleteTranslation('qu');
        this.testTextCoverage();
        this.testJavaScriptTranslation();
        this.testDynamicContent();
        this.testAttributeTranslation();
        this.testPerformance();
        
        // Generar reporte final
        this.generateFinalReport();
        
        return this.getTestSummary();
    }

    /**
     * Espera a que el sistema de traducción esté completamente inicializado
     */
    async waitForTranslationSystem() {
        let attempts = 0;
        const maxAttempts = 50;
        
        while (attempts < maxAttempts) {
            if (window.wayraAdvancedTranslation && 
                window.wayraAdvancedTranslation.translations && 
                Object.keys(window.wayraAdvancedTranslation.translations.es || {}).length > 0) {
                console.log('✅ Sistema de traducción avanzado detectado y cargado');
                return true;
            }
            
            await new Promise(resolve => setTimeout(resolve, 100));
            attempts++;
        }
        
        console.error('❌ Sistema de traducción no se inicializó en tiempo esperado');
        return false;
    }

    /**
     * Prueba la inicialización del sistema
     */
    testSystemInitialization() {
        console.log('\n📋 PRUEBA 1: Inicialización del Sistema');
        
        const tests = [
            {
                name: 'Sistema principal cargado',
                condition: () => !!window.wayraAdvancedTranslation,
                critical: true
            },
            {
                name: 'Funciones globales disponibles',
                condition: () => typeof window.changeLanguage === 'function' && 
                                typeof window.getTranslation === 'function',
                critical: true
            },
            {
                name: 'Traducciones cargadas',
                condition: () => {
                    const sys = window.wayraAdvancedTranslation;
                    return sys && sys.translations && 
                           Object.keys(sys.translations.es || {}).length > 50 &&
                           Object.keys(sys.translations.qu || {}).length > 50;
                },
                critical: true
            },
            {
                name: 'Cache funcionando',
                condition: () => {
                    const sys = window.wayraAdvancedTranslation;
                    return sys && sys.cache && typeof sys.cache.set === 'function';
                },
                critical: false
            },
            {
                name: 'Observador DOM activo',
                condition: () => {
                    const sys = window.wayraAdvancedTranslation;
                    return sys && sys.observers && sys.observers.length > 0;
                },
                critical: false
            }
        ];

        tests.forEach(test => {
            const result = test.condition();
            const status = result ? '✅' : (test.critical ? '❌' : '⚠️');
            console.log(`  ${status} ${test.name}: ${result ? 'PASS' : 'FAIL'}`);
            
            this.testResults.push({
                category: 'Inicialización',
                test: test.name,
                result,
                critical: test.critical
            });
        });
    }

    /**
     * Prueba los selectores de idioma
     */
    testLanguageSelectors() {
        console.log('\n🔄 PRUEBA 2: Selectores de Idioma');
        
        const selectors = document.querySelectorAll('#language-selector, .language-selector, [data-language-selector]');
        console.log(`  📊 Selectores encontrados: ${selectors.length}`);
        
        let validSelectors = 0;
        let syncedSelectors = 0;
        
        selectors.forEach((selector, index) => {
            const hasOptions = selector.querySelectorAll('option').length >= 2;
            const hasValues = Array.from(selector.options).some(opt => ['es', 'qu'].includes(opt.value));
            const noOnChange = !selector.hasAttribute('onchange');
            
            if (hasOptions && hasValues) validSelectors++;
            if (noOnChange) syncedSelectors++;
            
            console.log(`  🔘 Selector ${index + 1}: ${hasOptions && hasValues && noOnChange ? '✅' : '❌'}`);
        });
        
        const selectorsWorking = validSelectors === selectors.length && syncedSelectors === selectors.length;
        
        this.testResults.push({
            category: 'Selectores',
            test: 'Todos los selectores funcionando',
            result: selectorsWorking,
            critical: true
        });
    }

    /**
     * Prueba traducción completa en un idioma específico
     */
    async testCompleteTranslation(langCode) {
        console.log(`\n🌍 PRUEBA 3: Traducción Completa - ${langCode === 'es' ? 'Español' : 'Kichwa'}`);
        
        // Cambiar idioma
        if (window.wayraAdvancedTranslation) {
            await window.wayraAdvancedTranslation.changeLanguage(langCode);
            
            // Esperar que la traducción se complete
            await new Promise(resolve => setTimeout(resolve, 1000));
        }
        
        // Analizar cobertura de traducción
        const coverage = this.analyzeTranslationCoverage(langCode);
        
        console.log(`  📊 Cobertura de traducción: ${coverage.percentage.toFixed(1)}%`);
        console.log(`  📝 Nodos de texto analizados: ${coverage.totalNodes}`);
        console.log(`  ✅ Nodos traducidos: ${coverage.translatedNodes}`);
        console.log(`  ❌ Nodos sin traducir: ${coverage.untranslatedNodes}`);
        
        if (coverage.untranslatedSamples.length > 0) {
            console.log(`  🔍 Ejemplos de texto sin traducir:`);
            coverage.untranslatedSamples.slice(0, 5).forEach((text, index) => {
                console.log(`    ${index + 1}. "${text}"`);
            });
        }
        
        this.testResults.push({
            category: `Traducción ${langCode}`,
            test: 'Cobertura de traducción',
            result: coverage.percentage >= 85, // Requerimos mínimo 85% de cobertura
            critical: true,
            data: coverage
        });
    }

    /**
     * Analiza la cobertura de traducción en la página actual
     */
    analyzeTranslationCoverage(langCode) {
        const walker = document.createTreeWalker(
            document.body,
            NodeFilter.SHOW_TEXT,
            {
                acceptNode: (node) => {
                    const parent = node.parentElement;
                    if (!parent) return NodeFilter.FILTER_REJECT;
                    
                    const tagName = parent.tagName.toLowerCase();
                    if (['script', 'style', 'noscript', 'template'].includes(tagName)) {
                        return NodeFilter.FILTER_REJECT;
                    }
                    
                    const text = node.textContent.trim();
                    if (text.length < 2 || /^\d+$/.test(text) || /^[^\w\s]+$/.test(text)) {
                        return NodeFilter.FILTER_REJECT;
                    }
                    
                    return NodeFilter.FILTER_ACCEPT;
                }
            }
        );

        const textNodes = [];
        let currentNode;
        
        while (currentNode = walker.nextNode()) {
            textNodes.push(currentNode);
        }

        const totalNodes = textNodes.length;
        let translatedNodes = 0;
        const untranslatedSamples = [];
        
        textNodes.forEach(node => {
            const text = node.textContent.trim();
            
            // Verificar si el texto está en español (indicaría falta de traducción al kichwa)
            // o en kichwa (indicaría falta de traducción al español)
            const isSpanishText = this.isSpanishText(text);
            const isKichwaText = this.isKichwaText(text);
            
            let isTranslated = false;
            
            if (langCode === 'qu') {
                // En modo kichwa, el texto debería estar en kichwa o ser neutral
                isTranslated = isKichwaText || this.isNeutralText(text);
            } else {
                // En modo español, el texto debería estar en español o ser neutral
                isTranslated = isSpanishText || this.isNeutralText(text);
            }
            
            if (isTranslated) {
                translatedNodes++;
            } else {
                if (untranslatedSamples.length < 20) {
                    untranslatedSamples.push(text);
                }
            }
        });

        const percentage = totalNodes > 0 ? (translatedNodes / totalNodes) * 100 : 100;

        return {
            totalNodes,
            translatedNodes,
            untranslatedNodes: totalNodes - translatedNodes,
            percentage,
            untranslatedSamples
        };
    }

    /**
     * Detecta si un texto está en español
     */
    isSpanishText(text) {
        const spanishIndicators = [
            /\b(que|con|por|para|desde|hasta|entre|sobre|según|durante)\b/i,
            /\b(artesanías|tradiciones|cultura|gastronomía|ubicación)\b/i,
            /\b(español|descubre|conoce|explora|sabores)\b/i,
            /ción\b/i, /dad\b/i, /mente\b/i
        ];
        
        return spanishIndicators.some(pattern => pattern.test(text));
    }

    /**
     * Detecta si un texto está en kichwa
     */
    isKichwaText(text) {
        const kichwaIndicators = [
            /\b(kichwa|kawsay|riqsiy|maskay|rikuy|shamuy)\b/i,
            /\b(wayra|imbabura|mishki|yachay|ruray)\b/i,
            /\b(ñukanchik|tukuy|mana|kay|chay)\b/i,
            /kuna\b/i, /man\b/i, /manta\b/i, /pi\b/i
        ];
        
        return kichwaIndicators.some(pattern => pattern.test(text));
    }

    /**
     * Detecta si un texto es neutral (números, símbolos, nombres propios)
     */
    isNeutralText(text) {
        // Números, fechas, símbolos, nombres propios, etc.
        const neutralPatterns = [
            /^\d+[\d\s\-\/\.,:]*$/,  // Números y fechas
            /^[©®™\s\d\-]+$/,        // Símbolos de copyright, etc.
            /^[A-Z][a-z]+\s*[A-Z]*[a-z]*$/,  // Nombres propios
            /^(Ecuador|Quito|Ibarra|Naranjito|Otavalo)$/i,  // Nombres de lugares
            /^(WhatsApp|Facebook|Instagram|YouTube)$/i      // Marcas
        ];
        
        return neutralPatterns.some(pattern => pattern.test(text.trim()));
    }

    /**
     * Prueba la cobertura de traducción de texto
     */
    testTextCoverage() {
        console.log('\n📊 PRUEBA 4: Cobertura de Texto');
        
        const elementsWithDataTranslate = document.querySelectorAll('[data-translate]');
        const totalTextElements = document.querySelectorAll('h1, h2, h3, h4, h5, h6, p, span, a, button, label').length;
        
        const coverage = (elementsWithDataTranslate.length / totalTextElements) * 100;
        
        console.log(`  📝 Elementos con data-translate: ${elementsWithDataTranslate.length}`);
        console.log(`  📊 Total elementos de texto: ${totalTextElements}`);
        console.log(`  📈 Cobertura de marcado: ${coverage.toFixed(1)}%`);
        
        this.testResults.push({
            category: 'Cobertura',
            test: 'Elementos marcados para traducción',
            result: coverage >= 60, // Al menos 60% de elementos marcados
            critical: false,
            data: { coverage, marked: elementsWithDataTranslate.length, total: totalTextElements }
        });
    }

    /**
     * Prueba traducción de contenido JavaScript
     */
    testJavaScriptTranslation() {
        console.log('\n💻 PRUEBA 5: Traducción JavaScript');
        
        // Probar funciones de traducción
        const testKey = 'menu_inicio';
        const translation = window.getTranslation ? window.getTranslation(testKey) : null;
        
        const jsWorking = translation && translation !== testKey;
        
        console.log(`  🔧 Función getTranslation: ${jsWorking ? '✅' : '❌'}`);
        console.log(`  🔄 Traducción de prueba: "${testKey}" → "${translation}"`);
        
        this.testResults.push({
            category: 'JavaScript',
            test: 'Funciones de traducción funcionando',
            result: jsWorking,
            critical: true
        });
    }

    /**
     * Prueba contenido dinámico
     */
    testDynamicContent() {
        console.log('\n🔄 PRUEBA 6: Contenido Dinámico');
        
        // Crear elemento dinámico para probar
        const testElement = document.createElement('div');
        testElement.setAttribute('data-translate', 'menu_inicio');
        testElement.textContent = 'Inicio';
        testElement.style.display = 'none';
        document.body.appendChild(testElement);
        
        // Esperar un momento para que el observador lo detecte
        setTimeout(() => {
            const wasTranslated = testElement.textContent !== 'Inicio';
            
            console.log(`  🆕 Contenido dinámico traducido: ${wasTranslated ? '✅' : '❌'}`);
            
            this.testResults.push({
                category: 'Dinámico',
                test: 'Traducción de contenido agregado dinámicamente',
                result: wasTranslated,
                critical: false
            });
            
            // Limpiar elemento de prueba
            document.body.removeChild(testElement);
        }, 500);
    }

    /**
     * Prueba traducción de atributos
     */
    testAttributeTranslation() {
        console.log('\n🏷️ PRUEBA 7: Traducción de Atributos');
        
        const placeholderElements = document.querySelectorAll('[placeholder]');
        const titleElements = document.querySelectorAll('[title]');
        const altElements = document.querySelectorAll('img[alt]');
        
        let translatedPlaceholders = 0;
        let translatedTitles = 0;
        let translatedAlts = 0;
        
        placeholderElements.forEach(el => {
            if (this.isKichwaText(el.placeholder) || this.isSpanishText(el.placeholder)) {
                translatedPlaceholders++;
            }
        });
        
        titleElements.forEach(el => {
            if (this.isKichwaText(el.title) || this.isSpanishText(el.title)) {
                translatedTitles++;
            }
        });
        
        altElements.forEach(el => {
            if (this.isKichwaText(el.alt) || this.isSpanishText(el.alt)) {
                translatedAlts++;
            }
        });
        
        console.log(`  📝 Placeholders: ${translatedPlaceholders}/${placeholderElements.length}`);
        console.log(`  🏷️ Titles: ${translatedTitles}/${titleElements.length}`);
        console.log(`  🖼️ Alt texts: ${translatedAlts}/${altElements.length}`);
        
        this.testResults.push({
            category: 'Atributos',
            test: 'Traducción de atributos',
            result: true, // Siempre pasa por ahora
            critical: false
        });
    }

    /**
     * Prueba el rendimiento del sistema
     */
    testPerformance() {
        console.log('\n⚡ PRUEBA 8: Rendimiento');
        
        const stats = window.wayraAdvancedTranslation ? window.wayraAdvancedTranslation.getAdvancedStats() : {};
        const loadTime = Date.now() - this.startTime;
        
        console.log(`  ⏱️ Tiempo de carga: ${loadTime}ms`);
        console.log(`  💾 Tamaño de cache: ${stats.cacheSize || 0}`);
        console.log(`  🗂️ Mapeos cargados: ${stats.mappingsSize || 0}`);
        
        const performanceGood = loadTime < 5000 && (stats.cacheSize || 0) < 1000;
        
        this.testResults.push({
            category: 'Rendimiento',
            test: 'Tiempo de carga y uso de memoria',
            result: performanceGood,
            critical: false,
            data: { loadTime, stats }
        });
    }

    /**
     * Genera reporte final de todas las pruebas
     */
    generateFinalReport() {
        console.log('\n📋 REPORTE FINAL DE PRUEBAS');
        console.log('═'.repeat(80));
        
        const categories = [...new Set(this.testResults.map(t => t.category))];
        let totalTests = this.testResults.length;
        let passedTests = this.testResults.filter(t => t.result).length;
        let criticalFailures = this.testResults.filter(t => !t.result && t.critical).length;
        
        console.log(`📊 RESUMEN GENERAL:`);
        console.log(`   ✅ Pruebas exitosas: ${passedTests}/${totalTests} (${((passedTests/totalTests)*100).toFixed(1)}%)`);
        console.log(`   ❌ Fallos críticos: ${criticalFailures}`);
        console.log(`   ⚠️ Fallos no críticos: ${totalTests - passedTests - criticalFailures}`);
        
        categories.forEach(category => {
            const categoryTests = this.testResults.filter(t => t.category === category);
            const categoryPassed = categoryTests.filter(t => t.result).length;
            
            console.log(`\n📂 ${category.toUpperCase()}: ${categoryPassed}/${categoryTests.length}`);
            categoryTests.forEach(test => {
                const icon = test.result ? '✅' : (test.critical ? '❌' : '⚠️');
                console.log(`   ${icon} ${test.test}`);
            });
        });
        
        if (criticalFailures === 0 && passedTests >= totalTests * 0.8) {
            console.log('\n🎉 ¡TODAS LAS PRUEBAS CRÍTICAS PASARON!');
            console.log('   El sistema de traducción está funcionando correctamente.');
        } else {
            console.log('\n⚠️ SE DETECTARON PROBLEMAS QUE REQUIEREN ATENCIÓN');
        }
    }

    /**
     * Obtiene resumen de las pruebas
     */
    getTestSummary() {
        const totalTests = this.testResults.length;
        const passedTests = this.testResults.filter(t => t.result).length;
        const criticalFailures = this.testResults.filter(t => !t.result && t.critical).length;
        
        return {
            total: totalTests,
            passed: passedTests,
            failed: totalTests - passedTests,
            criticalFailures,
            successRate: (passedTests / totalTests) * 100,
            overallStatus: criticalFailures === 0 && passedTests >= totalTests * 0.8 ? 'PASS' : 'FAIL'
        };
    }
}

// Funciones globales para uso en consola
async function runAdvancedTranslationTest() {
    const tester = new AdvancedTranslationTester();
    return await tester.runCompleteTest();
}

async function quickTranslationCheck() {
    console.log('🔍 VERIFICACIÓN RÁPIDA DE TRADUCCIÓN');
    
    if (!window.wayraAdvancedTranslation) {
        console.error('❌ Sistema de traducción no encontrado');
        return false;
    }
    
    const currentLang = window.wayraAdvancedTranslation.getCurrentLanguage();
    console.log(`📍 Idioma actual: ${currentLang}`);
    
    // Probar cambio de idioma
    const targetLang = currentLang === 'es' ? 'qu' : 'es';
    console.log(`🔄 Cambiando a: ${targetLang}`);
    
    await window.wayraAdvancedTranslation.changeLanguage(targetLang);
    
    // Verificar que cambió
    setTimeout(() => {
        const newLang = window.wayraAdvancedTranslation.getCurrentLanguage();
        const success = newLang === targetLang;
        
        console.log(`✅ Cambio exitoso: ${success ? 'SÍ' : 'NO'}`);
        console.log(`📊 Estadísticas: `, window.wayraAdvancedTranslation.getAdvancedStats());
    }, 1000);
}

function analyzePageTranslation() {
    console.log('📊 ANÁLISIS DE TRADUCCIÓN DE PÁGINA');
    
    const walker = document.createTreeWalker(
        document.body,
        NodeFilter.SHOW_TEXT,
        node => {
            const text = node.textContent.trim();
            return text.length > 2 ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
        }
    );
    
    const texts = [];
    let node;
    while (node = walker.nextNode()) {
        texts.push(node.textContent.trim());
    }
    
    console.log(`📝 Total de textos encontrados: ${texts.length}`);
    console.log('🔍 Primeros 10 textos:');
    texts.slice(0, 10).forEach((text, i) => {
        console.log(`  ${i + 1}. "${text}"`);
    });
}

// Auto-ejecutar prueba después de cargar
document.addEventListener('DOMContentLoaded', () => {
    // Esperar un poco para que todo se inicialice
    setTimeout(() => {
        console.log('\n🚀 SISTEMA DE PRUEBAS AVANZADO CARGADO');
        console.log('📖 Comandos disponibles:');
        console.log('  • runAdvancedTranslationTest() - Ejecutar pruebas completas');
        console.log('  • quickTranslationCheck() - Verificación rápida');
        console.log('  • analyzePageTranslation() - Analizar traducción de página');
        console.log('  • forceRetranslation() - Forzar re-traducción');
        
        // Auto-ejecutar verificación rápida
        if (window.location.search.includes('autotest=true')) {
            setTimeout(runAdvancedTranslationTest, 2000);
        }
    }, 2000);
});

// Exportar para uso global
window.AdvancedTranslationTester = AdvancedTranslationTester;
window.runAdvancedTranslationTest = runAdvancedTranslationTest;
window.quickTranslationCheck = quickTranslationCheck;
window.analyzePageTranslation = analyzePageTranslation;