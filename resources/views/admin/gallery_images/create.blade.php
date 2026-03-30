@extends('layouts.app')

@section('title', 'Administrācija - Pievienot fotogrāfijas')

@section('content')

<div class="container" style="max-width:800px; margin:40px auto;">

    <h1>Pievienot fotogrāfijas</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.images.store') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin:15px 0;">
            <label>Albums</label><br>
            <select name="album_id" style="padding:8px; width:100%;">
                <option value="">Izvēlies albumu</option>
                @foreach($albums as $album)
                    <option value="{{ $album->id }}">
                        {{ $album->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin:15px 0;">
            <label>Fotogrāfijas</label><br>

            <label style="
                display:inline-block;
                padding:10px 16px;
                border-radius:10px;
                border:1px solid #ccc;
                cursor:pointer;
                background:#f5f5f5;
            ">
                Izvēlēties failus
                <input type="file" name="images[]" id="image-input" multiple style="display:none;">
            </label>

            <div style="margin-top:8px; color:#666;">
                Var izvēlēties vairākas fotogrāfijas
            </div>
        </div>

        <!-- PREVIEW -->
        <div id="preview" style="
            display:grid;
            grid-template-columns:repeat(auto-fill, minmax(120px, 1fr));
            gap:10px;
            margin-top:20px;
        "></div>

        <div style="display:flex; gap:10px; margin-top:25px;">
            <button type="submit" style="padding:10px 16px;">Saglabāt</button>

            <a href="{{ route('admin.gallery.images') }}"
               style="padding:10px 16px; background:#eee; text-decoration:none; color:#000;">
                Atcelt
            </a>
        </div>

    </form>

</div>

<script>
document.getElementById('image-input').addEventListener('change', function () {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';

    const files = this.files;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        if (!file.type.startsWith('image/')) continue;

        const reader = new FileReader();

        reader.onload = function (e) {
            const div = document.createElement('div');

            div.innerHTML = `
                <div style="
                    border:1px solid #ddd;
                    border-radius:10px;
                    overflow:hidden;
                ">
                    <img src="${e.target.result}"
                         style="width:100%; height:100px; object-fit:cover;">
                </div>
            `;

            preview.appendChild(div);
        };

        reader.readAsDataURL(file);
    }
});
</script>

@endsection
