#! /usr/bin/python python
import json
from bs4 import BeautifulSoup as bs
import UserAgents as ua

url_amazon = "https://www.amazon.es/"

#Este método se encarga de obtener los datos de un producto de Amazon desde su código de barras
def get_amazon(barcode):
    url_product = get_amazon_id(barcode)
    if url_product != "":
        get_amazon_data(url_product)
    else:
        print("El producto no existe en Amazon")

#Este método se encarga de obtener la url de un producto de Amazon desde su código de barras
def get_amazon_id(barcode):
    url_search = url_amazon + "s?k=" + barcode
    try:
        soup = ua.get_soup(url_search)
        url = soup.select_one('h2 > a')['href']
        return url_amazon + url
    except Exception as e:
        print(e)
        return ""

#Este método se encarga de obtener los datos y el precio de un producto de Amazon desde su url
def get_amazon_data(url_product):
    try:
        soup = ua.get_soup(url_product)
        producto_amazon = get_product(soup)
        precio_amazon = json.dumps([url_product, "Amazon", get_price(soup)])
        print(producto_amazon)
        print(precio_amazon)
    except Exception as e:
        print(e)

#Este método se encarga de obtener los datos de un producto de Amazon desde su url
def get_product(soup):
    try:
        product_name = soup.select_one('#productTitle').text
        img = soup.select_one("#imgTagWrapperId img")['src']
        descriptor = soup.select_one("#feature-bullets > ul > li").text
        specs = ""
        try:
            table = soup.select_one("#productDetails_techSpec_section_1")
            rows = table.select("tr")
            for row in rows:
                cells = row.select("td, th")
                attribute = cells[0].text
                value = cells[1].text
                specs += attribute + ": " + value + "\n"
        except Exception as e:
            specs = ""
        producto = json.dumps([product_name, img, descriptor, specs])
        return producto
    except Exception as e:
        print(e)

#Este método se encarga de obtener el precio de un producto de Amazon desde su url
def get_price(soup):
    try:
        decimal = soup.select_one(".a-price-whole").text.split(",")[0]
        fraction = soup.select_one(".a-price-fraction").text.split(" ")[0]
        precio = float(decimal + "." + fraction)
    except Exception as e:
        precio = soup.select_one("#priceblock_ourprice > span").text.split(" ")[0]
        precio = float(precio.replace(",", ".").replace("€", ""))
    #compruebo si el precio es un número, en caso de que no lo sea, no devuelvo nada
    if isinstance(precio, float):
        return precio

#Este método se encarga de actualizar el precio de un producto de Amazon desde su url
def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        print(get_price(soup))
    except Exception as e:
        print("El producto no tiene precio de Amazon")
        print(e)