import random
from bs4 import BeautifulSoup as bs
import requests
import warnings
warnings.filterwarnings("ignore", category=UserWarning)

file = 'WebScraping/UserAgents.txt'

def get_user_agent():
    #Función que devuelve un User-Agent aleatorio
    with open(file, 'r') as f:
        user_agents = f.read().splitlines()
        agent = random.choice(user_agents)
        return {'User-Agent': agent}
    
def get_proxy():
    response = requests.get('http://pubproxy.com/api/proxy?format=json')
    return response.json()

def get_soup(url_product):
    #Función que devuelve el objeto soup de un producto
    try:
        response = requests.get(url_product, headers=get_user_agent())
        soup = bs(response.text, 'html.parser', from_encoding="utf-8")
        return soup
    except Exception as e:
        print(e)
        return ""
