@extends('layouts.app')

@section('title', 'Aktualitātes')

@section('content')

<div class="container">

    <form method="GET" action="{{ url()->current() }}"
          style="margin:15px 0 25px 0; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">

        <div>
            <label>Nosaukums</label><br>
            <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nosaukums" style="padding:8px;">
        </div>

        <div>
            <label>No</label><br>
            <input type="date" name="from" value="{{ $from ?? '' }}" style="padding:8px;">
        </div>

        <div>
            <label>Līdz</label><br>
            <input type="date" name="to" value="{{ $to ?? '' }}" style="padding:8px;">
        </div>

        <button type="submit" style="padding:9px 14px;">Filtrēt</button>

        <a href="{{ url()->current() }}" style="padding:9px 14px; text-decoration:none;">
            Notīrīt
        </a>
    </form>

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
                    </small>

                    <p style="margin:0; color:#444; line-height:1.5;">
                        {{ \Illuminate\Support\Str::limit($item->saturs, 180) }}
                    </p>

                </div>
            </a>
        @endforeach

            <div class="pagination-wrapper" style="margin-top:20px;">
                {{ $news->links('pagination.default') }}
            </div>

        </main>

    </div>
</div>

@endsection