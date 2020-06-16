@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.kelurahan.index') }}">Kelurahan</a>
    </li>
    <li class="breadcrumb-item active">Edit Kelurahan</li>
@endsection


@section('content')


<form action="{{ route('admin.kelurahan.store') }}" class="form" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Edit Kelurahan</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama_kelurahan">Nama Kelurahan</label>
                    <input type="text" class="form-control" name="nama_kelurahan" id="nama_kelurahan" required placeholder="Nama Kelurahan" value="{{ old('nama_kelurahan',$kelurahan->nama_kelurahan) }}">
                    @error('nama_kelurahan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="kecamatan_id">Kecamatan</label>
                    <select name="kecamatan_id" id="kecamatan_id" class="form-control select2">
                        <option value="{{ $kelurahan->kecamatan_id }}" selected="selected">{{ $kelurahan->kecamatan->nama_kecamatan }}</option>
                    </select>
                    @error('kecamatan_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              

                <div class="form-group">
                    <button class="btn btn-success" >
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
                    url: "{{ url('select2/kecamatan') }}",
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

