@extends('layouts.app')

@section('title', 'Reģistrācija')

@section('content')
<div class="container" style="max-width:420px; margin:60px auto;">

    <div style="
        background:#fff;
        border:1px solid #ddd;
        border-radius:16px;
        padding:25px;
        box-shadow:0 4px 15px rgba(0,0,0,0.05);
    ">

        <h2 style="margin-top:0; text-align:center;">Reģistrācija</h2>

        @if($errors->any())
            <div style="
                padding:10px;
                background:#ffecec;
                border:1px solid #ffbcbc;
                border-radius:10px;
                margin:15px 0;
                color:#a30000;
            ">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- NAME -->
            <div style="margin-bottom:15px;">
                <label>Vārds</label><br>
                <input type="text" name="name" value="{{ old('name') }}" required
                       oninvalid="this.setCustomValidity('Lūdzu, aizpildiet šo lauku.')"
                       oninput="this.setCustomValidity('')"
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px;">
            </div>

            <!-- EMAIL -->
            <div style="margin-bottom:15px;">
                <label>E-pasts</label><br>
                <input type="email" name="email" value="{{ old('email') }}" required
                       oninvalid="if(this.value===''){this.setCustomValidity('Lūdzu, aizpildiet šo lauku.')}else{this.setCustomValidity('Lūdzu, ievadiet derīgu e-pasta adresi.')}"
                       oninput="this.setCustomValidity('')"
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px;">
            </div>

            <!-- PASSWORD -->
            <div style="margin-bottom:15px;">
                <label>Parole</label><br>
                <input type="password" name="password" required minlength="6"
                       oninvalid="if(this.value===''){this.setCustomValidity('Lūdzu, aizpildiet šo lauku.')}else{this.setCustomValidity('Parolei jābūt vismaz 6 simboliem.')}"
                       oninput="this.setCustomValidity('')"
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px;">
            </div>

            <!-- PASSWORD CONFIRM -->
            <div style="margin-bottom:15px;">
                <label>Atkārtot paroli</label><br>
                <input type="password" name="password_confirmation" required
                       oninvalid="this.setCustomValidity('Lūdzu, aizpildiet šo lauku.')"
                       oninput="this.setCustomValidity('')"
                       style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px;">
            </div>

            <button type="submit"
                    style="
                        width:100%;
                        padding:10px;
                        border:none;
                        border-radius:10px;
                        cursor:pointer;
                        font-weight:bold;
                    ">
                Izveidot kontu
            </button>

        </form>

        <p style="margin-top:15px; text-align:center;">
            Jau ir konts?
            <a href="{{ route('login') }}">Pieslēgties</a>
        </p>

    </div>

</div>
@endsection