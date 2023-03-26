package com.MarioUceda.easycom
import kotlinx.coroutines.*
import java.sql.*

class BBDD {
    companion object {
        private const val hostname = "sql973.main-hosting.eu"
        private const val bbdd = "u388516815_easycom"
        private const val url = "jdbc:mariadb://" + hostname + ":3306/" + bbdd
        private const val user = "u388516815_easycom"
        private const val password = "]5GvdZ12"
        private const val driver = "org.mariadb.jdbc.Driver"

        suspend fun getConnection(): Connection? = withContext(Dispatchers.IO) {
            try {
                Class.forName("org.mariadb.jdbc.Driver")
                DriverManager.getConnection(url, user, password)
            } catch (e: SQLException) {
                e.printStackTrace()
                null
            }
        }
    }

    suspend fun registrarUsuario(email: String, nombre: String, contraseña: String): Boolean = withContext(Dispatchers.IO) {
        val sql = "INSERT INTO usuario (email, nombre, contraseña, es_admin, fecha_creacion) VALUES (?, ?, ?, ?, ?);"
        val esAdmin = false
        val fechaCreacion = Date(System.currentTimeMillis())
        val conn = getConnection()
        println("-$email-$nombre-$contraseña-$esAdmin-$fechaCreacion-")
        println(conn)

        var resultado = false

        conn?.use {
            try {
                val ps = conn.prepareStatement(sql)
                ps.setString(1, email)
                ps.setString(2, nombre)
                ps.setString(3, contraseña)
                ps.setBoolean(4, esAdmin)
                ps.setDate(5, fechaCreacion)

                if (ps.executeUpdate() > 0) {
                    resultado = true
                }

                ps.close()
            } catch (e: SQLException) {
                e.printStackTrace()
            }
        }

        println(resultado)
        resultado
    }
}