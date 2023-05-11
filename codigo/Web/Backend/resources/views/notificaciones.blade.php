@extends('layouts.navbar')
<?php
use App\Http\Controllers\NotificacionController;

function convertirFecha($fecha) {
    $fechaObjeto = new DateTime($fecha);
    $fechaObjeto->add(new DateInterval('PT2H'));
    $fechaFormateada = $fechaObjeto->format('d/m/Y H:i:s');
    return $fechaFormateada;
}

$idUser = auth ()->user ()->id;
$notificacionController = new NotificacionController;
$resultado = $notificacionController->listarNotificaciones($idUser)->getData();
$productos = $resultado->products;
$notificaciones = $resultado->notificaciones;

//cambio el formato de la fecha
foreach ($notificaciones as $notificacion) {
    $notificacion->updated_at = convertirFecha($notificacion->updated_at);
}

?>
@section('contenidoPrincipal')
    <div class="container  mt-5">
        <div class="row">
            <div class="col-12  mb-5">
                <h1 class="text-center">Notificaciones</h1>
            </div>
            @for ($i = 0; $i < count($productos); $i++)
                <div class="col-8 mx-auto mb-4">
                    <a href="{{ route('detalle', $productos[$i]->id) }}" id="notificacionesLink">
                        <div class="card h-100">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <div class="img-container square-container">
                                        <div class="img-wrapper square-wrapper">
                                            <img src="{{ $productos[$i]->url_img }}" class="card-img-top p-2 img-fit" alt="{{ $productos[$i]->nombre }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 fechaTarjeta">
                                                <p class="card-title textoTarjeta float-right">{{ $notificaciones[$i]->updated_at }}</p>
                                            </div>
                                        </div>
                                        <h5 class="card-title textoTarjeta">{{ $productos[$i]->nombre }}</h5>
                                        <p class="card-text textoTarjeta">{{ $productos[$i]->descripcion }}</p>
                                        <div class="row justify-content-center">
                                            <div class="col-6">
                                                <p class="card-text textoTarjeta">Precio actual</p>
                                                <p class="card-text textoTarjeta">{{ $notificaciones[$i]->precio_nuevo }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="card-text textoTarjeta">Precio anterior</p>
                                                <p class="card-text textoTarjeta">{{ $notificaciones[$i]->precio_anterior }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endfor
        </div>
    </div>   
@endsection