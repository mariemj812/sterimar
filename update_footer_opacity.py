import sys

css_file = r'h:\stérimar\styles.css'
with open(css_file, 'r', encoding='utf-8') as f:
    content = f.read()

start = content.find('.footer {')
end = content.find('}', start) + 1

old_css = content[start:end]

new_css = '''.footer {
    position: relative;
    background-color: white;
    color: var(--blue-primary);
    padding: 4rem 0 2rem;
    overflow: hidden;
    z-index: 1;
}

.footer::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url('https://www.sterimar.com/fr/wp-content/uploads/sites/6/2023/02/230113-STERIMAR-REFONTE-SITE-INTERNET-MAQUETTE-PAGE-FAQ-V3_ARRIERE-PLAN.webp') no-repeat center bottom / cover;
    opacity: 0.5;
    z-index: -1;
    pointer-events: none;
}'''

if old_css:
    content = content.replace(old_css, new_css)
    with open(css_file, 'w', encoding='utf-8') as f:
        f.write(content)
    print("Updated footer opacity with pseudo-element")
