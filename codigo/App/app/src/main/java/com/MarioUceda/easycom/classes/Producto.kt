package com.MarioUceda.easycom.classes

import com.google.gson.annotations.SerializedName
import java.io.Serializable

data class Producto(
    @SerializedName("id") var id: String,
    @SerializedName("barcode") var barcode: String,
    @SerializedName("nombre") var name: String,
    @SerializedName("especificaciones_tecnicas") var especificaciones : String,
    @SerializedName("url_img") var img : String,
    @SerializedName("descripcion") var description: String,
    @SerializedName("created_at") var fecha : String
) : Serializable