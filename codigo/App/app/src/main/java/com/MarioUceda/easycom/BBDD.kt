package com.MarioUceda.easycom

import kotlinx.coroutines.*
import okhttp3.*
import okio.IOException

class BBDD {

    private val cliente = OkHttpClient()

    fun registrarUsuario(email: String, username: String, password: String): Deferred<Boolean> = CoroutineScope(Dispatchers.IO).async {
        val url = "https://easycom.mariouceda.es/backend/api/registrar"
        val registrado = CompletableDeferred<Boolean>()
        val formBody = FormBody.Builder()
            .add("nombre", username)
            .add("email", email)
            .add("contraseña", password)
            .add("es_admin", "0")
            .build()

        val request = Request.Builder()
            .url(url)
            .post(formBody)
            .build()

        cliente.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                e.printStackTrace()
            }

            override fun onResponse(call: Call, respuesta: Response) {
                if (respuesta.isSuccessful) {
                    val respuestaBody = respuesta.body?.string()
                    println(respuestaBody)
                    registrado.complete(true)
                }else {
                    registrado.complete(false)
                }
            }
        })
        registrado.await()
    }

    fun iniciarSesion(email: String, password: String): Deferred<Int> = CoroutineScope(Dispatchers.IO).async {
        val url = "https://easycom.mariouceda.es/backend/api/login"
        val logueado = CompletableDeferred<Int>()

        val formBody = FormBody.Builder()
            .add("email", email)
            .add("contraseña", password)
            .build()

        val request = Request.Builder()
            .url(url)
            .post(formBody)
            .build()

        cliente.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                e.printStackTrace()
            }

            override fun onResponse(call: Call, respuesta: Response) {
                if (respuesta.isSuccessful) {
                    val respuestaBody = respuesta.body?.string()
                    println(respuestaBody)
                    val regex = """"id_usuario"\s*:\s*(\d+)""".toRegex()
                    val matchResult = regex.find(respuestaBody.toString())
                    val idUsuario = matchResult?.groups?.get(1)?.value?.toInt()
                    if(idUsuario != null){
                        logueado.complete(idUsuario)
                    }else{
                        logueado.complete(0)
                    }

                }else {
                    logueado.complete(-1)
                }
            }
        })
        logueado.await()
    }

}
