<?php

require 'db.class.php';

/**
 * Clase que extiende de la clase Db y que contiene todas las consultas
 * a la base de datos
 * 
 */

class Consultas extends Db
{
    // FUNCIONES DE VALIDACIÓN
    public function validarDatos($datos)
    {
        // Validar los datos de entrada para prevenir SQL Injection
        // usuario
        $id_usuario = preg_replace("/[^0-9]/", "", $datos['id_usuario']);
        $nombre = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['nombre']);
        $email = filter_var($datos['email'], FILTER_SANITIZE_EMAIL);
        $email = mysqli_real_escape_string($this->conectar(), $email);
        $contraseña = mysqli_real_escape_string($this->conectar(), $datos['contraseña']);
        $es_admin = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['es_admin']);
        $fecha_creacion = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['fecha_creacion']);
        $fecha_modificacion = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['fecha_modificacion']);
        $fecha_eliminacion = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['fecha_eliminacion']);
        // producto
        $id_producto = preg_replace("/[^0-9]/", "", $datos['id_producto']);
        $barcode = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['barcode']);
        $nombre_producto = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['nombre_producto']);
        $descripcion = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['descripcion']);
        $url_img = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['url_img']);
        $especificaciones_tecnicas = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['especificaciones_tecnicas']);
        // precio
        $id_precio = preg_replace("/[^0-9]/", "", $datos['id_precio']);
        $precio = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['precio']);
        $tienda = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['tienda']);
        $url_producto = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['url_producto']);
        // historial
        $id_historial = preg_replace("/[^0-9]/", "", $datos['id_historial']);
        $es_favorito = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['es_favorito']);



        return [
            'id_usuario' => $id_usuario,
            'nombre' => $nombre,
            'email' => $email,
            'contraseña' => $contraseña,
            'es_admin' => $es_admin,
            'fecha_creacion' => $fecha_creacion,
            'fecha_modificacion' => $fecha_modificacion,
            'fecha_eliminacion' => $fecha_eliminacion,
            'id_producto' => $id_producto,
            'barcode' => $barcode,
            'nombre_producto' => $nombre_producto,
            'descripcion' => $descripcion,
            'url_img' => $url_img,
            'especificaciones_tecnicas' => $especificaciones_tecnicas,
            'id_precio' => $id_precio,
            'precio' => $precio,
            'tienda' => $tienda,
            'url_producto' => $url_producto,
            'id_historial' => $id_historial,
            'es_favorito' => $es_favorito
        ];
    }


    // FUNCIONES DE USUARIOS

    /**
     * Función para registrar un nuevo usuario
     */
    public function guardarUsuario($datos)
    {
        // Validar los datos
        $datosValidados = $this->validarDatos($datos);

        // Insertar en la base de datos
        $sql = "INSERT INTO usuario(email, nombre, contraseña, es_admin, fecha_creacion) VALUES(?,?,?,?,?)";
        $conexion = $this->conectar();
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param(
            'sssbs',
            $datosValidados['email'],
            $datosValidados['nombre'],
            $datosValidados['contraseña'],
            $datosValidados['es_admin'],
            $datosValidados['fecha_creacion']
        );
        $ejecucion = $stmt->execute();
        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

}