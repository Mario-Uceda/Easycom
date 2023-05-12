#! /usr/bin/python python
from html.parser import HTMLParser
import json
from bs4 import BeautifulSoup as bs
import UserAgents as ua
from lxml import html
from requests_html import HTMLSession

url_wallapop = "https://es.wallapop.com/"

# Este método se encarga de obtener los datos de un producto de wallapop desde su código de barras
def get_wallapop(barcode):
    url_product = get_wallapop_id(barcode)
    return get_wallapop_data(url_product)

# Este método se encarga de obtener la url de un producto de wallapop desde su código de barras


def get_wallapop_id(barcode):
    barcode = barcode.replace(" ", "%20")
    url_search = url_wallapop + "app/search?filters_source=search_box&keywords=" + barcode
    print(url_search)
    return url_search

# Este método se encarga de obtener los datos y el precio de un producto de wallapop desde su url
def get_wallapop_data(url_product):
    try:
        #soup = ua.get_soup(url_product)
        session = HTMLSession()
        response = session.get(url_product, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'})
        soup = bs(response.content, "html.parser")
        if soup == "":
            return ("")
        precio_wallapop = json.dumps([url_product, "wallapop", get_price(soup)])
        return (precio_wallapop)
    except Exception as e:
        return ("")

# Este método se encarga de obtener el precio de todos los producto encontrados de wallapop desde la url de búsqueda y devuelve el precio más bajo
def get_price(soup):
    try:
        print (soup)
        preciominimo = 50000000000
        productos = soup.select('body > tsl-root > tsl-public > div > div > tsl-search > div > tsl-search-layout > div > div:nth-child(2) > div > tsl-public-item-card-list > div')
        print (productos)
        for producto in productos:
            print (producto)
            precio = producto.find('a:nth-child(1) > tsl-public-item-card > div > div.ItemCard__data.border-top-0.ItemCard__data--with-description > div.d-flex.justify-content-between.pt-2 > div > span')
            if precio < preciominimo and precio >0:
                preciominimo = precio
        print (preciominimo)


        #return precio
    except Exception as e:
        print ("Error al obtener el precio del producto: "+str(e))
        return ""

# Este método se encarga de actualizar el precio de un producto de wallapop desde su url
def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        if soup == "":
            return ("")
        print(get_price(soup))
    except Exception as e:
        return ("")

get_wallapop("Mario kart 8")
