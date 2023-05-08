@extends('layouts.navbar')
<link href="{{ asset('css/tarjetas.css') }}" rel="stylesheet" type="text/css">

<?php
use App\Models\Historial;
use App\Models\Producto;
use App\Models\Precio;
$productos = [];

$idUser = auth ()->user ()->id;
$historial = Historial::where('id_user', $idUser)->where('favorito', 1)->get();
foreach ($historial as $h) {
    $producto = Producto::where('id', $h->id_producto)->first();
    $precio = Precio::where('id_producto', $h->id_producto)->latest('created_at')->select('id', 'id_producto', 'precio', 'tienda', 'url_producto', 'created_at')->first();
    $producto['precio'] = $precio;
    array_push($productos, $producto);
}

// Obtener la página actual a mostrar
$page = request()->input('page', 1);

// Definir la cantidad de elementos por página
$perPage = 12;

// Obtener los productos de la página actual
$productosPagina = array_slice($productos, ($page - 1) * $perPage, $perPage);

// Crear una instancia de LengthAwarePaginator
$paginator = new \Illuminate\Pagination\LengthAwarePaginator(
    $productosPagina, count($productos), $perPage, $page
);
?>

@section('contenidoPrincipal')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center">Favoritos</h1>
            <div id="productos" class="mt-5">
                <div class="row">
                    @foreach ($productosPagina as $producto)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <a href="{{ route('detalle', $producto['id']) }}">
                                    <div class="img-container square-container">
                                        <div class="img-wrapper square-wrapper">
                                            <img src="{{ $producto['url_img'] }}" class="card-img-top p-2 img-fit" alt="{{ $producto['nombre'] }}">
                                        </div>
                                    </div>
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title textoTarjeta">{{ $producto['nombre'] }}</h5>
                                    <p class="card-text textoTarjeta">{{ $producto['descripcion'] }}</p>
                                </div>
                            </div>
                        </div>
                        @if ($loop->iteration % 3 == 0)
                            </div><div class="row">
                        @endif
                    @endforeach


    
                </div>

                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item{{ $paginator->currentPage() == 1 ? ' disabled' : '' }}">
                            <a class="page-link" href="{{ url()->current() }}?page={{ $paginator->currentPage() - 1 }}" tabindex="-1" aria-disabled="{{ $paginator->currentPage() == 1 ? 'true' : 'false' }}">Anterior</a>
                        </li>
                       
                        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                        <li class="page-item{{ $paginator->currentPage() == $i ? ' active' : '' }}">
                            <a class="page-link" href="{{ url()->current() }}?page={{ $i }}">{{ $i }}</a>
                        </li>
                        @endfor
                        
                        <li class="page-item{{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}">
                            <a class="page-link" href="{{ url()->current() }}?page={{ $paginator->currentPage() + 1 }}">Siguiente</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
@endsection