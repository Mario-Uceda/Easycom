#! /usr/bin/python python
import json
import UserAgents as ua

url_amazon = "https://www.amazon.es/"

#Este método se encarga de obtener los datos de un producto de Amazon desde su código de barras
def get_amazon(barcode):
    url_product = get_amazon_id(barcode)
    if url_product != "":
        return get_amazon_data(url_product)
    else:
        return ("","")

#Este método se encarga de obtener la url de un producto de Amazon desde su código de barras
def get_amazon_id(barcode):
    barcode = barcode.replace(" ", "+")
    url_search = url_amazon + "s?k=" + barcode
    try:
        soup = ua.get_soup(url_search)
        url = soup.select_one('h2 > a')['href']
        return url_amazon + url
    except Exception as e:
        return ""

#Este método se encarga de obtener los datos y el precio de un producto de Amazon desde su url
def get_amazon_data(url_product):
    try:
        soup = ua.get_soup(url_product)
        producto_amazon = get_product(soup)
        precio_amazon = json.dumps([url_product, "Amazon", get_price(soup)])
        return (producto_amazon, precio_amazon)
    except Exception as e:
        return ("","")

#Este método se encarga de obtener los datos de un producto de Amazon desde su url
def get_product(soup):
    try:
        product_name = soup.select_one('#productTitle').text.replace("  ", "")
        img = soup.select_one("#imgTagWrapperId img")['src']
        descriptor = soup.select_one("#feature-bullets > ul > li").text.replace("  ", "")
        specs = ""
        try:
            table = soup.select_one("#productDetails_techSpec_section_1")
            rows = table.select("tr")
            for row in rows:
                cells = row.select("td, th")
                attribute = cells[0].text.replace("  ", "")
                value = cells[1].text.replace("  ", "")
                specs += attribute + ": " + value + "\n"
        except Exception as e:
            specs = ""
        producto = json.dumps([product_name, img, descriptor, specs])
        return producto
    except Exception as e:
        return ""

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
    else:
        return ""

#Este método se encarga de actualizar el precio de un producto de Amazon desde su url
def update_price(url_product):
    try:
        soup = ua.get_soup(url_product)
        if soup == "":
            return ("")
        return get_price(soup)
    except Exception as e:
        return ("")
