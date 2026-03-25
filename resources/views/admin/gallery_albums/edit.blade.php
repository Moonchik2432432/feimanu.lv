@extends('layouts.app')

@section('title', 'Rediģēt albumu')

@section('content')
<div class="container" style="max-width:700px; margin:40px auto;">
    <h1>Rediģēt albumu</h1>

    <form method="POST" action="{{ route('admin.gallery.albums.update', $album->id) }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:15px;">
            <label>Nosaukums</label><br>
            <input type="text" name="title" value="{{ old('title', $album->title) }}" style="width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:15px;">
            <label>Apraksts</label><br>
            <textarea name="description" rows="4" style="width:100%; padding:10px;">{{ old('description', $album->description) }}</textarea>
        </div>

        @if($album->cover_image)
            <div style="margin-bottom:15px;">
                <img src="{{ asset($album->cover_image) }}" style="width:180px; border-radius:10px;">
            </div>
        @endif

        <div style="margin-bottom:15px;">
            <label>Jauns vāka attēls</label><br>
            <input type="file" name="cover_image">
        </div>

        <button type="submit">Atjaunināt</button>
    </form>
</div>
@endsection