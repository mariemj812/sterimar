import re

with open(r'C:\Users\marie\.gemini\antigravity-ide\brain\1a34c8c0-7b58-4628-aeeb-e5a88273f7f7\.system_generated\steps\378\content.md', encoding='utf-8') as f:
    text = f.read()

urls = set(re.findall(r'(https?://[^\s"\'<>]+\.(?:png|jpg|webp|jpeg|svg))', text))
for u in sorted(urls):
    if 'uploads' in u:
        print(u)
