@extends('layouts.app')

@section('title', 'Kontakti - Arhīvs')

@section('content')

<div class="container" style="max-width:1050px; margin:40px auto;">

<h1>Kontakti</h1>

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

@auth
    <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px;">

        <div style="display:flex; gap:10px; margin-bottom:20px;">
            <a href="{{ route('contacts') }}"
               style="
                    padding:10px 16px;
                    border-radius:8px;
                    text-decoration:none;
                    {{ request()->routeIs('contacts') ? 'background:#093600;color:#fff;' : 'background:#eee;color:#000;' }}
               ">
                Aktīvie
            </a>

            <a href="{{ route('contacts.archive.page') }}"
               style="
                    padding:10px 16px;
                    border-radius:8px;
                    text-decoration:none;
                    {{ request()->routeIs('contacts.archive.page') ? 'background:#093600;color:#fff;' : 'background:#eee;color:#000;' }}
               ">
                Arhīvs
            </a>
        </div>

        <h2 style="margin-top:0;">Arhivētie ziņojumi</h2>

        @if($messages instanceof \Illuminate\Pagination\AbstractPaginator)
            @forelse($messages as $item)
                <div style="border:1px solid #ddd; border-radius:10px; padding:15px; margin-bottom:15px;">
                    <div style="display:flex; justify-content:space-between; gap:15px; flex-wrap:wrap; align-items:start;">
                        <div>
                            <div><strong>Tēma:</strong> {{ $item->subject }}</div>
                            <div style="color:#666; margin:8px 0;">{{ $item->created_at->format('d.m.Y H:i') }}</div>

                            <div>
                                <strong>Statuss:</strong>
                                <span style="
                                    display:inline-block;
                                    padding:4px 10px;
                                    border-radius:999px;
                                    font-size:13px;
                                    background:
                                        @if($item->status === 'new')
                                            #e8f0ff
                                        @elseif($item->status === 'read')
                                            #fff4db
                                        @elseif($item->status === 'overdue')
                                            #ffe5e5
                                        @elseif($item->status === 'answered')
                                            #e9ffe9
                                        @else
                                            #f0f0f0
                                        @endif
                                    ;
                                    color:#333;
                                ">
                                    {{ $statuses[$item->status] ?? $item->status }}
                                </span>
                            </div>
                        </div>

                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <form method="POST" action="{{ route('contacts.unarchive', $item->id) }}">
                                @csrf
                                <button type="submit" style="padding:8px 12px;">Atarhivēt</button>
                            </form>

                            <form method="POST"
                                  action="{{ route('contacts.delete', $item->id) }}"
                                  onsubmit="return confirm('Vai tiešām dzēst šo ziņojumu?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="padding:8px 12px; background:#ffd9d9; border:1px solid #e3a8a8; cursor:pointer;">
                                    Dzēst
                                </button>
                            </form>
                        </div>
                    </div>

                    <div style="margin:14px 0 10px 0;">
                        <strong>Jautājums:</strong><br>
                        {!! nl2br(e($item->message)) !!}
                    </div>

                    <div style="background:#f7f7f7; border-radius:10px; padding:12px;">
                        <strong>Atbilde:</strong><br>
                        @if($item->reply)
                            {!! nl2br(e($item->reply)) !!}
                        @else
                            <span style="color:#999;">Atbilde vēl nav sniegta.</span>
                        @endif
                    </div>
                </div>
            @empty
                <p>Arhīvā nav ziņojumu.</p>
            @endforelse

            <div style="margin-top:20px;">
                {{ $messages->links('pagination.default') }}
            </div>
        @else
            <p>Arhīvā nav ziņojumu.</p>
        @endif
    </div>
@endauth

</div>
@endsection
