@extends('layouts.app')

@section('title', 'Administrācija - Albumi')

@section('content')
<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Galerijas albumi</h1>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; margin:15px 0;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.gallery.albums.create') }}" style="display:inline-block; margin-bottom:20px; padding:10px 14px; background:#093600; color:#fff; text-decoration:none; border-radius:10px;">
        Pievienot albumu
    </a>

    <table style="width:100%; border-collapse:collapse; background:#fff;">
        <thead>
            <tr>
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Vāks</th>
                <th style="padding:10px; border:1px solid #ddd;">Nosaukums</th>
                <th style="padding:10px; border:1px solid #ddd;">Foto skaits</th>
                <th style="padding:10px; border:1px solid #ddd;">Darbības</th>
            </tr>
        </thead>
        <tbody>
            @forelse($albums as $album)
                <tr>
                    <td style="padding:10px; border:1px solid #ddd;">{{ $album->id }}</td>
                    <td style="padding:10px; border:1px solid #ddd;">
                        @if($album->cover_image)
                            <img src="{{ asset($album->cover_image) }}" style="width:80px; height:60px; object-fit:cover; border-radius:8px;">
                        @endif
                    </td>
                    <td style="padding:10px; border:1px solid #ddd;">{{ $album->title }}</td>
                    <td style="padding:10px; border:1px solid #ddd;">{{ $album->images_count }}</td>
                    <td style="padding:10px; border:1px solid #ddd;">
                        <a href="{{ route('admin.gallery.albums.edit', $album->id) }}">Rediģēt</a>

                        <form method="POST" action="{{ route('admin.gallery.albums.delete', $album->id) }}" style="display:inline-block; margin-left:10px;">
                            @csrf
                            <button type="submit" onclick="return confirm('Dzēst albumu?')" style="color:red;">Dzēst</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:10px; border:1px solid #ddd;">Nav albumu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $albums->links('pagination.default') }}
    </div>

</div>
@endsection