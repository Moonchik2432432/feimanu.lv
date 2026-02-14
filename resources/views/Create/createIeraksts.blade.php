@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Pievienot Ierakstu</h1>

    @if($errors->any())
        @foreach ($errors->all() as $kluda)
            <div class="alert alert-danger">{{ $kluda }}</div>
        @endforeach
    @endif

    <form action="/data/newSubmitIeraksts" method="POST">
        @csrf

        <div class="container" style="max-width: 70%;">

            <div class="mb-3">
                <label class="form-label">Nosaukums</label>
                <input type="text"
                       name="nosaukums"
                       class="form-control"
                       value="{{ old('nosaukums') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Kategorija</label>
                <select name="kategorija_id" class="form-control">
                    @foreach($kategorijas as $kat)
                        <option value="{{ $kat->kategorija_id }}">
                            {{ $kat->nosaukums }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Saturs</label>
                <textarea name="saturs"
                          class="form-control"
                          rows="6">{{ old('saturs') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Publicēšanas datums</label>
                <input type="date"
                       name="publicets_datums"
                       class="form-control"
                       value="{{ old('publicets_datums') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Bilde (URL)</label>
                <input type="text"
                       name="bilde"
                       class="form-control"
                       value="{{ old('bilde') }}">
            </div>

            <button type="submit" class="btn btn-info">Saglabāt</button>
        </div>
    </form>

    <br><br>
</div>
@endsection
