<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Feimanu')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    @include('inc.header')

    <main class="main-content">
        @yield('content')
    </main>

    @include('inc.footer')

</body>
</html>
