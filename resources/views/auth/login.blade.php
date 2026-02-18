@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container" style="max-width:400px; margin:40px auto;">

    <h2>Pieslēgties</h2>

    @if($errors->any())
        <div style="color:red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div style="margin-bottom:10px;">
            <label>E-pasts</label>
            <input type="email" name="email" required style="width:100%;">
        </div>

        <div style="margin-bottom:10px;">
            <label>Parole</label>
            <input type="password" name="password" required style="width:100%;">
        </div>

        <button type="submit">Pieslēgties</button>
        <a href="{{ route('register') }}">Register</a>
    </form>

</div>
@endsection
