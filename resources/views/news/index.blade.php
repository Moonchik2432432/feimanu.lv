@extends('layouts.app')

@section('title', 'Aktualitātes')

@section('content')

<div class="container">

```
@if(session('success'))
    <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; border-radius:8px; margin:15px 0;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="padding:10px; background:#ffe9e9; border:1px solid #f0b7b7; border-radius:8px; margin:15px 0;">
        {{ session('error') }}
    </div>
@endif

<form method="GET" action="{{ url()->current() }}"
      style="margin:15px 0 25px 0; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">

    <div>
        <label>Nosaukums</label><br>
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nosaukums" style="padding:8px;">
    </div>

    <div>
        <label>No</label><br>
        <input type="text" name="from" id="from" value="{{ $from ?? '' }}" placeholder="Datums no" style="padding:8px;">
    </div>

    <div>
        <label>Līdz</label><br>
        <input type="text" name="to" id="to" value="{{ $to ?? '' }}" placeholder="Datums līdz" style="padding:8px;">
    </div>

    <button type="submit" style="padding:9px 14px;">Filtrēt</button>

    <a href="{{ url()->current() }}" style="padding:9px 14px; background:#eee; text-decoration:none; color:#000;">
        Notīrīt
    </a>
</form>

<script>
    flatpickr("#from", {
        dateFormat: "Y-m-d",
        locale: "lv"
    });

    flatpickr("#to", {
        dateFormat: "Y-m-d",
        locale: "lv"
    });
</script>

<div style="display:flex; gap:30px; align-items:flex-start;">

    <aside style="width:250px; flex:0 0 250px;">
        <h3 style="margin-bottom:12px;">Kategorijas</h3>

        <div style="border:1px solid #ddd; border-radius:12px; overflow:hidden; background:#fff;">

            <a href="{{ route('news.index') }}"
               style="display:block; padding:12px 14px; border-bottom:1px solid #eee; text-decoration:none; color:#222;">
                Visas aktualitātes
            </a>

            @foreach($categories as $cat)
                <a href="{{ route('news.category', $cat->kategorija_id) }}"
                   style="display:block; padding:12px 14px; border-bottom:1px solid #eee; text-decoration:none; color:#222;">
                    {{ $cat->nosaukums }}
                </a>
            @endforeach

        </div>
    </aside>

    <main style="flex:1; min-width:0;">

        @if(isset($events) && $events->count())
            <div style="margin-bottom:30px;">
                <h2 style="margin:0 0 15px 0;">Ieplānotie pasākumi</h2>

                <div style="display:flex; flex-direction:column; gap:16px;">
                    @foreach($events as $event)
                        <a href="{{ route('news.show', $event->ieraksts_id) }}"
                           style="display:block; text-decoration:none; color:inherit; margin-bottom:0;">

                            <div style="
                                border:1px solid #bfd8ff;
                                border-left:6px solid #2f6fed;
                                border-radius:16px;
                                padding:18px;
                                background:linear-gradient(135deg, #eef4ff 0%, #f8fbff 100%);
                                box-shadow:0 2px 10px rgba(47,111,237,0.08);
                            ">

                                @if($event->bilde)
                                    <img src="{{ asset($event->bilde) }}"
                                         alt="{{ $event->nosaukums }}"
                                         style="width:100%; max-height:240px; object-fit:cover; border-radius:10px; margin-bottom:14px; display:block;">
                                @endif

                                <div style="
                                    display:inline-block;
                                    padding:6px 10px;
                                    border-radius:999px;
                                    background:#2f6fed;
                                    color:#fff;
                                    font-size:13px;
                                    margin-bottom:10px;
                                ">
                                    Pasākums
                                </div>

                                <h3 style="margin:0 0 8px 0; color:#163b7a;">
                                    {{ $event->nosaukums }}
                                </h3>

                                <small style="color:#4d648d; display:block; margin-bottom:10px;">
                                    {{ \Carbon\Carbon::parse($event->pasakuma_datums)->format('d.m.Y H:i') }}
                                    @if($event->category)
                                        • {{ $event->category->nosaukums }}
                                    @endif
                                    • ❤️ {{ $event->likes_count ?? 0 }}
                                </small>

                                <p style="margin:0; color:#445; line-height:1.5;">
                                    {{ \Illuminate\Support\Str::limit($event->saturs, 180) }}
                                </p>

                                @auth
                                    <form method="POST"
                                          action="{{ route('news.like', $event->ieraksts_id) }}"
                                          onclick="event.stopPropagation();"
                                          style="margin-top:10px;">
                                        @csrf

                                        <button type="submit"
                                                style="
                                                    padding:6px 12px;
                                                    border:none;
                                                    border-radius:8px;
                                                    cursor:pointer;
                                                    background:{{ in_array($event->ieraksts_id, $userLikes ?? []) ? '#ffdede' : '#eeeeee' }};
                                                    color:#222;
                                                ">
                                            {{ in_array($event->ieraksts_id, $userLikes ?? []) ? '💔 Noņemt' : '❤️ Patīk' }}
                                        </button>
                                    </form>
                                @endauth

                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @foreach($news as $item)
            <a href="{{ route('news.show', $item->ieraksts_id) }}"
               style="display:block; text-decoration:none; color:inherit; margin-bottom:20px;">

                <div style="
                    border:1px solid #ddd;
                    border-radius:14px;
                    padding:18px;
                    background:#fff;
                    transition:0.2s;
                    box-shadow:0 2px 8px rgba(0,0,0,0.04);
                ">

                    @if($item->bilde)
                        <img src="{{ asset($item->bilde) }}"
                             alt="{{ $item->nosaukums }}"
                             style="width:100%; max-height:260px; object-fit:cover; border-radius:10px; margin-bottom:14px; display:block;">
                    @endif

                    <h2 style="margin:0 0 8px 0; color:#222;">
                        {{ $item->nosaukums }}
                    </h2>

                    <small style="color:gray; display:block; margin-bottom:10px;">
                        {{ \Carbon\Carbon::parse($item->publicets_datums)->format('d.m.Y H:i') }}
                        @if($item->category)
                            • {{ $item->category->nosaukums }}
                        @endif
                        • ❤️ {{ $item->likes_count ?? 0 }}
                    </small>

                    <p style="margin:0; color:#444; line-height:1.5;">
                        {{ \Illuminate\Support\Str::limit($item->saturs, 180) }}
                    </p>

                    @auth
                        <form method="POST"
                              action="{{ route('news.like', $item->ieraksts_id) }}"
                              onclick="event.stopPropagation();"
                              style="margin-top:10px;">
                            @csrf

                            <button type="submit"
                                    style="
                                        padding:6px 12px;
                                        border:none;
                                        border-radius:8px;
                                        cursor:pointer;
                                        background:{{ in_array($item->ieraksts_id, $userLikes ?? []) ? '#ffdede' : '#eeeeee' }};
                                        color:#222;
                                    ">
                                {{ in_array($item->ieraksts_id, $userLikes ?? []) ? '💔 Noņemt' : '❤️ Patīk' }}
                            </button>
                        </form>
                    @endauth

                </div>
            </a>
        @endforeach

        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $news->links('pagination.default') }}
        </div>

    </main>

    <aside style="width:280px; flex:0 0 280px;">
        <h3 style="margin-bottom:12px;">Jaunākās fotogrāfijas</h3>

        <div style="border:1px solid #ddd; border-radius:12px; background:#fff; padding:12px;">
            <div style="display:flex; flex-direction:column; gap:12px;">
                @forelse($latestPhotos as $photo)
                    <a href="{{ route('gallery.show', $photo->album_id) }}?photo={{ $photo->id }}"
                       style="display:block; text-decoration:none;">
                        <img src="{{ asset($photo->image_path) }}"
                             alt="{{ $photo->title ?? 'Foto' }}"
                             style="width:100%; height:150px; object-fit:cover; border-radius:10px; display:block; transition:0.2s;">
                    </a>
                @empty
                    <p style="margin:0;">Nav fotogrāfiju</p>
                @endforelse
            </div>
        </div>
    </aside>

</div>
```

</div>

@endsection
