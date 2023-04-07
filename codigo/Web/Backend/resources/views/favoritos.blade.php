@extends('layouts.navbar')

<?php
$productos = [];

for ($i = 1; $i <= 100; $i++) {
    $producto = [
        'id' => $i,
        'titulo' => 'Producto ' . $i,
        'imagen' => 'https://picsum.photos/200/300',
        'descripcion' => 'descripcion' . $i,
    ];
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
                                    <img src="{{ $producto['imagen'] }}" class="card-img-top" alt="{{ $producto['titulo'] }}">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $producto['titulo'] }}</h5>
                                    <p class="card-text">{{ $producto['descripcion'] }}</p>
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
                            <a class="page-link" href="{{ url()->current() }}?page={{ $paginator->currentPage() - 1 }}" tabindex="-1" aria-disabled="{{ $paginator->currentPage() == 1 ? 'true' : 'false' }}">Previous</a>
                        </li>
                       
                        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                        <li class="page-item{{ $paginator->currentPage() == $i ? ' active' : '' }}">
                            <a class="page-link" href="{{ url()->current() }}?page={{ $i }}">{{ $i }}</a>
                        </li>
                        @endfor
                        
                        <li class="page-item{{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}">
                            <a class="page-link" href="{{ url()->current() }}?page={{ $paginator->currentPage() + 1 }}">Next</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
@endsection