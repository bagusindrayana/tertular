@extends('admin.layouts.app')


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.user.index') }}">User</a>
    </li>
    <li class="breadcrumb-item active">Edit User</li>
@endsection

@section('content')


<form action="{{ route('admin.user.update',$user->id) }}" class="form" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <b>Edit User</b>
            </div>
            <div class="float-right">
                
            </div>
            
        </div>

        <div class="card-body">
            
            
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama" required placeholder="Nama" value="{{ old('nama',$user->nama) }}">
                </div>

                <div class="form-group">
                    <label for="name">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required placeholder="Username" value="{{ old('username',$user->username) }}">
                </div>

                <div class="form-group">
                    <label for="level">Level</label>
                    <select name="level" id="level" class="form-control select2">
                        <option value="Admin" @if ($user->level == "Admin")
                            selected
                        @endif>Admin</option>
                        <option @if ($user->level == "Petugas")
                            selected
                        @endif value="Petugas">Petugas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="{{ old('password') }}">
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

