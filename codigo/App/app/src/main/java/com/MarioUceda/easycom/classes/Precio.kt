package com.MarioUceda.easycom.classes

import com.google.gson.annotations.SerializedName
import java.io.Serializable

data class Precio (
    @SerializedName("id") var id: String,
    @SerializedName("id_producto") var idProducto: String,
    @SerializedName("precio") var precio: Double,
    @SerializedName("tienda") var tienda: String,
    @SerializedName("url_producto") var urlProducto : String,
    @SerializedName("created_at") var fecha : String
) : Serializable