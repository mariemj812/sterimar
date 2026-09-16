import glob
import re

html_files = glob.glob(r"H:\stérimar\*.html")

pattern = r'<svg viewBox="0 0 200 50" class="logo-svg">.*?</svg>'
replacement = r'<img src="logo.png" alt="Stérimar Logo" class="logo-img">'

for filepath in html_files:
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = re.sub(pattern, replacement, content, flags=re.DOTALL)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)

print(f"Logo updated in {len(html_files)} HTML files.")
