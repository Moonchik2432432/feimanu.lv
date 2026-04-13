<header class="site-header">
    <div class="header-top">
        <div class="container header-inner">

            <div class="logo-wrap">
                <a href="{{ url('/') }}" class="logo" aria-label="Feimaņu sākumlapa">
                    <img src="{{ asset('img/logo-feimanu.jpg') }}" alt="Feimanu Logo">
                    <div class="logo-text">
                        <span class="logo-title">Feimaņu pagasts</span>
                        <span class="logo-subtitle">Oficiālā informācijas vietne</span>
                    </div>
                </a>
            </div>

            <nav class="nav" aria-label="Galvenā navigācija">

                <div class="menu">
                    <a href="{{ route('news.index') }}" class="nav-link">Aktualitātes</a>

                    <div class="submenu">
                        <a href="{{ route('news.index') }}">Visas aktualitātes</a>

                        @foreach($headerCategories as $cat)
                            <a href="{{ route('news.category', $cat->kategorija_id) }}">
                                {{ $cat->nosaukums }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="menu">
                    <a href="{{ route('pagasts.index') }}" class="nav-link">Pagasts</a>

                    <div class="submenu">
                        <a href="{{ route('pagasts.about') }}">Par mums</a>
                        <a href="{{ route('pagasts.history') }}">Pagasta vēsture</a>
                        <a href="{{ route('pagasts.sport') }}">Sports</a>
                        <a href="{{ route('pagasts.culture') }}">Kultūra</a>
                        <a href="{{ route('pagasts.religia') }}">Reliģija</a>
                    </div>
                </div>

                <a href="{{ route('rules.index') }}" class="nav-link">Noteikumi</a>
                <a href="{{ route('gallery.index') }}" class="nav-link">Galerijas</a>
                <a href="{{ route('contacts') }}" class="nav-link">Kontakti</a>
            </nav>

            <div class="header-actions">
                @auth
                    <div class="user-menu">
                        <div class="user-trigger">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('img/usersAvatars/' . auth()->user()->avatar) }}" class="avatar" alt="Avatar">
                            @else
                                <img src="{{ asset('img/usersAvatars/default_avatar.jpg') }}" class="avatar" alt="Avatar">
                            @endif

                            <div class="user-meta">
                                <span class="user-name">{{ auth()->user()->name }}</span>
                                <span class="user-role">
                                    {{ auth()->user()->role === 'admin' ? 'Administrators' : 'Lietotājs' }}
                                </span>
                            </div>

                            <span class="user-arrow">▾</span>
                        </div>

                        <div class="user-dropdown">
                            <a href="{{ route('profile.show') }}" class="dropdown-link">Profils</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-button">Iziet</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="login-link">Pieslēgties</a>
                @endauth
            </div>

        </div>
    </div>
</header>

@auth
    @if(auth()->user()->role === 'admin')
        <div class="admin-bar">
            <div class="container admin-bar-inner">

                <div class="admin-bar-title">
                    <span>Administrācijas panelis</span>
                </div>

                <div class="admin-bar-links">
                    <a href="{{ route('admin.users') }}">Lietotāji / Komentāri</a>
                    <a href="{{ route('admin.news') }}">Aktualitātes</a>
                    <a href="{{ route('admin.category') }}">Kategorijas</a>
                    <a href="{{ route('admin.block_reasons') }}">Bloķēšanas iemesli</a>
                    <a href="{{ route('admin.gallery.albums') }}">Galerijas albumi</a>
                    <a href="{{ route('admin.gallery.images') }}">Galerijas foto</a>
                    <a href="{{ route('admin.contacts') }}">Kontakti</a>
                </div>

            </div>
        </div>
    @endif
@endauth
