@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.klaster.index') }}">Klaster</a>
    </li>
    <li class="breadcrumb-item active">Edit Klaster</li>
@endsection

@section('content')


<form action="{{ route('admin.klaster.update',$klaster->id) }}" class="form" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Edit Klaster</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama_klaster">Nama Klaster</label>
                    <input type="text" class="form-control" name="nama_klaster" id="nama_klaster" required placeholder="Nama Klaster" value="{{ old('nama_klaster',$klaster->nama_klaster) }}">
                </div>
              

                <div class="form-group">
                    <button class="btn btn-success" nama_klaster="status" value="Publish">
                        Save
                    </button>
                </div>
        </div>
    </div>
</form>
@endsection

