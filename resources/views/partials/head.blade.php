{{-- ========================================
    HEAD PARTIAL
    - Meta tags
    - Title
    - Favicon
======================================== --}}

<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Page Title -->
<title>@yield('title', 'Purple Admin')</title>

<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
