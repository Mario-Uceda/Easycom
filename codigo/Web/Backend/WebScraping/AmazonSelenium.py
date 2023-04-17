from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
import time
from bs4 import BeautifulSoup as bs
import json

chrome_options = webdriver.ChromeOptions()
chrome_options.binary_location = '/usr/bin/chromium-browser'  # ruta de Chromium
chrome_options.add_argument('--disable-extensions')
chrome_options.add_argument('--headless')
chrome_options.add_argument('--disable-gpu')
chrome_options.add_argument('--no-sandbox')
chrome_options.add_argument('--disable-dev-shm-usage')
# puerto para Chrome DevTools
chrome_options.add_argument('--remote-debugging-port=9222')
chrome_options.add_argument('start-maximized')
chrome_options.add_argument('disable-infobars')
chrome_options.add_argument('--disable-extensions')
chrome_options.add_argument('--disable-popup-blocking')
chrome_options.add_argument('--disable-logging')
chrome_options.add_argument('--log-level=3')


barcode = '6972453163820'

browser_driver = Service('/usr/bin/chromedriver')
#pagina = webdriver.Chrome(options=chrome_options, service=browser_driver)
pagina = webdriver.Chrome(service=browser_driver)
pagina.get("https://www.amazon.es/s?k=" + barcode)

while(True):
    try:
        cookie = WebDriverWait(pagina, 3).until(EC.presence_of_element_located((By.ID, "sp-cc-accept")))
        cookie.click()
    except TimeoutException:
        pagina.refresh()
        continue
    else:
        break

#pagina.find_element(By.CLASS_NAME, "a-link-normal s-underline-text s-underline-link-text s-link-style a-text-normal").click()
links = pagina.find_elements(By.CSS_SELECTOR, ".a-link-normal.s-underline-text.s-underline-link-text.s-link-style.a-text-normal")
if len(links) > 0:
    links[0].click()

soup = bs(pagina.page_source, 'html.parser')

product_name = soup.select_one('#productTitle').text.strip()
img = soup.select_one("#imgTagWrapperId img")['src']
descriptor = soup.select_one("#feature-bullets > ul > li").text.strip()

# Obtener las especificaciones del producto
specs = ""
try:
    table = soup.select_one("#productDetails_techSpec_section_1")
    rows = table.select("tr")
    for row in rows:
        cells = row.select("td, th")
        attribute = cells[0].text.strip()
        value = cells[1].text.strip()
        specs += attribute + ": " + value + "\n"
except Exception as e:
    specs = ""

# Obtener el precio del producto
try:
    decimal = soup.select_one(".a-price-whole").text.split(",")[0]
    fraction = soup.select_one(".a-price-fraction").text.split(" ")[0]
    precio = float(decimal + "." + fraction)
except Exception as e:
    precio = 0.0

# Crear objeto JSON con los datos del producto
producto = json.dumps({
    'product_name': product_name,
    'img': img,
    'descriptor': descriptor,
    'specs': specs,
    'precio': precio
})

# Cerrar el driver de Selenium
pagina.quit()

# Imprimir el objeto JSON con los datos del producto
print(producto)
