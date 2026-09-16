import sys

file_path = r'h:\stérimar\styles.css'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

start = content.find('.btn-discover {')
if start != -1:
    end = content.find('.btn-discover:hover {')
    end = content.find('}', end) + 1
    
    old_css = content[start:end]
    
    new_css = '''.btn-discover {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #002288;
    color: white;
    border-radius: var(--radius-full);
    padding: 0.8rem 2.5rem;
    font-family: var(--font-primary);
    font-weight: 400;
    font-size: 1.2rem;
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

    content = content.replace(old_css, new_css)
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    print('Updated .btn-discover perfectly')
else:
    print('Could not find .btn-discover')
