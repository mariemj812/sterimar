import os
import re

dir_path = r"H:\stérimar"
file_path = os.path.join(dir_path, "boutique.html")

slugs = [
    "produit-hygiene-du-nez.html",
    "produit-nez-sujet-aux-rhumes.html",
    "produit-nez-bouche.html",
    "produit-nez-allergique.html",
    "produit-hygiene-du-nez-bebe.html",
    "produit-nez-bouche-bebe.html",
    "produit-stop-protect-rhume.html"
]

with open(file_path, "r", encoding="utf-8") as f:
    html = f.read()

for i in range(7):
    # Remplacer <button class="btn btn-quick" onclick="openModal(X)" id="quick-view-X">Voir détails</button>
    # par <a href="slug" class="btn btn-quick" id="quick-view-X">Voir détails</a>
    pattern = rf'<button class="btn btn-quick" onclick="openModal\({i}\)"[^>]*>Voir détails</button>'
    replacement = rf'<a href="{slugs[i]}" class="btn btn-quick" id="quick-view-{i}">Voir détails</a>'
    html = re.sub(pattern, replacement, html)

with open(file_path, "w", encoding="utf-8") as f:
    f.write(html)

print("Links updated in boutique.html")
