@extends('layouts.app')

@section('title', 'Mans profils')

@section('content')
<div class="container" style="max-width:700px;">
<!-- VIEW PROFILE-->
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

<!-- CHANGE PROFILE NAME AND AVATAR-->
    <hr style="margin:25px 0;">
    <h3>Mainīt profilu</h3>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="max-width:400px;">
        @csrf

        <div style="margin-bottom:10px;">
            <label>Vārds</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width:100%;" required>
            @error('name')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:15px;">
            <label>Jauns avatars</label><br>
            <input type="file" name="avatar" accept="image/*">
            @error('avatar')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Saglabāt</button>
    </form>

<!-- CHANGE PAROLE-->
    <hr style="margin:25px 0;">
    <h3>Mainīt paroli</h3>

    <form method="POST" action="{{ route('profile.password') }}" style="max-width:400px;">
        @csrf

        <div style="margin-bottom:10px;">
            <label>Pašreizējā parole</label>
            <input type="password" name="current_password" style="width:100%;" required>
            @error('current_password')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:10px;">
            <label>Jaunā parole</label>
            <input type="password" name="new_password" style="width:100%;" required>
            @error('new_password')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:15px;">
            <label>Atkārtot jauno paroli</label>
            <input type="password" name="new_password_confirmation" style="width:100%;" required>
        </div>

        <button type="submit">Mainīt paroli</button>
    </form>

<!-- CHANGE E-PASTS-->
    <hr style="margin:25px 0;">
    <h3>Mainīt e-pastu</h3>

    <form method="POST" action="{{ route('profile.email') }}" style="max-width:400px;">
        @csrf

        <div style="margin-bottom:10px;">
            <label>Jaunais e-pasts</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width:100%;" required>
            @error('email')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:15px;">
            <label>Apstipriniet ar paroli</label>
            <input type="password" name="password" style="width:100%;" required>
            @error('password')
                <div style="color:red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Mainīt e-pastu</button>
    </form>

</div>
@endsection


