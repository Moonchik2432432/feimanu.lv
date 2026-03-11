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

    <a href="{{ route('admin.block_reasons.create') }}"
       style="margin-bottom:20px; display:inline-block; padding:8px 14px; background:#eee; text-decoration:none; color:#000;">
        + Pievienot iemeslu
    </a>

    <table style="width:100%; border-collapse:collapse;">
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
                        <a href="{{ route('admin.block_reasons.edit', $reason->id) }}">Edit</a>

                        <form method="POST"
                              action="{{ route('admin.block_reasons.destroy', $reason->id) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Dzēst vai izslēgt šo iemeslu?');">
                            @csrf
                            @method('DELETE')

                            <button type="submit" style="margin-left:10px;">
                                Delete / Disable
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