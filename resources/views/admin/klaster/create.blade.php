@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.klaster.index') }}">Klaster</a>
    </li>
    <li class="breadcrumb-item active">Add Klaster</li>
@endsection

@section('content')


<form action="{{ route('admin.klaster.store') }}" class="form" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Add Klaster</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama_klaster">Nama Klaster</label>
                    <input type="text" class="form-control" name="nama_klaster" id="nama_klaster" required placeholder="Nama Klaster" value="{{ old('nama_klaster') }}">
                    @error('nama_klaster')
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

