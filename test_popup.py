import urllib.request
from bs4 import BeautifulSoup

url = 'http://localhost:8082/'
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
html = urllib.request.urlopen(req).read()
soup = BeautifulSoup(html, 'html.parser')

# Find the quick view container
quick_view = soup.find('div', class_='gp-quick-view-container')
if quick_view:
    print(quick_view.prettify())
else:
    print("Not found")
