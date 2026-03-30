@extends('layouts.app')

@section('title', $album->title)

@section('content')
<div class="container" style="max-width:1100px; margin:40px auto;">

    <a href="{{ url()->previous() }}"
       style="
            display:inline-block;
            margin-bottom:20px;
            padding:8px 14px;
            border:1px solid #ddd;
            border-radius:10px;
            text-decoration:none;
            color:#333;
            background:#fff;
            transition:0.2s;
       "
       onmouseover="this.style.background='#f5f5f5'"
       onmouseout="this.style.background='#fff'">
        ← Atpakaļ
    </a>

    <h1>{{ $album->title }}</h1>

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));
        gap:15px;
        margin-top:20px;
    ">
        @forelse($album->images as $img)
            <div>
                <a href="{{ asset($img->image_path) }}" data-lightbox="gallery">
                    <img
                        src="{{ asset($img->image_path) }}"
                        alt="{{ $img->title ?? 'Foto' }}"
                        style="width:100%; height:150px; object-fit:cover; border-radius:8px;"
                    >
                </a>

                <div style="margin-top:6px;">
                    <small style="color:#666; display:block;">
                        {{ $img->title ?: 'Bez nosaukuma' }}
                    </small>

                    <small style="color:#999;">
                        {{ $img->created_at ? $img->created_at->format('d.m.Y') : '' }}
                    </small>
                </div>
            </div>
        @empty
            <p>Nav fotogrāfiju</p>
        @endforelse
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            albumLabel: "Attēls %1 no %2"
        });
    }
});
</script>

@endsection
