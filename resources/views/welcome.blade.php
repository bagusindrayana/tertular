<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css' rel='stylesheet' />
        <link
            rel="stylesheet"
            href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css"
            type="text/css">

            <style>
                .map-overlay {
                    position: absolute;
                    bottom: 0;
                    right: 0;
                    background: rgba(255, 255, 255, 0.8);
                    margin-right: 20px;
                    font-family: Arial, sans-serif;
                    overflow: auto;
                    border-radius: 3px;
                }

                .legend {
                    padding: 10px;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
                    line-height: 18px;
                    height: 250px;
                    margin-bottom: 40px;
                    width: 200px;
                }

                .legend-key {
                    display: inline-block;
                    border-radius: 20%;
                    width: 10px;
                    height: 10px;
                    margin-right: 5px;
                }
            </style>
        
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
            <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home
                    <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                </ul>
            </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container">
            <div class="row">
                <div class="col-lg-12 my-4">
                    <div id="map" style="width: 100%; height: 500px;"></div>
                    <div class="map-overlay legend" id="provinsi-legend"></div>
                    <div id="kota-legend" class="map-overlay legend" style="display: none;"></div>
                </div>
            </div>
        </div>



        {{-- scripts --}}
        <script src='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.js'></script>
        <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
        <script>
            mapboxgl.accessToken = `{{ env('MAPBOX_TOKEN') }}`;
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/light-v9?optimize=true', // stylesheet location
                center: [116.92458132655304, -0.3310491154491473], // starting position [lng, lat]
                minZoom: 3,
                zoom: 3
            });
            var geocoder = new MapboxGeocoder({
                    accessToken: mapboxgl.accessToken,
                    mapboxgl: mapboxgl,
                    marker:false,
                    placeholder: 'Masukan kata kunci...',
                    zoom:3
                })
            map.addControl(
                geocoder
            );

            var zoomThreshold = 4;
            map.on('load', function() {
                var provinsiLegend = document.getElementById('provinsi-legend');
                var kotaLegend = document.getElementById('kota-legend');

                var provinsiLayer = ['0-100', '100-200', '200-500', '500-1000', '1000-2000', '2000-5000', '5000-10000', '10000+'];
                var kotaLayer = ['0-10', '10-20', '20-50', '50-100', '100-200', '200-500', '500-1000', '1000+'];

                var colors = ['#FFEDA0', '#FED976', '#FEB24C', '#FD8D3C', '#FC4E2A', '#E31A1C', '#BD0026', '#800026'];
               
                for (i = 0; i < provinsiLayer.length; i++) {
                    var layer = provinsiLayer[i];
                    var color = colors[i];
                    var item = document.createElement('div');
                    var key = document.createElement('span');
                    key.className = 'legend-key';
                    key.style.backgroundColor = color;

                    var value = document.createElement('span');
                    value.innerHTML = layer;
                    item.appendChild(key);
                    item.appendChild(value);
                    provinsiLegend.appendChild(item);
                }

                for (i = 0; i < kotaLayer.length; i++) {
                    var layer = kotaLayer[i];
                    var color = colors[i];
                    var item = document.createElement('div');
                    var key = document.createElement('span');
                    key.className = 'legend-key';
                    key.style.backgroundColor = color;

                    var value = document.createElement('span');
                    value.innerHTML = layer;
                    item.appendChild(key);
                    item.appendChild(value);
                    kotaLegend.appendChild(item);
                }
                map.addSource('provinsis', {
                    'type': 'geojson',
                    'data': '{{ url("/api/map-provinsi-geojson") }}'
                });

                map.addSource('kotas', {
                    'type': 'geojson',
                    'data': '{{ url("/api/map-kabkota-geojson") }}'
                });
                map.addLayer({
                    'id': 'provinsi-layer',
                    'type': 'fill',
                    'source': 'provinsis',
                    'maxzoom': zoomThreshold,
                    'tolerance':3.5,
                    'paint': {
                        'fill-color': ['get', 'color'],
                        'fill-opacity': 0.5,
                        'fill-outline-color':'#000000'
                    }
                });

                map.addLayer({
                    'id': 'kota-layer',
                    'type': 'fill',
                    'source': 'kotas',
                    'minzoom': zoomThreshold,
                    'tolerance':3.5,
                    'paint': {
                        'fill-color': ['get', 'color'],
                        'fill-opacity': 0.5,
                        'fill-outline-color':'#000000'
                    }
                });

                map.on('click', 'provinsi-layer', function(e) {
                    console.log(e)
                    const htmls = `
                        <strong>${e.features[0].properties.nama_provinsi}</strong>
                        <br/>
                        <label class="text-info">Kasus Positif : ${e.features[0].properties.total_kasus_positif}</label>
                        <br/>
                        <label class="text-success">Kasus Sembuh : ${e.features[0].properties.total_kasus_sembuh}</label>
                        <br/>
                        <label class="text-danger">Kasus Meninggal : ${e.features[0].properties.total_kasus_meninggal}</label>
                    `;
                    new mapboxgl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(htmls)
                        .addTo(map);

                    map.flyTo({
                        center: e.lngLat
                    });
                });

                map.on('mouseenter', 'provinsi-layer', function() {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'provinsi-layer', function() {
                    map.getCanvas().style.cursor = '';
                });

                map.on('click', 'kota-layer', function(e) {
                    console.log(e)
                    const htmls = `
                        <strong>${e.features[0].properties.nama_kota}</strong>
                        <br/>
                        <label class="text-info">Kasus Positif : ${e.features[0].properties.total_kasus_positif}</label>
                        <br/>
                        <label class="text-success">Kasus Sembuh : ${e.features[0].properties.total_kasus_sembuh}</label>
                        <br/>
                        <label class="text-danger">Kasus Meninggal : ${e.features[0].properties.total_kasus_meninggal}</label>
                    `;
                    new mapboxgl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(htmls)
                        .addTo(map);

                    map.flyTo({
                        center: e.lngLat
                    });
                });

                map.on('mouseenter', 'kota-layer', function() {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'kota-layer', function() {
                    map.getCanvas().style.cursor = '';
                });

                map.on('zoom', function() {
                    if (map.getZoom() > zoomThreshold) {
                        provinsiLegend.style.display = 'none';
                        kotaLegend.style.display = 'block';
                    } else {
                        provinsiLegend.style.display = 'block';
                        kotaLegend.style.display = 'none';
                    }
                });
            });
        </script>

    </body>
</html>

