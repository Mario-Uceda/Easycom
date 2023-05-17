<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use Illuminate\Http\Request;

class PrecioController extends Controller
{
    //Funcion para obtener el ultimo precio de todos los productos
    function obtenerUltimosPrecios()
    {
        $precios = Precio::select('id_producto', 'url_producto', 'tienda')
            ->latest('created_at')
            ->groupBy('id_producto', 'url_producto', 'tienda')
            ->get();
        return $precios;
    }

    //Funcion para guardar un precio de un producto
    function guardarPrecio(Request $request)
    {
        
        $id_producto = $request->id_producto;
        $precio = $request->precio;
        $tienda = $request->tienda;
        $url_producto = $request->url_producto;
        if($id_producto != null && $precio != null && $tienda != null && $url_producto != null){
            $precio_nuevo = new Precio($id_producto, $precio, $tienda, $url_producto);
            $precio_nuevo->save();
        }else{
            return response()->json([
                'message' => 'Error al guardar el precio'
            ], 400);
        }
    }
}
