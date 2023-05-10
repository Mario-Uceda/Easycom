@extends('layouts.navbar')
@section('contenidoPrincipal')
    <link href="{{ asset('css/botones.css') }}" rel="stylesheet" type="text/css">
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
                <button id="wallapop" class="col wallapop">Wallapop</button>
            </div>
        </div>
        
    </div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    let buscar = false;
    const searchIcon = document.querySelector('.fa-search');
    const spinner = document.querySelector('.spinner-border');
    const amazon = document.getElementById('amazon');
    const mediamarkt = document.getElementById('mediamarkt');
    const wallapop = document.getElementById('wallapop');
    const searchButton = document.getElementById('search-button');
    const searchInput = document.getElementById('search-input');
    const regexUrl = /^(http|https):\/\/[^ "]+$/;

    amazon.addEventListener('click', function() {
        window.open('https://www.amazon.es/', '_blank');
    });
    mediamarkt.addEventListener('click', function() {
        window.open('https://www.mediamarkt.es/', '_blank');
    });
    wallapop.addEventListener('click', function() {
        window.open('https://es.wallapop.com/', '_blank');
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
        if (regexUrl.test(inputValue)) {
            if (/www.amazon.es/.test(inputValue))
                alert("Amazon: "+inputValue);
            else if (/www.mediamarkt.es/.test(inputValue))
                alert("MediaMarkt: "+inputValue);
            else if (/es.wallapop.com/.test(inputValue))
                alert("Wallapop: "+inputValue);
            else
                alert("No se ha encontrado la tienda");
        }
        else{
            var parametros;
            if ({{ Auth::check() }}) {
                parametros = {
                    barcode: inputValue,
                    id: {{ Auth::user()->id }},
                    email: " {{ Auth::user()->email }}",
                };
            }
            else{
                parametros = {
                    barcode: inputValue
                };
            }
                        
            axios.post('/buscarProducto', parametros).then(function (response) {
                // handle success
                console.log(response);
                console.log(response.data);
                if (response.status == "ok") {
                    alert(response.data);
                }
                else{
                    alert('No se ha encontrado el producto');
                }
            })
        }

        changeIcon();
    });
</script>
@endsection