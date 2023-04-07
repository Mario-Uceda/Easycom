<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function buscarProducto(Request $request)
    {
        $barcode = $request->barcode;
        $producto = Producto::where('barcode', $barcode)->first();
        if ($producto == null) {
            return response()->json([
                registrarProducto($barcode)
            ]);
        } else {
            $precio = Precio::where('id_producto', $producto->id_producto)->first();
            return response()->json([
                'producto' => $producto,
                'precio' => $precio
            ]);
        }
    }

}
function registrarProducto(string $barcode)
{
    $script_path = '../WebScraping/WebScraping.py';

    // Creamos el comando para ejecutar el script
    $command = 'python ' . $script_path . ' ' . $barcode . ' 2>&1';

    // Ejecutamos el comando y guardamos la salida en $output
    exec($command, $output);

    // Convertimos la salida a un arreglo
    $producto_amazon = json_decode($output[0], true);
    $precio_amazon = json_decode($output[1], true);
    // Luego puedes hacer lo que necesites con el resultado
    $producto = new Producto();
    $producto->barcode = $barcode;
    $producto->nombre = $producto_amazon[0];
    $producto->url_img = $producto_amazon[1];
    $producto->descripcion = $producto_amazon[2];
    $producto->especificaciones_tecnicas = $producto_amazon[3];
    $producto->save();
    $precio = new Precio();
    $precio->id_producto = Producto::where('barcode', $barcode)->first()->id_producto;
    $precio->url_producto = $precio_amazon[0];
    $precio->tienda = $precio_amazon[1];
    $precio->precio = $precio_amazon[2];
    $precio->save();
    return response()->json([
        'producto' => $producto,
        'precio' => $precio
    ]);
}