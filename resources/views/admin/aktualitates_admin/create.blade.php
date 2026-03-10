@extends('layouts.app')

@section('title', 'Pievienot aktualitāti')

@section('content')
<div class="container" style="max-width:800px;">

    <h1>Pievienot aktualitāti</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffb3b3; border-radius:8px; margin:15px 0;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('aktualitates.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:12px;">
            <label>Nosaukums</label>
            <input type="text" name="nosaukums" value="{{ old('nosaukums') }}" style="width:100%;" required>
        </div>

        <div style="margin-bottom:12px;">
            <label>Kategorija</label>
            <select name="kategorija_id" style="width:100%;" required>
                <option value="">-- Izvēlēties --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->kategorija_id }}" @selected(old('kategorija_id') == $cat->kategorija_id)>
                        {{ $cat->nosaukums }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:12px;">
            <label>Status</label>
            <select name="status" style="width:100%;" required>
                <option value="published" @selected(old('status','published') === 'published')>Published</option>
                <option value="draft" @selected(old('status') === 'draft')>Draft</option>
            </select>
        </div>

        <div style="margin-bottom:12px;">
            <label>Saturs</label>
            <textarea name="saturs" rows="8" style="width:100%;" required>{{ old('saturs') }}</textarea>
        </div>

        <div style="margin-bottom:14px;">
            <label>Bilde (nav obligāti)</label><br>
            <input type="file" name="bilde" accept="image/*">
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit">Saglabāt</button>
            <a href="{{ route('aktualitates.index') }}">Atcelt</a>
        </div>
    </form>

</div>
@endsection