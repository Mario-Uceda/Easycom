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
                <div id='grafico'></div>
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
    <script src='https://cdn.plot.ly/plotly-2.20.0.min.js'></script>
    <script>
        //funcion para convertir la fecha en formato dd-mm-aaaa y sumarle 2horas
        function convertirFecha(fecha){
            const fechaArray = fecha.split(' ');
            const fechaArray2 = fechaArray[0].split('-');
            const fechaString = fechaArray2[2]+'-'+fechaArray2[1]+'-'+fechaArray2[0];
            const hora = fechaArray[1].split(':');
            //si la hora es mayor que 24, le restamos 24 y añadimos un dia
            if (parseInt(hora[0])+2 > 24) {
                const horaString = (parseInt(hora[0])+2-24)+':'+hora[1]+':'+hora[2];
                const fechaArray3 = fechaString.split('-');
                const fechaString2 = (parseInt(fechaArray3[0])+1)+'-'+fechaArray3[1]+'-'+fechaArray3[2];
                return fechaString2+' '+horaString;
            } else{
                const horaString = (parseInt(hora[0])+2)+':'+hora[1]+':'+hora[2];
                return fechaString+' '+horaString;
            }
        }
    </script>
    <script>
        const fechas = [
            @foreach ($precios as $precio)
                '{{ $precio['created_at'] }}',
            @endforeach
        ];
        const ultimaFecha = convertirFecha(String(fechas[fechas.length - 1]));
        const Fecha = String(ultimaFecha).split(' ')[0];
        const Hora = String(ultimaFecha).split(' ')[1];
        document.getElementById('ultimaFecha').innerHTML=Fecha;
        document.getElementById('ultimaHora').innerHTML=Hora; 
        
        const listaPrecios = [
            @foreach ($precios as $precio)
                parseFloat({{ $precio['precio'] }}),
            @endforeach
        ];

        amazon = {
            type: 'scatter',
            x: fechas,
            y: listaPrecios,
            mode: 'lines',
            name: 'Amazon',
            line: {
                color: 'rgba(67, 110, 218, 0.8)',
                width: 1
            }
        };

        mediamarkt = {
            type: 'scatter',
            x: fechas,
            y: listaPrecios.reverse(),
            mode: 'lines',
            name: 'mediamarkt',
            line: {
                color: 'rgba(255, 0, 0, 0.8)',
                width: 1
            }
        };

        var data = [amazon];

        Plotly.newPlot('grafico', data);

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
    
