#! /usr/bin/python python
import json
import sys
from bs4 import BeautifulSoup as bs
import UserAgents as ua
import requests

url_amazon = "https://www.amazon.es/"


def get_amazon(barcode):
    url_product = get_amazon_id(barcode)
    if url_product != "":
        get_amazon_data(url_product)
    else:
        print("El producto no existe en Amazon")


def get_amazon_id(barcode):
    url_search = url_amazon + "s?k=" + barcode
    try:
        headers = {'User-Agent': ua.get_user_agent()}
        response = requests.get(url_search, headers=headers)
        soup = bs(response.text, 'html.parser')
        url = soup.select_one('h2 > a')['href']
        return url_amazon + url
    except Exception as e:
        print(e)
        return ""

def get_amazon_data(url_product):
    try:
        headers = {'User-Agent': ua.get_user_agent()}
        response = requests.get(url_product, headers=headers)
        soup = bs(response.text, 'html.parser')
        product_name = soup.select_one('#productTitle').text
        img = soup.select_one("#imgTagWrapperId img")['src']
        descriptor = soup.select_one("#feature-bullets > ul > li").text
        decimal = soup.select_one(".a-price-whole").text.split(",")[0]
        fraction = soup.select_one(".a-price-fraction").text.split(" ")[0]
        price = float(decimal + "." + fraction)
        specs = ""
        try:
            table = soup.select_one("#productDetails_techSpec_section_1")
            rows = table.select("tr")
            for row in rows:
                cells = row.select("td, th")
                attribute = cells[0].text
                value = cells[1].text
                specs += attribute + ": " + value + "\n"
        except Exception as e:
            specs = ""
        producto_amazon = json.dumps([product_name, img, descriptor, specs])
        precio_amazon = json.dumps([url_product, "Amazon", price])
        print(producto_amazon)
        print(precio_amazon)
    except Exception as e:
        print(e)

def get_price(url_product):
    try:
        headers = {'User-Agent': ua.get_user_agent()}
        response = requests.get(url_product, headers=headers)
        soup = bs(response.text, 'html.parser')
        decimal = soup.select_one(".a-price-whole").text.split(",")[0]
        fraction = soup.select_one(".a-price-fraction").text.split(" ")[0]
        price = float(decimal + "." + fraction)
        precio_amazon = json.dumps([url_product, "Amazon", price])
        print(precio_amazon)
    except Exception as e:
        print(e)
