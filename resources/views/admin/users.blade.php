@extends('layouts.app')

@section('title', 'Administrācija - Lietotāji')

@section('content')

<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Lietotāju saraksts</h1>

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