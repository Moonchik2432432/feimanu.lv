@extends('layouts.app')

@section('title', 'Administrācija - Lietotāji')

@section('content')

<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Lietotāju saraksts</h1>

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

    <form method="GET" action="{{ route('admin.users') }}" style="margin:15px 0; display:flex; gap:10px; flex-wrap:wrap; align-items:end;">
        <div>
            <label>Nosaukums: </label><br>
            <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Vārds / E-pasts / ID" style="padding:8px; width:220px;">
        </div>
        
        <div>
            <label>No: </label><br>
            <input type="date" name="to" value="{{ $to ?? '' }}" style="padding:8px;">
        </div>

        <div>
            <label>Lidz: </label><br>
            <input type="date" name="from" value="{{ $from ?? '' }}" style="padding:8px;">
        </div>


        <button type="submit" style="padding:9px 14px;">Filtrēt</button>

        <a href="{{ route('admin.users') }}" style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
            Notīrīt
        </a>
    </form>

    <table style="width:100%; border-collapse: collapse; margin-top:20px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Vārds</th>
                <th style="padding:10px; border:1px solid #ddd;">E-pasts</th>
                <th style="padding:10px; border:1px solid #ddd;">Reģistrēts</th>
                <th style="padding:10px; border:1px solid #ddd;">Status</th>
                <th style="padding:10px; border:1px solid #ddd;">Bloķēts līdz</th>
                <th style="padding:10px; border:1px solid #ddd;">Info & Kommentariji</th>
                <th style="padding:10px; border:1px solid #ddd;">Blokešana vēsture</th>
                <th style="padding:10px; border:1px solid #ddd;">Blokešana</th>
            </tr>
        </thead>

        <tbody>
        @foreach($users as $user)
            <tr>
                <td style="padding:10px; border:1px solid #ddd;">{{ $user->id }}</td>
                <td style="padding:10px; border:1px solid #ddd;">{{ $user->name }}</td>
                <td style="padding:10px; border:1px solid #ddd;">{{ $user->email }}</td>
                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $user->created_at->format('d.m.Y H:i') }}
                </td>
                <td style="padding:10px; border:1px solid #ddd;">
                    @if($user->isBlockedNow())
                        <span style="color:red; font-weight:bold;">Bloķēts</span>
                    @else
                        <span style="color:green; font-weight:bold;">Aktīvs</span>
                    @endif
                </td>
                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $user->blocked_until ? $user->blocked_until->format('d.m.Y H:i') : '-' }}
                </td>

                <td style="padding:10px; border:1px solid #ddd;">  
                    <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                        <a href="{{ route('admin.users.show', $user->id) }}">Informacija & Komentariji</a>
                    </div>
                </td>

                <td style="padding:10px; border:1px solid #ddd;">  
                    <div style="display:flex; flex-wrap:wrap; gap:10px; align-items:center;">
                        <a href="{{ route('admin.users.history', $user->id) }}">Vesture blokešana</a>
                    </div>
                </td>

                <td style="padding:10px; border:1px solid #ddd; min-width:260px;">
                    <div style="display:flex; flex-direction:column; gap:8px;">
                
                        @if($user->isBlockedNow())
                            <form method="POST" action="{{ route('admin.users.unblock', $user->id) }}">
                                @csrf
                                <button type="submit" style="padding:6px 12px; color:green; width:100%;">
                                    Atbloķēt
                                </button>
                            </form>
                        @else
                            <button
                                type="button"
                                onclick="toggleBlockForm({{ $user->id }})"
                                style="padding:6px 12px; color:red;"
                            >
                                Bloķēt
                            </button>
                
                            <form
                                id="block-form-{{ $user->id }}"
                                method="POST"
                                action="{{ route('admin.users.block', $user->id) }}"
                                style="display:none; flex-direction:column; gap:8px;"
                            >
                                @csrf
                
                                <select name="block_reason_id" required style="padding:6px;">
                                    <option value="">Iemesls</option>
                                    @foreach($reasons as $reason)
                                        <option value="{{ $reason->id }}">{{ $reason->title }}</option>
                                    @endforeach
                                </select>
                
                                <textarea
                                    name="custom_reason"
                                    rows="2"
                                    placeholder="Papildu komentārs (optional)"
                                    style="padding:6px; resize:vertical;"
                                ></textarea>
                
                                <input type="datetime-local" name="blocked_until" required style="padding:6px;">
                
                                <div style="display:flex; gap:8px;">
                                    <button type="submit" style="padding:6px 12px; color:red;">
                                        Apstiprināt
                                    </button>
                
                                    <button
                                        type="button"
                                        onclick="toggleBlockForm({{ $user->id }})"
                                        style="padding:6px 12px;"
                                    >
                                        Aizvērt
                                    </button>
                                </div>
                            </form>
                        @endif
                
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $users->links() }}
    </div>

</div>

@endsection

<script>
    function toggleBlockForm(userId) {
        const form = document.getElementById('block-form-' + userId);
 
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'flex';
        } else {
            form.style.display = 'none';
        }
    }
</script>