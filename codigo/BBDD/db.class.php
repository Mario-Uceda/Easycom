<?php

/**
 * Clase Db para gestionar la base de datos
 */
class Db {
    private $servidor;
    private $db;
    private $usuario;
    private $password;

    /**
     * Función que devuelve un objeto mysqli
     */
    protected function conectar() {
        $this->servidor = "localhost";
        $this->db = "u388516815_easycom";
        $this->usuario = "u388516815_easycom";
        $this->password = "]5GvdZ12";

        $conexion = new mysqli(
            $this->servidor,
            $this->usuario,
            $this->password,
            $this->db
        );

        return $conexion;
    }
}