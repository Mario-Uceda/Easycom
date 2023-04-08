package com.MarioUceda.easycom

import com.bumptech.glide.load.HttpException
import kotlinx.coroutines.*
import okhttp3.*
import okio.IOException

class Peticiones {
    private val client = OkHttpClient()
    private val url = "http://easycom.sytes.net"
    private val token: String

    /*
    * val client = OkHttpClient.Builder()
    .addInterceptor { chain ->
        val original = chain.request()

        // Añade el token de sesión a la solicitud
        val requestBuilder = original.newBuilder()
            .header("Cookie", "laravel_session=$TOKEN_DE_SESION")
            .method(original.method, original.body)

        val request = requestBuilder.build()
        chain.proceed(request)
    }
    .build()
*/

    constructor() {
        this.token =runBlocking {getTokenCSRF().await() }
        println(token)
    }

    fun getTokenCSRF(): Deferred<String> = CoroutineScope(Dispatchers.IO).async {
        val CSRF = CompletableDeferred<String>()
        val urlCSRF = "$url/token_csrf"
        val request = Request.Builder().url(urlCSRF).build()
        client.newCall(request).execute().use { response ->
            if (!response.isSuccessful) throw IOException("Unexpected code $response")

            CSRF.complete( response.body?.string() ?: "")
        }
        CSRF.await()
    }

    fun registrarUsuario(email: String, username: String, password: String): Deferred<Boolean> = CoroutineScope(Dispatchers.IO).async {
        val urlRegister = "$url/register"
        val registrar = CompletableDeferred<Boolean>()
        val formBody = FormBody.Builder().add("nombre", username).add("email", email).add("contraseña", password).build()
        val request = Request.Builder().url(urlRegister).post(formBody).build()

        client.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                e.printStackTrace()
            }

            override fun onResponse(call: Call, respuesta: Response) {
                if (respuesta.isSuccessful) {
                    val requestBody = respuesta.body?.string()
                    println(requestBody)
                    registrar.complete(true)
                }else {
                    registrar.complete(false)
                }
            }
        })
        registrar.await()
    }

    fun iniciarSesion(email: String, password: String): Deferred<Int> = CoroutineScope(Dispatchers.IO).async {
        val urlLogin = "$url/loginMovil"
        val logged = CompletableDeferred<Int>()

        try {
            val requestBody = MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", email)
                .addFormDataPart("password", password)
                .build()

            val request = Request.Builder()
                .url(urlLogin)
                .addHeader("X-CSRF-Token", token)
                .post(requestBody)
                .build()

            val response = client.newCall(request).execute()

            val responseBodyString = response.body?.string()
            if (response.isSuccessful) {
                println(responseBodyString)
                println("Login correcto")
                logged.complete(0)
            } else {
                println(responseBodyString)
                println("Login incorrecto")
                logged.complete(-1)
            }
        } catch (e: IOException) {
            println("Error de conexión: ${e.message}")
            logged.complete(-1)
        } catch (e: HttpException) {
            println("Error en la respuesta del servidor: ${e.message}")
            logged.complete(-1)
        }

        logged.await()
    }

        /*cliente.newCall(request).enqueue(object : Callback {
            override fun onFailure(call: Call, e: IOException) {
                e.printStackTrace()
            }

            override fun onResponse(call: Call, respuesta: Response) {
                if (respuesta.isSuccessful) {
                    val requestBody = respuesta.body?.string()
                    println(requestBody)

                    /*val regex = """"id_usuario"\s*:\s*(\d+)""".toRegex()
                    val matchResult = regex.find(requestBody.toString())
                    val idUsuario = matchResult?.groups?.get(1)?.value?.toInt()
                    if(idUsuario != null){
                        logged.complete(idUsuario)
                    }else{
                        logged.complete(0)
                    }*/
                    logged.complete(0)
                }else {
                    logged.complete(-1)
                }
            }
        })
        logged.await()*/
    //}
}