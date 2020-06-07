@extends('admin.layouts.app')

@push('styles')
    <link href='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css' rel='stylesheet' />
    <link
        rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css"
        type="text/css">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.pasien.index') }}">Pasien</a>
    </li>
    <li class="breadcrumb-item active">Detail Pasien</li>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>
                    Detail Pasien
                </b>
            </div>
            <div class="float-right">
                
            </div>
        </div>
            
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4>
                                Data Diri Pasien
                            </h4>
                        </li>
                        <li class="list-group-item"><strong>No : </strong>{{ $pasien->no }}</li>
                        <li class="list-group-item"><strong>Nama Lengkap : </strong>{{ $pasien->nama_lengkap }}</li>
                        <li class="list-group-item"><strong>Alamat : </strong>{{ $pasien->alamat }}</li>
                        <li class="list-group-item"><strong>Provinsi : </strong>{{ $pasien->provinsi->nama_provinsi ?? "Tidak Ada" }}</li>
                        <li class="list-group-item"><strong>Kota : </strong>{{ $pasien->kota->nama_kota ?? "Tidak Ada" }}</li>
                        <li class="list-group-item"><strong>Kecamatan : </strong>{{ $pasien->kecamatan->nama_kecamatan ?? "Tidak Ada" }}</li>
                        <li class="list-group-item"><strong>Kelurahan : </strong>{{ $pasien->kelurahan->nama_kelurahan ?? "Tidak Ada" }}</li>
                        <li class="list-group-item"><strong>Status : </strong>{{ $pasien->status }}</li>
                        <li class="list-group-item"><strong>Klaster : </strong>{{ $pasien->klaster->nama_klaster }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4>
                                Lokasi Di Nyatakan Positif/Reaktif
                            </h4>
                        </li>
                        <li class="list-group-item"><strong>Lokasi : </strong>{{ $pasien->lokasi }}</li>
                        <li class="list-group-item">
                            <div id="map" style="width: 100%; height: 225px;"></div>
                        </li>
                        
                    </ul>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <h4>
                                Lokasi Riwayat Interaksi/Kontak/Perjalanan Pasien
                            </h4>
                        </li>
                        
                        <li class="list-group-item">
                            <div id="map_interaksi" style="width: 100%; height: 400px;"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.js'></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script> --}}
    <script>
        mapboxgl.accessToken = `{{ env("MAPBOX_TOKEN") }}`;
        //map

        const raw = (`{{ $pasien->koordinat_lokasi }}`).split(',')
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [raw[1],raw[0]],
            zoom: 10
        });

        map.on('load', function() {
            
            const lngLat = {
                lng:raw[1],
                lat:raw[0]
            }
            marker = new mapboxgl.Marker()
            .setLngLat(lngLat)
            .addTo(map);
        });

        //interaksi
        var mapInteraksi = new mapboxgl.Map({
            container: 'map_interaksi',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [116.924, -0.331],
            zoom: 3
        });

        mapInteraksi.on('load', function() {
            mapInteraksi.addSource('interaksis', {
                'type': 'geojson',
                'data': <?=json_encode($pasien->interaksi_geojson);?>
            });

            mapInteraksi.addLayer({
                'id': 'interaksis',
                'type': 'circle',
                'source': 'interaksis',
                'layout': {
                    // make layer visible by default
                    'visibility': 'visible'
                },
                'paint': {
                    'circle-radius': 8,
                    'circle-color': 'rgba(55,148,179,1)'
                },
                
            });
        });
    </script>
@endpush
