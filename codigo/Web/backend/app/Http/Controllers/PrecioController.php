<?php

namespace App\Http\Controllers;

use App\Models\Precio;

class PrecioController extends Controller
{
    function actualizarPrecios()
    {
        $script_path = '../WebScraping/ActualizarPrecios.py';

        $precios = Precio::select('id_producto', 'url_producto', 'tienda')->groupBy('id_producto', 'url_producto','tienda')->get();

        foreach ($precios as $precio_anterior) {
            $url = $precio_anterior->url_producto;
            $tienda = $precio_anterior->tienda;
            $command = 'python ' . $script_path . ' "' . $url . '" "' . $tienda . '" 2>&1';
            exec($command, $output);
            $precio = $output[0];
            echo $precio;
            $precio_nuevo = new Precio();
            $precio_nuevo->id_producto = $precio_anterior->id_producto;
            $precio_nuevo->precio = $precio;
            $precio_nuevo->tienda = $tienda;
            $precio_nuevo->url_producto = $url;
            $precio_nuevo->save();
        }
    }

}
