@extends('layouts.navbar')

@section('contenidoPrincipal')
  <div class="container">
    <h1 class="text-center mt-5 mb-6">Login</h1>

    <form method="POST" class="row g-3 needs-validation" novalidate>
      @csrf

      <div class="col-md-6 offset-md-3">
        
        <div class="form-floating m-4">
            <input type="email" class="form-control input-nombre @error('email') is-invalid @enderror" id="email" autofocus name="email" required value="{{ old('email') }}">
            <label for="name">Email</label>
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

        <div class="form-check m-4">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
          <label class="form-check-label" for="remember">
            Recuerdame
          </label>
        </div>
        <div class="d-flex justify-content-center mt-5">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </div>
    </form>
  </div>
@endsection