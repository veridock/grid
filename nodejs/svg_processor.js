#!/usr/bin/env node
/**
 * SVG+Node.js Processor - Wykonuje pliki SVG z wbudowanym kodem JavaScript
 * Podobnie jak router.php, ale dla Node.js
 * 
 * Użycie:
 *   node svg_processor.js <plik.svg>
 *   node svg_processor.js todo-manager-nodejs.svg
 */

const fs = require('fs');
const path = require('path');

function processSvgFile(svgFile) {
    if (!fs.existsSync(svgFile)) {
        console.error(`Błąd: Plik '${svgFile}' nie istnieje`);
        return false;
    }

    if (!svgFile.endsWith('.svg')) {
        console.error(`Błąd: Plik musi mieć rozszerzenie .svg`);
        return false;
    }

    // Przygotuj zmienne JavaScript dostępne w SVG
    const currentTime = new Date().toLocaleTimeString('pl-PL', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });
    const currentDate = new Date().toLocaleDateString('pl-PL');
    const tasksCount = 8; // Przykładowa wartość
    const nodeVersion = process.version;
    
//    console.log(`<!-- SVG+Node.js renderowany przez CLI: ${svgFile} o ${currentTime} -->`);
    
    try {
        // Wczytaj plik SVG
        const content = fs.readFileSync(svgFile, 'utf8');
        
        // Znajdź i wykonaj kod JavaScript między <?js ... ?>
        const processedContent = content.replace(
            /<\?js\s*([\s\S]*?)\s*\?>/g,
            (match, jsCode) => {
                try {
                    // Utwórz kontekst z dostępnymi zmiennymi
                    const sandbox = {
                        currentTime,
                        currentDate,
                        tasksCount,
                        nodeVersion,
                        Date,
                        Math,
                        console,
                        JSON,
                        // Capture output
                        output: '',
                        print: function(text) {
                            this.output += text;
                        }
                    };
                    
                    // Prostsza metoda - użyj eval w kontrolowanym kontekście
                    let output = '';
                    const print = (text) => { output += text; };
                    
                    // Wykonaj kod JavaScript z dostępem do zmiennych
                    eval(jsCode);
                    
                    const result = output;
                    
                    return result || '';
                    
                } catch (error) {
                    return `<!-- JavaScript Error: ${error.message} -->`;
                }
            }
        );
        
        // Wyświetl przetworzoną zawartość
        console.log(processedContent);
        
        return true;
        
    } catch (error) {
        console.error(`Błąd podczas przetwarzania pliku: ${error.message}`);
        return false;
    }
}

function main() {
    const args = process.argv.slice(2);
    
    if (args.length !== 1) {
        console.log('Użycie: node svg_processor.js <plik.svg>');
        console.log('Przykład: node svg_processor.js todo-manager-nodejs.svg');
        process.exit(1);
    }
    
    const svgFile = args[0];
    
    if (!processSvgFile(svgFile)) {
        process.exit(1);
    }
}

if (require.main === module) {
    main();
}
