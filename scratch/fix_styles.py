import re

with open('h:/stérimar/boutique.html', 'r', encoding='utf-8') as f:
    content = f.read()

def repl_style(m):
    style1 = m.group(1)
    style2 = m.group(2)
    return f'style="{style1} {style2}"'

new_content = re.sub(r'style="([^"]+)"\s+style="([^"]+)"', repl_style, content)

with open('h:/stérimar/boutique.html', 'w', encoding='utf-8') as f:
    f.write(new_content)

print("boutique.html style attributes merged.")
