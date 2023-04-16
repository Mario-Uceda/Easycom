import random
from bs4 import BeautifulSoup as bs
import requests

def get_user_agent():
    #Función que devuelve un User-Agent aleatorio
    with open('../WebScraping/UserAgents.txt', 'r') as f:
        user_agents = f.read().splitlines()
        agent = random.choice(user_agents)
        return agent
    
def get_proxy():
    response = requests.get('http://pubproxy.com/api/proxy?format=json')
    return response.json()

def get_soup(url_product):
    #Función que devuelve el objeto soup de un producto
    try:
        headers = {'User-Agent': get_user_agent()}
        response = requests.get(url_product, headers=headers, proxies=get_proxy())
        soup = bs(response.text, 'html.parser')
        return soup
    except Exception as e:
        print(e)
        return ""