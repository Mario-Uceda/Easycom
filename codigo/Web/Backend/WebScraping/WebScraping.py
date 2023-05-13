#! /usr/bin/python python
import sys
import Amazon
import Mediamarkt
import Ebay
import json

barcode = sys.argv[1]
metodo = sys.argv[2] if len(sys.argv) > 2 else ""



websites = {
    "Amazon": Amazon.get_amazon,
    "Mediamarkt": Mediamarkt.get_mediamarkt,
    "Ebay": Ebay.get_ebay
}


def scrape_websites(barcode):
    results = {}
    for website, scraper in websites.items():
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

if (metodo == ""):
    amazonData = Amazon.get_amazon(barcode)
else:
    print(scrape_websites(barcode))
