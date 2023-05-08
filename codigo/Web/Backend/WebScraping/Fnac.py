#! /usr/bin/python python
import json
from bs4 import BeautifulSoup as bs
import UserAgents as ua

url_fnac = "https://www.fnac.es/"

# Este método se encarga de obtener los datos de un producto de Fnac desde su código de barras
def get_fnac(barcode):
    url_product = get_fnac_id(barcode)
    if url_product != "":
        get_fnac_data(url_product)
    else:
        print("El producto no existe en Fnac")

# Este método se encarga de obtener la url de un producto de Fnac desde su código de barras
def get_fnac_id(barcode):
    url_search = url_fnac + "/SearchResult/ResultList.aspx?Search=" + barcode
    try:
        soup = ua.get_soup(url_search)
        print(soup)
        url = soup.select_one('div.Article-itemInfo > div > p.Article-desc > a')['href']
        return url_fnac + url
    except Exception as e:
        print(e)
        return ""

# Este método se encarga de obtener los datos y el precio de un producto de Fnac desde su url
def get_fnac_data(url_product):
    try:
        soup = ua.get_soup(url_product)
        producto_fnac = get_product(soup)
        precio_fnac = json.dumps([url_product, "Fnac", get_price(soup)])
        print(producto_fnac)
        print(precio_fnac)
    except Exception as e:
        print(e)

# Este método se encarga de obtener los datos de un producto de Fnac desde su url
def get_product(soup):
    try:
        product_name = soup.select_one(
            'h1.f-product-header-title').text.strip()
        img = soup.select_one("div.f-gallery-big > div > a > img")['src']
        descriptor = soup.select_one("p.f-product-desc").text.strip()
        specs = ""
        try:
            table = soup.select_one("table.f-tech-specs")
            rows = table.select("tr")
            for row in rows:
                cells = row.select("td")
                attribute = cells[0].text.strip()
                value = cells[1].text.strip()
                specs += attribute + ": " + value + "\n"
        except Exception as e:
            specs = ""
        producto = json.dumps([product_name, img, descriptor, specs])
        return producto
    except Exception as e:
        print(e)

# Este método se encarga de obtener el precio de un producto de Fnac desde su url
def get_price(soup):
    try:
        decimal = soup.select_one(
            "span.f-price-box-price > span.f-price-decimal").text.strip()
        fraction = soup.select_one(
            "span.f-price-box-price > span.f-price-fraction").text.strip()
        precio = float(decimal + "." + fraction)
    except Exception as e:
        precio = soup.select_one(
            "span.f-price-box-price > span.price > span > span > span > span")['content']
        precio = float(precio.replace(",", "."))
    # compruebo si el precio es un número, en caso de que no lo sea, no devuelvo nada
    if isinstance(precio, float):
        return precio

# Este método se encarga de actualizar el precio de un producto de Fnac desde su url
def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        print(get_price(soup))
    except Exception as e:
        print(e)


get_fnac("6972453163820")
