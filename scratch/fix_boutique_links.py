import re

with open('h:/stérimar/boutique.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace <a href="..." class="product-card-image" ...> with <div onclick="window.location.href='...'" class="product-card-image" ...>
def repl(m):
    url = m.group(1)
    rest = m.group(2)
    return f'<div onclick="window.location.href=\'{url}\'" class="product-card-image"{rest}>'

content = re.sub(r'<a href="([^"]+)" class="product-card-image"([^>]*)>', repl, content)

# Now we need to replace the </a> immediately following <div class="product-overlay"> ... </div>
# The structure is:
# <div onclick="...">
#   <div class="product-badge">...</div> (optional)
#   <img ...>
#   <div class="product-overlay">
#       <a href="..." class="btn btn-quick"...>Voir détails</a>
#   </div>
# </a>
# We just need to replace the </a> that follows </div>
content = re.sub(r'(<div class="product-overlay">\s*<a[^>]+>.*?</a>\s*</div>\s*)</a>', r'\1</div>', content)

with open('h:/stérimar/boutique.html', 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed nested <a> tags in boutique.html!")
