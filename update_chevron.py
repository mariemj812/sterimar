import sys

file_path = r'h:\stérimar\index.html'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 12px; margin-top: 2px;"><path d="M9 18l6-6-6-6"/></svg>'

content = content.replace('Je dcouvre &gt;', 'Je découvre ' + svg)
content = content.replace('Je dcouvre &gt;', 'Je découvre ' + svg)
content = content.replace('Je découvre &gt;', 'Je découvre ' + svg)
content = content.replace('Je lis l\'article &gt;', 'Je lis l\'article ' + svg)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print('Updated index.html')
