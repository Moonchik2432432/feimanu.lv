@extends('layouts.app')

@section('title', 'Administrācija - Rediģēt fotogrāfiju')

@section('content')

<div class="container" style="max-width:700px; margin:40px auto;">

    <h1>Rediģēt fotogrāfiju</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.images.update', $image->id) }}" enctype="multipart/form-data">
        @csrf

        <div style="margin:15px 0;">
            <label>Albums</label><br>
            <select name="album_id" style="padding:8px; width:100%; box-sizing:border-box;">
                @foreach($albums as $album)
                    <option value="{{ $album->id }}" {{ old('album_id', $image->album_id) == $album->id ? 'selected' : '' }}>
                        {{ $album->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin:15px 0;">
            <label>Nosaukums</label><br>
            <input type="text"
                   name="title"
                   value="{{ old('title', $image->title) }}"
                   style="padding:8px; width:100%; box-sizing:border-box;">
        </div>

        <div style="margin:15px 0;">
            <label>Secība</label><br>
            <input type="number"
                   name="sort_order"
                   value="{{ old('sort_order', $image->sort_order) }}"
                   style="padding:8px; width:100%; box-sizing:border-box;">
        </div>

        <div style="margin:15px 0;">
            <label>Pašreizējā fotogrāfija</label><br>
            <img src="{{ asset($image->image_path) }}"
                 alt="Foto"
                 style="width:180px; height:auto; border-radius:8px; margin-top:8px;">
        </div>

        <div style="margin:15px 0;">
            <label>Jauna fotogrāfija</label><br>
            <input type="file" name="image_path">
        </div>

        <div style="display:flex; gap:10px; margin-top:20px;">
            <button type="submit" style="padding:9px 14px;">Atjaunināt</button>

            <a href="{{ route('admin.gallery.images') }}"
               style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
                Atcelt
            </a>
        </div>
    </form>

</div>

@endsection