# -*- coding: utf-8 -*-
import os
import glob
import re

html_files = glob.glob('*.html')
slogan_html = '<span class="logo-slogan">Mieux respirer, c\'est mieux vivre</span>'

for file in html_files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Remove any existing slogan to avoid duplicates
    content = re.sub(r'<span class="logo-slogan">.*?</span>', '', content)
    
    # Insert new slogan right after the logo image
    content = re.sub(r'(<img src="logo\.png" alt="Stérimar Logo" class="logo-img">)', r'\1\n                ' + slogan_html, content)
    
    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f'Updated {file}')
