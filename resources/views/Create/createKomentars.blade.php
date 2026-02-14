@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Pievienot Komentāru</h1>

    @if($errors->any())
        @foreach ($errors->all() as $kluda)
            <div class="alert alert-danger">{{ $kluda }}</div>
        @endforeach
    @endif

    <form action="/data/newSubmitKomentars" method="POST">
        @csrf

        <div class="container" style="max-width: 70%;">

            <div class="mb-3">
                <label class="form-label">Ieraksts</label>
                <select name="ieraksts_id" class="form-control">
                    @foreach($ieraksti as $ier)
                        <option value="{{ $ier->ieraksts_id }}">
                            {{ $ier->nosaukums }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Lietotājs</label>
                <select name="lietotajs_id" class="form-control">
                    @foreach($lietotaji as $liet)
                        <option value="{{ $liet->lietotajs_id }}">
                            {{ $liet->vards }} {{ $liet->uzvards }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Teksts</label>
                <textarea name="teksts"
                          class="form-control"
                          rows="4">{{ old('teksts') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Izveidots datums</label>
                <input type="date"
                       name="izveidots_datums"
                       class="form-control"
                       value="{{ old('izveidots_datums') }}">
            </div>

            <button type="submit" class="btn btn-info">Saglabāt</button>
        </div>
    </form>

    <br><br>
</div>
@endsection
