<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'productos';

    protected $primaryKey = 'id_producto';

    protected $fillable = [
        'barcode',
        'nombre',
        'descripcion',
        'url_img',
        'especificaciones_tecnicas',
    ];
}