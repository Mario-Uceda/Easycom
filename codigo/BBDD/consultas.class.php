<?php

require 'db.class.php';

/**
 * Clase que extiende de la clase Db y que contiene todas las consultas
 * a la base de datos
 * 
 */

class Consultas extends Db
{

    // FUNCIONES DE USUARIOS

    /**
     * Función para registrar un nuevo usuario
     */

    public function guardarUsuario($datos)
    {
        // Validar los datos de entrada para prevenir SQL Injection
        $nombre = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['nombre']);
        $email = filter_var($datos['email'], FILTER_SANITIZE_EMAIL);
        $email = mysqli_real_escape_string($this->conectar(), $email);
        $password = mysqli_real_escape_string($this->conectar(), $datos['password']);
        $admin = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['admin']);
        $fecha_creacion = preg_replace("/[^a-zA-Z0-9\s.,@'\"-]/", "", $datos['fecha_creacion']);
        // Insertar en la base de datos
        $sql = "INSERT INTO usuario(email, nombre, contraseña, es_admin, fecha_creacion) VALUES(?,?,?,?,?)";
        $conexion = $this->conectar();
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('sssbs', $email, $nombre, $password, $admin, $fecha_creacion);
        $ejecucion = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ejecucion;
    }

    /**
     * Función para obtener todos los usuarios
     */
    public function getUsuarios()
    {
        $sql = "SELECT * FROM usuarios ORDER BY admin DESC;";
        $conexion = $this->conectar();

        $resultado = $conexion->query($sql);
        $usuarios = $resultado->fetch_all(MYSQLI_BOTH);

        $conexion->close();

        return $usuarios;
    }

    /**
     * Función para obtener los usuarios activos
     */
    public function getUsuariosActivos()
    {
        $sql = "SELECT * FROM usuarios WHERE activo = 1 ORDER BY admin DESC;";
        $conexion = $this->conectar();

        $resultado = $conexion->query($sql);
        $usuarios = $resultado->fetch_all(MYSQLI_BOTH);

        $conexion->close();

        return $usuarios;
    }

    /**
     * Función para actualizar los datos de un ususuario
     */
    public function actualizarUsuario($nombreUsuario, $apellidosUsuario, $telefonoUsuario, $emailUsuario, $rolUsuario, $id)
    {
        $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, telefono = ?, email = ?, admin = ? WHERE id= ?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssssii', $nombreUsuario, $apellidosUsuario, $telefonoUsuario, $emailUsuario, $rolUsuario, $id);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para actualizar la contraseña del usuario
     */
    public function actualizarPasswordUsuario($idUsuario, $nuevoPassword)
    {
        $sql = "UPDATE usuarios SET password= ? WHERE id= ?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('si', $nuevoPassword, $idUsuario);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para obtener el ID y el Nombre de un usuario a partir de su email
     * y contraseña 
     */
    public function getDatosUsuarioConEmailPassword($email, $password)
    {
        $sql = "SELECT * FROM usuarios WHERE email=? && password=? && activo=1 LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Función para obtener el email y la password de un usuario con un cierto id
     * y una contraseña
     */
    public function getDatosUsuarioConID($idUsuario)
    {
        $sql = "SELECT * FROM usuarios WHERE id = ? LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $idUsuario);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Función para obtener los datos de un usuario a través de un email
     */
    public function getDatosUsuarioConEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Función para comprobar si existe un usuario con un id y una cookie
     */
    public function comprobarCookieUsuario($id, $cookie)
    {
        $sql = "SELECT * FROM usuarios where id=? && cookie=? LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $id, $cookie);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $usuario;
    }

    /**
     * Funcion para desactivar un usuario en la base de datos
     */
    public function desactivarUsuario($id)
    {
        $sql1 = "UPDATE usuarios SET activo=0 WHERE id=?";
        $conexion = $this->conectar();

        $conexion->autocommit(FALSE);

        $stmt1 = $conexion->prepare($sql1);
        $stmt1->bind_param('i', $id);

        if (!$stmt1->execute()) {
            $conexion->rollback();
            return false;
        }

        $sql2 = "UPDATE registros SET activo=0 WHERE idusuario=?";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bind_param('i', $id);

        if (!$stmt2->execute()) {
            $conexion->rollback();
            return false;
        }

        $conexion->commit();

        $stmt1->close();
        $stmt2->close();
        $conexion->close();

        return true;
    }

    /**
     * Función para reactivar una cuenta de usuario
     */
    public function reactivarUsuario($id)
    {
        $sql = "UPDATE usuarios SET activo=1 WHERE id=?";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }


    // FUNCIONES DE CENTROS
    /**
     * Función para obtener todos los centros
     */
    public function getCentros()
    {
        $sql = "SELECT * FROM centros;";
        $conexion = $this->conectar();

        $resultado = $conexion->query($sql);
        $centros = $resultado->fetch_all(MYSQLI_BOTH);

        $conexion->close();

        return $centros;
    }

    /**
     * Función para obtener todos los centros activos
     */
    public function getCentrosActivos()
    {
        $sql = "SELECT * FROM centros WHERE activo=1;";
        $conexion = $this->conectar();

        $resultado = $conexion->query($sql);
        $centros = $resultado->fetch_all(MYSQLI_BOTH);

        $conexion->close();

        return $centros;
    }

    /**
     * Función para obtener los datos de un centro
     */
    public function getDatosCentro($id)
    {
        $sql = "SELECT * FROM centros WHERE id=? LIMIT 1";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $centro = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $centro;
    }

    /**
     * Función para actualizar los datos de un centro a partir de su id
     */
    public function actualizarCentro($idCentro, $nombreCentro, $direccionCentro, $poblacionCentro, $tlfnCentro, $idActual)
    {
        $sql = "UPDATE centros SET id = ?, nombre = ?, direccion = ?, poblacion = ?, telefono = ? WHERE id = $idActual;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("isssi", $idCentro, $nombreCentro, $direccionCentro, $poblacionCentro, $tlfnCentro);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para guardar un centro en la base de datos
     */

    public function guardarCentro($idCentro, $nombreCentro, $direccionCentro, $poblacionCentro, $tlfnCentro)
    {
        $sql = "INSERT INTO centros(id, nombre, direccion, poblacion, telefono, activo) VALUES(?,?,?,?,?,?);";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $activo = 1;
        $stmt->bind_param('isssii', $idCentro, $nombreCentro, $direccionCentro, $poblacionCentro, $tlfnCentro, $activo);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para eliminar un centro a partir de su id
     */
    public function desactivarCentro($id)
    {
        $sql = "UPDATE centros SET activo=0 WHERE id=?";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para reactivar un centro
     */
    public function reactivarCentro($id)
    {
        $sql = "UPDATE centros SET activo=1 WHERE id=?";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('i', $id);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    // FUNCIONES DE RESTABLECIMIENTO DE CONTRASEÑA

    /**
     * Función para borrar el registro de la tabla pwdreset que contenga $email
     */
    public function borrarRegistroPwdresetPorEmail($email)
    {
        $sql = "DELETE FROM pwdreset WHERE email=?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

    /**
     * Función para insertar un nuevo registro en la tabla pwdreset
     */
    public function nuevoRegistroPwdreset($email, $selector, $tokenHash, $expira)
    {
        $sql = "INSERT INTO pwdreset(email, selector, token, expira) VALUES(?,?,?,?);";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssss', $email, $selector, $tokenHash, $expira);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    /**
     * Función para comprobar si existe un registro con un selector y una expiración
     * superior a la dada
     */
    public function comprobarRegistroPwdreset($selector, $momentoActual)
    {
        $sql = "SELECT * FROM pwdreset WHERE selector=? && expira >= ? LIMIT 1;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ss', $selector, $momentoActual);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $registro = $resultado->fetch_array();

        $stmt->close();
        $conexion->close();

        return $registro;
    }

    // FUNCIONES DE LA TABLA REGISTRO


    /**
     * Función para devolver todos los registros
     */
    public function getRegistros()
    {
        $sql = "SELECT * FROM registros ORDER BY activo DESC;";
        $conexion = $this->conectar();

        $resultado = $conexion->query($sql);
        $centros = $resultado->fetch_all(MYSQLI_BOTH);

        $conexion->close();

        return $centros;
    }

    /**
     * Función para registrar un usuario en un centro
     */
    public function crearRegistro($idUsuario, $idCentro, $rol, $clase, $curso, $activo)
    {
        $sql = "INSERT INTO registros(idusuario, idcentro, rol, clase, curso, activo) VALUES(?,?,?,?,?,?);";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('iisssi', $idUsuario, $idCentro, $rol, $clase, $curso, $activo);
        $ejecucion = $stmt->execute();

        $stmt->close();
        $conexion->close();

        return $ejecucion;
    }

    // OTRAS FUNCIONES

    /**
     * Función para establecer la cookie al usuario
     */
    public function crearCookie($numero_aleatorio, $idUsuario)
    {
        $sql = "UPDATE usuarios set cookie=? where id=?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $numero_aleatorio, $idUsuario);
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

    /**
     * Función para borrar una cookie en la base de datos
     */
    public function borrarCookie($idUsuario, $cookie)
    {
        $sql = "UPDATE usuarios SET cookie=null WHERE id=? && cookie=?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ii', $idUsuario, $cookie);
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

    /**
     * Función para guardar la IP y la fecha de incio de sesión del usuario
     */
    public function guardarIpFecha($fecha, $ip, $idUsuario)
    {
        $sql = "UPDATE usuarios set ultimaCon=?, ipCon=? where id=?;";
        $conexion = $this->conectar();

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('ssi', $fecha, $ip, $idUsuario);
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

}