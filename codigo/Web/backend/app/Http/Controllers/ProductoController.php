<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\User;
use App\Models\Historial;
use App\Http\Controllers\NotificacionController;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Exception;

class ProductoController extends Controller
{

    public function detalle($id)
    {
        $producto = Producto::where('id',$id)->first();
        $precios = Precio::where('id_producto', $id)->get();
        //Si el usuario esta logeado, se busca si el producto esta en favoritos
        if (auth()->user()) {
            $historial = Historial::where('id_producto', $id)->where('id_user', auth()->user()->id)->first();
            return view('producto', [
                'favorito' => $historial,
                'producto' => $producto,
                'precios' => $precios
            ]);
        }else{
            return view('producto', [
                'producto' => $producto,
                'precios' => $precios,
                'favorito' => null,
            ]);
        }
        $historial = Historial::where('id_producto', $id)->where('id_user', auth()->user()->id)->first();
        return view('producto', [
            'favorito' => $historial,
            'producto' => $producto,
            'precios' => $precios
        ]);
    }

    public function buscarProducto(Request $request) {
        // comprobar si el producto ya existe en la base de datos
        $idProducto = $request->idProducto;
        if($idProducto != null || $idProducto != "") {
            $producto = Producto::where('id', $idProducto)->select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->first();
            $precio = Precio::where('id_producto', $idProducto)->latest('created_at')->select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->first();
            return response()->json([
                'status' => 'ok',
                'product' => $producto,
                'price' => $precio
            ]);
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
                $producto = $registro['producto'];
            }
            $id_producto = $producto->id;
            $precios = Precio::whereIn('id', function ($query) use ($id_producto) {
                $query->selectRaw('MAX(id)')
                    ->from('precios')
                    ->where('id_producto', $id_producto)
                    ->groupBy('tienda');
            })
                ->where('id_producto', $id_producto)
                ->get(['id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at']);

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
                $historial = Historial::where('id_user', $idUser)->where('id_producto', $idProducto)->first();
            }
            // Creo notificacion
            $notificacionController = new NotificacionController();
            $notificacionController->crearNotificaciones();
            // se devuelve el producto y el precio
            return response()->json([
                'status' => 'ok',
                'product' => $producto,
                'price' => $precios,
                'favorito' => $historial
            ]);
        }
    }

    // Funcion para registrar un producto en la base de datos
    public function registrarProducto(string $barcode)
    {
        $script_path = '../WebScraping/WebScraping.py';
        $command = 'python ' . $script_path . ' "' . $barcode . '" 2>&1';
        exec($command, $output);
        $resultado = $this->obtenerResultados($output, $barcode);
        if ($resultado['status'] == 'error') {
            return response()->json([
                'status' => 'error'
            ]);
        }
        $producto_amazon = $resultado['producto_amazon'];
        $producto_mediamarkt = $resultado['producto_mediamarkt'];
        if ($producto_amazon == null && $producto_mediamarkt == null) {
            return [
                'status' => 'error',
            ];
        } elseif ($producto_amazon != null) {
            $producto_amazon->save();
        } else {
            $producto_mediamarkt->save();
        }
        $producto = Producto::where('barcode', $barcode)->select('id', 'barcode', 'nombre', 'url_img', 'descripcion', 'especificaciones_tecnicas')->first();
        $listaPrecios = $resultado['precios'];
        $precios = [];
        foreach ($listaPrecios as $precio) {
            if ($precio != null) {
                if ($precio->precio != "") {
                    $precio->id_producto = $producto->id;
                    unset($precio->updated_at);
                    unset($precio->created_at);
                    $precio->save();
                    $precio = Precio::where('id_producto', $producto->id)->select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->first();
                    array_push($precios, $precio);
                }
            }
        }

        return [
            'status' => 'ok',
            'producto' => $producto,
            'precios' => $precios
        ];
    }
    public function obtenerResultados($output, $barcode) {
        try{
            $resultado = json_decode($output[0], true);
            $amazon = json_decode($resultado['Amazon'], true);
            $mediamarkt = json_decode($resultado['Mediamarkt'], true);
            $ebay = json_decode($resultado['Ebay'], true);
            $producto_amazon = $this->obtenerProducto($amazon['product'], $barcode);
            $precio_amazon = $this->obtenerPrecio($amazon['price']);
            $producto_mediamarkt = $this->obtenerProducto($mediamarkt['product'], $barcode);
            $precio_mediamarkt = $this->obtenerPrecio($mediamarkt['price']);
            $precio_ebay = $this->obtenerPrecio($ebay['price']);
            $precios = array($precio_amazon, $precio_mediamarkt, $precio_ebay);
            return [
                'status' => 'ok',
                'producto_amazon' => $producto_amazon,
                'producto_mediamarkt' => $producto_mediamarkt,
                'precios' => $precios,
            ];
        }catch(Exception $e){
            return [
                'status' => 'error',
            ];
        }

    }

    public function obtenerProducto($producto_json, $barcode) {
        $producto_json = json_decode($producto_json, true);
        if ($producto_json != null && count($producto_json) > 0) {
            $producto = new Producto($barcode, $producto_json[0], $producto_json[1], $producto_json[2], $producto_json[3]);
            return $producto;
        }else {
            return null;
        }
    }

    public function obtenerPrecio($precio_json) {
        $precio_json = json_decode($precio_json, true);
        if ($precio_json != null && count($precio_json) > 0) {
            $precio = new Precio(10, $precio_json[2], $precio_json[1], $precio_json[0]);
            return $precio;
        }else {
            return null;
        }
    }
}
