@extends('layouts.app')

@section('title', 'Mans profils')

@section('content')
<div class="container" style="max-width:700px;">

    <h1>Mans profils</h1>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; border-radius:8px; margin:15px 0;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:flex; gap:20px; align-items:center; margin:20px 0;">
        <div>
            @if($user->avatar)
                <img src="{{ asset('img/usersAvatars/' . $user->avatar) }}"
                     style="width:90px;height:90px;border-radius:50%;object-fit:cover;">
            @else
                <img src="{{ asset('img/usersAvatars/default_avatar.jpg') }}"
                     style="width:90px;height:90px;border-radius:50%;object-fit:cover;">
            @endif
        </div>

        <div>
            <div><b>Vārds:</b> {{ $user->name }}</div>
            <div><b>E-pasts:</b> {{ $user->email }}</div>

            @if(!empty($user->created_at))
                <div><b>Reģistrēts:</b> {{ \Carbon\Carbon::parse($user->created_at)->format('d.m.Y') }}</div>
            @endif

            @if(!is_null($commentsCount))
                <div><b>Komentāri:</b> {{ $commentsCount }}</div>
            @endif
        </div>
    </div>

    <hr style="margin:25px 0;">

    <h3>Mainīt vārdu</h3>
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:10px;">
            <label>Vārds</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width:100%;" required>
            @error('name')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Saglabāt</button>
    </form>

    <div style="margin-bottom:15px;">
        <label>Jauns avatars</label>
        <input type="file" name="avatar" accept="image/*">
        @error('avatar')
            <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>
</div>
@endsection
