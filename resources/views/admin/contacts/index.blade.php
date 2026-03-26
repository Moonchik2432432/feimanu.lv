@extends('layouts.app')

@section('title', 'Administrācija - Kontakti')

@section('content')
<div class="container" style="max-width:1000px; margin:40px auto;">

    <h1>Lietotāju jautājumi</h1>

    @if(session('success'))
        <div style="padding:10px; background:#e9ffe9; border:1px solid #b7f0b7; margin:15px 0;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:grid; gap:15px;">
        @forelse($messages as $item)
            <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px;">
                <div style="font-weight:bold;">{{ $item->subject }}</div>

                <div style="margin:10px 0; color:#666;">
                    {{ $item->name }} | {{ $item->email }} | {{ $item->created_at->format('d.m.Y H:i') }}
                </div>

                <div style="margin-bottom:15px;">
                    {{ \Illuminate\Support\Str::limit($item->message, 150) }}
                </div>

                <a href="{{ route('admin.contacts.show', $item->id) }}"
                   style="display:inline-block; padding:8px 14px; background:#093600; color:#fff; text-decoration:none; border-radius:8px;">
                    Atvērt
                </a>
            </div>
        @empty
            <div style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:20px;">
                Nav neviena jautājuma.
            </div>
        @endforelse
    </div>

</div>
@endsection