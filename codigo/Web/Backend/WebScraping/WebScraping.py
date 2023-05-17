#! /usr/bin/python python
import sys
import Amazon
import Mediamarkt
import Ebay
import json

barcode = sys.argv[1]

tiendas = {
    "Amazon": Amazon.get_amazon,
    "Mediamarkt": Mediamarkt.get_mediamarkt,
    "Ebay": Ebay.get_ebay
}

def scrape_tiendas(barcode):
    results = {}
    for tienda, scraper in tiendas.items():
        for i in range(5):
            datos = scraper(barcode)
            if datos:
                if isinstance(datos, tuple):
                    producto, precio = datos
                    results[tienda] = json.dumps({"product": producto, "price": precio})
                else:
                    results[tienda] = json.dumps({"price": datos})
                break
    return json.dumps(results)

print(scrape_tiendas(barcode))
