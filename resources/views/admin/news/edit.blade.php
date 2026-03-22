@extends('layouts.app')

@section('title', 'Rediģēt aktualitāti')

@section('content')
<div class="container" style="max-width:650px; margin:40px auto;">

    <h1>Rediģēt: {{ $post->nosaukums }}</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.news.update', $post->ieraksts_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin:10px 0;">
            <label>Nosaukums</label><br>
            <input type="text" name="nosaukums"
                   value="{{ old('nosaukums', $post->nosaukums) }}"
                   style="width:100%; padding:8px;">
        </div>

        <div style="margin:10px 0;">
            <label>Kategorija</label><br>
            <select name="kategorija_id" style="width:100%; padding:8px;">
                @foreach($categories as $cat)
                    <option value="{{ $cat->kategorija_id }}"
                        @selected(old('kategorija_id', $post->kategorija_id) == $cat->kategorija_id)>
                        {{ $cat->nosaukums }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin:10px 0;">
            <label>Status</label><br>
            <select name="status" style="width:100%; padding:8px;">
                <option value="publicets" @selected(old('status', $post->status) === 'publicets')>Publicēts</option>
                <option value="melnraksts" @selected(old('status', $post->status) === 'melnraksts')>Melnraksts</option>
            </select>
        </div>

        <div style="margin:10px 0;">
            <label>Saturs</label><br>
            <textarea name="saturs" rows="6" style="width:100%; padding:8px;">{{ old('saturs', $post->saturs) }}</textarea>
        </div>

        <div style="margin:10px 0;">
            <label>Pašreizējā bilde</label><br>

            @if($post->bilde)
                <img src="{{ asset($post->bilde) }}" style="max-width:200px; margin-top:10px;">
            @else
                <div style="color:gray;">Nav bildes</div>
            @endif
        </div>

        <div style="margin:10px 0;">
            <label>Jauna bilde</label><br>
            <input type="file" name="bilde">
        </div>

        <button type="submit" style="padding:10px 14px;">Saglabāt</button>
        <a href="{{ route('admin.news') }}" style="margin-left:10px;">Back</a>

    </form>

</div>
@endsection