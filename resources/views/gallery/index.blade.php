@extends('layouts.app')

@section('title', 'Galerija')

@section('content')
<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Galerija</h1>

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(250px, 1fr));
        gap:20px;
        margin-top:20px;
    ">
        @forelse($albums as $album)
            <a href="{{ route('gallery.show', $album->id) }}" style="text-decoration:none; color:black;">
                
                <div style="border:1px solid #ddd; border-radius:12px; overflow:hidden; background:white;">

                    @if($album->cover_image)
                        <img src="{{ asset($album->cover_image) }}"
                             style="width:100%; height:200px; object-fit:cover;">
                    @else
                        <div style="height:200px; background:#eee;"></div>
                    @endif

                    <div style="padding:15px;">
                        <h3 style="margin:0 0 6px 0;">{{ $album->title }}</h3>

                        <small style="color:#888; display:block; margin-bottom:8px;">
                            {{ \Carbon\Carbon::parse($album->created_at)->format('d.m.Y') }}
                        </small>

                        <p style="color:#666; margin:0;">
                            {{ $album->images_count }} foto
                        </p>
                    </div>

                </div>

            </a>
        @empty
            <p>Nav albumu</p>
        @endforelse
    </div>

</div>
@endsection