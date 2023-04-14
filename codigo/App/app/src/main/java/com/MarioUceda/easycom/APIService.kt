package com.MarioUceda.easycom

import com.MarioUceda.easycom.classes.AuthResponse
import com.MarioUceda.easycom.classes.ProdResponse
import retrofit2.Response
import retrofit2.http.*

interface APIService {

    @FormUrlEncoded
    @POST("login")
    suspend fun iniciarSesion(
        @Field("email") email: String,
        @Field("password") password: String
    ): Response<AuthResponse>


    @FormUrlEncoded
    @POST("register")
    suspend fun registrar(
        @Field("name") name: String,
        @Field("email") email: String,
        @Field("password") password: String
    ): Response<AuthResponse>

    @FormUrlEncoded
    @POST("buscarProducto")
    suspend fun buscarProductos(
        @Field("barcode") barcode: String,
        @Field("email") email: String,
        @Field("id") id: String
    ): Response<ProdResponse>

}
