#!/usr/bin/env python3
"""
SVG+Python Processor - Wykonuje pliki SVG z wbudowanym kodem Python
Podobnie jak router.php, ale dla Python

Użycie:
    python svg_processor.py <plik.svg>
    python svg_processor.py todo-manager-python.svg
"""

import sys
import os
import re
from datetime import datetime
import subprocess

def process_svg_file(svg_file):
    """Przetwarza plik SVG z wbudowanym kodem Python"""
    
    if not os.path.exists(svg_file):
        print(f"Błąd: Plik '{svg_file}' nie istnieje")
        return False
    
    if not svg_file.endswith('.svg'):
        print(f"Błąd: Plik musi mieć rozszerzenie .svg")
        return False
    
    # Przygotuj zmienne Python dostępne w SVG
    current_time = datetime.now().strftime("%H:%M:%S")
    current_date = datetime.now().strftime("%Y-%m-%d")
    tasks_count = 7  # Przykładowa wartość
    
    # print(f"<!-- SVG+Python renderowany przez CLI: {svg_file} o {current_time} -->")
    
    try:
        # Wczytaj plik SVG
        with open(svg_file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Znajdź i wykonaj kod Python między <?python ... ?>
        def replace_python_code(match):
            python_code = match.group(1)
            
            # Utwórz namespace z dostępnymi zmiennymi
            namespace = {
                'current_time': current_time,
                'current_date': current_date,
                'tasks_count': tasks_count,
                'datetime': datetime,
                'os': os,
                'sys': sys
            }
            
            # Wykonaj kod Python i zachwyć output
            old_stdout = sys.stdout
            from io import StringIO
            sys.stdout = StringIO()
            
            try:
                exec(python_code, namespace)
                result = sys.stdout.getvalue()
            except Exception as e:
                result = f"<!-- Python Error: {e} -->"
            finally:
                sys.stdout = old_stdout
            
            return result
        
        # Zamień kod Python na wyniki
        processed_content = re.sub(r'<\?python\s*(.*?)\s*\?>', replace_python_code, content, flags=re.DOTALL)
        
        # Wyświetl przetworzoną zawartość
        print(processed_content)
        
        return True
        
    except Exception as e:
        print(f"Błąd podczas przetwarzania pliku: {e}")
        return False

def main():
    if len(sys.argv) != 2:
        print("Użycie: python svg_processor.py <plik.svg>")
        print("Przykład: python svg_processor.py todo-manager-python.svg")
        sys.exit(1)
    
    svg_file = sys.argv[1]
    
    if not process_svg_file(svg_file):
        sys.exit(1)

if __name__ == "__main__":
    main()
