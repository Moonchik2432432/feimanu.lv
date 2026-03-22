@extends('layouts.app')

@section('title', 'Aktualitātes')

@section('content')

<div class="container">

    <form method="GET" action="{{ url()->current() }}"
          style="margin:15px 0; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">

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
            <h3>Kategorijas</h3>

            <div>
                <a href="{{ route('news.index') }}">Visas aktualitātes</a>
            </div>

            @foreach($categories as $cat)
                <div>
                    <a href="{{ route('news.category', $cat->kategorija_id) }}">
                        {{ $cat->nosaukums }}
                    </a>
                </div>
            @endforeach
        </aside>

        <main style="flex:1; min-width:0;">

            @foreach($news as $item)
                <div style="border-bottom:1px solid #ddd; padding:20px 0;">

                    <h2>
                        <a href="{{ route('news.show', $item->ieraksts_id) }}">
                            {{ $item->nosaukums }}
                        </a>
                    </h2>

                    <small style="color:gray;">
                        {{ \Carbon\Carbon::parse($item->publicets_datums)->format('d.m.Y H:i') }}
                        @if($item->category)
                            • {{ $item->category->nosaukums }}
                        @endif
                    </small>

                    <p style="margin-top:10px;">
                        {{ \Illuminate\Support\Str::limit($item->saturs, 100) }}
                    </p>

                    @if($item->bilde)
                        <img src="{{ asset($item->bilde) }}"
                             alt="{{ $item->nosaukums }}"
                             style="max-width:260px; border-radius:10px; margin:10px 0; display:block;">
                    @endif

                </div>
            @endforeach

            <div class="pagination-wrapper" style="margin-top:20px;">
                {{ $news->links('pagination.default') }}
            </div>

        </main>

    </div>
</div>

@endsection