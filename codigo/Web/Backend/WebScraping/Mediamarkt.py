#! /usr/bin/python python
import json
from bs4 import BeautifulSoup as bs
import UserAgents as ua
from lxml import etree

url_mediamarkt = "https://www.mediamarkt.es/"

# Este método se encarga de obtener los datos de un producto de Mediamarkt desde su código de barras
def get_mediamarkt(barcode):
    url_product = get_mediamarkt_id(barcode)
    if url_product != "":
        return get_mediamarkt_data(url_product)
    else:
        return ""

# Este método se encarga de obtener la url de un producto de Mediamarkt desde su código de barras
def get_mediamarkt_id(barcode):
    barcode = barcode.replace(" ", "%20")
    url_search = url_mediamarkt + "es/search.html?query=" + barcode
    try:
        soup = ua.get_soup(url_search)
        producto = soup.find('div', {'class': 'sc-iveFHk fEflDt ProductFlexBox__StyledFlexBox-sc-39215640-1 czuIAZ'})
        url = producto.find('a')['href'].removeprefix('/')
        return url_mediamarkt + url
    except Exception as e:
        return ""

# Este método se encarga de obtener los datos y el precio de un producto de Mediamarkt desde su url
def get_mediamarkt_data(url_product):
    try:
        soup = ua.get_soup(url_product)
        producto_mediamarkt = get_product(soup)
        precio_mediamarkt = json.dumps([url_product, "Mediamarkt", get_price(soup)])
        return (producto_mediamarkt, precio_mediamarkt)
    except Exception as e:
        return ""

# Este método se encarga de obtener los datos de un producto de Mediamarkt desde su url
def get_product(soup):
    try:
        product_name = soup.select_one('.product-name > h1').text.strip()
        img = soup.select_one('.swiper-slide img')['src']
        descriptor = soup.select_one(
            '.product-short-description > div').text.strip()
        specs = ""
        try:
            table = soup.select_one('.product-techspecs')
            rows = table.select('tr')
            for row in rows:
                cells = row.select('td')
                attribute = cells[0].text.strip()
                value = cells[1].text.strip()
                specs += attribute + ": " + value + "\n"
        except Exception as e:
            specs = ""
        producto = json.dumps([product_name, img, descriptor, specs])
        return producto
    except Exception as e:
        return ""

# Este método se encarga de obtener el precio de un producto de Mediamarkt desde su url
def get_price(soup):
    try:
        dom = etree.HTML(str(soup))
        precio = float(dom.xpath('//*[@class="sc-gikAfH fBksvO"]/text()')[1].split(' ')[1])
        return precio
    except Exception as e:
        return ""
        
# Este método se encarga de actualizar el precio de un producto de Mediamarkt desde su url
def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        print(get_price(soup))
    except Exception as e:
        print(e)
