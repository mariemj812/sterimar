import urllib.request
import re
import ssl

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

product_urls = [
    "https://www.sterimar.com/fr/produits/hygiene-et-confort-du-nez/",
    "https://www.sterimar.com/fr/produits/nez-sujet-aux-rhumes/",
    "https://www.sterimar.com/fr/produits/nez-bouche/",
    "https://www.sterimar.com/fr/produits/nez-allergique/",
    "https://www.sterimar.com/fr/produits/hygiene-du-nez-bebe/",
    "https://www.sterimar.com/fr/produits/nez-bouche-bebe/",
]

for url in product_urls:
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            content = resp.read().decode('utf-8', errors='ignore')
            imgs = set(re.findall(r'(https?://[^\s"\'<>]+\.(?:png|jpg|webp|jpeg))', content))
            product_imgs = [i for i in imgs if any(k in i.lower() for k in ['etuican', 'shooting', 'produit', 'can', 'spray', 'right', 'shadow'])]
            print(f"=== {url} ===")
            for img in sorted(product_imgs):
                print(img)
    except Exception as e:
        print(f"Error {url}: {e}")
