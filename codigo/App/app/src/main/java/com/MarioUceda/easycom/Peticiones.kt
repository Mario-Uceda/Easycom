package com.MarioUceda.easycom

import com.google.gson.GsonBuilder
import kotlinx.coroutines.*
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import okhttp3.*

class Peticiones {
    private val url = "http://easycom.sytes.net/"
    private var token: String = ""
    private var okHttpClient: OkHttpClient? = null

    /*init{
        getTokenCSRF()
        generarCliente()
    }*/

    /*
    val cookieJar: CookieJar = client.cookieJar

    val cookies: List<Cookie> = cookieJar.loadForRequest(request.url())

    val csrfCookieName = "nombre-de-la-cookie-csrf-en-el-servidor"

    val csrfCookie = cookies.find { cookie -> cookie.name == csrfCookieName }

    if (csrfCookie != null) {
    // El token CSRF está presente en la cookie
    val csrfToken = csrfCookie.value
} else {
    // La cookie CSRF no está presente
}



import okhttp3.*
import java.io.IOException

// Método que devuelve el nombre de la cookie CSRF para una URL dada
fun getCsrfCookieName(url: String): String? {
    val client = OkHttpClient()

    val request = Request.Builder()
        .url(url)
        .build()

    try {
        val response = client.newCall(request).execute()
        val headers = response.headers()

        // Busca el nombre de la cookie CSRF en las cabeceras de la respuesta
        for (i in 0 until headers.size()) {
            val headerName = headers.name(i)
            if (headerName.equals("Set-Cookie", true)) {
                val cookieHeader = headers.value(i)
                val cookies = Cookie.parseAll(request.url(), cookieHeader)
                for (cookie in cookies) {
                    if (cookie.name().startsWith("XSRF")) {
                        return cookie.name()
                    }
                }
            }
        }
    } catch (e: IOException) {
        // Maneja cualquier excepción de red que pueda ocurrir
        e.printStackTrace()
    }

    // Devuelve null si no se encuentra la cookie CSRF
    return null
}



val url = "https://tu-servidor.com"
val csrfCookieName = getCsrfCookieName(url)

if (csrfCookieName != null) {
    println("El nombre de la cookie CSRF es: $csrfCookieName")
} else {
    println("No se encontró la cookie CSRF en la URL proporcionada.")
}


     */

    private fun getRetrofit():Retrofit{
        val gson = GsonBuilder().setLenient().create()
        return Retrofit.Builder()
            .baseUrl(url)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build()
    }

    private fun getTokenCSRF() {
        CoroutineScope(Dispatchers.IO).launch {
            val call = getRetrofit().create(APIService::class.java).CSRF()
            val result = call.body()
            if (call.isSuccessful) {
                println("Peticion correcta")
                println(result)
                token = result.toString()
            } else {
                println("Peticion incorrecta")
                token = ""
            }
        }
    }

    private fun generarCliente(){
        okHttpClient = OkHttpClient.Builder()
            .addInterceptor { chain ->
                val request = chain.request().newBuilder()
                    .addHeader("X-CSRF-Token", token)
                    .build()
                chain.proceed(request)
            }
            .build()
    }

    private fun getRetrofitCSRF():Retrofit{
        val gson = GsonBuilder().setLenient().create()
        return Retrofit.Builder()
            .baseUrl(url)
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build()
    }

    fun iniciarSesion(email: String, password: String){
        getTokenCSRF()
        generarCliente()
        CoroutineScope(Dispatchers.IO).launch {
            val call = getRetrofitCSRF().create(APIService::class.java).iniciarSesion(token, email, password).execute()
            val result = call.body()
            if (call.isSuccessful) {
                println("Peticion correcta")
                println(result)
            } else {
                println("Peticion incorrecta")
                println("Token: $token")
                println("call: $call")
                println("result: $result")
                println("call.isSuccessful: ${call.isSuccessful}")
                println("call.code(): ${call.code()}")
                println("call.message(): ${call.message()}")
                println("call.errorBody(): ${call.errorBody()}")
                println("call.headers(): ${call.headers()}")
                println("call.raw(): ${call.raw()}")
                println("call.body(): ${call.body()}")

            }
        }
    }

}
/*
    suspend fun iniciarSesion(email: String, password: String): Int {
        return withContext(Dispatchers.IO) {
            val loginRequest = LoginRequest(email, password, token)
            val call = getRetrofit().create(APIService::class.java).iniciarSesion(loginRequest)
            val response = call.body()
            if (call.isSuccessful && response != null) {
                println("Peticion correcta")
                0
            } else {
                println("Peticion incorrecta")
                -1
            }
        }
    }

}
    /*
    fun getTokenCSRF(){
        CoroutineScope(Dispatchers.IO).launch {
            val call = getRetrofit().create(APIService::class.java).CSRF("token_csrf")
            val result = call.body()
            if(call.isSuccessful){
                println("Peticion correcta")
                println(result)
                token = result.toString()
            }else{
                println("Peticion incorrecta")
            }
        }
    }

    fun iniciarSesion(email: String, password: String){
        CoroutineScope(Dispatchers.IO).launch {
            val call = getRetrofit().create(APIService::class.java).login("loginMovil")
            val result = call.body()
            if(call.isSuccessful){
                println("Peticion correcta")
            }else{
                println("Peticion incorrecta")
            }
        }
    }

*/
/*
    fun iniciarSesion2(email: String, password: String): Deferred<Int> = CoroutineScope(Dispatchers.IO).async {
        val urlLogin = "$url/loginMovil"
        val logged = CompletableDeferred<Int>()
        println("Email: $email")
        println("Password: $password")
        println("Url: $urlLogin")
        println("Token: $token")
        try {
            val requestBody = MultipartBody.Builder()
                .setType(MultipartBody.FORM)
                .addFormDataPart("email", email)
                .addFormDataPart("password", password)
                .build()

            val request = Request.Builder()
                .url(urlLogin)
                .addHeader("X-CSRF-TOKEN", token)
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
    //}*/