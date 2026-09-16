import os
import re
import json

dir_path = r"H:\stérimar"

products = [
    {
        "id": 0, "name": "Hygiène du Nez", "categoryLabel": "Hygiène", "categoryColor": "#00B8E5",
        "type": "Physiologique", "molecule": "Oligo-éléments", "price": "16,00", "image": "hygiene-adulte.png",
        "description": "Spray nasal isotonique pour nettoyer, humidifier et protéger la muqueuse nasale au quotidien. Enrichi en oligo-éléments marins, il élimine les impuretés, poussières et allergènes tout en hydratant les cavités nasales.",
        "features": ["Solution isotonique d'eau de mer", "Enrichi en oligo-éléments marins", "Nettoie, humidifie et protège", "Sans conservateur, sans gaz propulseur", "Micro-diffusion brevetée", "Dès 3 ans et adulte"],
        "usage": "1 à 2 pulvérisations dans chaque narine, 1 à 3 fois par jour. Pencher la tête sur le côté, insérer l'embout dans la narine supérieure et pulvériser. Moucher après utilisation.",
        "audience": "Adulte & Enfant", "volume": "100ml", "slug": "produit-hygiene-du-nez.html"
    },
    {
        "id": 1, "name": "Nez sujet aux Rhumes", "categoryLabel": "Rhume", "categoryColor": "#F15B2B",
        "type": "Physiologique", "molecule": "Soufre", "price": "16,00", "image": "cold-adulte.png",
        "description": "Spray nasal préventif enrichi en soufre pour renforcer la résistance de la muqueuse nasale face aux agressions hivernales. Il aide à prévenir l'apparition des rhumes en maintenant une barrière nasale saine.",
        "features": ["Enrichi en soufre", "Renforce la résistance nasale", "Prévention des rhumes", "Solution isotonique d'eau de mer", "Sans conservateur", "Dès 3 ans et adulte"],
        "usage": "2 à 3 pulvérisations dans chaque narine, 2 à 3 fois par jour. Utilisation recommandée dès les premiers signes de refroidissement ou en période hivernale.",
        "audience": "Adulte & Enfant", "volume": "100ml", "slug": "produit-nez-sujet-aux-rhumes.html"
    },
    {
        "id": 2, "name": "Nez Bouché", "categoryLabel": "Rhume", "categoryColor": "#F15B2B",
        "type": "Hypertonique", "molecule": "Cuivre", "price": "16,00", "image": "nez-bouché-adulte.png",
        "description": "Spray hypertonique enrichi en cuivre pour décongestionner rapidement le nez bouché. Son action osmotique aide à réduire l'œdème de la muqueuse nasale et facilite l'élimination du mucus en cas de rhume ou de sinusite.",
        "features": ["Solution hypertonique concentrée", "Enrichi en cuivre anti-inflammatoire", "Décongestion rapide", "Action osmotique naturelle", "Sans vasoconstricteur", "Dès 3 ans et adulte"],
        "usage": "1 à 2 pulvérisations dans chaque narine, 2 à 3 fois par jour pendant la période de congestion. Ne pas dépasser 5 jours d'utilisation consécutifs.",
        "audience": "Dès 3 ans", "volume": "100ml", "slug": "produit-nez-bouche.html"
    },
    {
        "id": 3, "name": "Nez Allergique", "categoryLabel": "Allergie", "categoryColor": "#79C142",
        "type": "Physiologique", "molecule": "Manganèse", "price": "16,00", "image": "allergie.png",
        "description": "Spray enrichi en manganèse pour soulager les symptômes d'allergie nasale : éternuements, nez qui coule, congestion. Élu Meilleur Produit Pharma, il aide à éliminer les allergènes (pollens, acariens, poussières) et à apaiser la muqueuse nasale irritée.",
        "features": ["Enrichi en manganèse anti-allergique", "Élimine pollens et allergènes", "Apaise la muqueuse irritée", "Élu Meilleur Produit Pharma", "Sans corticoïdes", "Adulte & Enfant dès 3 ans"],
        "usage": "2 à 3 pulvérisations dans chaque narine, 1 à 3 fois par jour. Utilisation recommandée pendant toute la durée de l'exposition aux allergènes.",
        "audience": "Adulte & Enfant", "volume": "100ml", "slug": "produit-nez-allergique.html"
    },
    {
        "id": 4, "name": "Hygiène du Nez Bébé", "categoryLabel": "Bébé", "categoryColor": "#59AEE1",
        "type": "Physiologique", "molecule": "Oligo-éléments", "price": "16,00", "image": "hygiene-bébé.png",
        "description": "Spray nasal pour les petits de 0 à 3 ans destiné à nettoyer et hydrater le nez et éliminer les impuretés. Ce spray prévient et diminue les symptômes nasaux (rhume, rhinite, sinusite). Nouvel embout sécurité bébé à forme douce et ergonomique.",
        "features": ["Riche en oligo-éléments marins", "Solution physiologique", "Nouvel embout sécurité bébé", "Forme douce et ergonomique", "Sans conservateur", "De 0 à 3 ans"],
        "usage": "1 pulvérisation douce dans chaque narine, 1 à 3 fois par jour. Allonger le bébé sur le dos, tourner la tête sur le côté et pulvériser doucement. Moucher le nez du bébé.",
        "audience": "Dès la naissance", "volume": "100ml", "slug": "produit-hygiene-du-nez-bebe.html"
    },
    {
        "id": 5, "name": "Nez Bouché Bébé", "categoryLabel": "Bébé", "categoryColor": "#59AEE1",
        "type": "Hypertonique", "molecule": "Cuivre", "price": "16,00", "image": "nez-bouché-bébé.png",
        "description": "Spray hypertonique enrichi en cuivre pour décongestionner rapidement le nez de bébé dès 3 mois. Avec son nouvel embout sécurité bébé à forme douce et ergonomique, moucher son bébé devient un jeu d'enfant.",
        "features": ["Hypertonique enrichi en cuivre", "Décongestion rapide et naturelle", "Nouvel embout sécurité bébé", "Adapté aux narines de bébé", "Sans vasoconstricteur", "Dès 3 mois"],
        "usage": "1 pulvérisation dans chaque narine, 2 à 3 fois par jour. Allonger le bébé, tourner la tête et pulvériser doucement. Moucher après chaque application.",
        "audience": "Dès 3 mois", "volume": "100ml", "slug": "produit-nez-bouche-bebe.html"
    },
    {
        "id": 6, "name": "Stop & Protect Rhume", "categoryLabel": "Rhume", "categoryColor": "#F15B2B",
        "type": "Stop & Protect", "molecule": "Soufre", "price": "16,00", "image": "cold-adulte.png",
        "description": "Spray innovant combinant l'eau de mer enrichie en soufre avec une action protectrice. Stop & Protect aide à stopper le rhume dès les premiers symptômes et protège la muqueuse nasale grâce à un film protecteur. Format compact idéal pour emporter partout.",
        "features": ["Action Stop : stoppe le rhume dès les premiers symptômes", "Action Protect : forme un film protecteur nasal", "Enrichi en soufre naturel", "Eau de mer microfiltrée", "Format compact 20ml", "Adulte & Enfant dès 6 ans"],
        "usage": "1 à 2 pulvérisations dans chaque narine, 3 à 6 fois par jour. À utiliser dès les premiers signes de rhume. Peut être utilisé en complément des autres sprays Stérimar.",
        "audience": "Dès 6 ans", "volume": "20ml", "slug": "produit-stop-protect-rhume.html"
    }
]

# Read base HTML to extract head, nav, footer
with open(os.path.join(dir_path, "qui-sommes-nous.html"), "r", encoding="utf-8") as f:
    html = f.read()

head_match = re.search(r'(<!DOCTYPE html>.*?</head>\s*<body>)', html, re.DOTALL)
head = head_match.group(1)
if 'swiper' not in head:
    head = head.replace('</head>', '    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />\n</head>')

nav_match = re.search(r'(<!-- Navigation -->.*?</nav>)', html, re.DOTALL)
nav = nav_match.group(1)
nav = nav.replace('class="nav-link active" id="nav-about"', 'class="nav-link" id="nav-about"')

footer_match = re.search(r'(<!-- Footer -->.*?</html>)', html, re.DOTALL)
footer = footer_match.group(1)
if 'swiper' not in footer:
    footer = footer.replace('<script src="app.js"></script>', '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>\n    <script src="app.js"></script>')

for p in products:
    features_html = "".join([f"<li>{feat}</li>" for feat in p['features']])
    
    page_html = f"""{head}
    {nav}

    <!-- Product Detail Section -->
    <section class="product-page">
        <div class="container">
            <div class="product-detail-card">
                <div class="product-gallery-container">
                    <!-- Product Swiper Carousel -->
                    <div class="swiper productSwiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{p['image']}" alt="{p['name']}">
                            </div>
                            <div class="swiper-slide">
                                <img src="{p['image']}" alt="{p['name']} Vue détaillée" style="transform: scale(1.15);">
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
                <div class="product-info-container">
                    <div>
                        <div style="margin-bottom: 0.5rem;"><a href="boutique.html" style="color: var(--blue-primary); font-size: 0.85rem; font-weight: 600;">← Retour à la boutique</a></div>
                        <span class="product-category-tag" style="background: {p['categoryColor']}">{p['categoryLabel']}</span>
                        <h2 style="font-family: var(--font-display); font-size: 1.8rem; font-weight: 800; color: var(--gray-900); margin: 0.5rem 0;">{p['name']}</h2>
                        <div class="modal-badges">
                            <span class="detail-chip">🧪 {p['molecule']}</span>
                            <span class="detail-chip">📋 {p['type']}</span>
                            <span class="detail-chip">👤 {p['audience']}</span>
                            <span class="detail-chip">📦 {p['volume']}</span>
                        </div>
                        <p class="modal-description">{p['description']}</p>
                        
                        <div class="modal-features">
                            <h4>Caractéristiques</h4>
                            <ul>{features_html}</ul>
                        </div>
                        
                        <div class="modal-usage">
                            <h4>Mode d'emploi</h4>
                            <p>{p['usage']}</p>
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="margin-top: 2rem;">
                        <span class="product-price-xl">{p['price']} DT</span>
                        
                        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                            <div class="quantity-control" style="background: white;">
                                <button onclick="let v = document.getElementById('qty'); v.innerText = Math.max(1, parseInt(v.innerText) - 1);" aria-label="Diminuer">−</button>
                                <span id="qty">1</span>
                                <button onclick="let v = document.getElementById('qty'); v.innerText = parseInt(document.getElementById('qty').innerText) + 1;" aria-label="Augmenter">+</button>
                            </div>
                            
                            <button class="btn btn-primary btn-lg" onclick="addToCart({p['id']}, parseInt(document.getElementById('qty').innerText))">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 5v14m-7-7h14"/></svg>
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {footer}
"""
    # Fix the title
    page_html = re.sub(r'<title>.*?</title>', f'<title>{p["name"]} — Stérimar™</title>', page_html)
    
    with open(os.path.join(dir_path, p['slug']), "w", encoding="utf-8") as f:
        f.write(page_html)

print("Pages created.")
