<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(function () {
            return view('auth.login'); // sesuaikan dengan path file kamu
        });

        Fortify::registerView(function () {
            return view('auth.register'); // sesuaikan dengan path file kamu
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        // custom login
        Fortify::authenticateUsing(function (Request $request) {
            // 1. Cari user berdasarkan email (atau field lain seperti username)
            $user = User::where('email', $request->email)->first();

            // 2. Jika user tidak ditemukan
            if (!$user) {
                return null; // Login gagal
            }

            // 3. Cek apakah user terdaftar via Google (punya google_id tapi password tidak cocok)
            if ($user->google_id && !Hash::check($request->password, $user->password)) {
                // User terdaftar via Google, arahkan untuk login dengan Google atau reset password
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['Akun ini terdaftar melalui Google. Silakan login dengan Google atau reset password terlebih dahulu.'],
                ]);
            }

            // 4. Verifikasi password dan kondisi tambahan
            if (Hash::check($request->password, $user->password)) {
                
                // CONTOH CUSTOM LOGIC: Cek apakah user sudah diverifikasi atau aktif
                // if (!$user->is_active) {
                //     return null; 
                // }

                return $user; // Login berhasil
            }

            return null; // Login gagal
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
