@extends('layouts.app')

@section('title', 'Lietotājs')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <div style="display:flex; gap:20px; align-items:center; margin-bottom:20px;">
        @php
            $avatar = $user->avatar
                ? asset('img/usersAvatars/' . $user->avatar)
                : asset('img/usersAvatars/default_avatar.jpg');
        @endphp

        <img src="{{ $avatar }}" alt="Avatar"
             style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:1px solid #ccc;">

        <div>
            <h1 style="margin:0;">{{ $user->name }}</h1>
            <div style="opacity:.8;">{{ $user->email }}</div>
            <div style="margin-top:6px;">
                <b>Loma:</b> {{ $user->role }}
            </div>
        </div>
    </div>

    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="border:1px solid #ddd; padding:10px; width:220px;"><b>ID</b></td>
            <td style="border:1px solid #ddd; padding:10px;">{{ $user->id }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #ddd; padding:10px;"><b>Vārds</b></td>
            <td style="border:1px solid #ddd; padding:10px;">{{ $user->name }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #ddd; padding:10px;"><b>E-pasts</b></td>
            <td style="border:1px solid #ddd; padding:10px;">{{ $user->email }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #ddd; padding:10px;"><b>Loma</b></td>
            <td style="border:1px solid #ddd; padding:10px;">{{ $user->role }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #ddd; padding:10px;"><b>Reģistrēts</b></td>
            <td style="border:1px solid #ddd; padding:10px;">{{ $user->created_at }}</td>
        </tr>
        <tr>
            <td style="border:1px solid #ddd; padding:10px;"><b>Atjaunināts</b></td>
            <td style="border:1px solid #ddd; padding:10px;">{{ $user->updated_at }}</td>
        </tr>
    </table>

    <div style="margin-top:20px; display:flex; gap:12px;">
        <a href="{{ route('admin.users') }}">Back</a>
        <a href="{{ route('admin.users.edit', $user->id) }}">Edit</a>

        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
              onsubmit="return confirm('Dzēst šo lietotāju?');">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>

<hr style="margin:30px 0;">

<h2>Komentāri ({{ $comments->count() }})</h2>

@if($comments->isEmpty())
    <p>Šim lietotājam nav komentāru.</p>
@else
    <div style="display:flex; flex-direction:column; gap:12px; margin-top:15px;">
        @foreach($comments as $c)
            <div style="border:1px solid #ddd; border-radius:8px; padding:12px;">

                <div style="font-size:14px; opacity:.8; margin-bottom:6px;">
                    {{ \Carbon\Carbon::parse($c->izveidots_datums)->format('d.m.Y H:i') }}
                </div>

                <div style="margin-bottom:10px;">
                    {{ $c->text }}
                </div>

                <div style="font-size:14px;">
                    <b>Pie ieraksta:</b>
                    <a href="{{ route('aktualitates.show', $c->ieraksts_id) }}">
                        {{ $c->ieraksts_title }}
                    </a>
                </div>

            </div>
        @endforeach
    </div>
@endif

</div>
@endsection