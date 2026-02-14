<!DOCTYPE html>
<html lang="en">
<head>
    {{-- ========================================
        I. HEAD
        - Meta tags, Title, Favicon
    ======================================== --}}
    @include('partials.head')

    {{-- ========================================
        II. STYLE GLOBAL
        - CSS yang berlaku untuk SEMUA halaman
    ======================================== --}}
    @include('partials.styles')

    {{-- ========================================
        III. STYLE PAGE
        - CSS khusus untuk halaman ini saja
        - Programmer menambahkan dengan @push('styles')
    ======================================== --}}
    @stack('styles')
</head>
<body>
    <div class="container-scroller">
        {{-- ========================================
            IV. NAVBAR
        ======================================== --}}
        @include('partials.navbar')
        
        <div class="container-fluid page-body-wrapper">
            {{-- ========================================
                V. SIDEBAR
            ======================================== --}}
            @include('partials.sidebar')
            
            <!-- Main Panel -->
            <div class="main-panel">
                <div class="content-wrapper">
                    {{-- ========================================
                        VI. CONTENT
                        - Konten utama halaman
                        - Programmer menambahkan dengan @section('content')
                    ======================================== --}}
                    @yield('content')
                </div>
                
                {{-- ========================================
                    VII. FOOTER
                ======================================== --}}
                @include('partials.footer')
            </div>
        </div>
    </div>

    {{-- ========================================
        VIII. JAVASCRIPT GLOBAL
        - JS yang berlaku untuk SEMUA halaman
    ======================================== --}}
    @include('partials.scripts')

    {{-- ========================================
        IX. JAVASCRIPT PAGE
        - JS khusus untuk halaman ini saja
        - Programmer menambahkan dengan @push('scripts')
    ======================================== --}}
    @stack('scripts')
</body>
</html>