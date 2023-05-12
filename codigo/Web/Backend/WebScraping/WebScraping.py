#! /usr/bin/python python
import sys
import Amazon
import Mediamarkt
import Ebay

barcode = sys.argv[1]
productoAmazon, PrecioAmazon = '',''
contador = 0
while (not productoAmazon or not PrecioAmazon) and contador < 5:
    proAmazon, preAmazon = Amazon.get_amazon(barcode)
    if (productoAmazon == "" and proAmazon != ""):
        productoAmazon = proAmazon
    if (PrecioAmazon == "" and preAmazon != ""):
        PrecioAmazon = preAmazon
    contador += 1
contador = 0
productoMediamarkt, PrecioMediamarkt = '',''
while (not productoMediamarkt or not PrecioMediamarkt) and contador < 5:
    proMediamarkt, preMediamarkt = Mediamarkt.get_mediamarkt(barcode)
    if (productoMediamarkt == "" and proMediamarkt != ""):
        productoMediamarkt = proMediamarkt
    if (PrecioMediamarkt == "" and preMediamarkt != ""):
        PrecioMediamarkt = preMediamarkt
    contador += 1
PrecioEbay = Ebay.get_ebay(barcode)

if (productoAmazon != ""):
    print("Producto Amazon")
    print('----------------------------------------')
    print(productoAmazon)
    print('----------------------------------------')
    print('Precio Amazon')
    print('----------------------------------------')
    print(PrecioAmazon)
    print('----------------------------------------')
    if (PrecioMediamarkt != ""):
        print("Precio Mediamarkt")
        print('----------------------------------------')
        print(PrecioMediamarkt)
        print('----------------------------------------')
    if (PrecioEbay != ""):
        print("Precio Ebay")
        print('----------------------------------------')
        print(PrecioEbay)
elif (productoMediamarkt != ""):
    print("Producto Mediamarkt")
    print('----------------------------------------')
    print(productoMediamarkt)
    print('----------------------------------------')
    print('Precio Mediamarkt')
    print('----------------------------------------')
    print(PrecioMediamarkt)
    print('----------------------------------------')
    if (PrecioEbay != ""):
        print("Precio Ebay")
        print('----------------------------------------')
        print(PrecioEbay)
else:
    print("No se ha encontrado el producto")
