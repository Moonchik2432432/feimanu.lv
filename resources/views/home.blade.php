@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-1">Feimaņu Ziņas</h1>
            <p class="text-muted mb-0">
                Vietējie jaunumi, paziņojumi un notikumi no Feimaņiem un apkārtnes.
            </p>
        </div>

        @auth
            <a href="{{ url('/data') }}" class="btn btn-outline-dark">
                Admin panelis
            </a>
        @endauth
    </div>

    <div class="row g-4">
        <!-- Galvenais bloks -->
        <div class="col-lg-8">
            <div class="p-4 rounded border bg-light">
                <h4 class="mb-2">Jaunākie ieraksti</h4>
                <p class="text-muted mb-3">
                    Šeit redzami jaunākie publicētie ieraksti. Pievieno jaunus ierakstus sadaļā “Dati”.
                </p>

                @php
                    // Ja no kontroliera nav padots $ieraksti, rādām demo
                    $demo = [
                        ['title' => 'Ceļu remontdarbi Feimaņos', 'date' => '2026-02-10', 'text' => 'Informācija par remontdarbu grafiku un satiksmes ierobežojumiem.'],
                        ['title' => 'Pasākums kultūras namā', 'date' => '2026-02-07', 'text' => 'Aicinām uz koncertu un kopīgu tikšanos vietējiem iedzīvotājiem.'],
                        ['title' => 'Paziņojums par ūdens padevi', 'date' => '2026-02-03', 'text' => 'Plānoti īslaicīgi atslēgumi tehnisku darbu dēļ.'],
                    ];
                @endphp

                @if(isset($ieraksti) && $ieraksti->count())
                    <div class="list-group">
                        @foreach($ieraksti->take(6) as $post)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $post->nosaukums }}</h6>
                                        <small class="text-muted">
                                            {{ $post->publicets_datums ?? '—' }}
                                            @if(isset($post->status))
                                                · {{ $post->status }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @if(!empty($post->saturs))
                                    <p class="mb-0 mt-2 text-muted">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($post->saturs), 140) }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($demo as $d)
                            <div class="col-md-6">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">{{ $d['title'] }}</h6>
                                        <small class="text-muted">{{ $d['date'] }}</small>
                                        <p class="card-text mt-2 text-muted">{{ $d['text'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Sānjosla -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Ātrās darbības</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ url('/contacts') }}" class="btn btn-outline-secondary">Sazināties</a>

                        @auth
                            <a href="{{ url('/data/allIeraksti') }}" class="btn btn-outline-primary">Skatīt ierakstus</a>
                            <a href="{{ url('/data/createIeraksts') }}" class="btn btn-primary">Pievienot ierakstu</a>
                        @else
                            <a href="{{ url('/login') }}" class="btn btn-primary">Ienākt (admin)</a>
                        @endauth
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Par projektu</h5>
                    <p class="text-muted mb-0">
                        Šis ir vienkāršs ziņu portāls Feimaņu pagastam: ieraksti, kategorijas, komentāri un lietotāji.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('sidemenu')
<nav>
    <ul>
        <li><a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Sākums</a></li>

        @auth
            <li><a href="{{ url('/data') }}" class="{{ request()->is('data*') ? 'active' : '' }}">Dati</a></li>
        @endauth

        <li><a href="{{ url('/contacts') }}" class="{{ request()->is('contacts') ? 'active' : '' }}">Kontakti</a></li>

        @guest
            <li><a href="{{ url('/login') }}" class="{{ request()->is('login') ? 'active' : '' }}">Ienākt</a></li>
        @endguest
    </ul>
</nav>
@endsection
