#! /usr/bin/python python
import sys
import Amazon
import Mediamarkt

barcode = sys.argv[1]
productoAmazon, PrecioAmazon = Amazon.get_amazon(barcode)
productoMediamarkt, PrecioMediamarkt = Mediamarkt.get_mediamarkt(barcode)

if (productoAmazon != ""):
    print(productoAmazon)
    print(PrecioAmazon)
    if (productoMediamarkt != ""):
        print(PrecioMediamarkt)
elif (productoMediamarkt != ""):
    print(productoMediamarkt)
    print(PrecioMediamarkt)
else:
    print("No se ha encontrado el producto")