@extends('layouts.app')

@section('title', 'Administrācija - Kontakti')

@section('content')
<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Lietotāju jautājumi</h1>

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

    <form method="GET" action="{{ route('admin.contacts') }}"
          style="display:flex; gap:10px; flex-wrap:wrap; align-items:end; margin:20px 0;">
        <div>
            <label>Meklēšana</label><br>
            <input type="text"
                   name="q"
                   value="{{ $q ?? '' }}"
                   placeholder="Vārds, e-pasts, tēma, teksts"
                   style="padding:8px; min-width:230px;">
        </div>

        <div>
            <label>Statuss</label><br>
            <select name="status" style="padding:8px;">
                <option value="">Visi</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ ($status ?? '') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Arhīvs</label><br>
            <select name="archived" style="padding:8px;">
                <option value="">Visi</option>
                <option value="0" {{ ($archived ?? '') === '0' ? 'selected' : '' }}>Aktīvie</option>
                <option value="1" {{ ($archived ?? '') === '1' ? 'selected' : '' }}>Arhīvā</option>
            </select>
        </div>

        <div>
            <label>No datuma</label><br>
            <input type="text"
                   name="date_from"
                   value="{{ $dateFrom ?? '' }}"
                   class="date-picker"
                   style="padding:8px;">
        </div>

        <div>
            <label>Līdz datumam</label><br>
            <input type="text"
                   name="date_to"
                   value="{{ $dateTo ?? '' }}"
                   class="date-picker"
                   style="padding:8px;">
        </div>

        <button type="submit" style="padding:9px 16px;">Filtrēt</button>

        <a href="{{ route('admin.contacts') }}"
           style="padding:9px 16px; background:#eee; color:#000; text-decoration:none; border-radius:6px;">
            Notīrīt
        </a>
    </form>

    <div style="display:grid; gap:15px;">
        @forelse($messages as $item)
            <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px;">
                <div style="display:flex; justify-content:space-between; gap:15px; flex-wrap:wrap; align-items:start;">
                    <div>
                        <div style="font-weight:bold; font-size:18px; margin-bottom:10px;">
                            {{ $item->subject }}
                        </div>

                        <div style="margin:10px 0; color:#666; line-height:1.8;">
                            <div><strong>Vārds un uzvārds:</strong> {{ $item->name }}</div>

                            @if($item->user)
                                <div><strong>Lietotājvārds vietnē:</strong> {{ $item->user->name }}</div>
                            @else
                                <div><strong>Lietotājvārds vietnē:</strong> Nav piesaistīta konta</div>
                            @endif

                            <div><strong>E-pasts:</strong> {{ $item->email }}</div>
                            <div><strong>Datums:</strong> {{ $item->created_at->format('d.m.Y H:i') }}</div>
                            <div>
                                <strong>Statuss:</strong>
                                <span style="
                                    display:inline-block;
                                    padding:4px 10px;
                                    border-radius:999px;
                                    font-size:13px;
                                    background:
                                        @if($item->status === 'new') #e8f0ff
                                        @elseif($item->status === 'in_progress') #fff4db
                                        @elseif($item->status === 'answered') #e9ffe9
                                        @else #f0f0f0
                                        @endif;
                                    color:#333;
                                ">
                                    {{ $statuses[$item->status] ?? $item->status }}
                                </span>
                            </div>
                            <div>
                                <strong>Arhīvs:</strong>
                                @if($item->admin_archived_at)
                                    <span style="color:#777;">Arhivēts</span>
                                @else
                                    <span style="color:#2c7a2c;">Aktīvs</span>
                                @endif
                            </div>
                        </div>

                        <div style="margin-bottom:15px;">
                            <strong>Ziņojums:</strong><br>
                            {{ \Illuminate\Support\Str::limit($item->message, 150) }}
                        </div>
                    </div>

                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        <a href="{{ route('admin.contacts.show', $item->id) }}"
                           style="display:inline-block; padding:8px 14px; background:#093600; color:#fff; text-decoration:none; border-radius:8px;">
                            Atvērt
                        </a>

                        @if(!$item->admin_archived_at)
                            <form method="POST" action="{{ route('admin.contacts.archive', $item->id) }}">
                                @csrf
                                <button type="submit" style="padding:8px 12px;">Arhivēt</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.contacts.unarchive', $item->id) }}">
                                @csrf
                                <button type="submit" style="padding:8px 12px;">Atarhivēt</button>
                            </form>
                        @endif

                        <form method="POST"
                              action="{{ route('admin.contacts.delete', $item->id) }}"
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
            </div>
        @empty
            <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px;">
                Nav neviena jautājuma.
            </div>
        @endforelse
    </div>

    <div style="margin-top:20px;">
        {{ $messages->links('pagination.default') }}
    </div>

</div>
@endsection
