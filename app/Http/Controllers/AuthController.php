<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Akun sudah ada, hanya update google_id
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                // Akun baru, buat dengan password random
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => Hash::make(Str::random(32)),
                    'role'      => 'user',
                ]);
            }

            // Buat OTP 6 digit, simpan ke database dan session
            $otp = random_int(100000, 999999);
            $user->update(['otp' => (string) $otp]);
            session([
                'otp_expires' => now()->addMinutes(5)->timestamp,
                'otp_user_id' => $user->id,
            ]);

            // Kirim OTP ke email
            Mail::to($user->email)->send(new OtpMail($otp, $user->name));

            return redirect()->route('otp.verify.form');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    public function showOtpForm()
    {
        if (!session('otp_user_id')) {
            return redirect('/login');
        }

        return view('auth.otp-verify');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        if (!session('otp_user_id')) {
            return redirect('/login');
        }

        if (now()->timestamp > session('otp_expires')) {
            session()->forget(['otp_expires', 'otp_user_id']);
            return back()->with('error', 'OTP sudah kadaluarsa. Silakan login ulang.');
        }

        $user = User::findOrFail(session('otp_user_id'));

        if ($request->otp !== $user->otp) {
            return back()->with('error', 'Kode OTP salah. Silakan coba lagi.');
        }

        // Hapus OTP dari database dan session setelah berhasil
        $user->update(['otp' => null]);
        session()->forget(['otp_expires', 'otp_user_id']);

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }
}
