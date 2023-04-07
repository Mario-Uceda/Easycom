import json
import sys
from bs4 import BeautifulSoup
import requests

def get_amazon(product_id):
    urlProduct = get_product_amazon(product_id)
    if urlProduct != "":
        data = get_product_data_amazon(urlProduct)
        return data
    else:
        return ["product_name", "img", "descriptor", "specs", "price"]

def get_product_amazon(product_id):
    url_amazon = "https://www.amazon.es/"
    url_search = url_amazon + "s?k=" + product_id
    try:
        response = requests.get(url_search)
        soup = BeautifulSoup(response.text, 'html.parser')
        url_product = soup.select_one("h2 > a")['href']
        return url_amazon + url_product
    except Exception as e:
        print(e)
        return ""


def get_product_data_amazon(url):
    try:
        response = requests.get(url)
        soup = BeautifulSoup(response.text, 'html.parser')

        product_name = soup.select_one("#productTitle").text
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
            specs = "The table does not exist on the page"
        return [product_name, img, descriptor, specs, price]
    except Exception as e:
        print(e)
        return ["product_name", "img", "descriptor", "specs", "price"]


if __name__ == '__main__':
    # Get the function name from the command line argument
    function_name = sys.argv[1]
    # Get the product ID from the command line argument
    product_id = sys.argv[2]
    # Call the function with the product ID as a parameter
    result = globals()[function_name](product_id)
    # Convert the result to a JSON string and print it so it can be captured by the Laravel controller method
    print(json.dumps(result))
