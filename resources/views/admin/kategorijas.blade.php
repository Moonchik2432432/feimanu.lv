@extends('layouts.app')

@section('title', 'Administrācija - Kategorijas')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <h1>Kategorijas</h1>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; margin:15px 0;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:flex; justify-content:space-between; gap:15px; flex-wrap:wrap; align-items:end; margin:15px 0;">
        <form method="GET" action="{{ route('admin.kategorijas') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
            <div>
                <label>Search</label><br>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nosaukums" style="padding:8px;">
            </div>

            <button type="submit" style="padding:9px 14px;">Meklēt</button>

            <a href="{{ route('admin.kategorijas') }}" style="padding:9px 14px; background:#eee; text-decoration:none; color:#000;">
                Reset
            </a>
        </form>

        <a href="{{ route('admin.kategorijas_create') }}" style="padding:9px 14px; background:#28a745; color:#fff; text-decoration:none; border-radius:6px;">
            + Pievienot
        </a>
    </div>

    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Nosaukums</th>
                <th style="padding:10px; border:1px solid #ddd;">Darbības</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $cat)
            <tr>
                <td style="padding:10px; border:1px solid #ddd;">{{ $cat->kategorija_id }}</td>
                <td style="padding:10px; border:1px solid #ddd;">{{ $cat->nosaukums }}</td>
                <td style="padding:10px; border:1px solid #ddd; white-space:nowrap;">
                    <a href="{{ route('admin.kategorijas.edit', $cat->kategorija_id) }}">Edit</a>

                    <form action="{{ route('admin.kategorijas.destroy', $cat->kategorija_id) }}"
                          method="POST" style="display:inline;"
                          onsubmit="return confirm('Dzēst šo kategoriju?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="margin-left:10px;">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $categories->links() }}
    </div>

</div>
@endsection