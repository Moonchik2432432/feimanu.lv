<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Feimanu')</title>
 
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/lv.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/js/lightbox.min.js"></script>

    <script src="{{ asset('js/slider.js') }}"></script>
</head>
<body>
 
    @include('inc.header')
 
    <div class="slider">
        <div class="slides">
            <img src="{{ asset('img/slides/feimanuSlide1.jpg') }}" class="slide active">
            <img src="{{ asset('img/slides/feimanuSlide2.jpg') }}" class="slide">
            <img src="{{ asset('img/slides/feimanuSlide3.jpg') }}" class="slide">
        </div>
 
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
    </div>
 
    <main class="main-content">
        @yield('content')
    </main>
 
    @include('inc.footer')
 
</body>
</html>