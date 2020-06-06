@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.user.index') }}">User</a>
    </li>
    <li class="breadcrumb-item active">Add User</li>
@endsection

@section('content')


<form action="{{ route('admin.user.store') }}" class="form" method="POST">
    @csrf
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Add User</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama" required placeholder="Nama" value="{{ old('nama') }}">
                </div>

                <div class="form-group">
                    <label for="name">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required placeholder="Username" value="{{ old('username') }}">
                </div>

                <div class="form-group">
                    <label for="level">Level</label>
                    <select name="level" id="level" class="form-control select2">
                        <option value="Admin">Admin</option>
                        <option value="Petugas">Petugas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required placeholder="Password" value="{{ old('password') }}">
                </div>
                
              

                <div class="form-group">
                    <button class="btn btn-success" name="status" value="Publish">
                        Save
                    </button>
                </div>
        </div>
    </div>
</form>
@endsection

