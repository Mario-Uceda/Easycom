#! /usr/bin/python python
import requests
import sys
import Amazon
import Mediamarkt
import Ebay
import json

reqUrl = "http://easycom.sytes.net/api/obtenerPrecios"
response = requests.request("GET", reqUrl)
lista = json.loads(response.text)

tiendas = {
    "Amazon": Amazon.update_price,
    "Mediamarkt": Mediamarkt.update_price,
    "Ebay": Ebay.update_price
}

for precio in lista:
    id_producto = precio.get("id_producto")
    tienda = precio.get("tienda")
    url = precio.get("url_producto")
    print(precio.get("id_producto"))
    print(precio.get("tienda"))
    print(precio.get("url_producto"))

    for i in range(5):
        if tienda in tiendas:
            precio = tiendas[tienda](url)
            if precio:
                break

    postUrl = "http://easycom.sytes.net/api/guardarPrecio"
    payload = {
        "id_producto": id_producto,
        "tienda": tienda,
        "url_producto": url,
        "precio": precio
    }
    response = requests.request("POST", postUrl, data=payload)