package com.MarioUceda.easycom.classes

import com.google.gson.annotations.SerializedName

data class Historial(
    @SerializedName("id") var id: String,
    @SerializedName("id_user") var idUsuario: String,
    @SerializedName("id_producto") var idProducto: String,
    @SerializedName("favorito") var favorito: Boolean,
    @SerializedName("created_at") var fecha : String
)