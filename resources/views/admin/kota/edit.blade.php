@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.kota.index') }}">Kota</a>
    </li>
    <li class="breadcrumb-item active">Edit Kota</li>
@endsection


@section('content')


<form action="{{ route('admin.kota.edit',$kota->id) }}" class="form" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Edit Kota</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama_kota">Nama Kota</label>
                    <input type="text" class="form-control" name="nama_kota" id="nama_kota" required placeholder="Nama Kota" value="{{ old('nama_kota',$kota->nama_kota) }}">
                    @error('nama_kota')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="provinsi_id">Porvinsi</label>
                    <select name="provinsi_id" id="provinsi_id" class="form-control select2">
                        @foreach ($provinsis as $id => $name)
                            <option value="{{ $id }}" @if ($id == old('provinsi_id',$kota->provinsi_id))
                                selected
                            @endif>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('provinsi_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              

                <div class="form-group">
                    <button class="btn btn-success">
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
            $('.select2').select2();
        });
    </script>
@endpush

