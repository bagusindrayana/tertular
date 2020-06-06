@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.kecamatan.index') }}">Kecamatan</a>
    </li>
    <li class="breadcrumb-item active">Edit Kecamatan</li>
@endsection


@section('content')


<form action="{{ route('admin.kecamatan.store') }}" class="form" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Edit Kecamatan</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama_kecamatan">Nama Kecamatan</label>
                    <input type="text" class="form-control" name="nama_kecamatan" id="nama_kecamatan" required placeholder="Nama Kecamatan" value="{{ old('nama_kecamatan',$kecamatan->nama_kecamatan) }}">
                </div>

                <div class="form-group">
                    <label for="kota_id">Kota</label>
                    <select name="kota_id" id="kota_id" class="form-control select2">
                        <option value="{{ $kecamatan->kota_id }}" selected="selected">{{ $kecamatan->kota->nama_kota }}</option>
                    </select>
                </div>
              

                <div class="form-group">
                    <button class="btn btn-success" nama_kecamatan="status" value="Publish">
                        Save
                    </button>
                </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                ajax: {
                    url: "{{ url('select2/kota') }}",
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
        });
    </script>
@endpush

