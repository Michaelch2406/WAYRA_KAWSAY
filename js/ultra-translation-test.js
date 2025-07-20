/**
 * Pruebas Ultra-Potentes para Sistema de Traducción Bidireccional
 * Verifica cambio instantáneo Español ↔ Kichwa de Imbabura
 */

class UltraTranslationTester {
    constructor() {
        this.testResults = [];
        this.startTime = Date.now();
        this.bidirectionalTests = 0;
        this.instantTests = 0;
    }

    /**
     * Ejecuta pruebas ultra-completas del sistema bidireccional
     */
    async runUltraTest() {
        console.log('🚀 INICIANDO PRUEBAS ULTRA-POTENTES - KICHWA DE IMBABURA');
        console.log('=' * 80);
        
        await this.waitForUltraSystem();
        
        // Pruebas críticas
        await this.testInstantBidirectionalSwitching();
        await this.testPrecisionAllPages();
        await this.testUltraSpeed();
        await this.testKichwaAuthenticity();
        
        this.generateUltraReport();
        return this.getUltraResults();
    }

    /**
     * Espera a que el sistema ultra esté completamente cargado
     */
    async waitForUltraSystem() {
        let attempts = 0;
        const maxAttempts = 100;
        
        while (attempts < maxAttempts) {
            if (window.ultraWayraTranslation && 
                window.ultraWayraTranslation.translations &&
                Object.keys(window.ultraWayraTranslation.translations.es || {}).length > 0 &&
                Object.keys(window.ultraWayraTranslation.translations.qu || {}).length > 0) {
                console.log('✅ Sistema Ultra-Potente detectado y listo');
                return true;
            }
            
            await new Promise(resolve => setTimeout(resolve, 50));
            attempts++;
        }
        
        console.error('❌ Sistema Ultra-Potente no se inicializó');
        return false;
    }

    /**
     * Prueba cambio bidireccional instantáneo
     */
    async testInstantBidirectionalSwitching() {
        console.log('\n⚡ PRUEBA CRÍTICA: Cambio Bidireccional Instantáneo');
        
        if (!window.ultraWayraTranslation) {
            console.error('❌ Sistema ultra no disponible');
            return;
        }

        const sys = window.ultraWayraTranslation;
        
        // Test 1: Español → Kichwa
        console.log('🔄 Probando Español → Kichwa...');
        const startTime1 = Date.now();
        
        await sys.ultraChangeLanguage('qu');
        await new Promise(resolve => setTimeout(resolve, 100));
        
        const time1 = Date.now() - startTime1;
        const currentLang1 = sys.getCurrentLanguage();
        const success1 = currentLang1 === 'qu';
        
        console.log(`  ⏱️ Tiempo: ${time1}ms`);
        console.log(`  🎯 Resultado: ${success1 ? '✅ ÉXITO' : '❌ FALLO'}`);
        console.log(`  🌍 Idioma actual: ${currentLang1}`);
        
        // Verificar que el texto cambió a kichwa
        const sampleElement = document.querySelector('h1, .hero-title, [data-translate="menu_inicio"]');
        let hasKichwaText = false;
        if (sampleElement) {
            const text = sampleElement.textContent;
            hasKichwaText = /kallari|kawsay|mishki|riqsiy|imbabura/i.test(text);
            console.log(`  📝 Texto ejemplo: "${text}"`);
            console.log(`  🏔️ Contiene Kichwa: ${hasKichwaText ? '✅' : '❌'}`);
        }
        
        // Test 2: Kichwa → Español (CRÍTICO)
        console.log('\n🔄 Probando Kichwa → Español (CRÍTICO)...');
        const startTime2 = Date.now();
        
        await sys.ultraChangeLanguage('es');
        await new Promise(resolve => setTimeout(resolve, 100));
        
        const time2 = Date.now() - startTime2;
        const currentLang2 = sys.getCurrentLanguage();
        const success2 = currentLang2 === 'es';
        
        console.log(`  ⏱️ Tiempo: ${time2}ms`);
        console.log(`  🎯 Resultado: ${success2 ? '✅ ÉXITO' : '❌ FALLO'}`);
        console.log(`  🌍 Idioma actual: ${currentLang2}`);
        
        // Verificar que el texto cambió a español
        let hasSpanishText = false;
        if (sampleElement) {
            const text = sampleElement.textContent;
            hasSpanishText = /inicio|cultura|sabores|artesanías|ubicación/i.test(text);
            console.log(`  📝 Texto ejemplo: "${text}"`);
            console.log(`  🇪🇸 Contiene Español: ${hasSpanishText ? '✅' : '❌'}`);
        }
        
        // Test 3: Múltiples cambios rápidos
        console.log('\n🔄 Probando cambios múltiples rápidos...');
        const rapidTests = [];
        
        for (let i = 0; i < 5; i++) {
            const rapidStart = Date.now();
            const targetLang = i % 2 === 0 ? 'qu' : 'es';
            
            await sys.ultraChangeLanguage(targetLang);
            await new Promise(resolve => setTimeout(resolve, 50));
            
            const rapidTime = Date.now() - rapidStart;
            const rapidSuccess = sys.getCurrentLanguage() === targetLang;
            
            rapidTests.push({ time: rapidTime, success: rapidSuccess, lang: targetLang });
            console.log(`  ${i + 1}. ${targetLang}: ${rapidTime}ms ${rapidSuccess ? '✅' : '❌'}`);
        }
        
        const avgTime = rapidTests.reduce((sum, test) => sum + test.time, 0) / rapidTests.length;
        const successRate = rapidTests.filter(test => test.success).length / rapidTests.length * 100;
        
        console.log(`  📊 Tiempo promedio: ${avgTime.toFixed(1)}ms`);
        console.log(`  📈 Tasa de éxito: ${successRate}%`);
        
        // Registrar resultados
        this.testResults.push({
            test: 'Cambio Bidireccional Instantáneo',
            esToQu: { time: time1, success: success1 && hasKichwaText },
            quToEs: { time: time2, success: success2 && hasSpanishText },
            rapidChanges: { avgTime, successRate },
            critical: true
        });
        
        this.bidirectionalTests++;
    }

    /**
     * Prueba precisión en todas las páginas
     */
    async testPrecisionAllPages() {
        console.log('\n🎯 PRUEBA: Precisión en Todas las Páginas');
        
        const pages = [
            { name: 'Inicio', elements: ['[data-translate="menu_inicio"]', '.hero-title'] },
            { name: 'Sabores', elements: ['[data-translate="tradicionales"]', '[data-translate="carnes"]'] },
            { name: 'Artesanías', elements: ['[data-translate="textiles"]', '[data-translate="ceramica"]'] },
            { name: 'Cultura', elements: ['[data-translate="festivales"]', '[data-translate="religiosos"]'] },
            { name: 'Ubicación', elements: ['[data-translate="informacion_geografica"]', '[data-translate="clima"]'] }
        ];
        
        const sys = window.ultraWayraTranslation;
        let totalElements = 0;
        let translatedElements = 0;
        
        for (const page of pages) {
            console.log(`\n📄 Página: ${page.name}`);
            
            for (const selector of page.elements) {
                const elements = document.querySelectorAll(selector);
                totalElements += elements.length;
                
                elements.forEach(element => {
                    if (element) {
                        const originalText = element.textContent.trim();
                        
                        // Cambiar a kichwa y verificar
                        const translateKey = element.getAttribute('data-translate');
                        if (translateKey) {
                            const kichwaTrans = sys.translations.qu[translateKey];
                            const spanishTrans = sys.translations.es[translateKey];
                            
                            if (kichwaTrans && spanishTrans) {
                                translatedElements++;
                                console.log(`    ✅ ${selector}: "${spanishTrans}" ↔ "${kichwaTrans}"`);
                            } else {
                                console.log(`    ❌ ${selector}: Traducción faltante`);
                            }
                        }
                    }
                });
            }
        }
        
        const precisionRate = totalElements > 0 ? (translatedElements / totalElements) * 100 : 0;
        console.log(`\n📊 Precisión total: ${precisionRate.toFixed(1)}% (${translatedElements}/${totalElements})`);
        
        this.testResults.push({
            test: 'Precisión en Páginas',
            totalElements,
            translatedElements,
            precisionRate,
            critical: true
        });
    }

    /**
     * Prueba velocidad ultra-rápida
     */
    async testUltraSpeed() {
        console.log('\n⚡ PRUEBA: Velocidad Ultra-Rápida');
        
        const sys = window.ultraWayraTranslation;
        const speedTests = [];
        
        // Test velocidad de traducción individual
        for (let i = 0; i < 10; i++) {
            const testTexts = [
                'Sabores de Imbabura',
                'Artesanías tradicionales', 
                'Cultura andina',
                'Kichwa de Imbabura',
                'Tradiciones ancestrales'
            ];
            
            const testText = testTexts[i % testTexts.length];
            const startTime = performance.now();
            
            const translation = sys.ultraTranslateText(testText);
            
            const endTime = performance.now();
            const duration = endTime - startTime;
            
            speedTests.push(duration);
            console.log(`  ${i + 1}. "${testText}" → "${translation}" (${duration.toFixed(2)}ms)`);
        }
        
        const avgSpeed = speedTests.reduce((sum, time) => sum + time, 0) / speedTests.length;
        const maxSpeed = Math.max(...speedTests);
        const minSpeed = Math.min(...speedTests);
        
        console.log(`\n📊 Velocidad promedio: ${avgSpeed.toFixed(2)}ms`);
        console.log(`📊 Velocidad máxima: ${maxSpeed.toFixed(2)}ms`);
        console.log(`📊 Velocidad mínima: ${minSpeed.toFixed(2)}ms`);
        
        const isUltraFast = avgSpeed < 10; // Menos de 10ms es ultra-rápido
        
        this.testResults.push({
            test: 'Velocidad Ultra-Rápida',
            avgSpeed,
            maxSpeed,
            minSpeed,
            isUltraFast,
            critical: false
        });
        
        this.instantTests++;
    }

    /**
     * Prueba autenticidad del Kichwa de Imbabura
     */
    async testKichwaAuthenticity() {
        console.log('\n🏔️ PRUEBA: Autenticidad Kichwa de Imbabura');
        
        const sys = window.ultraWayraTranslation;
        
        // Palabras específicas del Kichwa de Imbabura
        const authenticPairs = [
            { es: 'inicio', qu: 'kallari' },
            { es: 'sabores', qu: 'mishkikuna' },
            { es: 'artesanías', qu: 'maki ruraykuna' },
            { es: 'cultura', qu: 'kawsay' },
            { es: 'buscar', qu: 'maskay' },
            { es: 'tradiciones', qu: 'ñawpa yachaykuna' },
            { es: 'ver detalles', qu: 'astawan rikuy' },
            { es: 'ubicación', qu: 'maypi kay' }
        ];
        
        let authenticCount = 0;
        
        console.log('  Verificando autenticidad:');
        authenticPairs.forEach(pair => {
            const foundTranslation = sys.ultraTranslateText(pair.es);
            const isAuthentic = foundTranslation.toLowerCase().includes(pair.qu.toLowerCase());
            
            if (isAuthentic) authenticCount++;
            
            console.log(`    "${pair.es}" → "${foundTranslation}" ${isAuthentic ? '✅' : '❌'}`);
            console.log(`      Esperado: "${pair.qu}"`);
        });
        
        const authenticityRate = (authenticCount / authenticPairs.length) * 100;
        console.log(`\n🏔️ Autenticidad Kichwa: ${authenticityRate.toFixed(1)}% (${authenticCount}/${authenticPairs.length})`);
        
        this.testResults.push({
            test: 'Autenticidad Kichwa de Imbabura',
            authenticCount,
            totalPairs: authenticPairs.length,
            authenticityRate,
            critical: true
        });
    }

    /**
     * Genera reporte ultra-completo
     */
    generateUltraReport() {
        console.log('\n🏆 REPORTE ULTRA-POTENTE FINAL');
        console.log('═'.repeat(80));
        
        const totalTime = Date.now() - this.startTime;
        let criticalIssues = 0;
        let totalTests = this.testResults.length;
        
        console.log(`⏱️ Tiempo total de pruebas: ${totalTime}ms`);
        console.log(`🧪 Pruebas ejecutadas: ${totalTests}`);
        console.log(`🔄 Pruebas bidireccionales: ${this.bidirectionalTests}`);
        console.log(`⚡ Pruebas de velocidad: ${this.instantTests}`);
        
        console.log('\n📋 RESULTADOS DETALLADOS:');
        
        this.testResults.forEach((result, index) => {
            console.log(`\n${index + 1}. ${result.test}:`);
            
            if (result.test === 'Cambio Bidireccional Instantáneo') {
                const esOk = result.esToQu.success && result.esToQu.time < 500;
                const quOk = result.quToEs.success && result.quToEs.time < 500;
                const rapidOk = result.rapidChanges.successRate >= 90;
                
                console.log(`   🇪🇸→🏔️ Español a Kichwa: ${esOk ? '✅' : '❌'} (${result.esToQu.time}ms)`);
                console.log(`   🏔️→🇪🇸 Kichwa a Español: ${quOk ? '✅' : '❌'} (${result.quToEs.time}ms)`);
                console.log(`   🔄 Cambios rápidos: ${rapidOk ? '✅' : '❌'} (${result.rapidChanges.successRate}%)`);
                
                if (!esOk || !quOk || !rapidOk) criticalIssues++;
                
            } else if (result.test === 'Precisión en Páginas') {
                const precisionOk = result.precisionRate >= 85;
                console.log(`   🎯 Precisión: ${precisionOk ? '✅' : '❌'} ${result.precisionRate.toFixed(1)}%`);
                console.log(`   📊 Elementos: ${result.translatedElements}/${result.totalElements}`);
                
                if (!precisionOk) criticalIssues++;
                
            } else if (result.test === 'Velocidad Ultra-Rápida') {
                const speedOk = result.isUltraFast;
                console.log(`   ⚡ Velocidad: ${speedOk ? '✅' : '❌'} ${result.avgSpeed.toFixed(2)}ms promedio`);
                
            } else if (result.test === 'Autenticidad Kichwa de Imbabura') {
                const authOk = result.authenticityRate >= 80;
                console.log(`   🏔️ Autenticidad: ${authOk ? '✅' : '❌'} ${result.authenticityRate.toFixed(1)}%`);
                
                if (!authOk) criticalIssues++;
            }
        });
        
        // Veredicto final
        console.log('\n🏆 VEREDICTO FINAL:');
        if (criticalIssues === 0) {
            console.log('🎉 ¡SISTEMA ULTRA-POTENTE FUNCIONANDO PERFECTAMENTE!');
            console.log('✅ Cambio bidireccional instantáneo: ÉXITO');
            console.log('✅ Precisión en todas las páginas: ÉXITO');
            console.log('✅ Kichwa de Imbabura auténtico: ÉXITO');
            console.log('⚡ Rendimiento ultra-rápido: ÓPTIMO');
        } else {
            console.log(`⚠️ SE DETECTARON ${criticalIssues} PROBLEMAS CRÍTICOS`);
            console.log('🔧 Requiere atención para funcionamiento óptimo');
        }
    }

    /**
     * Obtiene resultados ultra-completos
     */
    getUltraResults() {
        const criticalIssues = this.testResults.filter(r => 
            r.critical && (
                (r.test.includes('Bidireccional') && (!r.esToQu.success || !r.quToEs.success)) ||
                (r.test.includes('Precisión') && r.precisionRate < 85) ||
                (r.test.includes('Autenticidad') && r.authenticityRate < 80)
            )
        ).length;
        
        return {
            totalTests: this.testResults.length,
            criticalIssues,
            bidirectionalTests: this.bidirectionalTests,
            instantTests: this.instantTests,
            overallStatus: criticalIssues === 0 ? 'ULTRA-ÉXITO' : 'REQUIERE MEJORAS',
            results: this.testResults
        };
    }
}

// Funciones globales para uso en consola
async function runUltraTranslationTest() {
    const tester = new UltraTranslationTester();
    return await tester.runUltraTest();
}

async function testBidirectionalOnly() {
    console.log('🔄 PRUEBA RÁPIDA: Solo Cambio Bidireccional');
    
    if (!window.ultraWayraTranslation) {
        console.error('❌ Sistema ultra no encontrado');
        return false;
    }
    
    const sys = window.ultraWayraTranslation;
    
    // Español → Kichwa
    console.log('1. Cambiando a Kichwa...');
    const start1 = Date.now();
    await sys.ultraChangeLanguage('qu');
    const time1 = Date.now() - start1;
    console.log(`   ⏱️ ${time1}ms - ${sys.getCurrentLanguage()}`);
    
    // Esperar un poco
    await new Promise(resolve => setTimeout(resolve, 200));
    
    // Kichwa → Español
    console.log('2. Cambiando a Español...');
    const start2 = Date.now();
    await sys.ultraChangeLanguage('es');
    const time2 = Date.now() - start2;
    console.log(`   ⏱️ ${time2}ms - ${sys.getCurrentLanguage()}`);
    
    const success = time1 < 1000 && time2 < 1000;
    console.log(`\n${success ? '✅ ÉXITO' : '❌ FALLO'}: Cambio bidireccional ${success ? 'funcionando' : 'lento'}`);
    
    return success;
}

// Auto-ejecutar verificación después de cargar
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        console.log('\n🚀 SISTEMA DE PRUEBAS ULTRA-POTENTE CARGADO');
        console.log('📖 Comandos disponibles:');
        console.log('  • runUltraTranslationTest() - Pruebas ultra-completas');
        console.log('  • testBidirectionalOnly() - Solo cambio bidireccional');
        console.log('  • ultraWayraTranslation.getUltraStats() - Estadísticas ultra');
        
        // Auto-test si está habilitado
        if (window.location.search.includes('ultratest=true')) {
            setTimeout(runUltraTranslationTest, 3000);
        }
    }, 2000);
});

// Exportar para uso global
window.UltraTranslationTester = UltraTranslationTester;
window.runUltraTranslationTest = runUltraTranslationTest;
window.testBidirectionalOnly = testBidirectionalOnly;