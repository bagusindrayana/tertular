@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">   
    <style>
        .select2 {
            width:100%!important;
        }       
    </style> 
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Pasien</li>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <div class="float-left">
            <b>
                Pasien
            </b>
        </div>
        <div class="float-right">
            <form action="{{ route('admin.laporan.export','pasien') }}" method="POST" class="form-inline">
                @csrf
                <div class="form-group m-2" style="min-width: 200px;">
                    <select name="provinsi_id" id="provinsi_id"  class="form-control select2 provinsi_id">
                        @foreach ($provinsis as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group m-2" style="min-width: 200px;">
                    
                    <select name="kota_id" id="kota_id" class="form-control select2-kota kota_id">
                    
                    </select>
                </div>
                <div class="form-group m-2">
                    <button type="submit" class="btn btn-success">
                        Export Excel
                    </button>
                </div>
            </form>
           
        </div>
    </div>
        
    <div class="card-body ">
       
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            No
                        </th>
                        <th>
                            Nama
                        </th>
                        <th>
                            Alamat
                        </th>
                        <th>
                            Kelurahan
                        </th>
                        <th>
                            Kecamatan
                        </th>
                        <th>
                            Kota
                        </th>
                        <th>
                            Provinsi
                        </th>
                        <th>
                            Status
                        </th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td>
                                {{ $data->no }}
                            </td>
                            <td>
                                {{ $data->nama_lengkap }}
                            </td>
                            <td>
                                {{ $data->alamat }}
                            </td>
                            <td>
                                {{ $data->kelurahan->nama_kelurahan ?? "Tidak Ada" }}
                            </td>
                            <td>
                                {{ $data->kecamatan->nama_kecamatan ?? "Tidak Ada" }}
                            </td>
                            <td>
                                {{ $data->kota->nama_kota ?? "Tidak Ada" }}
                            </td>
                            <td>
                                {{ $data->provinsi->nama_provinsi ?? "Tidak Ada" }}
                            </td>
                            <td>
                                {{ $data->status }}
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">
                            {!! $datas->appends(request()->all())->links() !!}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            base_url = `{{ url('/') }}`
            $('.select2').select2();
            var provinsi_id = $("#provinsi_id").val()

            $('#provinsi_id').on('select2:select', function(e) {
                provinsi_id = e.params.data.id;
            });
            
            $('.select2-kota').select2({
                allowClear: true,
                placeholder: "Pilih Kota",
                ajax: {
                    url: function(params) {
                        console.log("ajax func", params, provinsi_id);
                        var url = `${base_url}/select2/kota/`+provinsi_id
                        return url;
                    },
                
                    dataType: 'json',
                    cache: true,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                }
            });
        })
        
    </script>
@endpush
