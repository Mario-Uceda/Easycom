package com.MarioUceda.easycom

import kotlinx.coroutines.*
import okhttp3.*
import okio.IOException
import java.sql.Date
import java.util.*

class BBDD {

    private val url = "https://mariouceda.es/classes/peticion.class.php"
    private val cliente = OkHttpClient()

    fun registrarUsuario(email: String, username: String, password: String): Deferred<Boolean> = CoroutineScope(Dispatchers.IO).async {
        val registrado = CompletableDeferred<Boolean>()
        val fecha_hoy = Date(Calendar.getInstance().timeInMillis)
        val formBody = FormBody.Builder()
            .add("metodo", "registrarUsuario")
            .add("nombre", username)
            .add("email", email)
            .add("contraseña", password)
            .add("es_admin", "false")
            .add("fecha_cracion", fecha_hoy.toString())
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
                    registrado.complete(true)
                }else {
                    registrado.complete(false)
                }
            }
        })
        registrado.await()
    }

}
