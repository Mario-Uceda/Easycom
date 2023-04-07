<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function registrarProducto(Request $request) {
        
        $function_name = 'get_amazon';
        $barcode = $request->barcode;
        $script_path = 'WebScraping/WebScraping.php';
        if (file_exists($script_path)) {
            $cmd = "php $script_path $function_name $barcode";
            $result = exec($cmd);
            var_dump($result);
            if ($result === null) {
                throw new \Exception("Error al ejecutar el script");
            }
    
            $data = json_decode($result);
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
        }else{
            var_dump($barcode);
            return json_encode($barcode);
        }
    }
}
