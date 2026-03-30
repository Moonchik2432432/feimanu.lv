@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container" style="max-width:420px; margin:60px auto;">

    <div style="
        background:#fff;
        border:1px solid #ddd;
        border-radius:16px;
        padding:25px;
        box-shadow:0 4px 15px rgba(0,0,0,0.05);
    ">

        <h2 style="margin-top:0; text-align:center;">Pieslēgties</h2>

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

        <form method="POST" action="/login" novalidate>
            @csrf

            <div style="margin-bottom:15px;">
                <label>E-pasts</label><br>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    oninvalid="
                        if (this.validity.valueMissing) {
                            this.setCustomValidity('Lūdzu, aizpildiet šo lauku.');
                        } else if (this.validity.typeMismatch) {
                            this.setCustomValidity('Lūdzu, ievadiet derīgu e-pasta adresi.');
                        } else {
                            this.setCustomValidity('');
                        }
                    "
                    oninput="this.setCustomValidity('')"
                    style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px;"
                >
            </div>

            <div style="margin-bottom:15px;">
                <label>Parole</label><br>
                <input
                    type="password"
                    name="password"
                    required
                    oninvalid="
                        if (this.validity.valueMissing) {
                            this.setCustomValidity('Lūdzu, ievadiet paroli.');
                        } else {
                            this.setCustomValidity('');
                        }
                    "
                    oninput="this.setCustomValidity('')"
                    style="width:100%; padding:10px; border:1px solid #ccc; border-radius:10px;"
                >
            </div>

            <div style="margin-bottom:15px; display:flex; justify-content:space-between; align-items:center;">
                <label style="display:flex; align-items:center; gap:6px;">
                    <input type="checkbox" name="remember">
                    Atcerēties mani
                </label>

                <a href="{{ route('register') }}" style="font-size:14px;">
                    Reģistrācija
                </a>
            </div>

            <button
                type="submit"
                style="
                    width:100%;
                    padding:10px;
                    border:none;
                    border-radius:10px;
                    cursor:pointer;
                    font-weight:bold;
                "
            >
                Pieslēgties
            </button>
        </form>

    </div>

</div>
@endsection
