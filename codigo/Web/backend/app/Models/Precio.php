<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Precio extends Model
{
    use HasFactory, SoftDeletes;

    public function __construct($id_producto = null, $precio = null, $tienda = null, $url_producto = null)
    {
        $this->id_producto = $id_producto;
        $this->precio = $precio;
        $this->tienda = $tienda;
        $this->url_producto = $url_producto;
    }
    protected $table = 'precios';

    protected $primaryKey = 'id_precio';

    protected $fillable = [
        'id_producto',
        'precio',
        'tienda',
        'url_producto',
    ];

}