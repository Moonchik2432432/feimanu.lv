@extends('layouts.app')

@section('title', 'Reģistrācija')

@section('content')
<div class="container" style="max-width:420px; margin:40px auto;">

    <h2>Reģistrācija</h2>

    @if($errors->any())
        <div style="color:red; margin-bottom:10px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div style="margin-bottom:10px;">
            <label>Vārds</label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;">
        </div>

        <div style="margin-bottom:10px;">
            <label>E-pasts</label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;">
        </div>

        <div style="margin-bottom:10px;">
            <label>Parole</label>
            <input type="password" name="password" required style="width:100%;">
        </div>

        <div style="margin-bottom:10px;">
            <label>Atkārtot paroli</label>
            <input type="password" name="password_confirmation" required style="width:100%;">
        </div>

        <button type="submit">Izveidot kontu</button>
    </form>

    <p style="margin-top:12px;">
        Jau ir konts? <a href="{{ route('login') }}">Pieslēgties</a>
    </p>

</div>
@endsection
