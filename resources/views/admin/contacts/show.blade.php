@extends('layouts.app')

@section('title', 'Administrācija - Jautājums')

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <h1>Jautājums #{{ $message->id }}</h1>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; margin:15px 0; border-radius:10px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0; border-radius:10px;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0; border-radius:10px;">
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
        <p>
            <strong>Statuss:</strong>
                <span style="
                    display:inline-block;
                    padding:4px 10px;
                    border-radius:999px;
                    font-size:13px;
                    background:
                        @if($message->status === 'new')
                            #e8f0ff
                        @elseif($message->status === 'read')
                            #fff4db
                        @elseif($message->status === 'overdue')
                            #ffe5e5
                        @elseif($message->status === 'answered')
                            #e9ffe9
                        @else
                            #f0f0f0
                        @endif
                    ;
                    color:#333;
                ">
                    {{ $statuses[$item->status] ?? $item->status }}
                </span>
        </p>
        <p><strong>Datums:</strong> {{ $message->created_at->format('d.m.Y H:i') }}</p>
        <p><strong>Ziņojums:</strong><br>{!! nl2br(e($message->message)) !!}</p>
    </div>

    <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px; margin-bottom:20px;">
        <form method="POST" action="{{ route('admin.contacts.reply', $message->id) }}">
            @csrf

            <label>Atbilde</label><br>
            <textarea name="reply" rows="8" style="width:100%; padding:10px; resize:vertical; box-sizing:border-box;">{{ old('reply', $message->reply) }}</textarea>

            <div style="margin-top:15px;">
                <button type="submit" style="padding:10px 18px;">Saglabāt atbildi</button>
            </div>
        </form>

        @if($message->reply)
            <div style="margin-top:20px; padding:15px; background:#f7f7f7; border-radius:10px;">
                <strong>Saglabātā atbilde:</strong><br>
                <div style="margin-top:8px;">{!! nl2br(e($message->reply)) !!}</div>

                @if($message->replied_at)
                    <div style="margin-top:10px; color:#666;">
                        Atbildēts: {{ $message->replied_at->format('d.m.Y H:i') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">
        @if(!$message->admin_archived_at)
            <form method="POST" action="{{ route('admin.contacts.archive', $message->id) }}">
                @csrf
                <button type="submit" style="padding:10px 18px;">Arhivēt</button>
            </form>
        @else
            <form method="POST" action="{{ route('admin.contacts.unarchive', $message->id) }}">
                @csrf
                <button type="submit" style="padding:10px 18px;">Atarhivēt</button>
            </form>
        @endif

        <form method="POST"
              action="{{ route('admin.contacts.delete', $message->id) }}"
              onsubmit="return confirm('Vai tiešām dzēst šo ziņojumu?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    style="padding:10px 18px; background:#ffd9d9; border:1px solid #e3a8a8; border-radius:8px; cursor:pointer;">
                Dzēst
            </button>
        </form>

        <a href="{{ route('admin.contacts') }}"
           style="display:inline-block; padding:10px 18px; background:#eee; color:#000; text-decoration:none; border-radius:8px;">
            ← Atpakaļ
        </a>
    </div>

</div>
@endsection
