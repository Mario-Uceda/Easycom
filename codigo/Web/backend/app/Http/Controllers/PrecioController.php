<?php

namespace App\Http\Controllers;

use App\Models\Precio;

class PrecioController extends Controller
{
    function actualizarPrecios()
    {
        $script_path = '../WebScraping/ActualizarPrecios.py';
        $precios = Precio::select('id_producto', 'url_producto', 'tienda')
        ->latest('created_at')
        ->groupBy('id_producto', 'url_producto', 'tienda')
        ->get();
        foreach ($precios as $precio_anterior) {
            $url = $precio_anterior->url_producto;
            $tienda = $precio_anterior->tienda;
            $id = $precio_anterior->id_producto;
            $command = 'python ' . $script_path . ' "' . $url . '" "' . $tienda . '" 2>&1';
            $output = [];
            exec($command, $output);
            $precio = $output[0];
            if (is_numeric($precio)) {
                $precio_nuevo = new Precio($id, $precio, $tienda, $url);
                $precio_nuevo->save();
            }
        }
    }
}
