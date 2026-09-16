import os

css_path = r"H:\stérimar\styles.css"

with open(css_path, "r", encoding="utf-8") as f:
    css = f.read()

# Revert the navbar height and padding from 144px back to 72px
css = css.replace("144px", "72px")

with open(css_path, "w", encoding="utf-8") as f:
    f.write(css)

print("Navbar block reduced by half (reverted to 72px).")
