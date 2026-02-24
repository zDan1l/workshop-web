# Penjelasan Sistem Autentikasi (Register & Login)

Proyek ini menggunakan **tiga mekanisme autentikasi** yang bekerja bersama-sama:

1. **Laravel Fortify** – menangani register dan login form standar (email + password)
2. **Laravel Socialite** – menangani login via Google OAuth
3. **OTP (One-Time Password)** – lapisan keamanan tambahan khusus untuk Google OAuth

---

## Daftar Isi

- [Paket yang Digunakan](#paket-yang-digunakan)
- [Struktur Database (Migrasi)](#struktur-database-migrasi)
- [Alur Register (Pendaftaran)](#alur-register-pendaftaran)
- [Alur Login Standar (Email + Password)](#alur-login-standar-email--password)
- [Alur Login Google + OTP](#alur-login-google--otp)
- [Alur Logout](#alur-logout)
- [Middleware dan Otorisasi](#middleware-dan-otorisasi)
- [Event & Listener (Manajemen Sesi)](#event--listener-manajemen-sesi)
- [Diagram Alur Visual](#diagram-alur-visual)

---

## Paket yang Digunakan

| Paket                      | Namespace                              | Fungsi                                                        |
| -------------------------- | -------------------------------------- | ------------------------------------------------------------- |
| `laravel/fortify`          | `Laravel\Fortify\Fortify`              | Engine autentikasi headless (login, register, reset password) |
| `laravel/socialite`        | `Laravel\Socialite\Facades\Socialite`  | OAuth login pihak ketiga (Google, GitHub, dll)                |
| `illuminate/auth`          | `Illuminate\Support\Facades\Auth`      | Core autentikasi Laravel (session, guard)                     |
| `illuminate/hashing`       | `Illuminate\Support\Facades\Hash`      | Hashing password (bcrypt)                                     |
| `illuminate/mail`          | `Illuminate\Support\Facades\Mail`      | Pengiriman email OTP                                          |
| `illuminate/validation`    | `Illuminate\Validation\Rules\Password` | Aturan validasi password                                      |
| `illuminate/rate-limiting` | `Illuminate\Cache\RateLimiting\Limit`  | Pembatasan percobaan login                                    |

---

## Struktur Database (Migrasi)

Tabel `users` dibangun dari beberapa migrasi:

```
users
├── id                  (primary key)
├── name                (string)
├── email               (string, unique)
├── password            (string, hashed)
├── role                (enum: 'admin' | 'user', default: 'user')
├── google_id           (string, nullable) ← untuk login Google
├── otp                 (string 6 char, nullable) ← kode OTP sementara
├── remember_token      (string, nullable)
├── email_verified_at   (timestamp, nullable)
└── timestamps
```

### File migrasi terkait auth:

- `0001_01_01_000000_create_users_table.php` – membuat tabel users dasar
- `2026_02_15_002726_add_role_to_users_table.php` – menambah kolom `role`
- `2026_02_18_020647_add_google_id_to_users_table.php` – menambah kolom `google_id`
- `2026_02_20_000001_add_otp_to_users_table.php` – menambah kolom `otp`

---

## Alur Register (Pendaftaran)

### Skema

```
User mengisi form register
    ↓
POST /register  (ditangani Fortify secara otomatis)
    ↓
FortifyServiceProvider::boot()
    └── Fortify::createUsersUsing(CreateNewUser::class)
    ↓
CreateNewUser::create($input)
    ├── Validator::make() → validasi name, email, password
    └── User::create() → simpan ke database
    ↓
Fortify redirect ke /dashboard
```

### File-file yang terlibat

#### 1. `app/Providers/FortifyServiceProvider.php`

```php
Fortify::registerView(function () {
    return view('auth.register'); // tampilkan halaman register
});

Fortify::createUsersUsing(CreateNewUser::class); // delegasi pembuatan user
```

`Fortify::registerView()` memberi tahu Fortify view mana yang harus ditampilkan saat `GET /register`.

`Fortify::createUsersUsing()` mendaftarkan kelas yang bertugas membuat user baru. Fortify memanggil `create()` saat `POST /register`.

#### 2. `app/Actions/Fortify/CreateNewUser.php`

```php
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules; // trait untuk aturan validasi password

    public function create(array $input): User
    {
        // Validasi input dari form
        Validator::make($input, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique(User::class)],
            'password' => $this->passwordRules(), // dari trait
        ])->validate();

        // Buat user baru, password di-hash otomatis
        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']), // bcrypt hashing
        ]);
    }
}
```

**Penjelasan baris-per-baris:**

- `implements CreatesNewUsers` – kontrak (interface) dari Fortify yang wajib diimplementasi
- `use PasswordValidationRules` – trait yang menyediakan method `passwordRules()`
- `Validator::make()` – membuat validator dengan aturan yang ditentukan
- `email:rfc,dns` – validasi format email sesuai standar RFC dan cek via DNS
- `Rule::unique(User::class)` – pastikan email belum dipakai di tabel users
- `Hash::make()` – hash password menggunakan bcrypt (tidak tersimpan plain text)

#### 3. `app/Actions/Fortify/PasswordValidationRules.php`

```php
trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }
}
```

- `Password::default()` – aturan password bawaan Laravel (min 8 karakter, dsb)
- `'confirmed'` – memastikan ada field `password_confirmation` yang cocok di form

---

## Alur Login Standar (Email + Password)

### Skema

```
User mengisi form login (email + password)
    ↓
POST /login  (ditangani Fortify secara otomatis)
    ↓
FortifyServiceProvider → Fortify::authenticateUsing(callback)
    ↓
Callback:
    ├── User::where('email', $request->email)->first()
    ├── Hash::check($request->password, $user->password)
    └── return $user (jika cocok) atau null (jika gagal)
    ↓
Auth::login($user) dipanggil oleh Fortify
    ↓
Event Login fired
    ↓
Listener StoreUserInSession::handle() → simpan data user ke session
    ↓
Redirect ke /dashboard (sesuai config fortify.php 'home' => '/dashboard')
```

### File-file yang terlibat

#### 1. `app/Providers/FortifyServiceProvider.php` – fungsi `authenticateUsing`

```php
Fortify::authenticateUsing(function (Request $request) {
    // Cari user berdasarkan email
    $user = User::where('email', $request->email)->first();

    // Cek apakah user ada DAN password cocok
    if ($user && Hash::check($request->password, $user->password)) {
        return $user; // kembalikan user → Fortify akan login user ini
    }

    return null; // kembalikan null → Fortify akan menolak login
});
```

`Fortify::authenticateUsing()` adalah **custom authentication callback**. Alih-alih menggunakan logika default Fortify, kita bisa menentukan sendiri cara memverifikasi user. Jika callback mengembalikan `null`, Fortify akan menambahkan error ke response.

#### 2. `config/fortify.php` – konfigurasi utama

```php
'guard'    => 'web',      // guard yang digunakan (session-based)
'username' => 'email',    // field yang dipakai sebagai "username"
'home'     => '/dashboard', // redirect setelah login/register berhasil
'limiters' => [
    'login' => 'login',   // nama rate limiter untuk login
],
'features' => [
    Features::registration(),           // aktifkan fitur register
    Features::resetPasswords(),         // aktifkan reset password
    Features::twoFactorAuthentication() // aktifkan 2FA
]
```

#### 3. Rate Limiting (di `FortifyServiceProvider`)

```php
RateLimiter::for('login', function (Request $request) {
    $throttleKey = Str::transliterate(
        Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
    );
    return Limit::perMinute(5)->by($throttleKey);
});
```

Rate limiter membatasi percobaan login hingga **5 kali per menit** per kombinasi email + IP. Jika melebihi, akan menerima error HTTP 429 (Too Many Requests).

`$throttleKey` dibuat dari gabungan email (lowercase) + IP address, sehingga berbeda orang dengan IP berbeda tidak saling mempengaruhi.

---

## Alur Login Google + OTP

Ini adalah alur yang lebih kompleks karena melibatkan **dua langkah**: OAuth dari Google, lalu verifikasi OTP via email.

### Skema Lengkap

```
User klik "Login dengan Google"
    ↓
GET /auth/google → AuthController::redirectToGoogle()
    └── Socialite::driver('google')->redirect()
    ↓ (browser redirect ke halaman login Google)
User login/pilih akun Google
    ↓
Google callback ke GET /auth/google/callback
    ↓
AuthController::handleGoogleCallback()
    ├── Socialite::driver('google')->user() → ambil data dari Google
    ├── Cari user di DB berdasarkan email Google
    │   ├── Sudah ada? → update google_id
    │   └── Belum ada? → buat user baru dengan password random
    ├── Generate OTP 6 digit (random_int)
    ├── Simpan OTP ke database (kolom otp)
    ├── Simpan otp_user_id + otp_expires ke session
    └── Kirim email OTP via Mail::to()->send(new OtpMail())
    ↓
Redirect ke GET /auth/otp (halaman form OTP)
    ↓
User memasukkan kode OTP dari email
    ↓
POST /auth/otp → AuthController::verifyOtp()
    ├── Validasi: otp harus 6 digit
    ├── Cek session otp_user_id ada?
    ├── Cek apakah OTP sudah kadaluarsa? (otp_expires)
    ├── Ambil user dari DB
    ├── Bandingkan OTP input vs OTP di database
    ├── Hapus OTP dari DB dan session (invalidate)
    └── Auth::login($user, true) → login permanen
    ↓
Event Login fired
    ↓
StoreUserInSession → simpan data user ke session
    ↓
Redirect ke /dashboard
```

### File-file yang terlibat

#### 1. `app/Http/Controllers/AuthController.php` – `redirectToGoogle()`

```php
public function redirectToGoogle()
{
    return Socialite::driver('google')->redirect();
}
```

`Socialite::driver('google')` mengambil konfigurasi Google dari `config/services.php` (client_id, client_secret, redirect_uri yang disimpan di `.env`). Method `redirect()` membuat URL otorisasi Google dan me-redirect browser ke sana.

#### 2. `AuthController::handleGoogleCallback()`

```php
public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->user();
    // $googleUser berisi: name, email, id (google_id), avatar, token

    $user = User::where('email', $googleUser->getEmail())->first();

    if ($user) {
        $user->update(['google_id' => $googleUser->getId()]);
    } else {
        $user = User::create([
            'name'      => $googleUser->getName(),
            'email'     => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'password'  => Hash::make(Str::random(32)), // password random agar kolom tidak null
            'role'      => 'user',
        ]);
    }

    // Buat OTP 6 digit
    $otp = random_int(100000, 999999);

    // Simpan ke DB (sementara, akan dihapus setelah verifikasi)
    $user->update(['otp' => (string) $otp]);

    // Simpan metadata ke session (bukan OTP-nya langsung, untuk keamanan)
    session([
        'otp_expires' => now()->addMinutes(5)->timestamp, // expired dalam 5 menit
        'otp_user_id' => $user->id,
    ]);

    // Kirim OTP ke email user
    Mail::to($user->email)->send(new OtpMail($otp, $user->name));

    return redirect()->route('otp.verify.form');
}
```

**Mengapa password random untuk user Google?**  
Kolom `password` di database memiliki constraint NOT NULL dan tidak boleh kosong. User yang login via Google tidak punya password konvensional, jadi diberi password acak yang tidak diketahui siapapun. User ini tetap bisa login hanya melalui Google (atau jika mau menggunakan "Lupa Password").

#### 3. `app/Mail/OtpMail.php`

```php
class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public int $otp,          // kode OTP
        public string $userName   // nama penerima
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Kode OTP Login Anda');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp'); // template email di resources/views/emails/otp.blade.php
    }
}
```

- `Mailable` – base class untuk semua email di Laravel
- `Queueable` – memungkinkan email dikirim via queue (async) → tidak memblokir response
- `SerializesModels` – memastikan Eloquent model bisa diserialisasi saat antrian
- Property `public $otp` dan `$userName` otomatis tersedia di view template email

#### 4. `AuthController::verifyOtp()`

```php
public function verifyOtp(Request $request)
{
    // Validasi input: wajib ada, harus 6 digit angka
    $request->validate(['otp' => 'required|digits:6']);

    // Guard: jika session tidak ada, kembalikan ke login
    if (!session('otp_user_id')) {
        return redirect('/login');
    }

    // Cek expiry: bandingkan timestamp sekarang vs batas waktu di session
    if (now()->timestamp > session('otp_expires')) {
        session()->forget(['otp_expires', 'otp_user_id']);
        return back()->with('error', 'OTP sudah kadaluarsa. Silakan login ulang.');
    }

    $user = User::findOrFail(session('otp_user_id'));

    // Bandingkan OTP input vs OTP di database
    if ($request->otp !== $user->otp) {
        return back()->with('error', 'Kode OTP salah. Silakan coba lagi.');
    }

    // Sukses: hapus OTP agar tidak bisa dipakai lagi (one-time use)
    $user->update(['otp' => null]);
    session()->forget(['otp_expires', 'otp_user_id']);

    // Login user (true = remember me)
    Auth::login($user, true);

    return redirect()->intended('/dashboard');
}
```

`redirect()->intended('/dashboard')` – jika sebelum login user mencoba membuka URL tertentu (misal `/dashboard`) dan diredirect ke login, setelah berhasil login mereka akan dikirim kembali ke URL yang awalnya dituju.

---

## Alur Logout

```
User klik tombol logout
    ↓
POST /logout  (ditangani Fortify secara otomatis)
    ↓
Auth::logout() → invalidasi session
    ↓
Event Logout fired
    ↓
Listener ClearUserFromSession::handle()
    └── session()->forget('user') → hapus data user dari session
    ↓
Redirect ke / (halaman login)
```

#### `app/Listeners/ClearUserFromSession.php`

```php
class ClearUserFromSession
{
    public function handle(Logout $event): void
    {
        session()->forget('user'); // bersihkan data user dari session
    }
}
```

---

## Middleware dan Otorisasi

Setelah login, akses ke halaman dilindungi oleh dua middleware kustom:

### `app/Http/Middleware/UserMiddleware.php`

```php
public function handle(Request $request, Closure $next): Response
{
    $user = session('user'); // ambil data dari session

    if (!$user) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    return $next($request); // lanjutkan ke controller
}
```

Middleware `user` hanya memeriksa apakah data user ada di session. Semua user yang sudah login (baik admin maupun user biasa) bisa melewati middleware ini.

### `app/Http/Middleware/AdminMiddleware.php`

```php
public function handle(Request $request, Closure $next): Response
{
    $user = session('user');

    if (!$user) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    if ($user['role'] !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses.');
    }

    return $next($request);
}
```

Middleware `admin` memeriksa dua hal: apakah sudah login, dan apakah role-nya adalah `admin`.

### Penggunaan di Routes

```php
// Hanya admin
Route::middleware('admin')->group(function () {
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
});

// Semua user yang sudah login
Route::middleware('user')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('buku', BukuController::class)->only(['index', 'show']);
});
```

---

## Event & Listener (Manajemen Sesi)

Laravel menggunakan sistem **Event-Listener** untuk menjalankan aksi ketika sesuatu terjadi.

### Registrasi di `app/Providers/AppServiceProvider.php`

```php
public function boot(): void
{
    // Setiap kali event Login fired → jalankan StoreUserInSession
    Event::listen(Login::class, StoreUserInSession::class);

    // Setiap kali event Logout fired → jalankan ClearUserFromSession
    Event::listen(Logout::class, ClearUserFromSession::class);
}
```

### `app/Listeners/StoreUserInSession.php`

```php
public function handle(Login $event): void
{
    $user = $event->user; // ambil user yang baru saja login dari event

    // Simpan data penting user ke session (bukan seluruh object User)
    session([
        'user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ]
    ]);
}
```

**Mengapa menyimpan ke session secara manual?**  
Middleware kustom (`AdminMiddleware`, `UserMiddleware`) membaca data dari `session('user')`, bukan dari `Auth::user()`. Ini memberi fleksibilitas untuk menyimpan data tambahan (seperti `role`) yang mudah diakses tanpa query database tambahan di setiap request.

---

## Diagram Alur Visual

### Register

```
[Form Register]
    → POST /register
    → Fortify
    → CreateNewUser::create()
    → Validator → User::create() → Hash::make(password)
    → [Dashboard]
```

### Login Standar

```
[Form Login]
    → POST /login
    → Fortify authenticateUsing()
    → User::where('email') + Hash::check()
    → Auth::login()
    → Event:Login → StoreUserInSession
    → [Dashboard]
```

### Login Google + OTP

```
[Klik Login Google]
    → /auth/google → Socialite::redirect()
    → [Halaman Google]
    → /auth/google/callback → Socialite::user()
    → Cari/buat User → generate OTP → Mail::send(OtpMail)
    → [Form OTP]
    → POST /auth/otp → verifyOtp()
    → Hash::check OTP → Auth::login()
    → Event:Login → StoreUserInSession
    → [Dashboard]
```

### Logout

```
[Tombol Logout]
    → POST /logout
    → Auth::logout()
    → Event:Logout → ClearUserFromSession (session()->forget('user'))
    → [Halaman Login]
```

---

## Ringkasan Keterkaitan Antar Komponen

```
config/fortify.php          → konfigurasi guard, home, features
FortifyServiceProvider      → mendaftarkan views, callbacks, rate limiter
  ├── registerView()        → resources/views/auth/register.blade.php
  ├── loginView()           → resources/views/auth/login.blade.php
  ├── createUsersUsing()    → Actions/Fortify/CreateNewUser.php
  └── authenticateUsing()   → custom login callback

AppServiceProvider          → mendaftarkan event listeners
  ├── Login event           → StoreUserInSession (isi session 'user')
  └── Logout event          → ClearUserFromSession (hapus session 'user')

AuthController              → Google OAuth + OTP
  ├── redirectToGoogle()    → Socialite redirect
  ├── handleGoogleCallback() → buat/update user + kirim OTP
  └── verifyOtp()           → validasi OTP → Auth::login()

Middleware
  ├── UserMiddleware        → cek session('user') ada?
  └── AdminMiddleware       → cek session('user')['role'] === 'admin'?

Models/User.php             → Eloquent model, fillable, isAdmin(), isUser()
```
