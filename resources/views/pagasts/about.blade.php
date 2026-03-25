@extends('layouts.app')

@section('title', 'Par mums')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <a href="{{ route('pagasts.index') }}"
       style="
            display:inline-block;
            margin-bottom:20px;
            padding:8px 14px;
            border:1px solid #ddd;
            border-radius:10px;
            text-decoration:none;
            color:#333;
            background:#fff;
        ">
        ← Atpakaļ
    </a>

    <h1 style="margin-bottom:20px;">Par mums</h1>

    <div style="background:#fff; border:1px solid #ddd; border-radius:14px; padding:24px;">

        <p style="line-height:1.7; margin-top:0;">
            Feimaņu pagasts ir Rēzeknes novada administratīvā teritorija Latgales reģionā, kas izceļas ar bagātu kultūrvēsturisko mantojumu un skaistu dabas vidi. Pagasta centrs ir Feimaņi, kas atrodas gleznainā vietā pie Feimaņu ezera.
        </p>

        <p style="line-height:1.7;">
            Mūsu pagasts ir vieta, kur harmoniski savienojas tradīcijas un mūsdienīga dzīve. Vietējā kopiena aktīvi piedalās kultūras, izglītības un sabiedriskajās aktivitātēs, saglabājot Latgales identitāti un vērtības.
        </p>

        <p style="line-height:1.7;">
            Feimaņu pagastā iedzīvotājiem ir pieejami svarīgi pakalpojumi, tostarp izglītības iestādes, kultūras nams, bibliotēka un citi sabiedriskie objekti, kas nodrošina kvalitatīvu dzīves vidi.
        </p>

        <p style="line-height:1.7;">
            Mēs cenšamies attīstīt pagastu, veicinot vietējo iniciatīvu, atbalstot projektus un uzlabojot infrastruktūru. Mūsu mērķis ir radīt drošu, sakārtotu un pievilcīgu vidi gan iedzīvotājiem, gan viesiem.
        </p>

        <p style="line-height:1.7; margin-bottom:0;">
            Feimaņu pagasts ir vieta, kur ikviens var justies piederīgs — šeit tiek cienītas tradīcijas, kopiena un daba.
        </p>

    </div>

</div>
@endsection