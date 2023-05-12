#! /usr/bin/python python
from html.parser import HTMLParser
import json
from bs4 import BeautifulSoup as bs
import UserAgents as ua
from lxml import html

url_milanuncios = "https://www.milanuncios.com/"

# Este método se encarga de obtener los datos de un producto de milanuncios desde su código de barras
def get_milanuncios(barcode):
    url_product = get_milanuncios_id(barcode)
    return get_milanuncios_data(url_product)

# Este método se encarga de obtener la url de un producto de milanuncios desde su código de barras


def get_milanuncios_id(barcode):
    barcode = barcode.replace(" ", "%20")
    url_search = url_milanuncios + "anuncios/?s=" + barcode + '&orden=relevance&fromSearch=1&fromSuggester=1&suggestionUsed=0&hitOrigin=home_search'
    print(url_search)
    return url_search

# Este método se encarga de obtener los datos y el precio de un producto de milanuncios desde su url
def get_milanuncios_data(url_product):
    try:
        soup = ua.get_soup(url_product)
        if soup == "":
            return ("")
        precio_milanuncios = json.dumps([url_product, "milanuncios", get_price(soup)])
        return (precio_milanuncios)
    except Exception as e:
        return ("")

# Este método se encarga de obtener el precio de todos los producto encontrados de milanuncios desde la url de búsqueda y devuelve el precio más bajo
def get_price(soup):
    try:
        print(soup)
        preciominimo = 50000000000
        productos = soup.select('#app > div > div > div > div > div > div > main > div > article')
        print(len(productos))
        for producto in productos:
            precio = producto.find('div > div > a:nth-child(2)').text
            #precio = float(precio)
            print (precio)
            #if precio < preciominimo and precio >0:
                #preciominimo = precio
        #print (preciominimo)


        #return precio
    except Exception as e:
        print ("Error al obtener el precio del producto: "+str(e))
        return ""

# Este método se encarga de actualizar el precio de un producto de milanuncios desde su url
def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        if soup == "":
            return ("")
        print(get_price(soup))
    except Exception as e:
        return ("")

get_milanuncios("Iphone 8")
