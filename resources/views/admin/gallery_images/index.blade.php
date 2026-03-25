@extends('layouts.app')

@section('title', 'Administrācija - Fotogrāfijas')

@section('content')
<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Galerijas fotogrāfijas</h1>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; margin:15px 0;">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.gallery.images.create') }}" style="display:inline-block; margin-bottom:20px; padding:10px 14px; background:#093600; color:#fff; text-decoration:none; border-radius:10px;">
        Pievienot fotogrāfiju
    </a>

    <table style="width:100%; border-collapse:collapse; background:#fff;">
        <thead>
            <tr>
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Foto</th>
                <th style="padding:10px; border:1px solid #ddd;">Albums</th>
                <th style="padding:10px; border:1px solid #ddd;">Nosaukums</th>
                <th style="padding:10px; border:1px solid #ddd;">Secība</th>
                <th style="padding:10px; border:1px solid #ddd;">Darbības</th>
            </tr>
        </thead>
        <tbody>
            @forelse($images as $image)
                <tr>
                    <td style="padding:10px; border:1px solid #ddd;">{{ $image->id }}</td>
                    <td style="padding:10px; border:1px solid #ddd;">
                        <img src="{{ asset($image->image_path) }}" style="width:80px; height:60px; object-fit:cover; border-radius:8px;">
                    </td>
                    <td style="padding:10px; border:1px solid #ddd;">{{ $image->album->title ?? '-' }}</td>
                    <td style="padding:10px; border:1px solid #ddd;">{{ $image->title }}</td>
                    <td style="padding:10px; border:1px solid #ddd;">{{ $image->sort_order }}</td>
                    <td style="padding:10px; border:1px solid #ddd;">
                        <a href="{{ route('admin.gallery.images.edit', $image->id) }}">Rediģēt</a>

                        <form method="POST" action="{{ route('admin.gallery.images.delete', $image->id) }}" style="display:inline-block; margin-left:10px;">
                            @csrf
                            <button type="submit" onclick="return confirm('Dzēst fotogrāfiju?')" style="color:red;">Dzēst</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:10px; border:1px solid #ddd;">Nav fotogrāfiju</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $images->links('pagination.default') }}
    </div>

</div>
@endsection