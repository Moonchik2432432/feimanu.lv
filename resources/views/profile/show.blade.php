@extends('layouts.app')

@section('title', 'Mans profils')

@section('content')
<div class="container" style="max-width:800px; margin:40px auto;">

    <h1 style="margin-bottom:20px;">Mans profils</h1>

    @if(session('success'))
        <div style="padding:12px; background:#e9ffe9; border:1px solid #b7f0b7; border-radius:10px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:12px; background:#ffecec; border:1px solid #ffbcbc; border-radius:10px; margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:12px; background:#ffecec; border:1px solid #ffbcbc; border-radius:10px; margin-bottom:20px;">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- PROFILE INFO --}}
    <div style="
        background:#fff;
        border:1px solid #ddd;
        border-radius:16px;
        padding:20px;
        box-shadow:0 2px 10px rgba(0,0,0,0.04);
        margin-bottom:25px;
        display:flex;
        gap:20px;
        align-items:center;
    ">

        <div>
            <img src="{{ asset($user->avatar ? 'img/usersAvatars/' . $user->avatar : 'img/usersAvatars/default_avatar.jpg') }}"
                 style="width:90px;height:90px;border-radius:50%;object-fit:cover;">
        </div>

        <div>
            <div><b>Vārds:</b> {{ $user->name }}</div>
            <div><b>E-pasts:</b> {{ $user->email }}</div>

            <div><b>Reģistrēts:</b> {{ $user->created_at->format('d.m.Y') }}</div>

            @if(!is_null($commentsCount))
                <div><b>Komentāri:</b> {{ $commentsCount }}</div>
            @endif
        </div>

    </div>

    {{-- EDIT PROFILE --}}
    <div style="
        background:#fff;
        border:1px solid #ddd;
        border-radius:16px;
        padding:20px;
        margin-bottom:25px;
    ">
        <h3>Mainīt profilu</h3>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom:12px;">
                <label>Vārds</label><br>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       style="width:100%; padding:10px; border-radius:10px; border:1px solid #ccc;">
            </div>

            <div style="margin-bottom:12px;">
                <label>Jauns avatars</label><br>

                <label style="
                    display:inline-block;
                    padding:8px 14px;
                    border-radius:10px;
                    border:1px solid #ccc;
                    cursor:pointer;
                    background:#f5f5f5;
                ">
                    Izvēlēties failu
                    <input type="file" name="avatar" id="avatar-input" style="display:none;">
                </label>

                <div id="avatar-name" style="margin-top:8px; color:#666;">
                    Fails nav izvēlēts
                </div>
            </div>

            <button style="padding:8px 14px; border-radius:10px; cursor:pointer;">
                Saglabāt
            </button>
        </form>
    </div>

    {{-- CHANGE PASSWORD --}}
    <div style="
        background:#fff;
        border:1px solid #ddd;
        border-radius:16px;
        padding:20px;
        margin-bottom:25px;
    ">
        <h3>Mainīt paroli</h3>

        <form method="POST" action="{{ route('profile.password') }}">
            @csrf

            <div style="margin-bottom:10px;">
                <label>Pašreizējā parole</label><br>
                <input type="password" name="current_password"
                       style="width:100%; padding:10px; border-radius:10px; border:1px solid #ccc;">
            </div>

            <div style="margin-bottom:10px;">
                <label>Jaunā parole</label><br>
                <input type="password" name="new_password"
                       style="width:100%; padding:10px; border-radius:10px; border:1px solid #ccc;">
            </div>

            <div style="margin-bottom:12px;">
                <label>Atkārtot paroli</label><br>
                <input type="password" name="new_password_confirmation"
                       style="width:100%; padding:10px; border-radius:10px; border:1px solid #ccc;">
            </div>

            <button style="padding:8px 14px; border-radius:10px; cursor:pointer;">
                Mainīt paroli
            </button>
        </form>
    </div>

    {{-- CHANGE EMAIL --}}
    <div style="
        background:#fff;
        border:1px solid #ddd;
        border-radius:16px;
        padding:20px;
    ">
        <h3>Mainīt e-pastu</h3>

        <form method="POST" action="{{ route('profile.email') }}" novalidate>
            @csrf

            <div style="margin-bottom:10px;">
                <label>Jaunais e-pasts</label><br>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       style="width:100%; padding:10px; border-radius:10px; border:1px solid #ccc;">
            </div>

            <div style="margin-bottom:12px;">
                <label>Parole apstiprināšanai</label><br>
                <input type="password" name="password"
                       style="width:100%; padding:10px; border-radius:10px; border:1px solid #ccc;">
            </div>

            <button style="padding:8px 14px; border-radius:10px; cursor:pointer;">
                Mainīt e-pastu
            </button>
        </form>
    </div>

</div>
@endsection
