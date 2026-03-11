@extends('layouts.app')

@section('title', $post->nosaukums)

@section('content')
<div class="container">

    <h1>{{ $post->nosaukums }}</h1>

    <div style="color:#666; margin:10px 0;">
        {{ \Carbon\Carbon::parse($post->publicets_datums)->format('d.m.Y H:i') }}
        @if($post->category)
            • {{ $post->category->nosaukums }}
        @endif
    </div>

    @if($post->bilde)
        <img
            src="{{ asset($post->bilde) }}"
            alt="{{ $post->nosaukums }}"
            style="max-width:600px; width:100%; border-radius:12px; margin:20px 0;"
        >
    @endif

    <div>
        {!! nl2br(e($post->saturs)) !!}
    </div>

    <hr style="margin:30px 0;">

    <h2>Komentāri</h2>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; margin:15px 0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    @forelse($post->comments as $c)
        <div style="display:flex; gap:15px; border-bottom:1px solid #ddd; padding:15px 0;">

            <div>
                @if($c->user && $c->user->avatar)
                    <img
                        src="{{ asset('img/usersAvatars/' . $c->user->avatar) }}"
                        style="width:50px; height:50px; border-radius:50%; object-fit:cover;"
                    >
                @else
                    <img
                        src="{{ asset('img/usersAvatars/default_avatar.jpg') }}"
                        style="width:50px; height:50px; border-radius:50%; object-fit:cover;"
                    >
                @endif
            </div>

            <div style="flex:1;">
                <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <b>{{ $c->user->name ?? 'Lietotājs' }}</b>
                    <small style="color:#777;">
                        {{ \Carbon\Carbon::parse($c->izveidots_datums)->format('d.m.Y H:i') }}
                    </small>
                </div>

                <div style="margin-top:6px;">
                    {{ $c->text }}
                </div>

                @auth
                    @if($c->user_id === auth()->id())
                        <form method="POST" action="{{ route('comments.destroy', $c->komentars_id) }}" style="margin-top:6px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Vai tiešām dzēst komentāru?')"
                                    style="background:none; border:none; color:red; cursor:pointer; padding:0;">
                                Dzēst
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

        </div>
    @empty
        <p style="color:#777;">Šeit vēl nav komentāru.</p>
    @endforelse

    @auth
        <form method="POST" action="{{ route('comments.store', $post->ieraksts_id) }}" style="margin-top:20px;">
            @csrf

            <label for="text"><b>Pievienot komentāru</b></label>

            <textarea
                id="text"
                name="text"
                required
                rows="4"
                style="width:100%; margin-top:8px; padding:8px;"
            >{{ old('text') }}</textarea>

            <button
                type="submit"
                style="margin-top:10px; padding:8px 15px;">
                Sūtīt
            </button>
        </form>
    @else
        <p style="margin-top:15px;">
            Lai pievienotu komentāru, lūdzu autorizējies.
        </p>
    @endauth

</div>
@endsection