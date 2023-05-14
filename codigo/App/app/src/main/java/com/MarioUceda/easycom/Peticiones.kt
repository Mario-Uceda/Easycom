package com.MarioUceda.easycom

import android.content.Context
import com.MarioUceda.easycom.classes.*
import com.google.gson.GsonBuilder
import kotlinx.coroutines.*
import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.util.concurrent.TimeUnit


class Peticiones(context: Context?) {
    private val url = "http://easycom.sytes.net/api/"
    private val sessionManager = SharedPreferences(context)

    // Funcion para crear el objeto retrofit
    private fun getRetrofit(): Retrofit {
        val okHttpClient = OkHttpClient.Builder()
            .readTimeout(30, TimeUnit.SECONDS)
            .connectTimeout(30, TimeUnit.SECONDS)
            .build()

        val gson = GsonBuilder().setLenient().create()
        return Retrofit.Builder()
            .baseUrl(url)
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build()
    }

    /*
    * Funciones para autenticacion
     */

    // Funcion para iniciar sesion
    fun iniciarSesion(email: String, password: String, callback: (AuthResponse) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            val call = getRetrofit().create(APIService::class.java).iniciarSesion(email, password)
            val result = call.body()
            withContext(Dispatchers.Main) {
                if (call.isSuccessful && result != null && result.status == "ok") {
                    println("result: $result")
                    sessionManager.saveUserData(result.user!!)
                    callback(result)
                }else{
                    callback(AuthResponse("error", null))
                }
            }
        }
    }

    // Funcion para registrar un usuario
    fun registrar(nombre: String, email: String, password: String, callback: (AuthResponse) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            val call =getRetrofit().create(APIService::class.java).registrar(nombre, email, password)
            val result = call.body()
            withContext(Dispatchers.Main) {
                if (call.isSuccessful && result != null && result.status == "ok") {
                    println("result: $result")
                    sessionManager.saveUserData(result.user!!)
                    callback(result)
                }else{
                    callback(AuthResponse("error", null))
                }
            }
        }
    }

    // Funcion para cerrar sesion
    fun cerrarSesion() {
        sessionManager.clearUserData()
    }

    /*
    * Funciones para buscar productos
     */

    // Funcion para buscar un producto
    fun buscarProducto(barcode: String, idProducto: String, callback: (ProdResponse) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            var email : String = ""
            var id : String  = ""
            if(sessionManager.isUserLogged()) {
                email = sessionManager.getUserData().email
                id = sessionManager.getUserData().id
            }
            val call = getRetrofit().create(APIService::class.java).buscarProductos(barcode, email, id, idProducto)
            val result = call.body()

            withContext(Dispatchers.Main) {
                if (call.isSuccessful && result != null && result.status == "ok") {
                    println("result: $result")
                    callback(result)
                }else{
                    callback(ProdResponse("error", null, null, null))
                }
            }

        }
    }

    //Funcion para obtener el historial de busqueda
    fun getHistorial(favorito : Int, callback: (HistResponse) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            var id : String  =""
            if(sessionManager.isUserLogged()) {
                id = sessionManager.getUserData().id
            }
            val call = getRetrofit().create(APIService::class.java).getHistorial(id, favorito)
            val result = call.body()
            withContext(Dispatchers.Main) {
                if (call.isSuccessful && result != null && result.status == "ok") {
                    println("result: $result")
                    callback(result)
                }else{
                    callback(HistResponse("error", null, null, null))
                }
            }
        }
    }
    //Funcion para cambiar el favorito
    fun cambiarFavorito(itemFavorito : Historial, callback: (FavResponse) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            var idProducto = itemFavorito.idProducto
            var idFavorite = itemFavorito.id
            val call = getRetrofit().create(APIService::class.java).changeFav(idProducto, idFavorite)
            val result = call.body()
            withContext(Dispatchers.Main) {
                if (call.isSuccessful && result != null && result.status == "ok") {
                    println("result: $result")
                    callback(result)
                }else{
                    callback(FavResponse("error", null))
                }
            }

        }
    }

    //Funcion para obtener las notificaciones
    fun getNotificaciones(callback: (NotiResponse) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            var id : String  =""
            if(sessionManager.isUserLogged()) {
                id = sessionManager.getUserData().id
            }
            val call = getRetrofit().create(APIService::class.java).getNotificaciones(id)
            val result = call.body()

            withContext(Dispatchers.Main) {
                if (call.isSuccessful && result != null && result.status == "ok") {
                    println("result: $result")
                    callback(result)
                }else{
                    callback(NotiResponse("error", null, null, null,null))
                }
            }
        }
    }
}