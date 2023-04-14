package com.MarioUceda.easycom.classes

import com.google.gson.annotations.SerializedName

data class Usuario(
    @SerializedName("id") var id: String,
    @SerializedName("name") var nombre: String,
    @SerializedName("email") var email: String,
    @SerializedName("created_at") var created_at : String,
)