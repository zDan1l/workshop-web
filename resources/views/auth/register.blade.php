<!DOCTYPE html>
<html class="dark" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tailor POS - Registrasi</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Tailwind Config -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-dark": "#1a2233",
                        "border-dark": "#2d3748"
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        .bg-pattern {
            background-image: radial-gradient(circle at 2px 2px, rgba(19, 91, 236, 0.05) 1px, transparent 0);
            background-size: 32px 32px;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen flex items-center justify-center font-display p-4 bg-pattern">
    <div class="max-w-6xl w-full grid lg:grid-cols-2 items-center gap-12">

        <!-- Left Side: Branding/Visual -->
        <div class="hidden lg:flex flex-col justify-center space-y-8 pr-12">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                    <span class="material-icons text-white text-3xl">straighten</span>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight">
                    Tailor<span class="text-primary">POS</span>
                </h1>
            </div>

            <!-- Headline -->
            <div class="space-y-4">
                <h2 class="text-5xl font-extrabold leading-tight">
                    Digitalisasi Bisnis <span class="text-primary">Jahit</span> Anda.
                </h2>
                <p class="text-xl text-slate-500 dark:text-slate-400 leading-relaxed">
                    Kelola pesanan pelanggan, ukuran tubuh, hingga inventaris bahan dalam satu platform yang terintegrasi.
                </p>
            </div>

            <!-- Image Card -->
            <div class="relative rounded-2xl overflow-hidden aspect-video shadow-2xl border border-border-dark/50">
                <img 
                    class="w-full h-full object-cover opacity-60 grayscale hover:grayscale-0 transition-all duration-700"
                    alt="Modern tailoring workshop with fabric and tools"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCg2yOF5InSE99AWoVS3QawLm7XqXHW0yVStog_bDWLI_e-ZfkHAqVbVfRQq9VzJfL4KV6jXyUAxK-gkIcsqbmyTq0g_5-yC6u8xllgekkxwPaJEpWEdAN-7b81nIp_QUbD5gnkW4DpqpfrBUlWCX2jTc_FzAtvZh07voqWQlI4U3ekizwAVTySeqajR54g7gRZJrj4z_516LXhGxVEOSmxwZT0L5FVUET5CLezHRsGZyNAEi_rUxg3eAHssA_gZutbpttAYOaAHM43" 
                />
                <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="material-icons text-primary text-sm">stars</span>
                        <span class="text-xs font-semibold uppercase tracking-widest text-primary">
                            Trusted by Professionals
                        </span>
                    </div>
                    <p class="text-sm italic text-slate-300">
                        "Sistem ini membantu saya melacak ratusan ukuran pelanggan tanpa pusing mencari buku catatan."
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side: Registration Card -->
        <div class="w-full flex justify-center lg:justify-end">
            <div class="w-full max-w-[500px] bg-white dark:bg-surface-dark p-8 lg:p-10 rounded-xl shadow-2xl border border-slate-200 dark:border-border-dark/50">
                
                <!-- Header -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold mb-2">Buat Akun Baru ✨</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        Mulai perjalanan digital bisnis jahitan Anda sekarang.
                    </p>
                </div>

                <!-- Form -->
                <form class="space-y-4">
                    <!-- Full Name -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-1">
                            Nama Lengkap
                        </label>
                        <div class="relative group">
                            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">
                                person
                            </span>
                            <input 
                                type="text"
                                name="name"
                                placeholder="Masukkan nama lengkap"
                                class="w-full bg-slate-50 dark:bg-background-dark/50 border border-slate-200 dark:border-border-dark rounded-lg py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm" 
                            />
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-1">
                            Alamat Email
                        </label>
                        <div class="relative group">
                            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">
                                mail_outline
                            </span>
                            <input 
                                type="email"
                                name="email"
                                placeholder="contoh@email.com"
                                class="w-full bg-slate-50 dark:bg-background-dark/50 border border-slate-200 dark:border-border-dark rounded-lg py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm" 
                            />
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-1">
                            Nomor Telepon/WhatsApp
                        </label>
                        <div class="relative group">
                            <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">
                                smartphone
                            </span>
                            <input 
                                type="tel"
                                name="phone"
                                placeholder="08xx xxxx xxxx"
                                class="w-full bg-slate-50 dark:bg-background-dark/50 border border-slate-200 dark:border-border-dark rounded-lg py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm" 
                            />
                        </div>
                    </div>

                    <!-- Password Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-1">
                                Kata Sandi
                            </label>
                            <div class="relative group">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">
                                    lock_open
                                </span>
                                <input 
                                    type="password"
                                    name="password"
                                    placeholder="••••••••"
                                    class="w-full bg-slate-50 dark:bg-background-dark/50 border border-slate-200 dark:border-border-dark rounded-lg py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm" 
                                />
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-1">
                                Konfirmasi
                            </label>
                            <div class="relative group">
                                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors text-xl">
                                    lock
                                </span>
                                <input 
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="••••••••"
                                    class="w-full bg-slate-50 dark:bg-background-dark/50 border border-slate-200 dark:border-border-dark rounded-lg py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm" 
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-center space-x-3 py-2">
                        <input 
                            type="checkbox"
                            id="terms"
                            name="terms"
                            class="w-5 h-5 rounded border-slate-300 dark:border-border-dark bg-transparent text-primary focus:ring-primary/30 cursor-pointer" 
                        />
                        <label class="text-sm text-slate-500 dark:text-slate-400 cursor-pointer select-none" for="terms">
                            Saya setuju dengan 
                            <a class="text-primary hover:underline" href="#">Syarat & Ketentuan</a> 
                            yang berlaku.
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit"
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3.5 rounded-lg transition-all shadow-lg shadow-primary/20 flex items-center justify-center space-x-2"
                    >
                        <span class="uppercase tracking-widest text-sm">DAFTAR</span>
                        <span class="material-icons text-xl">arrow_forward</span>
                    </button>

                    <!-- Divider -->
                    <div class="relative flex items-center py-4">
                        <div class="flex-grow border-t border-slate-200 dark:border-border-dark"></div>
                        <span class="flex-shrink mx-4 text-xs font-semibold text-slate-400 uppercase tracking-widest">
                            atau
                        </span>
                        <div class="flex-grow border-t border-slate-200 dark:border-border-dark"></div>
                    </div>

                    <!-- Google Button -->
                    <button 
                        type="button"
                        class="w-full bg-transparent border border-slate-200 dark:border-border-dark hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold py-3 rounded-lg transition-all flex items-center justify-center space-x-3"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path 
                                d="M12.48 10.92v3.28h7.84c-.24 1.84-.9 3.33-2.03 4.44-1.13 1.11-2.85 1.91-5.81 1.91-5.5 0-9.95-4.45-9.95-9.95s4.45-9.95 9.95-9.95c3.01 0 5.15 1.19 6.64 2.62l2.3-2.3C18.98 1.51 16.03 0 12.48 0 5.58 0 0 5.58 0 12.48s5.58 12.48 12.48 12.48c3.75 0 6.64-1.24 8.82-3.52 2.24-2.24 2.94-5.41 2.94-8.08 0-.68-.05-1.33-.14-1.95h-11.6z" 
                                fill="#EA4335"
                            />
                        </svg>
                        <span class="text-sm">Daftar dengan Google</span>
                    </button>

                    <!-- Footer -->
                    <div class="pt-6 text-center">
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Sudah punya akun?
                            <a class="text-primary font-bold hover:underline ml-1" href="{{ route('login') }}">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>