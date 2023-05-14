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

def scrape_websites(barcode):
    results = {}
    for website, scraper in tiendas.items():
        for i in range(5):
            data = scraper(barcode)
            if data:
                if isinstance(data, tuple):
                    product, price = data
                    results[website] = json.dumps({"product": product, "price": price})
                else:
                    results[website] = json.dumps({"price": data})
                break
    return json.dumps(results)

print(scrape_websites(barcode))
