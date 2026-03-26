@extends('layouts.app')

@section('title', 'Kontakti')

@section('content')
<div class="container" style="max-width:1050px; margin:40px auto;">

    <h1>Kontakti</h1>

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

    <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px; margin-bottom:25px;">
        <form method="POST" action="{{ route('contacts.store') }}">
            @csrf

            <div style="margin-bottom:15px;">
                <label>Vārds</label><br>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" style="width:100%; padding:10px;">
            </div>

            <div style="margin-bottom:15px;">
                <label>E-pasts</label><br>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" style="width:100%; padding:10px;">
            </div>

            <div style="margin-bottom:15px;">
                <label>Tēma</label><br>
                <input type="text" name="subject" value="{{ old('subject') }}" style="width:100%; padding:10px;">
            </div>

            <div style="margin-bottom:15px;">
                <label>Ziņojums</label><br>
                <textarea name="message" rows="6" style="width:100%; padding:10px; resize:vertical;">{{ old('message') }}</textarea>
            </div>

            <button type="submit" style="padding:10px 18px;">Nosūtīt</button>
        </form>
    </div>

    @auth
        <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px;">
            <h2>Mani ziņojumi</h2>

            @forelse($messages as $item)
                <div style="border:1px solid #ddd; border-radius:10px; padding:15px; margin-bottom:15px;">
                    <div><strong>Tēma:</strong> {{ $item->subject }}</div>
                    <div style="color:#666; margin:8px 0;">{{ $item->created_at->format('d.m.Y H:i') }}</div>

                    <div style="margin-bottom:10px;">
                        <strong>Jautājums:</strong><br>
                        {!! nl2br(e($item->message)) !!}
                    </div>

                    <div>
                        <strong>Atbilde:</strong><br>
                        @if($item->reply)
                            {!! nl2br(e($item->reply)) !!}
                        @else
                            <span style="color:#999;">Atbilde vēl nav sniegta.</span>
                        @endif
                    </div>
                </div>
            @empty
                <p>Ziņojumu vēl nav.</p>
            @endforelse
        </div>
    @endauth

</div>
@endsection
