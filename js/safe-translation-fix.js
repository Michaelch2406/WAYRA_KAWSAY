/**
 * FIX CRÍTICO: Sistema de traducción seguro que SOLO traduce elementos data-translate
 * Evita sobreescribir contenido ya gestionado por PHP
 */

// Sobrescribir las funciones problemáticas del ultra-translation-system
if (window.ultraWayraTranslation) {
    console.log('🔧 Aplicando fix crítico para preservar contenido PHP...');
    
    // Sobrescribir función crítica para SOLO traducir data-translate
    window.ultraWayraTranslation.performUltraTranslation = async function() {
        console.log('⚡ Traducción SEGURA - Solo elementos data-translate');
        
        // SOLO traducir elementos con data-translate
        const elementsToTranslate = document.querySelectorAll('[data-translate]');
        
        elementsToTranslate.forEach(element => {
            const translateKey = element.getAttribute('data-translate');
            if (translateKey && this.translations[this.currentLanguage]) {
                const translation = this.translations[this.currentLanguage][translateKey];
                if (translation) {
                    // Preservar iconos si los hay
                    const icons = element.querySelectorAll('i.fas, i.fab, i.far, i.fal');
                    if (icons.length > 0) {
                        // Preservar primer icono
                        const iconHTML = icons[0].outerHTML;
                        element.innerHTML = iconHTML + ' ' + translation;
                    } else {
                        element.textContent = translation;
                    }
                }
            }
        });
        
        console.log(`✅ ${elementsToTranslate.length} elementos data-translate procesados`);
    };
    
    // Sobrescribir función de traducción de elementos individuales
    window.ultraWayraTranslation.ultraTranslateElement = function(element) {
        if (!element || this.processedElements.has(element)) return;
        
        // SOLO procesar si tiene data-translate
        const translateKey = element.getAttribute('data-translate');
        if (!translateKey) return;
        
        const translation = this.translations[this.currentLanguage][translateKey];
        if (translation) {
            // Preservar iconos
            const icons = element.querySelectorAll('i.fas, i.fab, i.far, i.fal');
            if (icons.length > 0) {
                const iconHTML = icons[0].outerHTML;
                element.innerHTML = iconHTML + ' ' + translation;
            } else {
                element.textContent = translation;
            }
        }
        
        this.processedElements.add(element);
    };
    
    // Sobrescribir traducción de elementos restantes para que NO haga nada
    window.ultraWayraTranslation.translateRemainingElements = function() {
        // NO HACER NADA - Solo traducir data-translate
        console.log('🛡️ Traducción de elementos restantes DESACTIVADA para preservar contenido PHP');
    };
    
    // Sobrescribir traducción de elementos visibles
    window.ultraWayraTranslation.translateVisibleElements = async function() {
        const visibleElements = document.querySelectorAll('[data-translate]');
        visibleElements.forEach(element => {
            if (this.isElementVisible && this.isElementVisible(element)) {
                this.ultraTranslateElement(element);
            } else {
                this.ultraTranslateElement(element); // Traducir de todas formas si es data-translate
            }
        });
    };
    
    // Sobrescribir traducción de elementos críticos
    window.ultraWayraTranslation.translateCriticalElements = async function() {
        const criticalElements = document.querySelectorAll('[data-translate]');
        criticalElements.forEach(element => {
            this.ultraTranslateElement(element);
        });
        console.log(`⚡ ${criticalElements.length} elementos críticos data-translate procesados`);
    };
    
    console.log('✅ Fix aplicado: Sistema ahora SOLO traduce elementos data-translate');
    console.log('🛡️ Contenido PHP preservado correctamente');
    
    // Re-ejecutar traducción segura después del fix
    setTimeout(() => {
        if (window.ultraWayraTranslation.performUltraTranslation) {
            window.ultraWayraTranslation.performUltraTranslation();
        }
    }, 100);
}