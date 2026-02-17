@extends('layouts.app')

@section('title', 'Aktualitātes')

@section('content')
<div class="container" style="display:flex; gap:30px;">

    {{-- Sidebar --}}
    <aside style="width:250px;">
        <h3>Kategorijas</h3>

        <a href="{{ route('aktualitates.index') }}">Visas aktualitātes</a>

        @foreach($categories as $cat)
            <div>
                <a href="{{ route('aktualitates.category', $cat->kategorija_id) }}">
                    {{ $cat->nosaukums }}
                </a>
            </div>
        @endforeach
    </aside>

    <form method="GET" action="{{ url()->current() }}">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Meklēt ziņās...">
        <button type="submit">Meklēt</button>

        @if(!empty($q))
            <a href="{{ url()->current() }}">Notīrīt</a>
        @endif
    </form>


    {{-- News --}}
    <main style="flex:1;">
        @foreach($news as $item)
            <div style="border-bottom:1px solid #ddd; padding:15px 0;">

                <h2>
                    <a href="{{ route('aktualitates.show', $item->ieraksts_id) }}">
                        {{ $item->nosaukums }}
                    </a>
                </h2>

                <small style="color:gray;">
                    {{ \Carbon\Carbon::parse($item->publicets_datums)->format('d.m.Y H:i') }}
                    @if($item->kategorija)
                        • {{ $item->kategorija->nosaukums }}
                    @endif
                </small>

                <p>
                    {{ \Illuminate\Support\Str::limit($item->saturs, 75) }}
                </p>

                @if($item->bilde)
                    <img
                        src="{{ asset($item->bilde) }}"
                        alt="{{ $item->nosaukums }}"
                        style="max-width:260px; border-radius:10px; margin:10px 0; display:block;"
                    >
                @endif

            </div>
        @endforeach

        <div class="pagination-wrapper">
            {{ $news->links('pagination.default') }}
        </div>

    </main>

</div>
@endsection
