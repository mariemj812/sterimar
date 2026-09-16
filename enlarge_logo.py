import os
import re

css_path = r"H:\stérimar\styles.css"

with open(css_path, "r", encoding="utf-8") as f:
    css = f.read()

# Replace all occurrences of 72px with 144px to handle the new navbar height
css = css.replace("72px", "144px")

# Also update the logo dimensions (36px * 3 = 108px)
pattern = r'(\.logo-svg, \.logo-img {\s*height:\s*)36px([^}]*max-width:\s*)180px'
replacement = r'\g<1>108px\g<2>500px'

new_css = re.sub(pattern, replacement, css)

# Make sure we didn't miss it
if "height: 108px" not in new_css:
    # fallback replace if regex fails
    new_css = new_css.replace("height: 36px; /* Taille optimale pour ne pas déformer la barre */", "height: 108px;")
    new_css = new_css.replace("max-width: 180px;", "max-width: 500px;")

with open(css_path, "w", encoding="utf-8") as f:
    f.write(new_css)

print("CSS updated successfully.")
