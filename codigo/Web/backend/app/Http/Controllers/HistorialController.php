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
            //$precio = Precio::select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->where('id_producto', $id_producto)->latest('created_at')->first();
            $precio = Precio::whereIn('id', function ($query) use ($id_producto) {
                $query->selectRaw('MAX(id)')
                    ->from('precios')
                    ->where('id_producto', $id_producto)
                    ->groupBy('tienda');
            })
                ->where('id_producto', $id_producto)
                ->get(['id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at']);

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

    //funcion para cambiar el valor de favorito
    public function cambiarFavorito($id, Request $req)
    {
        $idFavorito = $req->id;
        $historial = Historial::where('id', $idFavorito)->where('id_producto',$id)->first();
        $historial->favorito = $historial->favorito == 1 ? 0 : 1;
        $historial->save();
        return response()->json([
            'status' => 'ok',
            'historial' => $historial
        ]);
    }


}
