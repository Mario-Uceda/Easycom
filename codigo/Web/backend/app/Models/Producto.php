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

    //constructor con parametros
    public function __construct($barcode = null, $nombre = null, $url_img = null, $descripcion = null,  $especificaciones_tecnicas = null)
    {
        $this->barcode = $barcode;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->url_img = $url_img;
        $this->especificaciones_tecnicas = $especificaciones_tecnicas;
    }
    protected $fillable = [
        'barcode',
        'nombre',
        'descripcion',
        'url_img',
        'especificaciones_tecnicas',
    ];
}