@extends('layouts.app')

@section('content')
    <h1>Ielogošanās</h1>

    <form method="POST" action="/login">
        @csrf

        <div class="mb-3">
            <label for="epasts" class="form-label">E-pasts</label>
            <input type="email" class="form-control" id="epasts" name="epasts" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Parole</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-outline-dark">Ieiet</button>
        <a href="{{ route('register') }}" class="btn btn-info">Reģistrēties</a>
    </form>

    @if($errors->any())
        <div class="alert alert-danger mt-3">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

@endsection
