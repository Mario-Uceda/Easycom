<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function registrarProducto(Request $request) {
        $function_name = 'get_amazon';
    	$barcode = $request->barcode;
    	$script_path = 'WebScraping/WebScraping.py';
        // Construye el comando que se utilizará para ejecutar el script
        $command = 'python ' . $script_path . ' ' . $function_name . ' ' . $barcode;
        
        // Ejecuta el comando y captura la salida del script
        $output = exec($command);
        
        // Decodifica la salida del script como JSON
        $data = json_decode($output);
            
        if ($data === null) {
                throw new \Exception("Error al procesar la salida del script");
        }
            
        $producto = new Producto();
        $producto->barcode = $barcode;
        $producto->nombre = $data[0];
        $producto->descripcion = $data[1];
        $producto->url_img = $data[2];
        $producto->especificaciones_tecnicas = $data[3];
        $producto->save();
        $producto->precio = $data[4];
        return json_encode($producto);
    }
}
