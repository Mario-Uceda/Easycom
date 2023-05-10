<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    //constructor con parametros
    public function __construct($id_producto = null, $precio_nuevo = null, $precio_anterior = null)
    {
        $this->id_producto = $id_producto;
        $this->id_precio_nuevo = $precio_nuevo;
        $this->id_precio_anterior = $precio_anterior;
    }

    //get notificacion by id_producto
    public static function getNotificacionByIdProducto($id_producto)
    {
        $notificacion = Notificacion::select('id', 'id_producto', 'id_precio_nuevo', 'id_precio_anterior', 'modified_at')->where('id_producto', $id_producto)->latest('created_at')->first();
        //obtengo el valor de precio nuevo y precio anterior
        $precio_nuevo = Precio::select('precio')->where('id', $notificacion->id_precio_nuevo)->first();
        $precio_anterior = Precio::select('precio')->where('id', $notificacion->id_precio_anterior)->first();
        $notificacion->precio_nuevo = $precio_nuevo->precio;
        $notificacion->precio_anterior = $precio_anterior->precio;
        //devuelvo el objeto notificacion quitando los id de precio nuevo y precio anterior
        unset($notificacion->id_precio_nuevo);
        unset($notificacion->id_precio_anterior);
        return $notificacion;
    }

}
