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

        <form method="GET" action="{{ route('admin.gallery.images') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end;">

            <div>
                <label>Nosaukums</label><br>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nosaukums" style="padding:8px; width:200px;">
            </div>

            <div>
                <label>Albums</label><br>
                <select name="album_id" style="padding:8px; width:150px;">
                    <option value="">Visi albumi</option>
                    @foreach($albums as $album)
                        <option value="{{ $album->id }}" {{ ($albumId ?? '') == $album->id ? 'selected' : '' }}>
                            {{ $album->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>No</label><br>
                <input type="text" id="from" name="from" value="{{ $from ?? '' }}" placeholder="Datums no" style="padding:8px;">
            </div>

            <div>
                <label>Līdz</label><br>
                <input type="text" id="to" name="to" value="{{ $to ?? '' }}" placeholder="Datums līdz" style="padding:8px;">
            </div>

            <button type="submit" style="padding:9px 14px;">Filtrēt</button>

            <a href="{{ route('admin.gallery.images') }}"
               style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
                Notīrīt
            </a>
        </form>

        <a href="{{ route('admin.gallery.images.create') }}"
           style="padding:9px 14px; background:#28a745; color:#fff; text-decoration:none; border-radius:6px;">
            + Pievienot
        </a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            flatpickr("#from", {
                dateFormat: "Y-m-d",
                locale: "lv"
            });

            flatpickr("#to", {
                dateFormat: "Y-m-d",
                locale: "lv"
            });
        });
    </script>

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
                    <td colspan="6" style="padding:10px; border:1px solid #ddd; text-align:center;">
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