#! /usr/bin/python python
import sys
import Amazon
import Mediamarkt
import Ebay

url = sys.argv[1]
tienda = sys.argv[2]
precio = ""

tiendas = {
    "Amazon": Amazon.update_price,
    "Mediamarkt": Mediamarkt.update_price,
    "Ebay": Ebay.update_price
}

for i in range(5):
    if tienda in tiendas:
        precio = tiendas[tienda](url)
        if precio:
            break

print(precio)