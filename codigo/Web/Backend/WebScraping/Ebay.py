#! /usr/bin/python python
import json
import UserAgents as ua

url_ebay = "https://www.ebay.es/"

# Este método se encarga de obtener los datos de un producto de ebay desde su código de barras


def get_ebay(barcode):
    url_product = get_ebay_id(barcode)
    return get_ebay_data(url_product)

# Este método se encarga de obtener la url de un producto de ebay desde su código de barras
def get_ebay_id(barcode):
    barcode = barcode.replace(" ", "+")
    url_search = url_ebay + "sch/i.html?_nkw=" + barcode
    return url_search

# Este método se encarga de obtener los datos y el precio de un producto de ebay desde su url
def get_ebay_data(url_product):
    try:
        soup = ua.get_soup(url_product)
        if soup == "":
            return ("")
        precio_ebay = json.dumps([url_product, "Ebay", get_price(soup)])
        return (precio_ebay)
    except Exception as e:
        return ("")

# Este método se encarga de obtener el precio de todos los producto encontrados de ebay desde la url de búsqueda y devuelve el precio más bajo
def get_price(soup):
    try:
        preciominimo = ""
        productos = soup.select('#srp-river-results > ul > li.s-item')
        for producto in productos:
            precio = producto.select('span.s-item__price')[0].text.split(' a ')[0].replace(' EUR', '').replace('.', '').replace(',', '.')
            precio = float(precio)
            if (preciominimo == "") or (precio < preciominimo and precio > 0):
                preciominimo = precio
        return preciominimo
    except Exception as e:
        return ""

# Este método se encarga de actualizar el precio de un producto de ebay desde su url
def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        if soup == "":
            return ("")
        return get_price(soup)
    except Exception as e:
        return ("")
