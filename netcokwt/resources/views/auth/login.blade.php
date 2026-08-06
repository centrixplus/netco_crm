@extends('layouts.plain')
@section('title','Login')
@section('StyleContent')
@endsection
@section('PageContent')
<div class="col-md-8 col-lg-6 col-xl-5">
<div class="card overflow-hidden">
<div class="bg-dark">
<div class="row">
<div class="col-12 text-center">
    <div class="text-dark p-4">
        <img src="{{asset('/assets')}}/img/logov1.jpg" alt="" height="55">
    </div>
</div>
</div>
</div>
<div class="card-body pt-0"> 
<div class="auth-logo">
<a href="" class="auth-logo-light">
    <div class="avatar-md profile-user-wid mb-4">
        <span class="avatar-title rounded-circle bg-light">
            <img src="{{asset('/assets')}}/images/logo-light.svg" alt="" class="rounded-circle" height="34">
        </span>
    </div>
</a>

<a href="" class="auth-logo-dark">
    <div class="avatar-md profile-user-wid mb-4">
        <span class="avatar-title rounded-circle bg-light">
            <i class="fa text-dark fa-lg fa-user"></i>
        </span>
    </div>
</a>
</div>
<div class="p-2">
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    </div>
    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group auth-pass-inputgroup">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" aria-describedby="password-addon">
            <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
        </div>
    </div>
    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="remember-check">
        <label class="form-check-label" for="remember-check">
            Remember me
        </label>
    </div>
    
    <div class="mt-3 d-grid">
        <button class="btn btn-dark waves-effect waves-light" type="submit">Log In</button>
    </div>
</form>
</div>

</div>
</div>

</div>
@endsection
@section('ScriptContent')
@endsection