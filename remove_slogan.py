import glob
import re

html_files = glob.glob(r"H:\stérimar\*.html")

# The exact snippet might have spaces/newlines around it, so let's use regex
pattern = r'<span class="logo-tagline">Eau de mer naturelle</span>'
replacement = ''

for filepath in html_files:
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = re.sub(pattern, replacement, content)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)

print(f"Slogan removed in {len(html_files)} HTML files.")
