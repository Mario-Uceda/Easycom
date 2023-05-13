@extends('layouts.navbar')
@section('contenidoPrincipal')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <div class="container mt-5">
        <div class="row">
            <div class="d-flex align-items-center justify-content-center">
                <img class="d-inline-block mx-2" src="{{ asset('images/IconApp.png') }}" alt="Logo" width="50" height="50">
                <h1 id="titulo" class="d-inline-block">EASYCOM</h1>
            </div>

            <div class="form-outline my-5 px-5 col-9 mx-auto d-flex align-items-stretch">
                <input type="search" id="search-input" class="form-control text-center" placeholder="Introduce el url, nombre o código de barras del producto" aria-label="Search" />
                <button id="search-button" type="button" class="btn btn-primary" style="height: 100%">
                    <i class="fas fa-search"></i>
                    <i class="spinner-border spinner-border-sm d-none"></i>
                </button>
            </div>
            <h2 class="text-center  mt-5">Empiza a ahorrar comparando el precio de los productos en las siguientes tiendas</h2>
            <div class="text-center mt-5">
                <button id="amazon" class="col amazon">Amazon</button>
                <button id="mediamarkt" class="col mediamarkt">MediaMarkt</button>
                <button id="ebay" class="col ebay">Ebay</button>
            </div>
        </div>
    </div>

<script>
    let buscar = false;
    const searchIcon = document.querySelector('.fa-search');
    const spinner = document.querySelector('.spinner-border');
    const amazon = document.getElementById('amazon');
    const mediamarkt = document.getElementById('mediamarkt');
    const ebay = document.getElementById('ebay');
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');
    const regexUrl = /^(http|https):\/\/[^ "]+$/;

    amazon.addEventListener('click', function() {
        window.open('https://www.amazon.es/', '_blank');
    });
    mediamarkt.addEventListener('click', function() {
        window.open('https://www.mediamarkt.es/', '_blank');
    });
    ebay.addEventListener('click', function() {
        window.open('https://www.ebay.es/', '_blank');
    });

    //Funcion para cambiar el icono de busqueda por el spinner y viceversa
    function changeIcon(){
        if (buscar == false) {
            searchIcon.classList.add('d-none');
            spinner.classList.remove('d-none');
            buscar = true;
        }
        else{
            searchIcon.classList.remove('d-none');
            spinner.classList.add('d-none');
            buscar = false;
        }
    }

    searchButton.addEventListener('click', function() {
        changeIcon();
        const inputValue = searchInput.value;
        if (inputValue == "") {
            alert("Introduce un valor");
        } else { 
            parametros = {
                barcode: inputValue,
                @auth
                    id: {{ auth()->user()->id }},
                    email: "{{ auth()->user()->email }}",
                @endauth
            };

            axios.post('/buscarProducto', parametros).then(function (response) {
                if (response.data.status == "ok") {
                    window.location.href = "/detalle/"+response.data.product.id;
                } else {
                    alert('No se ha encontrado el producto');
                    changeIcon();
                }
            });
        }        
    });

</script>
@endsection