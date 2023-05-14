package com.MarioUceda.easycom.classes

data class ProdResponse(
    val status: String,
    val product: Producto?,
    val price: ArrayList<Precio>?,
    val favorito: Historial?,
)
