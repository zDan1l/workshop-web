@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@push('styles')
<style>
    .otp-input-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin: 24px 0;
    }
    .otp-digit {
        width: 52px;
        height: 60px;
        font-size: 28px;
        font-weight: bold;
        text-align: center;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        transition: border-color .2s;
    }
    .otp-digit:focus {
        border-color: #7B5CB8;
        outline: none;
        box-shadow: 0 0 0 3px rgba(123,92,184,0.15);
    }
</style>
@endpush

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo">
            <img src="{{ asset('assets/images/logo.svg') }}">
        </div>
        <h4>Verifikasi OTP</h4>
        <h6 class="font-weight-light">Kode OTP telah dikirim ke email Anda. Berlaku selama 5 menit.</h6>

        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <form class="pt-3" method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <div class="otp-input-group">
                @for($i = 1; $i <= 6; $i++)
                    <input type="text"
                           class="otp-digit"
                           maxlength="1"
                           pattern="[0-9]"
                           inputmode="numeric"
                           data-index="{{ $i }}"
                           required>
                @endfor
                {{-- hidden input yang akan dikirim ke server --}}
                <input type="hidden" name="otp" id="otp-hidden">
            </div>

            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                    VERIFIKASI
                </button>
            </div>

            <div class="text-center mt-4 font-weight-light">
                <a href="{{ route('auth.google') }}" class="text-primary">Kirim ulang kode OTP</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const digits = document.querySelectorAll('.otp-digit');
    const hidden = document.getElementById('otp-hidden');

    digits.forEach((input, idx) => {
        input.addEventListener('input', () => {
            // Hanya izinkan angka
            input.value = input.value.replace(/\D/, '');
            if (input.value && idx < digits.length - 1) {
                digits[idx + 1].focus();
            }
            syncHidden();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && idx > 0) {
                digits[idx - 1].focus();
            }
        });

        // Handle paste on first digit
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasted = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            [...pasted].forEach((char, i) => {
                if (digits[i]) digits[i].value = char;
            });
            if (digits[pasted.length - 1]) digits[pasted.length - 1].focus();
            syncHidden();
        });
    });

    function syncHidden() {
        hidden.value = [...digits].map(d => d.value).join('');
    }
</script>
@endpush
