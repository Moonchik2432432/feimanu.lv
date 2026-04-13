<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Feimanu')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/css/lightbox.min.css">
</head>
<body>

    <div class="site-shell">
        @include('inc.header')

        @hasSection('showSlider')
            <section class="hero-slider-wrap">
                <div class="slider">
                    <div class="slides">
                        <img src="{{ asset('img/slides/feimanuSlide1.jpg') }}" class="slide active" alt="Feimaņu pagasts 1">
                        <img src="{{ asset('img/slides/feimanuSlide2.jpg') }}" class="slide" alt="Feimaņu pagasts 2">
                        <img src="{{ asset('img/slides/feimanuSlide3.jpg') }}" class="slide" alt="Feimaņu pagasts 3">
                    </div>

                    <button class="prev" type="button" aria-label="Iepriekšējais slaids">&#10094;</button>
                    <button class="next" type="button" aria-label="Nākamais slaids">&#10095;</button>
                </div>
            </section>
        @endif

        <main class="main-content">
            @yield('content')
        </main>

        @include('inc.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/lv.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2/dist/js/lightbox.min.js"></script>

    <script src="{{ asset('js/slider.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".date-picker", {
                    dateFormat: "Y-m-d",
                    locale: "lv"
                });
            }

            if (typeof lightbox !== 'undefined') {
                lightbox.option({
                    resizeDuration: 0,
                    fitImagesInViewport: false,
                    wrapAround: true,
                    positionFromTop: 30,
                    maxWidth: 1200,
                    maxHeight: 700
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
