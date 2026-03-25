@extends('layouts.app')

@section('title', 'Administrācija - Galerijas fotogrāfijas')

@section('content')

<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Galerijas fotogrāfijas</h1>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; margin:15px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <div style="display:flex; justify-content:space-between; gap:15px; flex-wrap:wrap; align-items:end; margin:15px 0;">
        <a href="{{ route('admin.gallery.images.create') }}"
        style="padding:9px 14px; background:#28a745; color:#fff; text-decoration:none; border-radius:6px;">
            + Pievienot
        </a>
    </div>

    <table style="width:100%; border-collapse: collapse; margin-top:20px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ddd;">Foto</th>
                <th style="padding:10px; border:1px solid #ddd;">Albums</th>
                <th style="padding:10px; border:1px solid #ddd;">Nosaukums</th>
                <th style="padding:10px; border:1px solid #ddd;">Secība</th>
                <th style="padding:10px; border:1px solid #ddd;">Izveidots</th>
                <th style="padding:10px; border:1px solid #ddd;">Darbības</th>
            </tr>
        </thead>

        <tbody>
            @forelse($images as $image)
                <tr>

                    <td style="padding:10px; border:1px solid #ddd;">
                        <img src="{{ asset($image->image_path) }}"
                             alt="Foto"
                             style="width:80px; height:60px; object-fit:cover; border-radius:8px;">
                    </td>

                    <td style="padding:10px; border:1px solid #ddd;">
                        {{ $image->album->title ?? '-' }}
                    </td>

                    <td style="padding:10px; border:1px solid #ddd;">
                        {{ $image->title ?: '-' }}
                    </td>

                    <td style="padding:10px; border:1px solid #ddd;">
                        {{ $image->sort_order }}
                    </td>

                    <td style="padding:10px; border:1px solid #ddd;">
                        {{ $image->created_at ? $image->created_at->format('d.m.Y H:i') : '-' }}
                    </td>

                    <td style="padding:10px; border:1px solid #ddd;">
                        <a href="{{ route('admin.gallery.images.edit', $image->id) }}">Rediģēt</a>

                        <form method="POST"
                              action="{{ route('admin.gallery.images.delete', $image->id) }}"
                              style="display:inline-block; margin-left:10px;">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Vai tiešām dzēst fotogrāfiju?')"
                                    style="color:red; cursor:pointer;">
                                Dzēst
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding:10px; border:1px solid #ddd; text-align:center;">
                        Nav fotogrāfiju
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $images->links('pagination.default') }}
    </div>

</div>

@endsection