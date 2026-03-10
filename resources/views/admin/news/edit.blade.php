@extends('layouts.app')

@section('title', 'Rediģēt aktualitāti')

@section('content')
<div class="container" style="max-width:800px; margin:40px auto;">

    <h1>Rediģēt aktualitāti</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffb3b3; border-radius:8px; margin:15px 0;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.news.update', $post->ieraksts_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-bottom:12px;">
            <label>Nosaukums</label>
            <input type="text" name="nosaukums" value="{{ old('nosaukums', $post->nosaukums) }}" style="width:100%;" required>
        </div>

        <div style="margin-bottom:12px;">
            <label>Kategorija</label>
            <select name="kategorija_id" style="width:100%;" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->kategorija_id }}"
                        @selected(old('kategorija_id', $post->kategorija_id) == $cat->kategorija_id)>
                        {{ $cat->nosaukums }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:12px;">
            <label>Status</label>
            <select name="status" style="width:100%;" required>
                <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
            </select>
        </div>

        <div style="margin-bottom:12px;">
            <label>Saturs</label>
            <textarea name="saturs" rows="8" style="width:100%;" required>{{ old('saturs', $post->saturs) }}</textarea>
        </div>

        <div style="margin-bottom:10px;">
            <label>Pašreizējā bilde</label><br>

            @if($post->attels)
                <img src="{{ asset('storage/' . $post->attels) }}"
                     style="max-width:260px; border-radius:10px; margin:10px 0; display:block;">
            @else
                <div style="color:gray;">Nav bildes</div>
            @endif
        </div>

        <div style="margin-bottom:14px;">
            <label>Jauna bilde (nav obligāti)</label><br>
            <input type="file" name="bilde" accept="image/*">
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit">Saglabāt izmaiņas</button>
            <a href="{{ route('admin.news') }}">Atpakaļ</a>
        </div>
    </form>

</div>
@endsection