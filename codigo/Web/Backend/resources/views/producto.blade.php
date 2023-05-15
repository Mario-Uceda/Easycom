@extends('layouts.navbar')
@section('contenidoPrincipal')

    <script>
        const precio_amazon = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Amazon')
                    {{ $precio['precio'] }},
                @endif
            @endforeach
        ];
        const precio_mediamarkt = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Mediamarkt')
                    {{ $precio['precio'] }},
                @endif
            @endforeach
        ];
        const precio_ebay = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Ebay')
                    {{ $precio['precio'] }},
                @endif
            @endforeach
        ];
    </script>
    <script>
        const fecha_amazon = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Amazon')
                    '{{ $precio['created_at'] }}',
                @endif
            @endforeach
        ];
        const fecha_mediamarkt = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Mediamarkt')
                    '{{ $precio['created_at'] }}',
                @endif
            @endforeach
        ];
        const fecha_ebay = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Ebay')
                    '{{ $precio['created_at'] }}',
                @endif
            @endforeach
        ];
    </script>
    <script>
        const url_amazon = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Amazon')
                    '{{ $precio['url_producto'] }}',
                @endif
            @endforeach
        ];
        const url_mediamarkt = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Mediamarkt')
                    '{{ $precio['url_producto'] }}',
                @endif
            @endforeach
        ];
        const url_ebay = [
            @foreach ($precios as $precio)
                @if ($precio['tienda'] === 'Ebay')
                    '{{ $precio['url_producto'] }}',
                @endif
            @endforeach
        ];
    </script>

    <script>
        //funcion para convertir la fecha enformato dd-mm-aaaa y sumarle 2horas
        function convertirFecha(fecha){
            const fechaArray = fecha.split(' ');
            const fechaArray2 = fechaArray[0].split('-');
            const fechaString = fechaArray2[2]+'-'+fechaArray2[1]+'-'+fechaArray2[0];
            const hora = fechaArray[1].split(':');
            //si la hora es mayorque 24, le restamos 24 y añadimos un dia
            if (parseInt(hora[0])+2 > 24) {
                const horaString = (parseInt(hora[0])+2-24)+':'+hora[1]+':'+hora[2];
                const fechaArray3= fechaString.split('-');
                const fechaString2 = (parseInt(fechaArray3[0])+1)+'-'+fechaArray3[1]+'-'+fechaArray3[2];
                return fechaString2+' '+horaString;
            } else{

                const horaString = (parseInt(hora[0])+2)+':'+hora[1]+':'+hora[2];
                return fechaString+' '+horaString;
            }
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://kit.fontawesome.com/5526ac0700.js" crossorigin="anonymous"></script>
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
                        <p class="col text-center" id="ultimoPrecioA"></p>
                        <p class="col text-center" id="ultimaFechaA"></p>
                        <p class="col text-center" id="ultimaHoraA"></p>
                    </div>
                    <div class="col">
                        <button id="mediamarkt" class="col mediamarkt">Mediamarkt</button>
                        <p class="col text-center" id="ultimoPrecioM"></p>
                        <p class="col text-center" id="ultimaFechaM"></p>
                        <p class="col text-center" id="ultimaHoraM"></p>
                    </div>
                    <div class="col">
                        <button id="ebay" class="col ebay">Ebay</button>
                        <p class="col text-center" id="ultimoPrecioE"></p>
                        <p class="col text-center" id="ultimaFechaE"></p>
                        <p class="col text-center" id="ultimaHoraE"></p>
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
        Precios
    -->

    <script>
        if (fecha_amazon.length > 0){
            const ultimaFechaA = convertirFecha(String(fecha_amazon[fecha_amazon.length - 1]));
            const FechaA = String(ultimaFechaA).split(' ')[0];
            const HoraA = String(ultimaFechaA).split(' ')[1];
            const PrecioA = precio_amazon[precio_amazon.length - 1];
            document.getElementById('ultimoPrecioA').innerHTML=PrecioA+'€';
            document.getElementById('ultimaFechaA').innerHTML=FechaA;
            document.getElementById('ultimaHoraA').innerHTML=HoraA;
        }
        if (fecha_mediamarkt.length > 0){
            const ultimaFechaM = convertirFecha(String(fecha_mediamarkt[fecha_mediamarkt.length - 1]));
            const FechaM = String(ultimaFechaM).split(' ')[0];
            const HoraM = String(ultimaFechaM).split(' ')[1];
            const PrecioM = precio_mediamarkt[precio_mediamarkt.length - 1];
            document.getElementById('ultimoPrecioM').innerHTML=PrecioM+'€';
            document.getElementById('ultimaFechaM').innerHTML=FechaM;
            document.getElementById('ultimaHoraM').innerHTML=HoraM;
        }
        if (fecha_ebay.length > 0){
            const ultimaFechaE = convertirFecha(String(fecha_ebay[fecha_ebay.length - 1]));
            const FechaE = String(ultimaFechaE).split(' ')[0];
            const HoraE = String(ultimaFechaE).split(' ')[1];
            const PrecioE = precio_ebay[precio_ebay.length - 1];
            document.getElementById('ultimoPrecioE').innerHTML=PrecioE+'€';
            document.getElementById('ultimaFechaE').innerHTML=FechaE;
            document.getElementById('ultimaHoraE').innerHTML=HoraE;
        }   
    </script>


    <!--
        Grafica de precios
    -->
    <script src='https://cdn.plot.ly/plotly-2.20.0.min.js'></script>

    <script>
        
        amazon = {
            type: 'scatter',
            x: fecha_amazon,
            y: precio_amazon,
            mode: 'scatter',
            name: 'Amazon',
            line: {
                color: 'rgba(67, 110, 218, 0.8)',
                width: 1
            }
        };

        mediamarkt = {
            type: 'scatter',
            x: fecha_mediamarkt,
            y: precio_mediamarkt,
            mode: 'scatter',
            name: 'mediamarkt',
            line: {
                color: 'rgba(255, 0, 0, 0.8)',
                width: 1
            }
        };

        ebay = {
            type: 'scatter',
            x: fecha_ebay,
            y: precio_ebay,
            mode: 'scatter',
            name: 'ebay',
            line: {
                color: 'rgba(0, 255, 0, 0.8)',
                width: 1
            }
        };

        var data = [amazon, mediamarkt, ebay];

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
        const ebay = document.getElementById('ebay');
        amazon.addEventListener('click', function() {
            if (url_amazon.length > 0)
                window.open(url_amazon[0], '_blank');
        });
        mediamarkt.addEventListener('click', function() {
            if (url_mediamarkt.length > 0)
                window.open(url_mediamarkt[0], '_blank');
        });
        ebay.addEventListener('click', function() {
            if (url_ebay.length > 0)
                window.open(url_ebay[0], '_blank');
        });
    </script>

@endsection