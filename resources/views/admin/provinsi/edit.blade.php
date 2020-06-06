@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.provinsi.index') }}">Provinsi</a>
    </li>
    <li class="breadcrumb-item active">Edit Provinsi</li>
@endsection

@section('content')


<form action="{{ route('admin.provinsi.update',$provinsi->id) }}" class="form" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Edit Provinsi</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama_provinsi">Nama Provinsi</label>
                    <input type="text" class="form-control" name="nama_provinsi" id="nama_provinsi" required placeholder="Nama Provinsi" value="{{ old('nama_provinsi',$provinsi->nama_provinsi) }}">
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

