@extends('layouts.app')

@section('title', $post->nosaukums)

@section('content')
<div class="container">

    <h1>{{ $post->nosaukums }}</h1>

    <div style="color:#666;margin:10px 0;">
        {{ \Carbon\Carbon::parse($post->publicets_datums)->format('d.m.Y H:i') }}
        @if($post->kategorija)
            • {{ $post->kategorija->nosaukums }}
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

    {{-- Список комментариев --}}
    @forelse($post->komentari as $c)
        <div style="display:flex; gap:15px; border-bottom:1px solid #ddd; padding:15px 0;">

            {{-- Аватар --}}
            <div>
                @if($c->user && $c->user->avatar)
                    <img 
                        src="{{ asset('img/usersAvatars/' . $c->user->avatar) }}"
                        style="width:50px;height:50px;border-radius:50%;object-fit:cover;"
                    >
                @else
                    <img 
                        src="{{ asset('img/usersAvatars/default_avatar.png') }}"
                        style="width:50px;height:50px;border-radius:50%;object-fit:cover;"
                    >
                @endif
            </div>

            {{-- Контент комментария --}}
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
            </div>

        </div>
    @empty
        <p style="color:#777;">Šeit vēl nav komentāru.</p>
    @endforelse


    {{-- Форма добавления комментария --}}
    @auth
        <form method="POST" action="{{ route('komentari.store', $post->ieraksts_id) }}" style="margin-top:20px;">
            @csrf

            <label for="text"><b>Pievienot komentāru</b></label>

            <textarea 
                id="text"
                name="text"
                required
                rows="4"
                style="width:100%; margin-top:8px; padding:8px;"
            ></textarea>

            <button 
                type="submit"
                style="margin-top:10px; padding:8px 15px;"
            >
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
