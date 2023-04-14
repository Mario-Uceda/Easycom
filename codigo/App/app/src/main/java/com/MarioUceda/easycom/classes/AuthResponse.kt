package com.MarioUceda.easycom.classes

data class AuthResponse(
    val status: String,
    val message: String,
    val user: Usuario?
)