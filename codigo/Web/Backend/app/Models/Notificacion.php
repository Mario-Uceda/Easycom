<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    //constructor con parametros
    public function __construct($id_producto = null, $precio_minimo = null, $precio_actual = null)
    {
        $this->id_producto = $id_producto;
        $this->id_precio_minimo = $precio_minimo;
        $this->id_precio_actual = $precio_actual;
    }

    protected $fillable = [
        'id_producto',
        'id_precio_minimo',
        'id_precio_actual'
    ];

    //get notificacion by id_producto
    public function getNotificacionByIdProducto($id_producto)
    {
        $notificacion = Notificacion::select('id', 'id_producto', 'id_precio_minimo', 'id_precio_actual', 'updated_at')->where('id_producto', $id_producto)->latest('created_at')->first();
        //obtengo el valor de precio minimo y precio actual
        if ($notificacion == null) {
            $notificacion = new Notificacion($id_producto, 0, 0);
            $notificacion->precio_minimo = 0;
            $notificacion->precio_actual = 0;
            $notificacion->updated_at = null;
        }else{
            $precio_minimo = Precio::select('precio')->where('id', $notificacion->id_precio_minimo)->first();
            $precio_actual = Precio::select('precio')->where('id', $notificacion->id_precio_actual)->first();
            $notificacion->precio_minimo = $precio_minimo->precio;
            $notificacion->precio_actual = $precio_actual->precio;
        }
        //devuelvo el objeto notificacion quitando los id de precio minimo y precio actual
        unset($notificacion->id_precio_minimo);
        unset($notificacion->id_precio_actual);
        return $notificacion;
    }

}

