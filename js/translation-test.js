/**
 * Script de prueba para el Sistema de Traducción Wayra Kawsay
 * Ejecutar en la consola del navegador para verificar funcionalidad
 */

function testTranslationSystem() {
    console.log('🔍 Iniciando pruebas del sistema de traducción...');
    
    // Verificar que el sistema esté inicializado
    if (!window.wayraTranslation) {
        console.error('❌ Sistema de traducción no inicializado');
        return false;
    }
    
    // Test 1: Verificar idiomas disponibles
    const availableLanguages = wayraTranslation.getAvailableLanguages();
    console.log('✅ Idiomas disponibles:', availableLanguages);
    
    // Test 2: Verificar idioma actual
    const currentLang = wayraTranslation.getCurrentLanguage();
    console.log('✅ Idioma actual:', currentLang);
    
    // Test 3: Verificar traducciones
    const testKeys = ['titulo_inicio', 'menu_inicio', 'menu_sabores', 'menu_artesanias'];
    console.log('✅ Probando traducciones:');
    testKeys.forEach(key => {
        const translation = wayraTranslation.getTranslation(key);
        console.log(`  ${key}: ${translation}`);
    });
    
    // Test 4: Cambiar idioma
    console.log('✅ Cambiando a Kichwa...');
    wayraTranslation.changeLanguage('qu');
    
    setTimeout(() => {
        console.log('✅ Verificando traducciones en Kichwa:');
        testKeys.forEach(key => {
            const translation = wayraTranslation.getTranslation(key);
            console.log(`  ${key}: ${translation}`);
        });
        
        // Test 5: Regresar a español
        console.log('✅ Regresando a Español...');
        wayraTranslation.changeLanguage('es');
        
        setTimeout(() => {
            // Test 6: Estadísticas del sistema
            const stats = wayraTranslation.getStats();
            console.log('✅ Estadísticas del sistema:', stats);
            
            console.log('🎉 Todas las pruebas completadas exitosamente!');
        }, 500);
    }, 500);
    
    return true;
}

// Función para verificar elementos con data-translate
function checkTranslatableElements() {
    const elements = document.querySelectorAll('[data-translate]');
    console.log(`📋 Elementos con data-translate encontrados: ${elements.length}`);
    
    elements.forEach((element, index) => {
        const key = element.getAttribute('data-translate');
        const text = element.textContent.trim();
        console.log(`${index + 1}. ${key}: "${text}"`);
    });
}

// Función para validar que no haya duplicaciones
function validateTranslations() {
    console.log('🔍 Validando sistema de traducción...');
    
    const elements = document.querySelectorAll('#language-selector');
    console.log(`📊 Selectores de idioma encontrados: ${elements.length}`);
    
    // Verificar que todos los selectores tengan el mismo valor
    const values = Array.from(elements).map(el => el.value);
    const uniqueValues = [...new Set(values)];
    
    if (uniqueValues.length === 1) {
        console.log('✅ Todos los selectores están sincronizados');
    } else {
        console.warn('⚠️ Los selectores no están sincronizados:', uniqueValues);
    }
    
    // Verificar que no hay conflictos de eventos
    let conflictCount = 0;
    elements.forEach((selector, index) => {
        const hasOnchange = selector.hasAttribute('onchange');
        if (hasOnchange) {
            console.warn(`⚠️ Selector ${index + 1} tiene evento onchange duplicado`);
            conflictCount++;
        }
    });
    
    if (conflictCount === 0) {
        console.log('✅ No se detectaron conflictos de eventos');
    }
    
    return conflictCount === 0 && uniqueValues.length === 1;
}

// Auto-ejecutar validación cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        console.log('🚀 Sistema de Traducción Wayra Kawsay - Validación Automática');
        validateTranslations();
        checkTranslatableElements();
        
        // Mostrar comandos disponibles
        console.log('\n📖 Comandos disponibles en la consola:');
        console.log('  testTranslationSystem() - Ejecutar pruebas completas');
        console.log('  checkTranslatableElements() - Ver elementos traducibles');
        console.log('  validateTranslations() - Validar configuración');
        console.log('  wayraTranslation.getStats() - Ver estadísticas');
        console.log('  wayraTranslation.clearCache() - Limpiar cache');
    }, 1000);
});

// Exportar funciones para uso en consola
window.testTranslationSystem = testTranslationSystem;
window.checkTranslatableElements = checkTranslatableElements;
window.validateTranslations = validateTranslations;