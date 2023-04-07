<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Precio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'precios';

    protected $primaryKey = 'id_precio';

    protected $fillable = [
        'id_producto',
        'precio',
        'tienda',
        'url_producto',
    ];

    /**
     * Get the producto that owns the precio.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}