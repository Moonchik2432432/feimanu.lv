<header class="site-header">
    <div class="container">

        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo-feimanu.jpg') }}" alt="Feimanu Logo">
            </a>
        </div>

        <nav class="nav">

            <div class="dropdown">
                <a href="{{ route('aktualitates.index') }}" class="dropbtn">
                    Aktualitātes
                </a>

                <div class="dropdown-content">
                    <a href="{{ route('aktualitates.index') }}">
                        Visas aktualitātes
                    </a>

                    @foreach($headerCategories as $cat)
                        <a href="{{ route('aktualitates.category', $cat->kategorija_id) }}">
                            {{ $cat->nosaukums }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="#">Ziņas</a>
            <a href="#">Par mums</a>
            <a href="#">Kontakti</a>

        </nav>
    </div>
</header>
