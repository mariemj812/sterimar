import sys
import re

file_path = r'h:\stérimar\index.html'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

new_slides = """
                <div class="swiper-slide slide-bebe">
                    <picture class="slide-bebe-bg">
                        <source media="(max-width: 768px)" srcset="banner-bebe-mobile.jpg">
                        <img src="banner-bebe-desktop.jpg" alt="Fond mer">
                    </picture>
                    <div class="slide-bebe-overlay">
                        <img src="hygiene-adulte.png" alt="Stérimar Hygiène" class="slide-bebe-packshot">
                        <div class="slide-bebe-text">
                            <h2>L'expert<br>de l'hygiène nasale</h2>
                            <hr class="separator-line">
                            <p class="subtitle">Mieux respirer, c'est mieux vivre</p>
                            <a href="boutique.html" class="btn btn-discover">Je découvre ></a>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide slide-bebe">
                    <picture class="slide-bebe-bg">
                        <source media="(max-width: 768px)" srcset="banner-bebe-mobile.jpg">
                        <img src="banner-bebe-desktop.jpg" alt="Fond Rhume">
                    </picture>
                    <div class="slide-bebe-overlay">
                        <img src="cold-adulte.png" alt="Stérimar Rhume" class="slide-bebe-packshot">
                        <div class="slide-bebe-text">
                            <h2>Nez bouché ?<br>Rhume & Sinusite</h2>
                            <hr class="separator-line">
                            <p class="subtitle">Libérez votre nez rapidement</p>
                            <a href="boutique.html#rhume" class="btn btn-discover">Je découvre ></a>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide slide-bebe">
                    <picture class="slide-bebe-bg">
                        <source media="(max-width: 768px)" srcset="banner-bebe-mobile.jpg">
                        <img src="banner-bebe-desktop.jpg" alt="Fond Allergie">
                    </picture>
                    <div class="slide-bebe-overlay">
                        <img src="allergie.png" alt="Stérimar Allergie" class="slide-bebe-packshot">
                        <div class="slide-bebe-text">
                            <h2>Nez Allergique ?<br>Pollen & Poussière</h2>
                            <hr class="separator-line">
                            <p class="subtitle">Prévenez et soulagez</p>
                            <a href="boutique.html#allergie" class="btn btn-discover">Je découvre ></a>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide slide-bebe">
                    <picture class="slide-bebe-bg">
                        <source media="(max-width: 768px)" srcset="banner-bebe-mobile.jpg">
                        <img src="banner-bebe-desktop.jpg" alt="Fond mer bébé">
                    </picture>
                    <div class="slide-bebe-overlay">
                        <img src="hygiene-bébé.png" alt="Stérimar Hygiène Bébé" class="slide-bebe-packshot">
                        <div class="slide-bebe-text">
                            <h2>Hygiène<br>du nez bébé</h2>
                            <hr class="separator-line">
                            <p class="subtitle">Dès la naissance</p>
                            <a href="boutique.html#bebe" class="btn btn-discover">Je découvre ></a>
                        </div>
                    </div>
                </div>
"""

start = content.find('<div class="swiper-wrapper">')
end = content.find('<div class="swiper-pagination"></div>')

if start != -1 and end != -1:
    content = content[:start+28] + '\n' + new_slides + '\n            ' + content[end:]
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print('Updated sliders to be identical!')
else:
    print('Could not find slider tags')
