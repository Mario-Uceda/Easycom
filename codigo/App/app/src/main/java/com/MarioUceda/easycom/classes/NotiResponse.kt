package com.MarioUceda.easycom.classes

data class NotiResponse(
    val status: String,
    val historials: List<Historial>?,
    val products: List<Producto>?,
    val prices: List<List<Precio>>?,
    val notificaciones: List<Notificacion>?
)
