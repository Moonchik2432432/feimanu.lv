@extends('layouts.app')

@section('title', 'Pievienot aktualitāti')

@section('content')
<div class="container" style="max-width:650px; margin:40px auto;">

    <h1>Pievienot aktualitāti</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin:10px 0;">
            <label>Nosaukums</label><br>
            <input type="text" name="nosaukums"
                   value="{{ old('nosaukums') }}"
                   style="width:100%; padding:8px;">
        </div>

        <div style="margin:10px 0;">
            <label>Kategorija</label><br>
            <select name="kategorija_id" style="width:100%; padding:8px;">
                <option value="">-- Izvēlēties --</option>

                @foreach($categories as $cat)
                    <option value="{{ $cat->kategorija_id }}"
                        @selected(old('kategorija_id') == $cat->kategorija_id)>
                        {{ $cat->nosaukums }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin:10px 0;">
            <label>Saturs</label><br>
            <textarea name="saturs" rows="6" style="width:100%; padding:8px;">{{ old('saturs') }}</textarea>
        </div>

        {{-- FILE INPUT --}}
        <div style="margin:10px 0;">
            <label>Bilde</label><br>

            <label style="
                display:inline-block;
                padding:8px 14px;
                border-radius:10px;
                border:1px solid #ccc;
                cursor:pointer;
                background:#f5f5f5;
            ">
                Izvēlēties failu
                <input type="file" name="bilde" id="bilde-input" required style="display:none;">
            </label>

            <div id="bilde-name" style="margin-top:8px; color:#666;">
                Fails nav izvēlēts
            </div>
        </div>

        <button type="submit" style="padding:10px 14px;">Saglabāt</button>
        <a href="{{ route('admin.news') }}" style="margin-left:10px;">Atpakaļ</a>

    </form>

</div>

{{-- JS --}}
<script>
document.getElementById('bilde-input')?.addEventListener('change', function() {
    const fileName = this.files.length > 0
        ? this.files[0].name
        : 'Fails nav izvēlēts';

    document.getElementById('bilde-name').textContent = fileName;
});
</script>

@endsection