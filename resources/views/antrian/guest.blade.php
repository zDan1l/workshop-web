<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrian - Sistem Antrian Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Sistem Antrian Digital</h1>
            <p class="text-white/80">Silakan daftar untuk mendapatkan nomor antrian</p>
        </div>

        <!-- Form Card -->
        <div class="form-card rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Form Pendaftaran</h2>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $errors->first('nama') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ url('/antrian') }}" method="POST">
                @csrf

                <!-- Nama Input -->
                <div class="mb-6">
                    <label for="nama" class="block text-gray-700 font-semibold mb-2">
                        Nama Lengkap
                    </label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        class="input-field w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-purple-500 focus:outline-none"
                        placeholder="Masukkan nama lengkap Anda"
                        required
                        maxlength="255"
                        value="{{ old('nama') }}"
                    >
                    <p class="mt-2 text-sm text-gray-500">
                        Masukkan nama Anda untuk mendapatkan nomor antrian
                    </p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="submit-btn w-full text-white font-bold py-4 px-6 rounded-lg"
                >
                    🎫 Daftar Antrian
                </button>

                <!-- Info Text -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">
                        Setelah mendaftar, Anda akan mendapatkan nomor antrian dan dapat memantau status secara real-time
                    </p>
                </div>
            </form>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-white/80 hover:text-white transition-colors">
                ← Kembali ke Papan Antrian
            </a>
        </div>
    </div>
</body>
</html>