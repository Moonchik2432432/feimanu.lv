@extends('layouts.app')

@section('title', 'Aktualitātes')

@section('content')

<div class="container">

    {{-- Поиск и фильтрация --}}
    <form method="GET" action="{{ url()->current() }}" style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">

        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Meklēt...">

        <label>No:</label>
        <input type="date" name="from" value="{{ $from ?? '' }}">

        <label>Līdz:</label>
        <input type="date" name="to" value="{{ $to ?? '' }}">

        <button type="submit">Filtrēt</button>

        <a href="{{ url()->current() }}">Notīrīt</a>
    </form>

    <div style="display:flex; gap:30px; align-items:flex-start;">

        {{-- Категории --}}
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

        {{-- Новости --}}
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
                        @if($item->kategorija)
                            • {{ $item->kategorija->nosaukums }}
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