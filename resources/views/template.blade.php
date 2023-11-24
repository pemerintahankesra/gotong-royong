<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi Gotong Royong | Bagian Pemerintahan dan Kesejahteraan Rakyat</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    @include('_partials.css')
    @yield('styles')
  </head>
  <body>
    @include('_partials.header')
    @include('_partials.sidebar')
    <main id="main" class="main">
      @yield('content')
    </main>
    @include('_partials.footer')
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    @include('_partials.js')
    @yield('scripts')
  </body>
</html>