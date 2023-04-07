import random

def get_user_agent():
    #Función que devuelve un User-Agent aleatorio
    with open('../WebScraping/UserAgents.txt', 'r') as f:
        user_agents = f.read().splitlines()
        agent = random.choice(user_agents)
        return agent
