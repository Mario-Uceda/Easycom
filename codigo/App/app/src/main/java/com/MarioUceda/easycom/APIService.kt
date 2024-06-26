package com.MarioUceda.easycom

import com.MarioUceda.easycom.classes.*
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
        @Field("id") id: String,
        @Field("idProducto") idProducto: String
    ): Response<ProdResponse>

    @FormUrlEncoded
    @POST("historial")
    suspend fun getHistorial(
        @Field("id_usuario") id: String,
        @Field("favorito") favorito: Int
    ): Response<HistResponse>

    @FormUrlEncoded
    @POST("producto/{id}/favorito")
    suspend fun changeFav(
        @Path("id") idProducto: String,
        @Field("id") idFavorite: String
    ): Response<FavResponse>

    @FormUrlEncoded
    @POST("notificaciones")
    suspend fun getNotificaciones(
        @Field("id_usuario") id: String,
    ): Response<NotiResponse>


}
