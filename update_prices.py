import os
import re

dir_path = r"H:\stérimar"

# Update boutique.html
html_file = os.path.join(dir_path, "boutique.html")
with open(html_file, 'r', encoding='utf-8') as f:
    content = f.read()

content = re.sub(r'<span class="product-price-lg">[\d,]+\s*€</span>', '<span class="product-price-lg">19,00 DT</span>', content)

with open(html_file, 'w', encoding='utf-8') as f:
    f.write(content)

# Update app.js
js_file = os.path.join(dir_path, "app.js")
with open(js_file, 'r', encoding='utf-8') as f:
    js_content = f.read()

# Replace all price: x.xx, with price: 19.00,
js_content = re.sub(r'price:\s*\d+\.\d+,', 'price: 19.00,', js_content)

# Replace the "€" symbols formatting in app.js
js_content = js_content.replace('€', 'DT')
js_content = js_content.replace('>= 20 ? 0 : 4.90', '>= 50 ? 0 : 4.90')

with open(js_file, 'w', encoding='utf-8') as f:
    f.write(js_content)

# Update panier.html
panier_file = os.path.join(dir_path, "panier.html")
with open(panier_file, 'r', encoding='utf-8') as f:
    panier_content = f.read()

panier_content = panier_content.replace('0,00 €', '0,00 DT')
panier_content = panier_content.replace('Livraison gratuite dès 20€', 'Livraison gratuite dès 50 DT')

with open(panier_file, 'w', encoding='utf-8') as f:
    f.write(panier_content)
