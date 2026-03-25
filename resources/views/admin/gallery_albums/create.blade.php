@extends('layouts.app')

@section('title', 'Pievienot albumu')

@section('content')
<div class="container" style="max-width:700px; margin:40px auto;">
    <h1>Pievienot albumu</h1>

    <form method="POST" action="{{ route('admin.gallery.albums.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:15px;">
            <label>Nosaukums</label><br>
            <input type="text" name="title" value="{{ old('title') }}" style="width:100%; padding:10px;">
        </div>

        <div style="margin-bottom:15px;">
            <label>Apraksts</label><br>
            <textarea name="description" rows="4" style="width:100%; padding:10px;">{{ old('description') }}</textarea>
        </div>

        <div style="margin-bottom:15px;">
            <label>Vāka attēls</label><br>
            <input type="file" name="cover_image">
        </div>

        <button type="submit">Saglabāt</button>
    </form>
</div>
@endsection