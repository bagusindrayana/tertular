@extends('admin.layouts.app')
@push('styles')
    <link href='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css' rel='stylesheet' />
    <link
        rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css"
        type="text/css">
    <style>
        .map { top: 0; bottom: 0; width: 100%;height: 60vh; }
        .menu {
            background: #fff;
            position: absolute;
            z-index: 1;
            bottom: 50px;
            right: 50px;
            border-radius: 3px;
            width: 120px;
            border: 1px solid rgba(0, 0, 0, 0.4);
            font-family: 'Open Sans', sans-serif;
        }

        .menu a {
            font-size: 13px;
            color: #404040;
            display: block;
            margin: 0;
            padding: 0;
            padding: 10px;
            text-decoration: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.25);
            text-align: center;
        }
        
        .menu a:last-child {
            border: none;
        }
        
        .menu a:hover {
            background-color: #f8f8f8;
            color: #404040;
        }
        
        .menu a.active {
            background-color: #3887be;
            color: #ffffff;
        }
        
        .menu a.active:hover {
            background: #3074a4;
        }

        .map-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.8);
            margin-left: 20px;
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
@endpush
@section('content')
    <div class="card">
        <div class="card-header">Sebaran Pasien</div>

        <div class="card-body">
            <nav id="menu-pasien" class="menu"></nav>
            <div id="map-pasien" class="map"></div>
            <div class="map-overlay legend" id="provinsi-legend"></div>
            <div id="kota-legend" class="map-overlay legend" style="display: none;"></div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Wilayah Rawan (Berdasarkan lokasi interaksi/kontak dan riwayat perjalanan pasien)</div>

        <div class="card-body">
            <div id="map-rawan" class="map"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.js'></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
    <script>
        $(document).ready(function(){
            mapboxgl.accessToken = `{{ env('MAPBOX_TOKEN') }}`;
            var mapPasien = new mapboxgl.Map({
                container: 'map-pasien',
                style: 'mapbox://styles/mapbox/dark-v10',
                center: [116.924, -0.331],
                minZoom: 3,
                zoom: 3
            });
            mapPasien.addControl(new mapboxgl.NavigationControl());
            var zoomThreshold = 4;
            mapPasien.on('load', function() {
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
                mapPasien.addSource('sebarans', {
                    'type': 'geojson',
                    'data': '{{ url("/api/map-sebaran-geojson") }}'
                });
                mapPasien.addLayer({
                    'id': 'sebarans-heat',
                    'type': 'heatmap',
                    'source': 'sebarans',
                    'maxzoom': 9,
                    'paint': {
                        'heatmap-weight': ['interpolate', ['linear'],
                            1,
                            0,
                            0,
                            6,
                            1
                        ],
                        'heatmap-intensity': ['interpolate', ['linear'],
                            ['zoom'],
                            0,
                            1,
                            9,
                            3
                        ],
                        'heatmap-color': ['interpolate', ['linear'],
                            ['heatmap-density'],
                            0, 'rgba(33,102,172,0)',
                            0.2, 'rgb(103,169,207)',
                            0.4, 'rgb(209,229,240)',
                            0.6, 'rgb(253,219,199)',
                            0.8, 'rgb(239,138,98)',
                            1, 'rgb(178,24,43)'
                        ],
                        'heatmap-radius': ['interpolate', ['linear'],
                            ['zoom'],
                            0,
                            2,
                            9,
                            20
                        ],
                        'heatmap-opacity': ['interpolate', ['linear'],
                            ['zoom'],
                            7,
                            1,
                            9,
                            0
                        ]
                    },
                    'layout':{
                        'visibility': 'visible',
                    }
                }, 'waterway-label');
                mapPasien.addLayer({
                    'id': 'sebarans-point',
                    'type': 'circle',
                    'source': 'sebarans',
                    'minzoom': 7,
                    'paint': {
                        'circle-radius': ['interpolate', ['linear'],
                            ['zoom'],
                            7, ['interpolate', ['linear'],
                                2, 1, 1, 6, 4
                            ],
                            16, ['interpolate', ['linear'],
                                2, 1, 5, 6, 50
                            ]
                        ],
                        'circle-color': ['interpolate', ['linear'],
                            2,
                            1, 'rgba(33,102,172,0)',
                            2, 'rgb(103,169,207)',
                            3, 'rgb(209,229,240)',
                            4, 'rgb(253,219,199)',
                            5, 'rgb(239,138,98)',
                            6, 'rgb(178,24,43)'
                        ],
                        'circle-stroke-color': 'white',
                        'circle-stroke-width': 1,
                        'circle-opacity': ['interpolate', ['linear'],
                            ['zoom'],
                            7,
                            0,
                            8,
                            1
                        ]
                    },
                    'layout':{
                        'visibility': 'visible',
                    }
                }, 'waterway-label');

                mapPasien.addSource('provinsis', {
                    'type': 'geojson',
                    'data': '{{ url("/api/map-provinsi-geojson") }}'
                });

                mapPasien.addSource('kotas', {
                    'type': 'geojson',
                    'data': '{{ url("/api/map-kabkota-geojson") }}'
                });
                mapPasien.addLayer({
                    'id': 'provinsi-layer',
                    'type': 'fill',
                    'source': 'provinsis',
                    'maxzoom': zoomThreshold,
                    'tolerance':3.5,
                    'paint': {
                        'fill-color': ['get', 'color'],
                        'fill-opacity': 0.5,
                        'fill-outline-color':'#000000'
                    },
                    'layout':{
                        'visibility': 'visible',
                    }
                });

                mapPasien.addLayer({
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
                    ,
                    'layout':{
                        'visibility': 'visible',
                    }
                });

                var toggleableLayerIds = ['Titik Sebaran', 'Wilayah Sebaran'];
                for (var i = 0; i < toggleableLayerIds.length; i++) {
                    var id = toggleableLayerIds[i];
                    
                    var link = document.createElement('a');
                    link.href = '#';
                    link.className = 'active';
                    link.textContent = id;
                    
                    link.onclick = function(e) {
                        var clickedLayer = this.textContent;
                        e.preventDefault();
                        e.stopPropagation();

                        if(clickedLayer == "Titik Sebaran"){
                            var visibility = mapPasien.getLayoutProperty("sebarans-point", 'visibility');
                        
                            // toggle layer visibility by changing the layout object's visibility property
                            if (visibility === 'visible') {
                                mapPasien.setLayoutProperty("sebarans-point", 'visibility', 'none');
                                mapPasien.setLayoutProperty("sebarans-heat", 'visibility', 'none');
                                this.className = '';
                            } else {
                                this.className = 'active';
                                mapPasien.setLayoutProperty("sebarans-point", 'visibility', 'visible');
                                mapPasien.setLayoutProperty("sebarans-heat", 'visibility', 'visible');
                            }
                        } else {
                            var visibility = mapPasien.getLayoutProperty("provinsi-layer", 'visibility');
                        
                            // toggle layer visibility by changing the layout object's visibility property
                            if (visibility === 'visible') {
                                mapPasien.setLayoutProperty("provinsi-layer", 'visibility', 'none');
                                mapPasien.setLayoutProperty("kota-layer", 'visibility', 'none');
                                this.className = '';
                            } else {
                                this.className = 'active';
                                mapPasien.setLayoutProperty("provinsi-layer", 'visibility', 'visible');
                                mapPasien.setLayoutProperty("kota-layer", 'visibility', 'visible');
                            }
                        }
                        
                        
                    };
                    
                    var layers = document.getElementById('menu-pasien');
                    layers.appendChild(link);
                }
                mapPasien.on('click', 'provinsi-layer', function(e) {
                        
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
                        .addTo(mapPasien);

                        mapPasien.flyTo({
                        center: e.lngLat
                    });
                });

                mapPasien.on('mouseenter', 'provinsi-layer', function() {
                    mapPasien.getCanvas().style.cursor = 'pointer';
                });
                mapPasien.on('mouseleave', 'provinsi-layer', function() {
                    mapPasien.getCanvas().style.cursor = '';
                });

                mapPasien.on('click', 'kota-layer', function(e) {
                    const htmls = `
                        <strong>${e.features[0].properties.nama_kota}</strong>
                        <label class="text-info">Kasus Positif : ${e.features[0].properties.total_kasus_positif}</label>
                        <br/>
                        <label class="text-success">Kasus Sembuh : ${e.features[0].properties.total_kasus_sembuh}</label>
                        <br/>
                        <label class="text-danger">Kasus Meninggal : ${e.features[0].properties.total_kasus_meninggal}</label>
                        <br/>
                    `;
                    new mapboxgl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML(htmls)
                        .addTo(mapPasien);

                        mapPasien.flyTo({
                        center: e.lngLat
                    });
                });

                mapPasien.on('mouseenter', 'kota-layer', function() {
                    mapPasien.getCanvas().style.cursor = 'pointer';
                });
                mapPasien.on('mouseleave', 'kota-layer', function() {
                    mapPasien.getCanvas().style.cursor = '';
                });

                mapPasien.on('zoom', function() {
                    if (mapPasien.getZoom() > zoomThreshold) {
                        provinsiLegend.style.display = 'none';
                        kotaLegend.style.display = 'block';
                    } else {
                        provinsiLegend.style.display = 'block';
                        kotaLegend.style.display = 'none';
                    }
                });
            });
            mapPasien.addControl(new mapboxgl.FullscreenControl());
            

            var mapRawan = new mapboxgl.Map({
                container: 'map-rawan',
                style: 'mapbox://styles/mapbox/dark-v10',
                center: [116.924, -0.331],
                minZoom: 3,
                zoom: 3
            });
            mapRawan.on('load', function() {

                mapRawan.addSource('rawans', {
                    'type': 'geojson',
                    'data': '{{ url("/api/map-rawan-geojson") }}'
                });
                mapRawan.addLayer({
                    'id': 'sebarans-heat',
                    'type': 'heatmap',
                    'source': 'rawans',
                    'maxzoom': 9,
                    'paint': {
                        'heatmap-weight': ['interpolate', ['linear'],
                            1,
                            0,
                            0,
                            6,
                            1
                        ],
                        'heatmap-intensity': ['interpolate', ['linear'],
                            ['zoom'],
                            0,
                            1,
                            9,
                            3
                        ],
                        'heatmap-color': ['interpolate', ['linear'],
                            ['heatmap-density'],
                            0, 'rgba(33,102,172,0)',
                            0.2, 'rgb(103,169,207)',
                            0.4, 'rgb(209,229,240)',
                            0.6, 'rgb(253,219,199)',
                            0.8, 'rgb(239,138,98)',
                            1, 'rgb(178,24,43)'
                        ],
                        'heatmap-radius': ['interpolate', ['linear'],
                            ['zoom'],
                            0,
                            2,
                            9,
                            20
                        ],
                        'heatmap-opacity': ['interpolate', ['linear'],
                            ['zoom'],
                            7,
                            1,
                            9,
                            0
                        ]
                    }
                }, 'waterway-label');
                mapRawan.addLayer({
                    'id': 'rawans-point',
                    'type': 'circle',
                    'source': 'rawans',
                    'minzoom': 7,
                    'paint': {
                        'circle-radius': ['interpolate', ['linear'],
                            ['zoom'],
                            7, ['interpolate', ['linear'],
                                2, 1, 1, 6, 4
                            ],
                            16, ['interpolate', ['linear'],
                                2, 1, 5, 6, 50
                            ]
                        ],
                        'circle-color': ['interpolate', ['linear'],
                            2,
                            1, 'rgba(33,102,172,0)',
                            2, 'rgb(103,169,207)',
                            3, 'rgb(209,229,240)',
                            4, 'rgb(253,219,199)',
                            5, 'rgb(239,138,98)',
                            6, 'rgb(178,24,43)'
                        ],
                        'circle-stroke-color': 'white',
                        'circle-stroke-width': 1,
                        'circle-opacity': ['interpolate', ['linear'],
                            ['zoom'],
                            7,
                            0,
                            8,
                            1
                        ]
                    }
                }, 'waterway-label');
            });
            mapRawan.addControl(new mapboxgl.FullscreenControl());
            mapRawan.addControl(new mapboxgl.NavigationControl());
        });
    </script>
@endpush
