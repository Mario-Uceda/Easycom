package com.MarioUceda.easycom

import android.content.Context
import com.MarioUceda.easycom.classes.AuthResponse
import com.MarioUceda.easycom.classes.HistResponse
import com.MarioUceda.easycom.classes.ProdResponse
import com.MarioUceda.easycom.classes.SharedPreferences
import com.google.gson.Gson
import com.google.gson.GsonBuilder
import com.google.gson.reflect.TypeToken
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
            if (call.isSuccessful && result != null && result.status == "ok") {
                sessionManager.saveUserData(result.user!!)
            }
            withContext(Dispatchers.Main) {
                callback(result!!)
            }
        }
    }

    // Funcion para registrar un usuario
    fun registrar(nombre: String, email: String, password: String, callback: (AuthResponse) -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            val call =getRetrofit().create(APIService::class.java).registrar(nombre, email, password)
            val result = call.body()
            if (call.isSuccessful && result != null && result.status == "ok") {
                sessionManager.saveUserData(result.user!!)
            }
            withContext(Dispatchers.Main) {
                callback(result!!)
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

            if (call.isSuccessful && result != null && result.status == "ok") {
                println("result: $result")
            }
            withContext(Dispatchers.Main) {
                callback(result!!)
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
            if (call.isSuccessful && result != null && result.status == "ok") {
                println("result: $result")
            }
            withContext(Dispatchers.Main) {
                callback(result!!)
            }
        }
    }
}