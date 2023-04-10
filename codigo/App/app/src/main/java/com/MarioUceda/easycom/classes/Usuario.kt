package com.MarioUceda.easycom.classes

import com.google.gson.annotations.SerializedName

data class Usuario(
    @SerializedName("id") var id: String,
    @SerializedName("name") var nombre: String,
    @SerializedName("email") var email: String,
    @SerializedName("remember_token") var token : String,
    @SerializedName("created_at") var fecha : String,
    @SerializedName("deleted_at") var fechaEliminacion : String
)