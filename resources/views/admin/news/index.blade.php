@extends('layouts.app')

@section('title', 'Administrācija - Ieraksti')

@section('content')
<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Ierakstu saraksts</h1>

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

    <div style="margin:15px 0;">
        <a href="{{ route('admin.news.create') }}"
           style="background:#28a745; color:white; padding:8px 14px; border-radius:6px; text-decoration:none;">
            ➕ Pievienot ierakstu
        </a>
    </div>

    <form method="GET" action="{{ route('admin.news') }}" style="margin:15px 0; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
        <div>
            <label>Search</label><br>
            <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nosaukums" style="padding:8px;">
        </div>

        <div>
            <label>From</label><br>
            <input type="date" name="from" value="{{ $from ?? '' }}" style="padding:8px;">
        </div>

        <div>
            <label>To</label><br>
            <input type="date" name="to" value="{{ $to ?? '' }}" style="padding:8px;">
        </div>

        <button type="submit" style="padding:9px 14px;">Filter</button>

        <a href="{{ route('admin.news') }}"
           style="padding:9px 14px; background:#eee; text-decoration:none; color:#000;">
            Reset
        </a>
    </form>

    <table style="width:100%; border-collapse: collapse; margin-top:20px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Nosaukums</th>
                <th style="padding:10px; border:1px solid #ddd;">Kategorija</th>
                <th style="padding:10px; border:1px solid #ddd;">Status</th>
                <th style="padding:10px; border:1px solid #ddd;">Publicēts</th>
                <th style="padding:10px; border:1px solid #ddd;">Darbības</th>
            </tr>
        </thead>

        <tbody>
        @forelse($news as $i)
            <tr>
                <td style="padding:10px; border:1px solid #ddd;">{{ $i->ieraksts_id }}</td>
                <td style="padding:10px; border:1px solid #ddd;">{{ $i->nosaukums }}</td>
                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $i->category?->nosaukums ?? '-' }}
                </td>
                <td style="padding:10px; border:1px solid #ddd;">{{ $i->status }}</td>
                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $i->publicets_datums }}
                </td>

                <td style="padding:10px; border:1px solid #ddd; white-space:nowrap;">
                    <a href="{{ route('news.show', $i->ieraksts_id) }}">View</a>

                    <a href="{{ route('admin.news.edit', $i->ieraksts_id) }}" style="margin-left:10px;">Edit</a>

                    <form action="{{ route('admin.news.destroy', $i->ieraksts_id) }}"
                          method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Dzēst šo ierakstu?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="margin-left:10px;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding:15px; border:1px solid #ddd; text-align:center;">
                    Ieraksti nav atrasti
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $news->links() }}
    </div>

</div>
@endsection