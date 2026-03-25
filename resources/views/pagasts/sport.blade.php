@extends('layouts.app')

@section('title', 'Sports')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <h1 style="margin-bottom:20px;">Sports</h1>

    <img 
        src="{{ asset('img/pagasts/feimani4.jpg') }}"
        alt="Feimaņu pagasts"
        style="width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
    >

    <div style="background:#fff; border:1px solid #ddd; border-radius:14px; padding:24px;">

        <p style="line-height:1.7; margin-top:0;">
            Sporta aktivitātes Feimaņu pagastā un tuvējā Maltā nodrošina iedzīvotājiem iespēju nodarboties ar fiziskām aktivitātēm, piedalīties sacensībās un uzturēt aktīvu dzīvesveidu.
        </p>

        <p style="line-height:1.7;">
            Feimaņu pagasta teritorijā un tās tuvumā pieejamas sporta infrastruktūras iespējas,
            tostarp sporta laukumi, dabas teritorijas aktīvai atpūtai, kā arī izglītības iestāžu sporta zāles.
        </p>

        <p style="line-height:1.7;">
            Tuvējā Maltā iedzīvotājiem pieejamas plašākas sporta iespējas, kur tiek organizētas dažādas sporta nodarbības,
            sacensības un pasākumi dažādām vecuma grupām.
        </p>

        <p style="line-height:1.7;">
            Pagasta iedzīvotāji aktīvi iesaistās sporta un veselīga dzīvesveida aktivitātēs,
            piedaloties vietējās un novada līmeņa iniciatīvās.
        </p>

        <p style="line-height:1.7; margin-bottom:0;">
            Sporta dzīve reģionā veicina kopienas saliedētību un nodrošina iespēju kvalitatīvi pavadīt brīvo laiku.
        </p>

    </div>

    <div style="margin-top:20px; color:#777;">
        Informācijas avoti: rezeknesnovads.lv
    </div>

</div>
@endsection