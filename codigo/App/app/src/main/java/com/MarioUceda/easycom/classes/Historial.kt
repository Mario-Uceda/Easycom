package com.MarioUceda.easycom.classes

import com.google.gson.annotations.SerializedName
import java.io.Serializable

data class Historial(
    @SerializedName("id") var id: String,
    @SerializedName("id_user") var idUsuario: String,
    @SerializedName("id_producto") var idProducto: String,
    @SerializedName("favorito") var favorito: Int ,
    @SerializedName("created_at") var fecha : String
) : Serializable