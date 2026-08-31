<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#08090a">

@include('partials.seo')

<link rel="icon" href="{{ asset('assets/img/brand/favicon-32.png') }}" sizes="32x32" type="image/png">
<link rel="icon" href="{{ asset('assets/img/brand/favicon-192.png') }}" sizes="192x192" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('assets/img/brand/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

<link rel="preload" href="{{ asset('assets/fonts/sora-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('assets/fonts/inter-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="{{ asset('assets/fonts/fonts.css') }}">

@stack('preload')

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
@stack('styles')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

<a class="skip-link" href="#main">Skip to content</a>

@include('partials.sprite')
@include('partials.preloader')
@include('partials.header')

<main id="main">
@yield('content')
</main>

@include('partials.footer')
@include('partials.floats')
@stack('modals')

@stack('scripts-head')
<script src="{{ asset('assets/js/lenis.min.js') }}" defer></script>
@stack('scripts')
<script src="{{ asset('assets/js/main.js') }}" defer></script>
@if($s->get('body_scripts')){!! $s->get('body_scripts') !!}@endif
</body>
</html>
