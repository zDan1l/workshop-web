@extends('layouts.auth')

@section('title', 'Login - Purple Admin')

@push('styles')
<style>
    .btn-google {
        background-color: #ffffff !important;
        color: #1f1f1f !important;
        border: 0.5px solid #747775 !important;
        border-radius: 4px !important;
        font-family: 'Roboto', sans-serif !important;
        font-weight: 500 !important;
        transition: background-color .218s, border-color .218s, box-shadow .218s !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 10px 16px !important;
        text-decoration: none !important;
        width: 100%;
    }
    .btn-google:hover {
        background-color: #f8f9fa !important;
        border-color: #d2d2d2 !important;
        box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15) !important;
        text-decoration: none !important;
    }
    .google-icon {
        width: 20px;
        height: 20px;
        margin-right: 12px;
    }
</style>
@endpush

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo">
            <img src="{{ asset('assets/images/logo.svg') }}">
        </div>
        <h4>Hello! let's get started</h4>
        <h6 class="font-weight-light">Sign in to continue.</h6>
        <form class="pt-3" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" class="form-control form-control-lg" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
            </div>
            
            <div class="my-3 d-flex align-items-center">
                <hr class="flex-grow-1">
                <span class="mx-3 text-muted">OR</span>
                <hr class="flex-grow-1">
            </div>
            
            <div class="d-grid gap-2">
                <a href="{{ route('auth.google') }}" class="btn-google">
                    <svg class="google-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger mt-3">{{ session('error') }}</div>
            @endif
            
            <div class="text-center mt-4 font-weight-light">
                Don't have an account? <a href="{{ route('register') }}" class="text-primary">Create</a>
            </div>
        </form>
    </div>
</div>
@endsection