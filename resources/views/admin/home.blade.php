@extends('admin.layouts.app')
@push('styles')
    <link href='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css' rel='stylesheet' />
    <link
        rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css"
        type="text/css">
    <style>
        #map { top: 0; bottom: 0; width: 100%;height: 60vh; }
    </style>
@endpush
@section('content')
<div class="card">
    <div class="card-header">Sebaran Pasien</div>

    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div id="map"></div>
    </div>
</div>
@endsection

@push('scripts')
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.js'></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
    <script>
        $(document).ready(function(){
            mapboxgl.accessToken = `{{ env('MAPBOX_TOKEN') }}`;
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/dark-v10',
                center: [116.924, -0.331], // starting position [lng, lat]
                minZoom: 3,
                zoom: 3
            });
            map.on('load', function() {
            // Add a geojson point source.
            // Heatmap layers also work with a vector tile source.
            map.addSource('earthquakes', {
                'type': 'geojson',
                'data': '{{ url("/api/map-sebaran-geojson") }}'
            });
            map.addLayer({
                'id': 'earthquakes-heat',
                'type': 'heatmap',
                'source': 'earthquakes',
                'maxzoom': 9,
                'paint': {
                    // Increase the heatmap weight based on frequency and property magnitude
                    'heatmap-weight': ['interpolate', ['linear'],
                        1,
                        0,
                        0,
                        6,
                        1
                    ],
                    // Increase the heatmap color weight weight by zoom level
                    // heatmap-intensity is a multiplier on top of heatmap-weight
                    'heatmap-intensity': ['interpolate', ['linear'],
                        ['zoom'],
                        0,
                        1,
                        9,
                        3
                    ],
                    // Color ramp for heatmap.  Domain is 0 (low) to 1 (high).
                    // Begin color ramp at 0-stop with a 0-transparancy color
                    // to create a blur-like effect.
                    'heatmap-color': ['interpolate', ['linear'],
                        ['heatmap-density'],
                        0, 'rgba(33,102,172,0)',
                        0.2, 'rgb(103,169,207)',
                        0.4, 'rgb(209,229,240)',
                        0.6, 'rgb(253,219,199)',
                        0.8, 'rgb(239,138,98)',
                        1, 'rgb(178,24,43)'
                    ],
                    // Adjust the heatmap radius by zoom level
                    'heatmap-radius': ['interpolate', ['linear'],
                        ['zoom'],
                        0,
                        2,
                        9,
                        20
                    ],
                    // Transition from heatmap to circle layer by zoom level
                    'heatmap-opacity': ['interpolate', ['linear'],
                        ['zoom'],
                        7,
                        1,
                        9,
                        0
                    ]
                }
            }, 'waterway-label');
            map.addLayer({
                'id': 'earthquakes-point',
                'type': 'circle',
                'source': 'earthquakes',
                'minzoom': 7,
                'paint': {
                    // Size circle radius by earthquake magnitude and zoom level
                    'circle-radius': ['interpolate', ['linear'],
                        ['zoom'],
                        7, ['interpolate', ['linear'],
                            ['get', 'mag'], 1, 1, 6, 4
                        ],
                        16, ['interpolate', ['linear'],
                            ['get', 'mag'], 1, 5, 6, 50
                        ]
                    ],
                    // Color circle by earthquake magnitude
                    'circle-color': ['interpolate', ['linear'],
                        ['get', 'mag'],
                        1, 'rgba(33,102,172,0)',
                        2, 'rgb(103,169,207)',
                        3, 'rgb(209,229,240)',
                        4, 'rgb(253,219,199)',
                        5, 'rgb(239,138,98)',
                        6, 'rgb(178,24,43)'
                    ],
                    'circle-stroke-color': 'white',
                    'circle-stroke-width': 1,
                    // Transition from heatmap to circle layer by zoom level
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
        });
    </script>
@endpush
