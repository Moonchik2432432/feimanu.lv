@extends('layouts.app')

@section('title', $post->nosaukums)

@section('content')
<div class="container" style="max-width:900px; margin:40px auto;">

    <a href="{{ url()->previous() }}"
        style="
            display:inline-block;
            margin-bottom:20px;
            padding:8px 14px;
            border:1px solid #ddd;
            border-radius:10px;
            text-decoration:none;
            color:#333;
            background:#fff;
            transition:0.2s;
        "
        onmouseover="this.style.background='#f5f5f5'"
        onmouseout="this.style.background='#fff'">
            ← Atpakaļ
    </a>

    <article style="background:#fff; border:1px solid #ddd; border-radius:16px; padding:25px; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <h1 style="margin-top:0; margin-bottom:10px;">{{ $post->nosaukums }}</h1>

        <div style="color:#666; margin-bottom:20px;">
            {{ \Carbon\Carbon::parse($post->publicets_datums)->format('d.m.Y H:i') }}
            @if($post->category)
                • {{ $post->category->nosaukums }}
            @endif
        </div>

        @if($post->bilde)
            <img
                src="{{ asset($post->bilde) }}"
                alt="{{ $post->nosaukums }}"
                style="width:100%; max-height:420px; object-fit:cover; border-radius:14px; margin-bottom:20px;"
            >
        @endif

        <div style="line-height:1.7; color:#333; font-size:16px;">
            {!! nl2br(e($post->saturs)) !!}
        </div>
    </article>

    <section style="margin-top:30px;">
        <h2 style="margin-bottom:15px;">Komentāri</h2>

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

        @forelse($post->comments as $c)
            <div style="display:flex; gap:15px; padding:16px; border:1px solid #e5e5e5; border-radius:14px; background:#fff; margin-bottom:14px; box-shadow:0 1px 6px rgba(0,0,0,0.03);">

                <div>
                    @if($c->user && $c->user->avatar)
                        <img
                            src="{{ asset('img/usersAvatars/' . $c->user->avatar) }}"
                            style="width:52px; height:52px; border-radius:50%; object-fit:cover;"
                            alt="Avatar"
                        >
                    @else
                        <img
                            src="{{ asset('img/usersAvatars/default_avatar.jpg') }}"
                            style="width:52px; height:52px; border-radius:50%; object-fit:cover;"
                            alt="Avatar"
                        >
                    @endif
                </div>

                <div style="flex:1;">
                    <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
                        <div>
                            <b>{{ $c->user->name ?? 'Lietotājs' }}</b><br>
                            <small style="color:#777;">
                                {{ \Carbon\Carbon::parse($c->izveidots_datums)->format('d.m.Y H:i') }}
                            </small>
                        </div>

                        @auth
                            @if($c->user_id === auth()->id())
                                <form method="POST" action="{{ route('comments.destroy', $c->komentars_id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Vai tiešām dzēst komentāru?')"
                                            style="background:#fff0f0; border:1px solid #f1b4b4; color:#c40000; cursor:pointer; padding:6px 10px; border-radius:8px;">
                                        Dzēst
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>

                    <div style="margin-top:10px; color:#333; line-height:1.6; overflow-wrap:anywhere; word-break:break-word; white-space:pre-wrap;">
                        {{ $c->text }}
                    </div>
                </div>

            </div>
        @empty
            <div style="padding:14px; border:1px solid #ddd; border-radius:12px; background:#fafafa; color:#777;">
                Šeit vēl nav komentāru.
            </div>
        @endforelse

        @auth
            <form method="POST" action="{{ route('comments.store', $post->ieraksts_id) }}"
                  style="margin-top:25px; background:#fff; border:1px solid #ddd; border-radius:16px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.04);">
                @csrf

                <label for="text"><b>Pievienot komentāru</b></label>

                <textarea
                    id="text"
                    name="text"
                    required
                    rows="4"
                    style="width:100%; margin-top:10px; padding:10px; border:1px solid #ccc; border-radius:10px; resize:vertical;"
                >{{ old('text') }}</textarea>

                <button
                    type="submit"
                    style="margin-top:12px; padding:10px 16px; border:none; border-radius:10px; cursor:pointer;">
                    Sūtīt
                </button>
            </form>
        @else
            <div style="margin-top:20px; padding:14px; border:1px solid #ddd; border-radius:12px; background:#fafafa;">
                Lai pievienotu komentāru, lūdzu autorizējies.
            </div>
        @endauth
    </section>

</div>
@endsection