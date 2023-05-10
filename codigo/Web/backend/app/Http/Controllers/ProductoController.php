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
        $producto = Producto::where('id',$id)->first();
        $precios = Precio::where('id_producto', $id)->get();
        $historial = Historial::where('id_producto', $id)->where('id_user', auth()->user()->id)->first();
        return view('producto', [
            'favorito' => $historial,
            'producto' => $producto,
            'precios' => $precios
        ]);
    }

    //Este metodo se llama desde la web y ejecuta el metodo buscarProducto y luego me devuelve la view de producto con los datos
    public function buscarProductoWeb(Request $request) {
        $response = $this->buscarProducto($request);
        $idProducto = $response->getData()->product->idProducto;
        return $this->detalle($idProducto);
    }

    public function buscarProducto(Request $request) {
        // comprobar si el producto ya existe en la base de datos
        $idProducto = $request->idProducto;
        if($idProducto != null || $idProducto != "") {
            $producto = Producto::where('id', $idProducto)->select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->first();
            $precio = Precio::where('id_producto', $idProducto)->latest('created_at')->select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->first();
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
            $idProducto = $producto->id;
            $idUser = $request->id;
            $email = $request->email;
            $usuario = User::where('id', $idUser)->where('email', $email)->first();
            $historial = Historial::where('id_user', $idUser)->where('id_producto', $idProducto)->first();
            // si existe, y no había buscado antes ese producto se registra en el historial
            if ($usuario != null && $historial == null) {
                $historial = new Historial($idUser, $idProducto, false);
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
        $producto = new Producto($barcode, trim($producto_amazon[0]), trim($producto_amazon[1]), trim($producto_amazon[2]), trim($producto_amazon[3]));
        $producto->save();
        $producto = Producto::where('barcode', $barcode)->select('id','barcode', 'nombre', 'url_img', 'descripcion','especificaciones_tecnicas')->first();
        //guardar precio
        $precio = new Precio($producto->id, $precio_amazon[2], $precio_amazon[1], $precio_amazon[0]);
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