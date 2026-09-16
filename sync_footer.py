import sys

# Get the footer from blog.html
with open(r'h:\stérimar\blog.html', 'r', encoding='utf-8') as f:
    blog_content = f.read()

start_footer = blog_content.find('<footer class="footer"')
end_footer = blog_content.find('</footer>', start_footer) + 9
standard_footer = blog_content[start_footer:end_footer]

# Replace in index.html
with open(r'h:\stérimar\index.html', 'r', encoding='utf-8') as f:
    index_content = f.read()

start_index = index_content.find('<footer class="footer-new"')
if start_index != -1:
    end_index = index_content.find('</footer>', start_index) + 9
    index_content = index_content[:start_index] + standard_footer + index_content[end_index:]
    with open(r'h:\stérimar\index.html', 'w', encoding='utf-8') as f:
        f.write(index_content)
    print('Replaced footer in index.html')

# Ensure Swiper arrows are white
css_file = r'h:\stérimar\styles.css'
with open(css_file, 'r', encoding='utf-8') as f:
    css_content = f.read()

if '.swiper-button-next::after' not in css_content or 'color: white !important;' not in css_content:
    css_content += '\n.swiper-button-next::after, .swiper-button-prev::after { color: white !important; }\n'
    with open(css_file, 'w', encoding='utf-8') as f:
        f.write(css_content)
    print('Forced swiper arrows to white')
