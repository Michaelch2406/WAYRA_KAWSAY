<?php
/**
 * Script para extraer vocabulario de los archivos txt en info_kichwa/
 * y procesarlo para el diccionario interactivo
 */

include_once '../config/Conexion.php';

class KichwaVocabularyExtractor {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Extrae vocabulario del archivo de frases comunes
     */
    public function extractFromFrasesComunes($filePath) {
        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        
        $vocabulary = [];
        $currentKichwa = '';
        $currentSpanish = '';
        
        for ($i = 0; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            
            if (empty($line) || $line === '→' || is_numeric($line)) {
                continue;
            }
            
            // Detectar si es una línea con número
            if (preg_match('/^\d+→(.+)/', $line, $matches)) {
                $phrase = trim($matches[1]);
                
                // Determinar si es kichwa o español basado en el patrón
                if ($this->isKichwaPhrase($phrase)) {
                    $currentKichwa = $phrase;
                    // Buscar la traducción en las siguientes líneas
                    if (isset($lines[$i + 2])) {
                        $nextLine = trim($lines[$i + 2]);
                        if (!preg_match('/^\d+→/', $nextLine) && !empty($nextLine)) {
                            $currentSpanish = $nextLine;
                            $vocabulary[] = [
                                'kichwa' => $currentKichwa,
                                'spanish' => $currentSpanish,
                                'type' => 'phrase'
                            ];
                        }
                    }
                }
            }
        }
        
        return $vocabulary;
    }
    
    /**
     * Extrae vocabulario del diccionario principal
     */
    public function extractFromDiccionario($filePath, $limit = 1000) {
        $handle = fopen($filePath, 'r');
        if (!$handle) return [];
        
        $vocabulary = [];
        $count = 0;
        
        while (($line = fgets($handle)) !== false && $count < $limit) {
            $line = trim($line);
            
            // Buscar entradas que sigan el patrón: palabra [pronunciación] definición
            if (preg_match('/^([a-záéíóúñü]+)\s*\[([^\]]+)\]\s*(.*?)\.(.*)/', $line, $matches)) {
                $kichwaWord = trim($matches[1]);
                $pronunciation = trim($matches[2]);
                $definition = trim($matches[3]);
                $example = trim($matches[4]);
                
                // Extraer la traducción principal
                if (preg_match('/^(.*?)\.\s*(.*)/', $definition, $defMatches)) {
                    $mainTranslation = trim($defMatches[1]);
                    $description = trim($defMatches[2]);
                } else {
                    $mainTranslation = $definition;
                    $description = '';
                }
                
                $vocabulary[] = [
                    'kichwa' => $kichwaWord,
                    'spanish' => $mainTranslation,
                    'pronunciation' => $pronunciation,
                    'description' => $description,
                    'example' => $example,
                    'type' => 'word'
                ];
                
                $count++;
            }
        }
        
        fclose($handle);
        return $vocabulary;
    }
    
    /**
     * Extrae vocabulario del archivo LEXTN
     */
    public function extractFromLEXTN($filePath, $limit = 500) {
        $handle = fopen($filePath, 'r');
        if (!$handle) return [];
        
        $vocabulary = [];
        $count = 0;
        $inDictionary = false;
        
        while (($line = fgets($handle)) !== false && $count < $limit) {
            $line = trim($line);
            
            // Detectar cuando comenzamos el diccionario
            if (strpos($line, 'Kichwa - Castellano') !== false) {
                $inDictionary = true;
                continue;
            }
            
            if (!$inDictionary) continue;
            
            // Buscar patrones de palabras kichwa
            if (preg_match('/^([a-záéíóúñü]+)\s+(.+)$/', $line, $matches)) {
                $kichwaWord = trim($matches[1]);
                $translation = trim($matches[2]);
                
                // Limpiar la traducción
                $translation = preg_replace('/\[.*?\]/', '', $translation);
                $translation = preg_replace('/\s+/', ' ', $translation);
                
                if (strlen($kichwaWord) > 2 && strlen($translation) > 2) {
                    $vocabulary[] = [
                        'kichwa' => $kichwaWord,
                        'spanish' => $translation,
                        'type' => 'lexicon'
                    ];
                    $count++;
                }
            }
        }
        
        fclose($handle);
        return $vocabulary;
    }
    
    /**
     * Determina si una frase es en kichwa
     */
    private function isKichwaPhrase($phrase) {
        // Patrones comunes en kichwa
        $kichwaPatterns = [
            '/\b(ca|mi|ta|ma|pak|manta|pi|wan)\b/',
            '/[kqwy]/',
            '/chu\b/',
            '/huai\b/',
            '/angui\b/',
            '/cani\b/',
            '/shca\b/'
        ];
        
        foreach ($kichwaPatterns as $pattern) {
            if (preg_match($pattern, strtolower($phrase))) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Inserta vocabulario en la base de datos
     */
    public function insertVocabulary($vocabularyArray) {
        $insertQuery = "INSERT IGNORE INTO palabras_kichwa (palabra_kichwa, traduccion_espanol, pronunciacion, descripcion, ejemplo, tipo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($insertQuery);
        
        $inserted = 0;
        foreach ($vocabularyArray as $vocab) {
            $pronunciation = isset($vocab['pronunciation']) ? $vocab['pronunciation'] : null;
            $description = isset($vocab['description']) ? $vocab['description'] : null;
            $example = isset($vocab['example']) ? $vocab['example'] : null;
            
            if ($stmt->execute([
                $vocab['kichwa'],
                $vocab['spanish'],
                $pronunciation,
                $description,
                $example,
                $vocab['type']
            ])) {
                $inserted++;
            }
        }
        
        return $inserted;
    }
    
    /**
     * Proceso completo de extracción
     */
    public function extractAll() {
        $baseDir = '../info_kichwa/';
        $totalInserted = 0;
        
        echo "Iniciando extracción de vocabulario...\n";
        
        // Extraer de frases comunes
        if (file_exists($baseDir . 'FRASES COMUNES.txt')) {
            echo "Procesando frases comunes...\n";
            $phrases = $this->extractFromFrasesComunes($baseDir . 'FRASES COMUNES.txt');
            $inserted = $this->insertVocabulary($phrases);
            echo "Insertadas $inserted frases comunes\n";
            $totalInserted += $inserted;
        }
        
        // Extraer del diccionario principal
        if (file_exists($baseDir . 'RK_diccionario_kichwa_castellano.txt')) {
            echo "Procesando diccionario principal...\n";
            $words = $this->extractFromDiccionario($baseDir . 'RK_diccionario_kichwa_castellano.txt', 2000);
            $inserted = $this->insertVocabulary($words);
            echo "Insertadas $inserted palabras del diccionario\n";
            $totalInserted += $inserted;
        }
        
        // Extraer del LEXTN
        if (file_exists($baseDir . 'LEXTN-Dea-142622-PUBCOM.txt')) {
            echo "Procesando LEXTN...\n";
            $lexicon = $this->extractFromLEXTN($baseDir . 'LEXTN-Dea-142622-PUBCOM.txt', 1000);
            $inserted = $this->insertVocabulary($lexicon);
            echo "Insertadas $inserted palabras del lexicón\n";
            $totalInserted += $inserted;
        }
        
        echo "Total de palabras procesadas: $totalInserted\n";
        return $totalInserted;
    }
}

// Ejecutar si se llama directamente
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $database = new Conexion();
    $db = $database->getConnection();
    
    $extractor = new KichwaVocabularyExtractor($db);
    $result = $extractor->extractAll();
    
    echo "Proceso completado. Total de palabras procesadas: $result\n";
}
?>