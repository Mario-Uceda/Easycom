<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrecioController extends Controller
{
    function priceUpdate()
    {   
        $script_path = '../WebScraping/PriceUpdate.py';
        // quiero generar una lista de Precios haciendo un group by por id_producto, id_precio, url_producto y tienda
        // y luego hacer un foreach para actualizar el precio de cada producto
        $precios = Precio::select('id_producto', 'id_precio', 'url_producto', 'tienda')->groupBy('id_producto', 'id_precio', 'url_producto','tienda')->get();
        foreach ($precios as $precio) {
            $command = 'python ' . $script_path . ' ' . $precio->url_producto . ' ' . $precio->tienda . ' 2>&1';
            exec($command, $output);
            $nuevo_precio = json_decode($output[0], true);
            $precio->precio = $nuevo_precio[2];
            $precio->save();
        }
    }
}
