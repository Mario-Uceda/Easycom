package com.MarioUceda.easycom

import java.io.Serializable
import java.time.LocalDateTime

data class Price(
    var tienda: String,
    var idProducto: String,
    var precio: String,
    var fecha: LocalDateTime

) : Serializable {
    constructor(tienda: String, idProducto: String,  precio: String) : this(tienda, idProducto, precio,  LocalDateTime.now())

    override fun toString(): String {
        return ("tienda: $tienda idProducto: $idProducto precio: $precio fecha: $fecha")
    }
}