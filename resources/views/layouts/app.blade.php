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
        @include('partials.navbar')
        
        <div class="container-fluid page-body-wrapper">
            @include('partials.sidebar')
            
            <!-- Main Panel -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('partials.footer')
            </div>
        </div>
    </div>

    @include('partials.scripts')

    {{-- ========================================
        IX. JAVASCRIPT PAGE
        - JS khusus untuk halaman ini saja
        - Programmer menambahkan dengan @push('scripts')
    ======================================== --}}
    @stack('scripts')
</body>
</html>