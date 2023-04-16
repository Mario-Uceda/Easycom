#! /usr/bin/python python
import sys
import Amazon

barcode = '6972453163820'
#barcode = sys.argv[1]
amazonData = Amazon.get_amazon(barcode)
