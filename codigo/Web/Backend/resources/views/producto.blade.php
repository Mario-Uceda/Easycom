@extends('layouts.navbar')
@section('contenidoPrincipal')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://kit.fontawesome.com/5526ac0700.js" crossorigin="anonymous"></script>
    <link href="{{ asset('css/botones.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/estilos.css') }}" rel="stylesheet" type="text/css">
    <div class="container mt-5">

        <div class="row">
            <div class="col-11">
                <h1 class="mx-auto text-justify m-1">{{ $producto['nombre'] }}</h1>
            </div>
            <div class="col custom-checkbox">
                <input type="checkbox" id="id-of-input"/>
                <label for="id-of-input">
                    <i class="fa-regular fa-heart"></i>
                    <i class="fa-solid fa-heart"></i>
                </label>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-4">
                <h2 class="text-center">Precio actual</h2>
                <div class="row">
                    <div class="col">
                        <button id="amazon" class="col amazon">Amazon</button>
                        <p class="col text-center">{{$precios[count($precios) - 1]['precio']}}€</p>
                        <p class="col text-center" id="ultimaFecha"></p>
                        <p class="col text-center" id="ultimaHora"></p>
                    </div>
                    <div class="col">
                        <button id="mediamarkt" class="col mediamarkt">Mediamarkt</button>
                        <p class="col text-center">{{$precios[count($precios) - 1]['precio']}}€</p>
                        <p class="col text-center" id="ultimaFecha"></p>
                        <p class="col text-center" id="ultimaHora"></p>
                    </div>
                    <div class="col">
                        <button id="wallapop" class="col wallapop">Wallapop</button>
                        <p class="col text-center">{{$precios[count($precios) - 1]['precio']}}€</p>
                        <p class="col text-center" id="ultimaFecha"></p>
                        <p class="col text-center" id="ultimaHora"></p>
                    </div>
                </div>
                <img src="{{$producto['url_img']}}" class=" mt-5 mx-auto d-block" alt="Imagen del producto">
             </div>
            
            <div class="col-8">
                <h2 class="text-center">Historial de Precios</h2>
                <canvas id="myChart" ></canvas>
            </div>
        </div>
        <div>
            <H2>Descripción</H2>
            <p>{{$producto['descripcion']}}</p>
            <h2>Especificaciones técnicas</h2>
            <table class="table table-striped table-bordered" id='especificaciones'></table>        
        </div>
    </div>
    <!--
        favorito
    -->
    <script>
        function cambiarFavorito(favorito){
            const checkbox = document.getElementById('id-of-input');
            if (favorito == 1) {
                checkbox.checked = true;
                document.querySelector('.fa-regular').style.display = 'none';
                document.querySelector('.fa-solid').style.display = 'block';
                return 0;
            } else {
                checkbox.checked = false;
                document.querySelector('.fa-regular').style.display = 'block';
                document.querySelector('.fa-solid').style.display = 'none';
                return 1;
            }
        }
    </script>

    <script>
        const productoId = {{ $producto['id'] }};
        const usuarioId = {{ $favorito['id_user'] }};
        const favorito = {{ $favorito['favorito'] }};
        const historialId = {{ $favorito['id'] }};
        const checkbox = document.getElementById('id-of-input');
        console.log("El producto: "+productoId);
        console.log("El usuario: "+usuarioId);
        console.log("El favorito: "+favorito);        
        cambiarFavorito(favorito);

        checkbox.addEventListener('change', function() {
            const fav = checkbox.checked ? 1 : 0;
            fetch(`/api/producto/${productoId}/favorito`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    id: historialId
                })
            })
            .then(response => response.json())
            .then(data => {console.log('Success:', data);})
            .catch(error => {console.error('Error:', error);});
            favorito = cambiarFavorito(fav);
        });
        

    </script>

    <!--
        Grafica de precios
    -->
    <script>
        const fechas = [
            @foreach ($precios as $precio)
                '{{ $precio['created_at'] }}',
            @endforeach
        ];
        const ultimaFecha = String(fechas[fechas.length - 1]).split(' ')[0];
        const ultimaHora = String(fechas[fechas.length - 1]).split(' ')[1];
        document.getElementById('ultimaFecha').innerHTML=ultimaFecha;
        document.getElementById('ultimaHora').innerHTML=ultimaHora; 
        
        const listaPrecios = [
            @foreach ($precios as $precio)
                parseFloat({{ $precio['precio'] }}),
            @endforeach
        ];

        var ctx = document.getElementById('myChart').getContext('2d');
        var labels = fechas;
        var values = listaPrecios;
        labels = labels.map(e => {
            const m = moment(e, 'YYYY-MM-DD HH:mm:ss');
            return m
        });
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Amazon",
                    data: values,
                    borderWidth: 1,
                    borderColor: 'rgba(67, 110, 218, 0.8)',
                    backgroundColor: 'rgba(67, 110, 218, 0.2)',
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            displayFormats: {
                                'hour': 'HH:mm',
                            },
                            tooltipFormat: 'HH:mm'
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 5,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    }],
                },
            }
        });
    </script>

    <!--
        Especificaciones
    -->
    <script>
        const especificaciones = `{{ $producto['especificaciones_tecnicas'] }}`.split('\n');
        const table = document.createElement('table');
        table.classList.add('table');
        for (let i = 0; i < especificaciones.length; i += 2) {
            const tr = document.createElement('tr');
            const td1 = document.createElement('td');
            td1.innerText = especificaciones[i];
            const td2 = document.createElement('td');
            td2.innerText = especificaciones[i + 1];
            tr.appendChild(td1);
            tr.appendChild(td2);
            table.appendChild(tr);
        }
    
        document.getElementById('especificaciones').appendChild(table);

    </script>

    <!--
        Botones
    -->
    <script>
        const amazon = document.getElementById('amazon');
        const mediamarkt = document.getElementById('mediamarkt');
        const wallapop = document.getElementById('wallapop');

        amazon.addEventListener('click', function() {
            window.open("{{ $precios[0]['url_producto'] }}", '_blank');
        });
        mediamarkt.addEventListener('click', function() {
            window.open("{{ $precios[1]['url_producto'] }}", "_blank");
        });
        wallapop.addEventListener('click', function() {
            window.open("{{ $precios[2]['url_producto'] }}", "_blank");
        });
    </script>

@endsection
    
