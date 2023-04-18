<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;

    //constructor con parametros
    public function __construct($id_user = null, $id_producto = null, $favorito = null)
    {
        $this->id_user = $id_user;
        $this->id_producto = $id_producto;
        $this->favorito = $favorito;
    }

    protected $table = 'historials';
    protected $fillable = [
        'id_user',
        'id_producto',
        'favorito',
    ];

    //relacion con la tabla users
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    //relacion con la tabla productos
    public function producto()
    {
        return $this->belongsTo('App\Models\Producto');
    }
}
