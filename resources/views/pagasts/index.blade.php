@extends('layouts.app')

@section('title', 'Pagasts')

@section('content')
<div class="container" style="max-width:1000px; margin:40px auto;">

    <h1 style="margin-bottom:20px;">Feimaņu pagasts</h1>

    <img 
        src="{{ asset('img/pagasts/feimani.jpg') }}"
        alt="Feimaņu pagasts"
        style="width:100%; max-height:400px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
    >

    <div style="background:#fff; border:1px solid #ddd; border-radius:14px; padding:24px; margin-bottom:25px;">
        <p style="line-height:1.7; margin-top:0;">
            Feimaņu pagasts atrodas Rēzeknes novada dienvidu daļā, 32 km no Rēzeknes un 60 km no Daugavpils. Feimaņu pagasta centrs ir Feimaņi, kas 1945. gadā izveidojies Feimaņu ezera krastā pie automaģistrāles un dzelzceļa līnijas Sanktpēterburga — Varšava.
        </p>

        <p style="line-height:1.7;">
            Feimaņi agrāk tautā saukti par “Vīminu”, kas varētu būt cēlies no vārda “vīnmyns”, tas ir, “vienmiņa” (mīt podnieka ripu ar vienu kāju). Kādreiz šajā apvidū dzīvoja ap 70 podnieku. Apkārt Feimaņu ezeram gandrīz uz katra pakalna stāvēja trauku apdedzināšanas namiņš un vai katrā trešajā sētā istabas stūrī vai kādā citā telpā, griezās podnieka ripa. Svešie poļu un vācu kungi, šo vārdu nesaprazdami, balsīgā vīnmins vietā sāka lietot fimin, kas saglabājies līdz mūsdienām kā Feimaņi.

        <p style="line-height:1.7;">
            Ir arī otrs vārda izcelsmes skaidrojums: Feimaņi cēlušies no vācu vārda Vieh (lops), ko tautā izrunāja par Vīmaņiem. Kad 1939. gadā K. Ulmanis izdeva norādījumus latviskot vietvārdus, nelatvisko ciema nosaukumu Feimaņi Rēzeknes apriņķa vadība nolēma mainīt uz Daudzezeru, tikai šo procesu tā arī neizdevās pabeigt.
        </p>

        <p style="line-height:1.7;">
            Feimaņu pagasts iekļaujas Latgales augstienē, un skaistās dabas ainavas ir tās, kas saista gan vietējos iedzīvotājus, gan neatstāj vienaldzīgu vienkāršo garāmbraucēju. Lielisks skats uz Feimaņu ciemu paveras no Leinakolna un Guļāniem, uz Feimaņu ezeru no Kazimirovas un Leperiem, uz Rušonas ezera Ūbeļu līci ar tajā esošajām salām no Dudariem, bet uz visu Feimaņu pagasta teritoriju no Rušonas mežniecības skatu torņa.
        </p>

        <p style="line-height:1.7; margin-bottom:0;">
            Feimaņu pagastu var droši dēvēt par vienu no ezeriem bagātākiem pagastiem Latvijā, jo tā teritorijā atrodas 14 ezeri, no kuriem septiņi ir lielāki par 10 ha, tostarp arī Rušonas ezers (662,9 ha, maksimālais dziļums 29,9 m), kas ir astotais lielākais ezers Latvijā. Tajā ir 34 pussalas, no kurām 10 iekļautas īpaši aizsargājamo dabas objektu skaitā. Feimaņu ezeru un Rušonas ezeru savos dzejoļos ir apcerējis dzejnieks Andris Vējāns: “Tu esi kadiķis un lēpes lapa, Pie kuriem steidzas zinātnieks un gans. Kā zelts tiem dārga tava dūņa slapa, Zied zilā pulkstenīte – viļņu zvans.”
        </p>
    </div>

</div>
@endsection