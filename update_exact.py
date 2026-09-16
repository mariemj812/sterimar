import sys
import re

# Update CSS
css_file = r'h:\stérimar\styles.css'
with open(css_file, 'r', encoding='utf-8') as f:
    css_content = f.read()

start = css_content.find('.btn-discover {')
end = css_content.find('.btn-discover:hover {')
end = css_content.find('}', end) + 1

old_css = css_content[start:end]

new_css = '''.btn-discover {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #003487;
    color: white;
    border-radius: 50px;
    padding: 12px 38px;
    font-family: var(--font-display);
    font-weight: 400;
    font-size: 1.4rem;
    letter-spacing: 0.02em;
    text-transform: none;
    text-decoration: none;
    box-shadow: none;
    transition: all var(--transition-fast);
    border: none;
}

.btn-discover:hover {
    background-color: #001A66;
    transform: translateY(-2px);
    color: white;
}'''

if old_css:
    css_content = css_content.replace(old_css, new_css)
    with open(css_file, 'w', encoding='utf-8') as f:
        f.write(css_content)

# Update HTML SVG
html_file = r'h:\stérimar\index.html'
with open(html_file, 'r', encoding='utf-8') as f:
    html_content = f.read()

old_svg_pattern = r'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 12px; margin-top: 2px;"><path d="M9 18l6-6-6-6"/></svg>'
new_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" style="margin-left: 12px; margin-top: 2px;"><path d="M10 20l8-8-8-8"/></svg>'

html_content = html_content.replace(old_svg_pattern, new_svg)

with open(html_file, 'w', encoding='utf-8') as f:
    f.write(html_content)

print("Updated CSS and HTML for exact match")
