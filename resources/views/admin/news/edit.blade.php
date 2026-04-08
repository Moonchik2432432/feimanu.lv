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
            <label>Saturs</label><br>
            <textarea name="saturs" rows="6" style="width:100%; padding:8px;">{{ old('saturs', $post->saturs) }}</textarea>
        </div>

        <div style="margin:10px 0;">
            <label>Pašreizējā bilde</label><br>

            @if($post->bilde)
                <img src="{{ asset($post->bilde) }}" style="max-width:200px; margin-top:10px; border-radius:10px;">
            @else
                <div style="color:gray;">Nav bildes</div>
            @endif
        </div>

        <div style="margin:10px 0;">
            <label>Jauna bilde no datora</label><br>

            <label style="
                display:inline-block;
                padding:8px 14px;
                border-radius:10px;
                border:1px solid #ccc;
                cursor:pointer;
                background:#f5f5f5;
            ">
                Izvēlēties failu
                <input type="file" name="bilde" id="bilde-input" style="display:none;">
            </label>

            <div id="bilde-name" style="margin-top:8px; color:#666;">
                Fails nav izvēlēts
            </div>
        </div>

        <div style="margin:15px 0;">
            <label>Vai izvēlēties bildi no galerijas</label><br>
            <select name="gallery_image" style="width:100%; padding:8px;">
                <option value="">-- Neizmainīt --</option>

                @foreach($galleryImages as $img)
                    <option value="{{ $img->image_path }}"
                        @selected(old('gallery_image', $post->bilde) == $img->image_path)>
                        {{ $img->title ?: 'Foto #' . $img->id }}
                    </option>
                @endforeach
            </select>
            <small style="color:#777; display:block; margin-top:6px;">
                Ja izvēlēsies failu no datora, tas aizvietos galerijas bildi.
            </small>
        </div>

        <button type="submit" style="padding:10px 14px;">Saglabāt</button>
        <a href="{{ route('admin.news') }}" style="margin-left:10px;">Atpakaļ</a>

    </form>

</div>

<script>
document.getElementById('bilde-input')?.addEventListener('change', function() {
    const fileName = this.files.length > 0
        ? this.files[0].name
        : 'Fails nav izvēlēts';

    document.getElementById('bilde-name').textContent = fileName;
});
</script>
@endsection
