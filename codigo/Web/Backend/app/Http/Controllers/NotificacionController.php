<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\Notificacion;
use App\Models\Precio;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $precio = Precio::whereIn('id', function ($query) use ($id_producto) {
                $query->selectRaw('MAX(id)')
                    ->from('precios')
                    ->where('id_producto', $id_producto)
                    ->groupBy('tienda');
            })
                ->where('id_producto', $id_producto)
                ->get(['id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at']);

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

    public function listarNotificaciones (int $id_usuario) {
        $historial = Historial::select('id', 'id_user', 'id_producto', 'favorito', 'created_at')->where('id_user', $id_usuario)->where('favorito', 1)->get();
        $productos = array();
        $notificaciones = array();
        foreach ($historial as $h) {
            $id_producto = $h->id_producto;
            $producto = Producto::select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->where('id', $id_producto)->first();
            $notificacion = new Notificacion();
            $notificacion = $notificacion->getNotificacionByIdProducto($id_producto);
            array_push($productos, $producto);
            array_push($notificaciones, $notificacion);
        }
        return response()->json([
            'status' => 'ok',
            'products' => $productos,
            'notificaciones' => $notificaciones
        ]);
    }



    public function crearNotificaciones() {
        $productos = Producto::select('id')->get();
        foreach ($productos as $p) {
            if (Notificacion::where('id_producto', $p->id)->count() == 0) {
                $notificacion = new Notificacion($p->id);
                $precioMinimo = Precio::select('id', 'precio', \DB::raw('MIN(precio) as precio_minimo'))
                    ->where('id_producto', $p->id)
                    ->where('tienda', '!=', 'Ebay')
                    ->groupBy('id', 'precio')
                    ->orderBy('precio', 'asc')
                    ->first();
                if ($precioMinimo != null) {
                    $notificacion->id_precio_minimo = $precioMinimo->id;
                    $preciosActuales = Precio::whereIn('id', function ($query) use ($p) {
                        $query->selectRaw('MAX(id)')
                            ->from('precios')
                            ->where('id_producto', $p->id)
                            ->where('tienda', '!=', 'Ebay')
                            ->groupBy('tienda');
                    })->get();
                    $idMinimo = 0;
                    $pMinimo = 0;
                    foreach ($preciosActuales as $pa) {
                        if ($pMinimo == 0 || $pa->precio < $pMinimo) {
                            $pMinimo = $pa->precio;
                            $idMinimo = $pa->id;
                        }
                    }
                    $notificacion->id_precio_actual = $idMinimo;
                    $notificacion->save();
                }
            }
        }
    }

    //metodo para actualizar las notificaciones
    public function actualizarNotificaciones() {
        $this->crearNotificaciones();
        $notificaciones = Notificacion::get();
        foreach ($notificaciones as $n) {
            $preciosActuales = Precio::whereIn('id', function ($query) use ($n) {
                $query->selectRaw('MAX(id)')
                    ->from('precios')
                    ->where('id_producto', $n->id_producto)
                    ->where('tienda', '!=', 'Ebay')
                    ->where('created_at', '>', $n->updated_at)
                    ->groupBy('tienda');
            })->get();

            $idMinimo = 0;
            $pMinimo = 0;
            foreach ($preciosActuales as $pa) {
                if ($pMinimo == 0 || $pa->precio < $pMinimo) {
                    $pMinimo = $pa->precio;
                    $idMinimo = $pa->id;
                }
            }
            $n->id_precio_actual = $idMinimo;
            $minimoHistorico = $n->getNotificacionByIdProducto($n->id_producto)->precio_minimo;
            //comprobar si el precio minimo ha cambiado
            if ($pMinimo < $minimoHistorico) {
                $n->id_precio_minimo = $idMinimo;
            }
            $n->save();
        }
    }

}
