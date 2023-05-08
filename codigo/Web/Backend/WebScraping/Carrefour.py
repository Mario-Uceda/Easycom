#! /usr/bin/python python
import json
from bs4 import BeautifulSoup as bs
import UserAgents as ua

url_carrefour = "https://www.carrefour.es/"

# Este método se encarga de obtener los datos de un producto de Carrefour desde su código de barras
def get_carrefour(barcode):
    url_product = get_carrefour_id(barcode)
    print(url_product)
    if url_product != "":
        get_carrefour_data(url_product)
    else:
        print("El producto no existe en Carrefour")

# Este método se encarga de obtener la url de un producto de Carrefour desde su código de barras


def get_carrefour_id(barcode):
    url_search = url_carrefour + "?q=" + barcode
    print(url_search)
    try:
        soup = ua.get_soup(url_search)
        guardar_soup(soup)
        url = soup.select_one('#ebx-grid > article > div > div:nth-child(4) > a')['href']
        return url_carrefour + url
    except Exception as e:
        print(e)
        return ""

# Este método se encarga de obtener los datos y el precio de un producto de Carrefour desde su url


def get_carrefour_data(url_product):
    try:
        soup = ua.get_soup(url_product)
        producto_carrefour = get_product(soup)
        precio_carrefour = json.dumps(
            [url_product, "Carrefour", get_price(soup)])
        print(producto_carrefour)
        print(precio_carrefour)
    except Exception as e:
        print(e)

# Este método se encarga de obtener los datos de un producto de Carrefour desde su url


def get_product(soup):
    try:
        product_name = soup.select_one('.product-title').text.strip()
        img = soup.select_one('.image-container img')['src']
        descriptor = soup.select_one('.product-synopsis').text.strip()
        specs = ""
        try:
            table = soup.select_one('.product-description > table')
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
        print(e)

# Este método se encarga de obtener el precio de un producto de Carrefour desde su url


def get_price(soup):
    try:
        precio = soup.select_one('.price > .value').text.strip()
        precio = float(precio.replace(",", ".").replace("€", ""))
    except Exception as e:
        print("El producto no tiene precio de Carrefour")
        print(e)
        precio = ""
    # compruebo si el precio es un número, en caso de que no lo sea, no devuelvo nada
    if isinstance(precio, float):
        return precio

# Este método se encarga de actualizar el precio de un producto de Carrefour desde su url


def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        print(get_price(soup))
    except Exception as e:
        print("El producto no tiene precio de Carrefour")
        print(e)

def guardar_soup(soup):
    with open('WebScraping/index.html', 'w', encoding='utf-8') as file:
        file.write(str(soup))

get_carrefour("6972453163820")
