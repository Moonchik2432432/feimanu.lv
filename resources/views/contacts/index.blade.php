@extends('layouts.app')

@section('title', 'Kontakti')

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

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0; border-radius:10px;">
            {{ $errors->first() }}
        </div>
    @endif

    @auth
        <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px; margin-bottom:25px;">
            <form method="POST" action="{{ route('contacts.store') }}" novalidate>
                @csrf

                <div style="margin-bottom:15px;">
                    <label>Vārds un uzvārds</label><br>
                    <input type="text"
                           name="name"
                           value="{{ old('name', auth()->user()->name ?? '') }}"
                           style="width:100%; padding:10px; box-sizing:border-box;">
                </div>

                <div style="margin-bottom:15px;">
                    <label>E-pasts</label><br>
                    <input type="text"
                           name="email"
                           value="{{ old('email', auth()->user()->email ?? '') }}"
                           style="width:100%; padding:10px; box-sizing:border-box;">
                </div>

                <div style="margin-bottom:15px;">
                    <label>Tēma</label><br>
                    <input type="text"
                           name="subject"
                           value="{{ old('subject') }}"
                           style="width:100%; padding:10px; box-sizing:border-box;">
                </div>

                <div style="margin-bottom:15px;">
                    <label>Ziņojums</label><br>
                    <textarea name="message"
                              rows="6"
                              style="width:100%; padding:10px; resize:vertical; box-sizing:border-box;">{{ old('message') }}</textarea>
                </div>

                <button type="submit" style="padding:10px 18px;">Nosūtīt</button>
            </form>
        </div>
    @endauth

    @guest
        <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px; margin-bottom:25px; text-align:center;">
            <p style="margin:0 0 12px 0;">Lai nosūtītu ziņojumu, lūdzu, piesakieties savā kontā.</p>

            <a href="{{ route('login') }}"
               style="display:inline-block; padding:10px 18px; background:#093600; color:#fff; text-decoration:none; border-radius:8px;">
                Pieslēgties
            </a>
        </div>
    @endguest

    @auth
        <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px;">
            <h2 style="margin-top:0;">Mani ziņojumi</h2>

            <form method="GET" action="{{ route('contacts') }}"
                  style="display:flex; gap:10px; flex-wrap:wrap; align-items:end; margin-bottom:20px;">
                <div>
                    <label>Meklēšana</label><br>
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Tēma vai teksts"
                           style="padding:8px; min-width:200px;">
                </div>

                <div>
                    <label>Statuss</label><br>
                    <select name="status" style="padding:8px;">
                        <option value="">Visi</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Arhīvs</label><br>
                    <select name="archived" style="padding:8px;">
                        <option value="">Visi</option>
                        <option value="0" {{ request('archived') === '0' ? 'selected' : '' }}>Aktīvie</option>
                        <option value="1" {{ request('archived') === '1' ? 'selected' : '' }}>Arhīvā</option>
                    </select>
                </div>

                <div>
                    <label>No datuma</label><br>
                    <input type="text"
                           name="date_from"
                           value="{{ request('date_from') }}"
                           class="date-picker"
                           style="padding:8px;">
                </div>

                <div>
                    <label>Līdz datumam</label><br>
                    <input type="text"
                           name="date_to"
                           value="{{ request('date_to') }}"
                           class="date-picker"
                           style="padding:8px;">
                </div>

                <button type="submit" style="padding:9px 16px;">Filtrēt</button>

                <a href="{{ route('contacts') }}"
                   style="padding:9px 16px; background:#eee; color:#000; text-decoration:none; border-radius:6px;">
                    Notīrīt
                </a>
            </form>

            @if($messages instanceof \Illuminate\Pagination\AbstractPaginator)
                @forelse($messages as $item)
                    <div style="border:1px solid #ddd; border-radius:10px; padding:15px; margin-bottom:15px;">
                        <div style="display:flex; justify-content:space-between; gap:15px; flex-wrap:wrap; align-items:start;">
                            <div>
                                <div><strong>Tēma:</strong> {{ $item->subject }}</div>
                                <div style="color:#666; margin:8px 0;">{{ $item->created_at->format('d.m.Y H:i') }}</div>

                                <div style="margin-bottom:8px;">
                                    <strong>Statuss:</strong>
                                    <span style="
                                        display:inline-block;
                                        padding:4px 10px;
                                        border-radius:999px;
                                        font-size:13px;
                                        background:
                                            @if($item->status === 'new')
                                                background:#e8f0ff;
                                            @elseif($item->status === 'overdue')
                                                background:#ffe5e5;
                                            @elseif($item->status === 'answered')
                                                background:#e9ffe9;
                                            @endif
                                        color:#333;
                                    ">
                                        {{ $statuses[$item->status] ?? $item->status }}
                                    </span>
                                </div>

                                <div style="margin-bottom:8px;">
                                    <strong>Arhīvs:</strong>
                                    @if($item->user_archived_at)
                                        <span style="color:#777;">Arhivēts</span>
                                    @else
                                        <span style="color:#2c7a2c;">Aktīvs</span>
                                    @endif
                                </div>
                            </div>

                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                @if(!$item->user_archived_at)
                                    <form method="POST" action="{{ route('contacts.archive', $item->id) }}">
                                        @csrf
                                        <button type="submit" style="padding:8px 12px;">Arhivēt</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('contacts.unarchive', $item->id) }}">
                                        @csrf
                                        <button type="submit" style="padding:8px 12px;">Atarhivēt</button>
                                    </form>
                                @endif

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
                    <p>Ziņojumu vēl nav.</p>
                @endforelse

                <div style="margin-top:20px;">
                    {{ $messages->links('pagination.default') }}
                </div>
            @else
                <p>Ziņojumu vēl nav.</p>
            @endif
        </div>
    @endauth

</div>
@endsection
