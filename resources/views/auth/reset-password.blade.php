@extends('layouts.auth')

@section('title', 'Reset Password - Purple Admin')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo">
            <img src="{{ asset('assets/images/logo.svg') }}">
        </div>
        <h4>Reset Password</h4>
        <h6 class="font-weight-light">Enter your new password below.</h6>

        <form class="pt-3" method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <div class="form-group">
                <input type="email" 
                       name="email" 
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       placeholder="Email" 
                       value="{{ old('email', $request->email) }}" 
                       required 
                       autofocus>
                @error('email')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" 
                       name="password" 
                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                       placeholder="New Password" 
                       required>
                @error('password')
                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" 
                       name="password_confirmation" 
                       class="form-control form-control-lg" 
                       placeholder="Confirm New Password" 
                       required>
            </div>
            
            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                    RESET PASSWORD
                </button>
            </div>
            
            <div class="text-center mt-4 font-weight-light">
                Remember your password? <a href="{{ route('login') }}" class="text-primary">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
