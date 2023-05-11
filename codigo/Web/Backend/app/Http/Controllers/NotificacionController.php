<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\Notificacion;
use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function notificaciones (Request $req) {
        $historial = Historial::select('id', 'id_user', 'id_producto', 'favorito', 'created_at')->where('id_user', $req->id_usuario)->where('favorito', 1)->get();
        $productos = array();
        $precios = array();
        $notificaciones = array();
        foreach ($historial as $h) {
            $id_producto = $h->id_producto;
            $producto = Producto::select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->where('id', $id_producto)->first();
            $precio = Precio::select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->where('id_producto', $id_producto)->latest('created_at')->first();
            $notificacion = new Notificacion();
            $notificacion = $notificacion->getNotificacionByIdProducto($id_producto);
            array_push($productos, $producto);
            array_push($precios, $precio);
            array_push($notificaciones, $notificacion);
        }
        return response()->json([
            'status' => 'ok',
            'historials' => $historial,
            'products' => $productos,
            'prices' => $precios,
            'notificaciones' => $notificaciones
        ]);
    }

    public function crearNotificaciones() {
        $productos = Producto::select('id')->get();
        foreach ($productos as $p) {
            $notificacion = new Notificacion($p->id);
            $precio = Precio::select('id')->where('id_producto', $p->id)->latest('created_at')->first();
            $notificacion->id_precio_nuevo = $precio->id;
            $notificacion->id_precio_anterior = $precio->id;
            $notificacion->save();
        }
    }

}
