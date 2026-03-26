@extends('layouts.app')

@section('title', 'Administrācija - Jautājums')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <h1>Jautājums #{{ $message->id }}</h1>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; margin:15px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px; margin-bottom:20px;">
        <p><strong>Vārds un uzvārds:</strong> {{ $message->name }}</p>

        @if($message->user)
            <p><strong>Lietotājvārds vietnē:</strong> {{ $message->user->name }}</p>
        @else
            <p><strong>Lietotājvārds vietnē:</strong> Nav piesaistīta konta</p>
        @endif

        <p><strong>E-pasts:</strong> {{ $message->email }}</p>
        <p><strong>Tēma:</strong> {{ $message->subject }}</p>
        <p><strong>Ziņojums:</strong><br>{!! nl2br(e($message->message)) !!}</p>
    </div>

    <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px; margin-bottom:20px;">
        <form method="POST" action="{{ route('admin.contacts.reply', $message->id) }}">
            @csrf

            <label>Atbilde</label><br>
            <textarea name="reply" rows="8" style="width:100%; padding:10px; resize:vertical;">{{ old('reply', $message->reply) }}</textarea>

            <div style="margin-top:15px;">
                <button type="submit" style="padding:10px 18px;">Saglabāt atbildi</button>
            </div>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.contacts.close', $message->id) }}">
        @csrf
        @method('DELETE')

        <button type="submit"
                onclick="return confirm('Vai tiešām aizvērt jautājumu?')"
                style="padding:10px 18px; background:#999; color:#fff; border:none; border-radius:8px; cursor:pointer;">
            Aizvērt jautājumu
        </button>
    </form>

</div>
@endsection
