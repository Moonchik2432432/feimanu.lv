@extends('layouts.app')

@section('title', 'Bloķēšanas vēsture')

@section('content')

<div class="container" style="max-width:1100px; margin:40px auto;">

    <h1>Bloķēšanas vēsture</h1>

    <div style="margin-bottom:20px;">
        <strong>Lietotājs:</strong> {{ $user->name }} <br>
        <strong>E-pasts:</strong> {{ $user->email }}
    </div>

    <a href="{{ route('admin.users') }}"
       style="padding:8px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block; margin-bottom:20px;">
        ← Atpakaļ uz lietotājiem
    </a>

    <table style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:10px; border:1px solid #ddd;">ID</th>
                <th style="padding:10px; border:1px solid #ddd;">Iemesls</th>
                <th style="padding:10px; border:1px solid #ddd;">Papildu komentārs</th>
                <th style="padding:10px; border:1px solid #ddd;">Bloķēja</th>
                <th style="padding:10px; border:1px solid #ddd;">Bloķēts no</th>
                <th style="padding:10px; border:1px solid #ddd;">Bloķēts līdz</th>
                <th style="padding:10px; border:1px solid #ddd;">Atbloķēts</th>
                <th style="padding:10px; border:1px solid #ddd;">Atbloķēja</th>
            </tr>
        </thead>

        <tbody>

        @forelse($blocks as $block)

            <tr>
                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $block->id }}
                </td>

                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $block->reason?->title ?? 'Nav' }}
                </td>

                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $block->custom_reason ?? '-' }}
                </td>

                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $block->blocker?->name ?? '-' }}
                </td>

                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $block->blocked_from ? $block->blocked_from->format('d.m.Y H:i') : '-' }}
                </td>

                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $block->blocked_until ? $block->blocked_until->format('d.m.Y H:i') : '-' }}
                </td>

                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $block->unblocked_at ? $block->unblocked_at->format('d.m.Y H:i') : '-' }}
                </td>

                <td style="padding:10px; border:1px solid #ddd;">
                    {{ $block->unblockedBy?->name ?? '-' }}
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="8" style="padding:20px; text-align:center; border:1px solid #ddd;">
                    Nav bloķēšanas vēstures
                </td>
            </tr>

        @endforelse

        </tbody>
    </table>

</div>

@endsection