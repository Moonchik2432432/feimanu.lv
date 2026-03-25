@extends('layouts.app')

@section('title', 'Administrācija - Pievienot fotogrāfiju')

@section('content')

<div class="container" style="max-width:700px; margin:40px auto;">

    <h1>Pievienot fotogrāfiju</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.images.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin:15px 0;">
            <label>Albums</label><br>
            <select name="album_id" style="padding:8px; width:100%; box-sizing:border-box;">
                <option value="">Izvēlies albumu</option>
                @foreach($albums as $album)
                    <option value="{{ $album->id }}" {{ old('album_id') == $album->id ? 'selected' : '' }}>
                        {{ $album->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin:15px 0;">
            <label>Nosaukums</label><br>
            <input type="text"
                   name="title"
                   value="{{ old('title') }}"
                   style="padding:8px; width:100%; box-sizing:border-box;">
        </div>

        <div style="margin:15px 0;">
            <label>Secība</label><br>
            <input type="number"
                   name="sort_order"
                   value="{{ old('sort_order', 0) }}"
                   style="padding:8px; width:100%; box-sizing:border-box;">
        </div>

        <div style="margin:15px 0;">
            <label>Fotogrāfija</label><br>
            <input type="file" name="image_path" required>
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" style="padding:9px 14px;">Saglabāt</button>

            <a href="{{ route('admin.gallery.images') }}"
               style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
                Atcelt
            </a>
        </div>
    </form>

</div>

@endsection