<header class="site-header">
    <div class="container">

        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo-feimanu.jpg') }}" alt="Feimanu Logo">
            </a>
        </div>

        <nav class="nav">

            <div class="menu">
                <a href="{{ route('aktualitates.index') }}">Aktualitātes</a>

                <div class="submenu">
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

            <div class="menu">
                <a href="{{ route('pagasts.index') }}">Pagasts</a>

                <div class="submenu">
                    <a href="{{ route('pagasts.history') }}">
                        Pagasts vesture
                    </a>
                </div>
            </div>
            
            <a href="{{ url('/') }}">Pagasta pārvalde</a>
            <a href="{{ url('/') }}">Iestādes</a>
            <a href="{{ url('/') }}">Tūrisms</a>
            <a href="{{ url('/') }}">Galerijas</a>
            <a href="{{ url('/') }}">Kontakti</a>
        </nav>

        @auth
        <div class="user-menu">
            <div class="user-trigger">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('img/usersAvatars/' . auth()->user()->avatar) }}" class="avatar">
                @else
                    <img src="{{ asset('img/usersAvatars/default_avatar.jpg') }}" class="avatar">
                @endif

                <a href="{{ route('profile.show') }}" 
                    style="color:white; text-decoration:none;">
                        {{ auth()->user()->name }}
                </a>
            </div>

            <div class="user-dropdown">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Iziet</button>
                </form>
            </div>
        </div>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth

    </div>
</header>
