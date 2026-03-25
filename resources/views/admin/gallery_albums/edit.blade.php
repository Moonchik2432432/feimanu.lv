@extends('layouts.app')

@section('title', 'Administrācija - Rediģēt albumu')

@section('content')

<div class="container" style="max-width:700px; margin:40px auto;">

    <h1>Rediģēt albumu</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.albums.update', $album->id) }}" enctype="multipart/form-data">
        @csrf

        <div style="margin:15px 0;">
            <label>Nosaukums</label><br>
            <input type="text"
                   name="title"
                   value="{{ old('title', $album->title) }}"
                   style="padding:8px; width:100%; box-sizing:border-box;">
        </div>

        <div style="margin:15px 0;">
            <label>Apraksts</label><br>
            <textarea name="description"
                      rows="4"
                      style="padding:8px; width:100%; box-sizing:border-box; resize:vertical;">{{ old('description', $album->description) }}</textarea>
        </div>

        @if($album->cover_image)
            <div style="margin:15px 0;">
                <label>Pašreizējais vāks</label><br>
                <img src="{{ asset($album->cover_image) }}"
                     alt="Cover"
                     style="width:180px; height:auto; border-radius:8px; margin-top:8px;">
            </div>
        @endif

        <div style="margin:15px 0;">
            <label>Jauns vāka attēls</label><br>
            <input type="file" name="cover_image">
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" style="padding:9px 14px;">Atjaunināt</button>

            <a href="{{ route('admin.gallery.albums') }}"
               style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
                Atcelt
            </a>
        </div>
    </form>

</div>

@endsection