import random
from bs4 import BeautifulSoup as bs
import requests
import ssl
url_amazon = "https://www.amazon.es/s"

def get_user_agent():
    #Función que devuelve un User-Agent aleatorio
    with open('D:/Users/Mario/Documents/Personal/Universidad/Easycom/codigo/Web/backend/WebScraping/UserAgents.txt', 'r') as f:
        user_agents = f.read().splitlines()
        agent = random.choice(user_agents)
        return agent


def get_proxy():
    response = requests.get('http://pubproxy.com/api/proxy?format=json')
    return response.json()


def get_soup(barcode):
    #Función que devuelve el objeto soup de un producto
    try:
        headers = {'User-Agent': get_user_agent(), 'Accept': '*/*'}
        response = requests.get(url_amazon , headers=headers, params={'k': barcode}, proxies=get_proxy())
        soup = bs(response.text, 'html.parser')
        return soup
    except Exception as e:
        print(e)
        return ""

'''
#barcode = '6972453163820'
barcode = '8410581000000'

try:
    soup = get_soup(barcode)
    print(soup)
    #url = soup.select_one('h2 > a')['href']
    #url_product = url_amazon + url
except Exception as e:
    print(e)

'''
reqUrl = "https://www.amazon.es/s?k=6972453163820"

headersList = {"User-Agent": get_user_agent()}
print (headersList)
payload = ""

#response = requests.request("GET", reqUrl,  headers=headersList)
response = requests.get(reqUrl, headers=headersList)
print(response.text)
