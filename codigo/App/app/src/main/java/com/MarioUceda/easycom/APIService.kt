package com.MarioUceda.easycom

import com.google.gson.JsonObject
import okhttp3.ResponseBody
import retrofit2.Call
import retrofit2.Response
import retrofit2.http.*

interface APIService {
    @GET("token_csrf")
    suspend fun CSRF(): Response<String>
    @POST("login")
    suspend fun iniciarSesion(@Body usuario: JsonObject): Response<ResponseBody>
    @FormUrlEncoded
    @POST("loginMovil")
    suspend fun iniciarSesion(
        @Header("X-CSRF-Token") token: String,
        @Field("email") email: String,
        @Field("password") password: String
    ): Call<ResponseBody>

}
