@extends('layouts.app')

@section('title', 'Pievienot fotogrāfiju')

@section('content')
<div class="container" style="max-width:700px; margin:40px auto;">
    <h1>Pievienot fotogrāfiju</h1>

    <form method="POST" action="{{ route('admin.gallery.images.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:15px;">
            <label>Albums</label><br>
            <select name="album_id" style="width:100%; padding:10px;">
                @foreach($albums as $album)
                    <option value="{{ $album->id }}">{{ $album->title }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:15px;">
            <label>Nosaukums</label><br>
            <input type="text" name="title" value="{{ old('title') }}" style="width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:15px;">
            <label>Secība</label><br>
            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" style="width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:15px;">
            <label>Fotogrāfija</label><br>
            <input type="file" name="image_path" required>
        </div>

        <button type="submit">Saglabāt</button>
    </form>
</div>
@endsection