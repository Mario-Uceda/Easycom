package com.MarioUceda.easycom.classes

import com.google.gson.annotations.SerializedName
import java.io.Serializable

data class Notificacion (
    @SerializedName("id") var id: String,
    @SerializedName("id_producto") var idProducto: String,
    @SerializedName("precio_anterior") var precio_anterior: Double,
    @SerializedName("precio_actual") var precio_actual: Double,
    @SerializedName("modified_at") var modified_at : String,
) : Serializable