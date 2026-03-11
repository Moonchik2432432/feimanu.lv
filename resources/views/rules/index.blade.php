@extends('layouts.app')

@section('title', 'Noteikumi')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <h1>Lietošanas noteikumi</h1>

    <p style="margin-bottom:20px;">
        Lai nodrošinātu drošu un cieņpilnu vidi visiem lietotājiem, vietnē jāievēro noteikumi.
        Par pārkāpumiem administrācija var piemērot pagaidu bloķēšanu.
    </p>

    @if($reasons->count())
        <h2 style="margin-bottom:15px;">Pārkāpumu veidi</h2>

        <div style="display:flex; flex-direction:column; gap:15px;">
            @foreach($reasons as $reason)
                <div style="padding:15px; border:1px solid #ddd; border-radius:8px; background:#fafafa;">
                    <h3 style="margin:0 0 8px 0;">{{ $reason->title }}</h3>

                    @if($reason->description)
                        <p style="margin:0;">{{ $reason->description }}</p>
                    @else
                        <p style="margin:0; color:#666;">Apraksts nav norādīts.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <p>Pašlaik nav pievienotu noteikumu.</p>
    @endif

    <p style="margin-top:25px;">
        Ja uzskatāt, ka bloķēšana tika piemērota kļūdaini, sazinieties ar vietnes administrāciju.
    </p>

</div>
@endsection