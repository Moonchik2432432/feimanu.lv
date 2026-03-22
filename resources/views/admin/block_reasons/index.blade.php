@extends('layouts.app')

@section('title', 'Block reasons')

@section('content')

<div class="container" style="max-width:900px; margin:40px auto;">

    <h1>Bloķēšanas iemesli</h1>

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

    <div style="display:flex; justify-content:space-between; gap:15px; flex-wrap:wrap; align-items:end; margin:15px 0;">

        <form method="GET" action="{{ route('admin.block_reasons') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
            <div>
                <label>Meklēšana</label><br>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nosaukums / Apraksts" style="padding:8px;">
            </div>

            <button type="submit" style="padding:9px 14px;">Filtrēt</button>

            <a href="{{ route('admin.block_reasons') }}"
               style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
                Notīrīt
            </a>
        </form>

        <a href="{{ route('admin.block_reasons.create') }}"
           style="padding:9px 14px; background:#28a745; text-decoration:none; color:#000; display:inline-block;">
            + Pievienot
        </a>
    </div>

    <table style="width:100%; border-collapse:collapse; margin-top:20px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Nosaukums</th>
                <th style="padding:10px; border:1px solid #ddd;">Apraksts</th>
                <th style="padding:10px; border:1px solid #ddd;">Aktīvs</th>
                <th style="padding:10px; border:1px solid #ddd;">Darbības</th>
            </tr>
        </thead>

        <tbody>
            @forelse($reasons as $reason)
                <tr>
                    <td style="padding:10px; border:1px solid #ddd;">{{ $reason->id }}</td>

                    <td style="padding:10px; border:1px solid #ddd;">
                        {{ $reason->title }}
                    </td>

                    <td style="padding:10px; border:1px solid #ddd;">
                        {{ $reason->description ?: '-' }}
                    </td>

                    <td style="padding:10px; border:1px solid #ddd;">
                        @if($reason->is_active)
                            <span style="color:green; font-weight:bold;">Aktīvs</span>
                        @else
                            <span style="color:red; font-weight:bold;">Izslēgts</span>
                        @endif
                    </td>

                    <td style="padding:10px; border:1px solid #ddd; white-space:nowrap;">
                        <a href="{{ route('admin.block_reasons.edit', $reason->id) }}">Rediģēt</a>

                        <form method="POST"
                              action="{{ route('admin.block_reasons.destroy', $reason->id) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Dzēst vai izslēgt šo iemeslu?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit" style="margin-left:10px;">
                                Dzēst
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:20px; border:1px solid #ddd; text-align:center;">
                        Nav neviena bloķēšanas iemesla
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection