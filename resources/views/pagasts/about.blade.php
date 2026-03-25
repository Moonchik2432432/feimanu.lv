@extends('layouts.app')

@section('title', 'Par mums')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <h1 style="margin-bottom:20px;">Par mums</h1>

    <img 
        src="{{ asset('img/pagasts/feimani2.jpg') }}"
        alt="Feimaņu pagasts"
        style="width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
    >

    <div style="background:#fff; border:1px solid #ddd; border-radius:14px; padding:24px 24px;">

        <p style="line-height:1.7; margin-top:0;">
            Feimaņu pagasts ir Rēzeknes novada administratīvā teritorija Latgales reģionā, kas atrodas novada dienvidu daļā. Pagasta centrs ir Feimaņi, kas izvietots gleznainā vietā pie Feimaņu ezera.</p>
        </p>

        <p style="line-height:1.7;">
            Pagasts ietilpst Maltas apvienības pārvaldes sastāvā un nodrošina iedzīvotājiem nepieciešamos pakalpojumus un infrastruktūru. Feimaņu pagastā pieejama pamatskola, bibliotēka, kultūras nams un citi sabiedriskie objekti, kas veicina vietējās kopienas attīstību.
        </p>

        <p style="line-height:1.7;">
            Feimaņu pagasts ir vieta, kur tiek saglabātas Latgales kultūras tradīcijas, valoda un identitāte. Vietējie iedzīvotāji aktīvi piedalās kultūras pasākumos, sabiedriskajā dzīvē un dažādos projektos, kas veicina pagasta attīstību.
        </p>

        <p style="line-height:1.7;">
            Apkārtne izceļas ar bagātu dabas vidi – ezeriem, mežiem un ainavām, kas piesaista gan vietējos iedzīvotājus, gan viesus. Feimaņu pagasts ir piemērota vieta mierīgai dzīvei, atpūtai dabā un tradicionālo vērtību saglabāšanai.
        </p>

        <p style="line-height:1.7; margin-bottom:0;">
            Pagasta attīstības mērķis ir nodrošināt kvalitatīvu dzīves vidi iedzīvotājiem, attīstīt infrastruktūru un saglabāt kultūrvēsturisko mantojumu nākamajām paaudzēm.
        </p>

    </div>

    <img 
        src="{{ asset('img/pagasts/feimani3.jpg') }}"
        alt="Feimaņu pagasts"
        style="width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
    >

</div>
@endsection