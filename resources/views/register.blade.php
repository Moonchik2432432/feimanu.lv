@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-info">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<h1>Reģistrēties!</h1>

<form method="POST" action="/register">
    @csrf

    <div class="mb-3">
        <label for="vards" class="form-label">Vārds</label>
        <input type="text" class="form-control" id="vards" name="vards" value="{{ old('vards') }}" required>
    </div>

    <div class="mb-3">
        <label for="uzvards" class="form-label">Uzvārds</label>
        <input type="text" class="form-control" id="uzvards" name="uzvards" value="{{ old('uzvards') }}" required>
    </div>

    <div class="mb-3">
        <label for="epasts" class="form-label">E-pasts</label>
        <input type="email" class="form-control" id="epasts" name="epasts" value="{{ old('epasts') }}" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Parole</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <button type="submit" class="btn btn-outline-dark">Reģistrēties</button>
    <a href="/login" class="btn btn-info">Ienākt</a>
</form>

@endsection
