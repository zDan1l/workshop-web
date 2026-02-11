<!DOCTYPE html>
<html class="dark" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Tailor POS - Login</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons+Round"
        rel="stylesheet"
    />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet"
    />

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
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px",
                    },
                },
            },
        };
    </script>

    <!-- Custom Styles -->
    <style>
        body {
            font-family: "Manrope", sans-serif;
        }

        .fabric-pattern {
            background-color: #101622;
            background-image: url(https://lh3.googleusercontent.com/aida-public/AB6AXuCdPkQvK5e4Bme71DFSat8vBcnBddWLH38-n27cqXUXqJBKSZWLjmZvWcDMmQUrE9KQq7CarAY2qkE0fBpRoGRXdn-Ql2MyjIW__YzbMl9Hho25TqCib6MYDZJIKQKQUA2EmUFCLBOxZo_fyel55P6EGvimMmKYudZwWLui11JZuTzN0XMyU2OlZj5p4-AddRvY0rHDjVcE9UoQbsDKLWzZgX1h8Le0xedFsXgGkx1k3ZY1EQKQ9J2lg6SsympPiWM69gTD8r94zVLF);
            /* Subtle texture overlay to simulate fabric weave in dark mode */
        }

        .glass-card {
            background: rgba(16, 22, 34, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen flex items-center justify-center">

    <div class="flex flex-col lg:flex-row w-full min-h-screen">

        <!-- ============================================ -->
        <!-- Left Side: Visual Content (Desktop Only)     -->
        <!-- ============================================ -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary/10 items-center justify-center p-12">

            <!-- Background Image with Texture Overlay -->
            <div class="absolute inset-0 z-0">
                <img
                    alt="Tailoring Workshop"
                    class="w-full h-full object-cover opacity-40 mix-blend-overlay"
                    data-alt="Close up of a tailor workspace with sewing machine and fabrics"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAjunonr_miYchKyErVWMhu3q_szOj8FnnHBI1BDqC46BKmrA88D8aScAeNlwe76D88qaYqLKzn3g8br8CUIvDkI3CvODp8YliK7dIPdsfYG-UYfgOrPzGa7xYzOcGCSTkTSb8M651TgTH_mrwZPFnL-u0FepCkc14Pc2UiW-_2-tdxZrkTZ7Mg8dvrv2hVi6hxmF8JlOX-LB3biobBnfBhx99z8k0sLEUiQn-5RP_9Ciy8W19MuuMgwEUgcHFfLDq2D3XUqZvRWnfC"
                />
                <div class="absolute inset-0 bg-gradient-to-tr from-background-dark via-background-dark/80 to-primary/20"></div>
            </div>

            <!-- Content Overlay -->
            <div class="relative z-10 max-w-lg">

                <!-- Logo -->
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                        <span class="material-icons-round text-white text-3xl">architecture</span>
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-white">
                        TAILOR <span class="text-primary-light font-light opacity-80">POS</span>
                    </h1>
                </div>

                <!-- Tagline -->
                <h2 class="text-4xl font-bold text-white mb-6 leading-tight">
                    Sempurnakan Setiap Jahitan dengan Manajemen Pintar.
                </h2>
                <p class="text-slate-400 text-lg mb-8 leading-relaxed">
                    Satu platform untuk mengelola pesanan, pengukuran pelanggan,
                    inventaris kain, hingga laporan keuangan bisnis butik Anda.
                </p>

                <!-- Feature Highlights -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="flex items-center gap-3 bg-white/5 p-4 rounded-xl border border-white/10">
                        <span class="material-icons-round text-primary">straighten</span>
                        <span class="text-sm font-medium text-slate-300">Data Ukuran Presisi</span>
                    </div>
                    <div class="flex items-center gap-3 bg-white/5 p-4 rounded-xl border border-white/10">
                        <span class="material-icons-round text-primary">inventory_2</span>
                        <span class="text-sm font-medium text-slate-300">Stok Kain Real-time</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- ============================================ -->
        <!-- Right Side: Login Form                       -->
        <!-- ============================================ -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 md:p-20 bg-background-light dark:bg-background-dark">
            <div class="w-full max-w-md space-y-8">

                <!-- Logo (Mobile Only) -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-round text-primary text-4xl">architecture</span>
                        <span class="text-2xl font-bold tracking-tight">TAILOR POS</span>
                    </div>
                </div>

                <!-- Heading -->
                <div class="text-left space-y-2">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Masuk ke Akun Anda 👋
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400">
                        Kelola pesanan jahitan Anda dengan mudah dan cepat.
                    </p>
                </div>

                <!-- Login Form -->
                <form class="mt-8 space-y-6">
                    <div class="space-y-4">

                        <!-- Email Field -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2"
                                for="email"
                            >
                                Email Address
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-icons-round text-slate-400 group-focus-within:text-primary transition-colors text-xl">
                                        alternate_email
                                    </span>
                                </div>
                                <input
                                    autocomplete="email"
                                    class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                                    id="email"
                                    name="email"
                                    placeholder="nama@email.com"
                                    required
                                    type="email"
                                />
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label
                                class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2"
                                for="password"
                            >
                                Kata Sandi
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="material-icons-round text-slate-400 group-focus-within:text-primary transition-colors text-xl">
                                        lock_open
                                    </span>
                                </div>
                                <input
                                    autocomplete="current-password"
                                    class="block w-full pl-10 pr-12 py-3 bg-white dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                                    id="password"
                                    name="password"
                                    placeholder="••••••••"
                                    required
                                    type="password"
                                />
                                <button
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                                    type="button"
                                >
                                    <span class="material-icons-round text-xl">visibility</span>
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                class="h-4 w-4 text-primary focus:ring-primary border-slate-300 dark:border-slate-700 rounded bg-transparent transition-all"
                                id="remember-me"
                                name="remember-me"
                                type="checkbox"
                            />
                            <label
                                class="ml-2 block text-sm text-slate-600 dark:text-slate-400"
                                for="remember-me"
                            >
                                Remember me
                            </label>
                        </div>
                        <div class="text-sm">
                            <a class="font-semibold text-primary hover:text-primary/80 transition-colors" href="#">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all uppercase tracking-wider shadow-lg shadow-primary/25"
                            type="submit"
                        >
                            MASUK
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="relative flex items-center py-2">
                        <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                        <span class="flex-shrink mx-4 text-slate-400 text-sm font-medium">atau</span>
                        <div class="flex-grow border-t border-slate-200 dark:border-slate-700"></div>
                    </div>

                    <!-- Google Login -->
                    <div class="grid grid-cols-1">
                        <button
                            class="w-full inline-flex justify-center py-3 px-4 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/50 text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm"
                            type="button"
                        >
                            <img
                                alt="Google logo"
                                class="h-5 w-5 mr-2"
                                data-alt="Google colorful G logo icon"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDK7XL0L-BNf06IZn7Kv61rE-R6g8JuaCoIs0FnvGLCloPA6E09Mpcws7cThv__BR66PE3Fqq-Hb6VK0GM5Pwe7KwHqeg4g-lLyxcf4Sk8t7mPP6YmrmsRRBmZWcTKzHZNj6M-pNWtbjZwk-eGe1U4WjPmq46-mNniJDdUMCCx3gkD4VcQBxuU-7BtUdwTtr2KjawkvPUFZhbjhFlIbrmqYCEZ_BLzTQd3zXvsbfJdDVwKKkcHkLqCaHVAR_vwLhvXnhwJJgiHD-u91"
                            />
                            <span>Masuk dengan Google</span>
                        </button>
                    </div>
                </form>

                <!-- Register Link -->
                <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
                    Belum punya akun?
                    <a class="font-bold text-primary hover:text-primary/80 transition-colors" href="{{ route('register') }}">
                        Daftar sekarang
                    </a>
                </p>

                <!-- Footer Links -->
                <div class="pt-8 mt-8 border-t border-slate-100 dark:border-slate-800 flex justify-center gap-6 text-xs text-slate-400">
                    <a class="hover:underline" href="#">Bantuan</a>
                    <a class="hover:underline" href="#">Ketentuan Layanan</a>
                    <a class="hover:underline" href="#">Privasi</a>
                </div>

            </div>
        </div>

    </div>

</body>

</html>