package com.MarioUceda.easycom.classes

import com.google.gson.annotations.SerializedName
import java.io.Serializable

data class Notificacion (
    @SerializedName("id") var id: String,
    @SerializedName("id_producto") var idProducto: String,
    @SerializedName("precio_minimo") var precio_minimo: Double,
    @SerializedName("precio_actual") var precio_actual: Double,
    @SerializedName("updated_at") var updated_at : String,
) : Serializable