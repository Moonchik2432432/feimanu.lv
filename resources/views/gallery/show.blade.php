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
            <a href="{{ asset($img->image_path) }}" data-lightbox="gallery">
                <img src="{{ asset($img->image_path) }}"
                     style="width:100%; height:150px; object-fit:cover; border-radius:8px;">
            </a>
        @empty
            <p>Nav fotogrāfiju</p>
        @endforelse
    </div>

</div>
@endsection