@extends('layouts.app')

@section('title', 'Administrācija - Galerijas fotogrāfijas')

@section('content')

<div class="container" style="max-width:1200px; margin:40px auto;">

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
                <label>Albums</label><br>
                <select name="album_id" style="padding:8px; width:180px;">
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

    <div style="
        display:grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap:20px;
        margin-top:25px;
    ">
        @forelse($images as $image)
            <div style="
                background:#fff;
                border:1px solid #ddd;
                border-radius:14px;
                overflow:hidden;
                box-shadow:0 2px 8px rgba(0,0,0,0.06);
            ">
                <div style="position:relative;">
                <a href="{{ asset($image->image_path) }}" data-lightbox="admin-gallery">
                    <img src="{{ asset($image->image_path) }}"
                         alt="Foto"
                         style="width:100%; height:180px; object-fit:cover; display:block; cursor:pointer;">
                </a>

                    <div style="
                        position:absolute;
                        bottom:10px;
                        left:10px;
                        background:rgba(0,0,0,0.65);
                        color:#fff;
                        padding:5px 10px;
                        border-radius:8px;
                        font-size:13px;
                    ">
                        {{ $image->album->title ?? '-' }}
                    </div>
                </div>

                <div style="padding:12px;">
                    <div style="font-size:13px; color:#666; margin-bottom:12px;">
                        {{ $image->created_at ? $image->created_at->format('d.m.Y H:i') : '-' }}
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; gap:10px;">
                        <form method="POST"
                              action="{{ route('admin.gallery.images.delete', $image->id) }}"
                              style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Vai tiešām dzēst fotogrāfiju?')"
                                    style="border:none; background:none; color:red; cursor:pointer; padding:0;">
                                Dzēst
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div style="
                grid-column:1 / -1;
                text-align:center;
                padding:30px;
                border:1px solid #ddd;
                border-radius:12px;
                background:#fafafa;
                color:#666;
            ">
                Nav fotogrāfiju
            </div>
        @endforelse
    </div>

    <div style="margin-top:25px;">
        {{ $images->links('pagination.default') }}
    </div>

</div>

@endsection
