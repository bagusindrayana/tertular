@extends('auth.layouts.app')

@section('content')
<form method="POST" action="{{ route('admin.login') }}">
    @csrf
    <div class="form-group">
       
        <label class="small mb-1" for="inputusernameAddress">username</label>
        <input id="inputusernameAddress" type="text" class="form-control py-4 @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Username">

        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        
    </div>
    <div class="form-group">
        <label class="small mb-1" for="inputPassword">Password</label>
        
        <input id="inputPassword" type="password" class="form-control py-4 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
    </div>
    <div class="form-group">
        <div class="custom-control custom-checkbox"><input class="custom-control-input" id="rememberPasswordCheck" type="checkbox" name="remember" /><label class="custom-control-label" for="rememberPasswordCheck">Remember password</label></div>
    </div>
    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0"><a class="small" href="password.html">Forgot Password?</a><button class="btn btn-primary" type="submit">Login</button></div>
</form>
 
@endsection
