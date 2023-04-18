<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function getHistorial(Request $req)
    {
        if ($req->favorito == 1) {
            $historial = Historial::select('id', 'id_user', 'id_producto', 'favorito', 'created_at')->where('id_user', $req->id_usuario)->where('favorito', 1)->get();
        }else{
            $historial = Historial::select('id', 'id_user', 'id_producto', 'favorito', 'created_at')->where('id_user', $req->id_usuario)->get();
        }
        $productos = array();
        $precios = array();
        foreach ($historial as $h) {
            $id_producto = $h->id_producto;
            $producto = Producto::select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->where('id', $id_producto)->first();
            $precio = Precio::select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->where('id_producto', $id_producto)->latest('created_at')->first();
            array_push($productos, $producto);
            array_push($precios, $precio);
        }
        return response()->json([
            'status' => 'ok',
            'historials' => $historial,
            'products' => $productos,
            'prices' => $precios
        ]);
    }

    public function getHistorial2(Request $req)
    {
        // Seleccione el historial correspondiente al usuario y favorito si se especifica
        $historialQuery = Historial::select('id', 'id_user', 'id_producto', 'favorito', 'created_at')
            ->where('id_user', $req->id_usuario);

        if ($req->favorito == 1) {
            $historialQuery->where('favorito', 1);
        }

        $historial = $historialQuery->get();

        // Seleccione los productos correspondientes al historial y almacénelos en una colección indexada por ID
        $productos = Producto::select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')
            ->whereIn('id', $historial->pluck('id_producto')->toArray())
            ->get()
            ->keyBy('id');

        // Seleccione los precios más recientes para cada producto en el historial y almacénelos en una colección agrupada por ID de producto
        $precios = Precio::select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')
            ->whereIn('id_producto', $historial->pluck('id_producto')->toArray())
            ->latest('created_at')
            ->get()
            ->groupBy('id_producto');

        // Agregue los datos de producto y precio a cada elemento del historial y almacénelos en una colección
        $historial = $historial->map(function ($item) use ($productos, $precios) {
            return [
                'id' => $item->id,
                'id_user' => $item->id_user,
                'id_producto' => $item->id_producto,
                'favorito' => $item->favorito,
                'created_at' => $item->created_at,
                'producto' => $productos->get($item->id_producto),
                'precio' => $precios->get($item->id_producto)->first(),
            ];
        });

        // Devuelve una respuesta JSON que contiene el historial con datos de producto y precio agregados
        return response()->json([
            'status' => 'ok',
            'historials' => $historial,
        ]);
    }


}
