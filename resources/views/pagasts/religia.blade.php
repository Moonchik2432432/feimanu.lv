@extends('layouts.app')

@section('title', 'Kultūras objekti')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <h1 style="margin-bottom:20px;">Kultūras objekti Feimaņu pagastā</h1>

    <img 
        src="{{ asset('img/pagasts/feimani6.jpg') }}"
        alt="Feimaņu pagasts"
        style="width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
    >

    {{-- 1. Baznīca --}}
    <div style="background:#fff; border:1px solid #ddd; border-radius:14px; padding:20px; margin-bottom:20px;">
        <h2 style="margin-top:0;">Feimaņu katoļu baznīca</h2>

        <p style="line-height:1.7;">
            Feimaņu Svētā Jāņa Kristītāja Romas katoļu baznīca ir viens no nozīmīgākajiem kultūras un reliģiskajiem objektiem pagastā.
            Baznīcas koka ēka celta 1751. gadā.
        </p>

        <p style="line-height:1.7;">
            Aptuveni 200 metrus no baznīcas atrodas akmens mūra kapella, bet pie ieejas izvietots mūra zvanu tornis ar četriem zvaniem.
        </p>
    </div>

    <img 
        src="{{ asset('img/pagasts/feimani7.jpg') }}"
        alt="Feimaņu pagasts"
        style="width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
    >

    {{-- 2. Piemineklis --}}
    <div style="background:#fff; border:1px solid #ddd; border-radius:14px; padding:20px; margin-bottom:20px;">
        <h2 style="margin-top:0;">Piemineklis “Krusta nesējs”</h2>

        <p style="line-height:1.7;">
            Piemineklis “Krusta nesējs” jeb “Myvra Krysts” izveidots 1828. gadā un atrodas netālu no Feimaņu katoļu baznīcas,
            Preiļu ceļa malā.
        </p>

        <p style="line-height:1.7;">
            Tā autors ir Livonijas kanoniķis, Rēzeknes dekāns un rakstnieks Jazeps Kirkillo.
            Piemineklis tapis par godu katoļu pāvesta izsludinātajam jubilejas gadam.
        </p>

        <p style="line-height:1.7;">
            Padomju laikā piemineklis tika nopostīts, bet 1990. gadā tas tika atjaunots,
            pateicoties Tautas frontes Feimaņu nodaļas iniciatīvai.
        </p>
    </div>

    <img 
        src="{{ asset('img/pagasts/feimani8.jpg') }}"
        alt="Feimaņu pagasts"
        style="width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
    >

    {{-- 3. Krucifikss --}}
    <div style="background:#fff; border:1px solid #ddd; border-radius:14px; padding:20px; margin-bottom:20px;">
        <h2 style="margin-top:0;">Krucifikss Feimaņos</h2>

        <p style="line-height:1.7;">
            Feimaņos atrodas viens no vecākajiem brīvā dabā izvietotajiem krucifiksiem Latgalē.
        </p>

        <p style="line-height:1.7;">
            Zem uz mūrētām kolonām balstītas nojumes atrodas krusts ar kokā grieztu Pestītāja tēlu.
        </p>

        <p style="line-height:1.7;">
            Pastāv pieņēmums, ka krucifikss veidots 18.–19. gadsimtu mijā.
        </p>
    </div>

</div>
@endsection