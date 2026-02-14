@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Pievienot Lietotāju</h1>

    @if($errors->any())
        @foreach ($errors->all() as $kluda)
            <div class="alert alert-danger">{{ $kluda }}</div>
        @endforeach
    @endif

    <form action="/data/newSubmitLietotajs" method="POST">
        @csrf

        <div class="container" style="max-width: 60%;">

            <div class="mb-3">
                <label class="form-label">Vārds</label>
                <input type="text"
                       name="vards"
                       class="form-control"
                       value="{{ old('vards') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Uzvārds</label>
                <input type="text"
                       name="uzvards"
                       class="form-control"
                       value="{{ old('uzvards') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Epasts</label>
                <input type="email"
                       name="epasts"
                       class="form-control"
                       value="{{ old('epasts') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Loma</label>
                <select name="loma" class="form-control">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-info">Saglabāt</button>
        </div>
    </form>

    <br><br>
</div>
@endsection
