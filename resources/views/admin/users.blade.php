@extends('layouts.app')

@section('title', 'Administrācija - Lietotāji')

@section('content')

<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Lietotāju saraksts</h1>

    <form method="GET" action="{{ route('admin.users') }}" style="margin:15px 0; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
        <div>
            <label>Search</label><br>
            <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Name / Email / ID" style="padding:8px; width:220px;">
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

        <a href="{{ route('admin.users') }}" style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
            Reset
        </a>
    </form>

    <table style="width:100%; border-collapse: collapse; margin-top:20px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Vārds</th>
                <th style="padding:10px; border:1px solid #ddd;">E-pasts</th>
                <th style="padding:10px; border:1px solid #ddd;">Loma</th>
                <th style="padding:10px; border:1px solid #ddd;">Reģistrēts</th>
            </tr>
        </thead>

        <tbody>
        @foreach($users as $user)
            <tr>
                <td style="padding:10px; border:1px solid #ddd;">{{ $user->id }}</td>
                <td style="padding:10px; border:1px solid #ddd;">{{ $user->name }}</td>
                <td style="padding:10px; border:1px solid #ddd;">{{ $user->email }}</td>
                <td style="padding:10px; border:1px solid #ddd;">{{ $user->role }}</td>
                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $user->created_at->format('d.m.Y H:i') }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $users->links() }}
    </div>

</div>

@endsection