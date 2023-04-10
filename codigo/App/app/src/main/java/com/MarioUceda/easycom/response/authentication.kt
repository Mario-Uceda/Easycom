package com.MarioUceda.easycom.response

import com.MarioUceda.easycom.classes.Usuario

data class authentication (val token: String, val user: Usuario)