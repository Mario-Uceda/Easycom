<?php
require 'consultas.class.php';


// Verificar si se recibió el parámetro "metodo"
if (isset($_POST['metodo'])) {

    // Crear una instancia de la clase Consultas
    $consultas = new Consultas();

    // Obtener el nombre del método a llamar
    $metodo = $_POST['metodo'];

    // Verificar si el método existe en la clase Consultas
    if (method_exists($consultas, $metodo)) {

        // Llamar al método con los parámetros recibidos en la petición
        $resultado = call_user_func_array(array($consultas, $metodo), array($_POST));

        // Retornar el resultado de la ejecución del método
        echo json_encode(array("success" => true, "result" => $resultado));

    } else {

        // El método no existe en la clase Consultas
        echo json_encode(array("success" => false, "error" => "Método no encontrado"));

    }
} else {

    // No se recibió el parámetro "metodo"
    echo json_encode(array("success" => false, "error" => "Parámetro 'metodo' no recibido"));

}