#! /usr/bin/python python
from html.parser import HTMLParser
import json
from bs4 import BeautifulSoup as bs
import UserAgents as ua
from lxml import html

url_mediamarkt = "https://www.mediamarkt.es/"

# Este método se encarga de obtener los datos de un producto de Mediamarkt desde su código de barras
def get_mediamarkt(barcode):
    url_product = get_mediamarkt_id(barcode)
    if url_product != "":
        return get_mediamarkt_data(url_product)
    else:
        return ("", "")

# Este método se encarga de obtener la url de un producto de Mediamarkt desde su código de barras


def get_mediamarkt_id(barcode):
    barcode = barcode.replace(" ", "%20")
    url_search = url_mediamarkt + "es/search.html?query=" + barcode
    try:
        soup = ua.get_soup(url_search)
        if soup == "":
            return ""
        element = soup.select_one('#mms-search-srp-productlist > div:nth-child(1) > div > div > a')
        url = element.get('href').removeprefix('/')
        return url_mediamarkt + url
    except Exception as e:
        return ""

# Este método se encarga de obtener los datos y el precio de un producto de Mediamarkt desde su url
def get_mediamarkt_data(url_product):
    try:
        soup = ua.get_soup(url_product)
        if soup == "":
            return ("", "")
        producto_mediamarkt = get_product(soup)
        precio_mediamarkt = json.dumps([url_product, "Mediamarkt", get_price(soup)])
        return (producto_mediamarkt, precio_mediamarkt)
    except Exception as e:
        return ("", "")

# Este método se encarga de obtener los datos de un producto de Mediamarkt desde su url
def get_product(soup):
    try:
        tree = html.fromstring(str(soup))
        product_name = soup.select_one('h1').text.strip()
        img = tree.xpath('//*[@id="StyledPdpWrapper"]/div[1]/div/div[3]/div/div/div[1]/div/div[3]/ul/li[1]/div/picture/img/@src')[0]
        descripcion = tree.xpath('//*[@id = "acc-content-id-description"]/div/div[1]/p[1]/text()')[0]
        specs = ""
        try:
            table = soup.select_one('#acc-content-id-features > div > div > table:nth-child(1)')
            rows = table.find('tbody').find_all('tr')
            for row in rows:
                attribute = row.find('p').text
                value = row.find_all('td')[1].text
                specs += attribute + ": " + value + "\n"
        except Exception as e:
            specs = ""
        producto = json.dumps([product_name, img, descripcion, specs])
        return producto
    except Exception as e:
        return ""

# Este método se encarga de obtener el precio de un producto de Mediamarkt desde su url
def get_price(soup):
    tree = html.fromstring(str(soup))
    precio = tree.xpath('//*[@id="StyledPdpWrapper"]/div[2]/div[2]/div/div/div[2]/div/div/div[2]/div/span/text()')[0].replace(',', '.').replace('–', '')
    precio = float(precio)
    return precio    
        
# Este método se encarga de actualizar el precio de un producto de Mediamarkt desde su url
def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        if soup == "":
            return ("")
        print(get_price(soup))
    except Exception as e:
        return ("")
