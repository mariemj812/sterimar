import sys
import re

file_path = r'h:\stérimar\index.html'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

old_slides = """
                <div class="swiper-slide">
                    <a href="boutique.html" style="display: block; width: 100%; height: 100%;">
                        <picture>
                            <source media="(max-width: 768px)" srcset="https://www.sterimar.com/fr/wp-content/uploads/sites/6/2023/04/230321-STERIMAR-WEB-BANNIERE-GENERALE_MOBILE-min_1200x1200-jpg.webp">
                            <img src="https://www.sterimar.com/fr/wp-content/uploads/sites/6/2023/03/230321-STERIMAR-WEB-BANNIERE-GENERALE_DESKTOP-03-min-scaled.jpg" alt="Bannière Générale">
                        </picture>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="boutique.html#rhume" style="display: block; width: 100%; height: 100%;">
                        <picture>
                            <source media="(max-width: 768px)" srcset="https://www.sterimar.com/fr/wp-content/uploads/sites/6/2023/04/230321-STERIMAR-WEB-BANNIERES-RHUME_MOBILE-min_1200x1200-jpg.webp">
                            <img src="https://www.sterimar.com/fr/wp-content/uploads/sites/6/2023/03/230321-STERIMAR-WEB-BANNIERES-RHUME_DESKTOP-min-1-scaled.jpg" alt="Bannière Rhume">
                        </picture>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="boutique.html#allergie" style="display: block; width: 100%; height: 100%;">
                        <picture>
                            <source media="(max-width: 768px)" srcset="https://www.sterimar.com/fr/wp-content/uploads/sites/6/2023/04/230321-STERIMAR-WEB-BANNIERES-ALLERGIE_MOBILE-min_1200x1200-jpg.webp">
                            <img src="https://www.sterimar.com/fr/wp-content/uploads/sites/6/2023/03/230321-STERIMAR-WEB-BANNIERES-ALLERGIE_DESKTOP-min-scaled.jpg" alt="Bannière Allergie">
                        </picture>
                    </a>
                </div>
                <div class="swiper-slide slide-bebe">
                    <picture class="slide-bebe-bg">
                        <source media="(max-width: 768px)" srcset="banner-bebe-mobile.jpg">
                        <img src="banner-bebe-desktop.jpg" alt="Fond mer">
                    </picture>
                    <div class="slide-bebe-overlay">
                        <img src="hygiene-bébé.png" alt="Stérimar Hygiène Bébé" class="slide-bebe-packshot">
                        <div class="slide-bebe-text">
                            <h2>Hygiène<br>du nez bébé</h2>
                            <hr class="separator-line">
                            <p class="subtitle">Dès la naissance</p>
                            <a href="boutique.html#bebe" class="btn btn-discover">Je découvre &gt;</a>
                        </div>
                    </div>
                </div>
"""

start = content.find('<div class="swiper-wrapper">')
end = content.find('<div class="swiper-pagination"></div>')

if start != -1 and end != -1:
    content = content[:start+28] + '\n' + old_slides + '\n            ' + content[end:]
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print('Reverted sliders!')
else:
    print('Could not find slider tags')
