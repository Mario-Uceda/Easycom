@extends('layouts.navbar')

@section('contenidoPrincipal')
  <div class="container">
        <h1 class="text-center mt-5 mb-6">Registro</h1>

        <form method="POST" class="row g-3 needs-validation" novalidate>
            @csrf
            
            <div class="col-md-6 offset-md-3">

                <div class="form-floating m-4">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" autofocus name="name" required value="{{ old('name') }}">
                    <label for="name">Nombre</label>
                    @error('name') 
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating m-4">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email') }}">
                    <label for="email">Correo electrónico</label>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating m-4">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    <label for="password">Contraseña</label>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-floating m-4">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    <label for="password_confirmation">Confirmar contraseña</label>
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </div>
            </div>

        </form>
    </div>
@endsection

