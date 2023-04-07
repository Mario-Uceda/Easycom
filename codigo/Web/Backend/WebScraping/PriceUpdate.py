#! /usr/bin/python python
import sys
import Amazon

url = sys.argv[1]
tienda = sys.argv[2]
if(tienda == "Amazon"):
    amazonPrice = Amazon.get_price(url)
