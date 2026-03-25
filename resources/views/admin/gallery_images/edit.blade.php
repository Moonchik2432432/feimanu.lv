@extends('layouts.app')

@section('title', 'Rediģēt fotogrāfiju')

@section('content')
<div class="container" style="max-width:700px; margin:40px auto;">
    <h1>Rediģēt fotogrāfiju</h1>

    <form method="POST" action="{{ route('admin.gallery.images.update', $image->id) }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:15px;">
            <label>Albums</label><br>
            <select name="album_id" style="width:100%; padding:10px;">
                @foreach($albums as $album)
                    <option value="{{ $album->id }}" {{ $image->album_id == $album->id ? 'selected' : '' }}>
                        {{ $album->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:15px;">
            <label>Nosaukums</label><br>
            <input type="text" name="title" value="{{ old('title', $image->title) }}" style="width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:15px;">
            <label>Secība</label><br>
            <input type="number" name="sort_order" value="{{ old('sort_order', $image->sort_order) }}" style="width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:15px;">
            <img src="{{ asset($image->image_path) }}" style="width:180px; border-radius:10px;">
        </div>

        <div style="margin-bottom:15px;">
            <label>Jauna fotogrāfija</label><br>
            <input type="file" name="image_path">
        </div>

        <button type="submit">Atjaunināt</button>
    </form>
</div>
@endsection