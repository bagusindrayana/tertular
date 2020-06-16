@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.provinsi.index') }}">Provinsi</a>
    </li>
    <li class="breadcrumb-item active">Add Provinsi</li>
@endsection

@section('content')


<form action="{{ route('admin.provinsi.store') }}" class="form" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Add Provinsi</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama_provinsi">Nama Provinsi</label>
                    <input type="text" class="form-control" name="nama_provinsi" id="nama_provinsi" required placeholder="Nama Provinsi" value="{{ old('nama_provinsi') }}">
                    @error('nama_provinsi')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              

                <div class="form-group">
                    <button class="btn btn-success" nama_provinsi="status" value="Publish">
                        Save
                    </button>
                </div>
        </div>
    </div>
</form>
@endsection

