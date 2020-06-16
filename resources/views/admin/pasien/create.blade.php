@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link href='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.css' rel='stylesheet' />
    <link
        rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css"
        type="text/css">
    <style>
        .select2 {
            width:100%!important;
        }       
    </style>

@endpush

@section('breadcrumb')

    <li class="breadcrumb-item">
        <a href="{{ route('admin.pasien.index') }}">Pasien</a>
    </li>
    <li class="breadcrumb-item active">Add Pasien</li>
@endsection


@section('content')


    <form action="{{ route('admin.pasien.store') }}" class="form" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="float-left">
                    <b>Add Pasien</b>
                </div>
                <div class="float-right">
                    
                </div>
                
            </div>

            <div class="card-body">
                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" required placeholder="Nama lengkap" value="{{ old('nama_lengkap') }}">
                                @error('nama_lengkap')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control select2">
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" required placeholder="Tanggal Lahir" value="{{ old('nama_lengkap') }}">
                                @error('tanggal_lahir')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" id="alamat"  class="form-control" placeholder="Alamat">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan"  class="form-control" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="provinsi_id">Provinsi</label>
                                <select name="provinsi_id" id="provinsi_id" class="form-control select2 provinsi_id">
                                    @foreach ($provinsis as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('provinsi_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
        
                            <div class="form-group">
                                <label for="kota_id">Kota</label>
                                <select name="kota_id" id="kota_id" class="form-control select2-kota kota_id">
                                
                                </select>
                                @error('kota_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
        
                            <div class="form-group">
                                <label for="kecamatan_id">Kecamatan</label>
                                <select name="kecamatan_id" id="kecamatan_id" class="form-control select2-kecamatan kecamatan_id">
                                
                                </select>
                                @error('kecamatan_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
        
                            <div class="form-group">
                                <label for="kelurahan_id">Kelurahan</label>
                                <select name="kelurahan_id" id="kelurahan_id" class="form-control select2-kelurahan kelurahan_id">
                                
                                </select>
                                @error('kelurahan_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control select2" requireds>
                                    <option value="Suspect">Suspect</option>
                                    <option value="ODP">ODP (Orang Dalam Pemantauan)</option>
                                    <option value="PDP">PDP (Pasien Dalam Pengawasan)</option>
                                    <option value="OTG">OTG (Orang Tanpa Gejala)</option>
                                    <option value="Positif">Positif</option>
                                    <option value="Sembuh">Sembuh</option>
                                    <option value="Meninggal">Meninggal</option>
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
        
                            <div class="form-group">
                                <label for="klaster_id">Klaster</label>
                                <select name="klaster_id" id="klaster_id" class="form-control select2" required>
                                    @foreach ($klasters as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('klaster_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label >Lokasi Dinyatakan Positif/Reaktif</label>
                                <input type="text" name="lokasi" class="form-control mb-2 lokasi" id="lokasi" placeholder="Lokasi">
                                <input type="text" name="koordinat_lokasi" class="form-control mb-2 koordinat_lokasi" id="koordinat_lokasi" placeholder="Kordinat Lokasi">
                                <button type="button" class="btn btn-primary pilih-lokasi" data-toggle="modal" data-target="#pilihLokasi" type="button">Pilih Lokasi...</button>
                    
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lokasi_provinsi_id">Provinsi</label>
                                <select name="lokasi_provinsi_id" id="lokasi_provinsi_id" class="form-control select2 provinsi_id">
                                    @foreach ($provinsis as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
        
                            <div class="form-group">
                                <label for="lokasi_kota_id">Kota</label>
                                <select name="lokasi_kota_id" id="lokasi_kota_id" class="form-control select2-kota kota_id">
                                
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="lokasi_tanggal">Tanggal</label>
                                <input type="date" class="form-control" name="lokasi_tanggal" id="lokasi_tanggal" required placeholder="Tanggal" value="{{ old('lokasi_tanggal') }}">
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            
        
                            <div class="form-group">
                                <label for="lokasi_kecamatan_id">Kecamatan</label>
                                <select name="lokasi_kecamatan_id" id="lokasi_kecamatan_id" class="form-control select2-kecamatan kecamatan_id">
                                
                                </select>
                            </div>
        
                            <div class="form-group">
                                <label for="lokasi_kelurahan_id">Kelurahan</label>
                                <select name="lokasi_kelurahan_id" id="lokasi_kelurahan_id" class="form-control select2-kelurahan kelurahan_id">
                                
                                </select>
                            </div>

                            
                        </div>
                    </div>

                    <div class="row  mt-4">
                        <div class="col-md-12">
                            

                            <div class="form-group">
                                <label for="" class="text-danger"><strong>Kontak/Interaksi/Perjalanan</strong></label>
                                <div class="input_fields_wrap">
                                    <div class="row" id="clone">
                                        <div class="col-md-12">
                                            <hr/>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label >Keterangan</label>
                                                <textarea name="interaksi_keterangan[]"  class="form-control"></textarea>
                                            </div>
        
                                            <div class="form-group">
                                                <label >Tanggal Interaksi/Kontak/Perjalanan</label>
                                                <input type="date" class="form-control" name="interaksi_tanggal[]" placeholder="Tanggal Interaksi">
                                            </div>
                                    
                                            <div class="form-group">
                                                <label for="nama_lengkap">Lokasi</label>
                                                <input type="text" class="form-control mb-2 lokasi" name="interaksi_lokasi[]"  placeholder="Lokasi">
                                                <input type="text" class="form-control mb-2 koordinat_lokasi" name="interaksi_koordinat_lokasi[]"  placeholder="Kordinat Lokasi">
                                                <button type="button" class="btn btn-primary pilih-lokasi" data-toggle="modal" data-target="#pilihLokasi" type="button">Pilih Lokasi...</button>
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Provinsi</label>
                                                <select name="interaksi_provinsi_id[]"  class="form-control interaksi_provinsi_id select2">
                                                    @foreach ($provinsis as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label >Kota</label>
                                                <select name="interaksi_kota_id[]"  class="form-control interaksi_kota_id ajax" data-url="kota">
                                                    
                                                </select>
                                            </div>
                            
                                            <div class="form-group">
                                                <label >Kecamatan</label>
                                                <select name="interaksi_kecamatan_id[]"  class="form-control interaksi_kecamatan_id ajax" data-url="kecamatan">
                                                    
                                                </select>
                                            </div>
                            
                                            <div class="form-group">
                                                <label >Kelurahan</label>
                                                <select name="interaksi_kelurahan_id[]"  class="form-control interaksi_kelurahan_id ajax" data-url="kelurahan">
                                                   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="action col-md-12">
                                            <button  class="add_field_button btn btn-info active" type="button">Add More Field</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-success" nama_pasien="status" value="Publish">
                            Save
                        </button>
                    </div>
            </div>
        </div>
    </form>

    <div class="modal" tabindex="-1" role="dialog" id="pilihLokasi">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Pilih Lokasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div id="map" style="width: 100%; height: 400px;"></div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.10.1/mapbox-gl.js'></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
    <script src="{{ url('admin/js/admin.js') }}"></script>
    <script>
        $(document).ready(function(){
            base_url = `{{ url('/') }}`
            mapboxToken = `{{ env('MAPBOX_TOKEN') }}`;
            geocodeToken = `{{ env('GEOCODE_TOKEN') }}`;
            initPasienFeature(base_url,mapboxToken,geocodeToken);
        })
    </script>
@endpush

