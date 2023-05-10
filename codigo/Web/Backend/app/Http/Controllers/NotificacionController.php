<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function notificaciones (Request $request) {
        $historial = Historial::select('id', 'id_user', 'id_producto', 'favorito', 'created_at')->where('id_user', $req->id_usuario)->where('favorito', 1)->get();
        $productos = array();
        $precios = array();
        $notificaciones = array();
        foreach ($historial as $h) {
            $id_producto = $h->id_producto;
            $producto = Producto::select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->where('id', $id_producto)->first();
            $precio = Precio::select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->where('id_producto', $id_producto)->latest('created_at')->first();
            $notificacion = new Notificacion().getNotificacionByIdProducto($id_producto);
            array_push($productos, $producto);
            array_push($precios, $precio);
        }
        return response()->json([
            'status' => 'ok',
            'historials' => $historial,
            'products' => $productos,
            'prices' => $precios,
            'notificaciones' => $notificaciones
        ]);
    }
}
