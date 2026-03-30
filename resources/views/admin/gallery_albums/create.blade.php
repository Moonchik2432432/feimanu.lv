@extends('layouts.app')

@section('title', 'Administrācija - Pievienot albumu')

@section('content')

<div class="container" style="max-width:700px; margin:40px auto;">

    <h1>Pievienot albumu</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.albums.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin:15px 0;">
            <label>Nosaukums</label><br>
            <input type="text"
                   name="title"
                   value="{{ old('title') }}"
                   style="padding:8px; width:100%; box-sizing:border-box;">
        </div>

        <div style="margin:15px 0;">
            <label>Apraksts</label><br>
            <textarea name="description"
                      rows="4"
                      style="padding:8px; width:100%; box-sizing:border-box; resize:vertical;">{{ old('description') }}</textarea>
        </div>

        <div style="margin:15px 0;">
            <label>Vāka attēls</label><br>
        
            <label style="
                display:inline-block;
                padding:8px 14px;
                border-radius:10px;
                border:1px solid #ccc;
                cursor:pointer;
                background:#f5f5f5;
            ">
                Izvēlēties failu
                <input type="file" name="cover_image" id="cover-input" style="display:none;">
            </label>
        
            <div id="cover-name" style="margin-top:8px; color:#666;">
                Fails nav izvēlēts
            </div>
        
            <div style="margin-top:6px; color:#888; font-size:14px;">
                Ja attēls netiks izvēlēts, automātiski tiks izmantots jaunākais pievienotais attēls no galerijas.
            </div>
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" style="padding:9px 14px;">Saglabāt</button>

            <a href="{{ route('admin.gallery.albums') }}"
               style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
                Atcelt
            </a>
        </div>
    </form>

</div>

<script>
document.getElementById('cover-input')?.addEventListener('change', function() {
    const fileName = this.files.length > 0
        ? this.files[0].name
        : 'Fails nav izvēlēts';

    document.getElementById('cover-name').textContent = fileName;
});
</script>

@endsection
