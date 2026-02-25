@extends('layouts.auth')

@section('title', 'Forgot Password - Purple Admin')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo">
            <img src="{{ asset('assets/images/logo.svg') }}">
        </div>
        <h4>Forgot Password?</h4>
        <h6 class="font-weight-light">No problem. Just let us know your email address and we will email you a password reset link.</h6>
        
        @if (session('status'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form class="pt-3" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <input type="email" 
                       name="email" 
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       placeholder="Email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus>
                @error('email')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                    EMAIL PASSWORD RESET LINK
                </button>
            </div>
            <div class="text-center mt-4 font-weight-light">
                Remember your password? <a href="{{ route('login') }}" class="text-primary">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
