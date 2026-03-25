@extends('layouts.app')

@section('title', 'Kultūra')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <h1 style="margin-bottom:20px;">Kultūra</h1>

    <img 
        src="{{ asset('img/pagasts/feimani5.jpg') }}"
        alt="Feimaņu pagasts"
        style="width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
    >

    <div style="background:#fff; border:1px solid #ddd; border-radius:14px; padding:24px;">

        <h2 style="margin-top:0;">Feimaņu pagasta kultūras nams</h2>

        <p style="line-height:1.7;">
            Feimaņu pagasta kultūras nams ir nozīmīgs vietējās sabiedrības kultūras un sabiedriskās dzīves centrs,
            kur satiekas paaudzes, tiek koptas tradīcijas un radītas jaunas kultūras iniciatīvas.
            Tas kalpo kā vieta radošai izpausmei un kultūras vērtību saglabāšanai Feimaņu pagastā.
        </p>

        <p style="line-height:1.7;">
            Kultūras nama misija ir būt par radošu, atvērtu un saliedējošu vietu,
            kas veicina kultūras dzīves attīstību Feimaņu pagastā, saglabā vietējās tradīcijas
            un vienlaikus iedvesmo jaunradei.
        </p>

        <p style="line-height:1.7;">
            Kultūras nams nodrošina daudzveidīgas kultūras, izglītības un brīvā laika aktivitātes visām paaudzēm,
            stiprinot kopienas piederības sajūtu un veicinot sabiedrības līdzdalību.
        </p>

        <p style="line-height:1.7;">
            Tā darbība ietver koncertu, izrāžu, izstāžu, svētku un tradicionālo pasākumu rīkošanu,
            kā arī māksliniecisko kolektīvu darbības nodrošināšanu.
            Kultūras nams sadarbojas ar izglītības iestādēm un pašvaldību,
            sekmējot kultūrvides attīstību un kultūras mantojuma saglabāšanu Feimaņu pagastā.
        </p>

        <h3 style="margin-top:25px;">Pamatinformācija</h3>

        <p style="line-height:1.7; margin-bottom:8px;">
            <b>Dibināšanas gads:</b> 1954
        </p>

        <p style="line-height:1.7; margin-bottom:8px;">
            <b>Juridiskais statuss:</b> Pašvaldības struktūrvienība
        </p>

        <p style="line-height:1.7; margin-bottom:8px;">
            <b>Adrese:</b> "Pagasta māja", Feimaņi, Feimaņu pag., Rēzeknes nov., LV-4623
        </p>

        <p style="line-height:1.7; margin-bottom:8px;">
            <b>Kultūras pasākumu organizatore:</b> Dina Šmaukstele
        </p>

        <p style="line-height:1.7; margin-bottom:8px;">
            <b>Mobilais tālrunis:</b> 26478741
        </p>

        <h3 style="margin-top:25px;">Informācija citās tīmekļvietnēs</h3>

        <ul style="line-height:1.8; padding-left:20px; margin-bottom:0;">
            <li>Feimaņu pagasts Facebook</li>
            <li>Feimaņu pagasts - Zudusī Latvija</li>
            <li>Rēzeknes novads</li>
        </ul>

    </div>

</div>
@endsection