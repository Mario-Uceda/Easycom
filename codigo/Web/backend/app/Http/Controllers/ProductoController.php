<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\User;
use App\Models\Historial;
use Illuminate\Http\Request;

class ProductoController extends Controller
{

    public function detalle($id)
    {
        $producto = Producto::find($id);
        $precios = Precio::where('id_producto', $id);
        return view('detalle', [
            'producto' => $producto,
            'precios' => $precios
        ]);
    }

    public function buscarProducto(Request $request) {
        // comprobar si el producto ya existe en la base de datos
        $idProducto = $request->idProducto;
        if($idProducto != null || $idProducto != "") {
            $producto = Producto::where('id', $idProducto)->select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->first();
            $precio = Precio::where('id_producto', $producto->id)->latest('created_at')->select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->first();
        }else{
            $barcode = $request->barcode;
            $producto = Producto::where('barcode', $barcode)->select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->first();
            if ($producto == null) { // si no existe, se registra
                $registro = $this->registrarProducto($barcode);
                if ($registro['status'] == 'error') {
                    return response()->json([
                        'status' => 'error'
                    ]);
                }
                $precio = $registro['precio'];
                $producto = $registro['producto'];
            }else { // si existe, se obtiene el precio
                $precio = Precio::where('id_producto', $producto->id)->latest('created_at')->select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->first();
            }
            // comprobar si el usuario existe
            $idUser = $request->id;
            $email = $request->email;
            $usuario = User::where('id', $idUser)->where('email', $email)->first();
            $historial = Historial::where('id_user', $idUser)->where('id_producto', $producto->id)->first();
            // si existe, y no había buscado antes ese producto se registra en el historial
            if ($usuario != null && $historial == null) {
                $historial = new Historial();
                $historial->id_user = $usuario->id;
                $historial->id_producto = $producto->id;
                $historial->favorito = false;
                $historial->save();
            }

            // se devuelve el producto y el precio
            return response()->json([
                'status' => 'ok',
                'product' => $producto,
                'price' => $precio
            ]);
        }
    }

    // Funcion para registrar un producto en la base de datos
    public function registrarProducto(string $barcode) {
        $script_path = '../WebScraping/WebScraping.py';
        $command = 'python ' . $script_path . ' ' . $barcode . ' 2>&1';
        exec($command, $output);
        $producto_amazon = json_decode($output[0], true);
        $precio_amazon = json_decode($output[1], true);
        if ($producto_amazon == null || $precio_amazon == null) {
            return [
                'status' => 'error',
            ];
        }
        //guardar producto
        $producto = new Producto();
        $producto->barcode = $barcode;
        $producto->nombre = $producto_amazon[0];
        $producto->url_img = $producto_amazon[1];
        $producto->descripcion = $producto_amazon[2];
        $producto->especificaciones_tecnicas = $producto_amazon[3];
        $producto->save();
        $producto = Producto::where('barcode', $barcode)->select('id','barcode', 'nombre', 'url_img', 'descripcion','especificaciones_tecnicas')->first();
        //guardar precio
        $precio = new Precio();
        $precio->id_producto = $producto->id;
        $precio->url_producto = $precio_amazon[0];
        $precio->tienda = $precio_amazon[1];
        $precio->precio = $precio_amazon[2];
        $precio->save();
        $precio = Precio::where('id_producto', $producto->id)->select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->first();
        //devolver producto y precio
        return [
            'status' => 'ok',
            'producto' => $producto,
            'precio' => $precio
        ];
    }
}