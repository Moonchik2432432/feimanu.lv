@extends('layouts.app')

@section('title', $post->nosaukums)

@section('content')
<div class="container">
  <h1>{{ $post->nosaukums }}</h1>

  <div style="color:#666;margin:10px 0;">
    {{ \Carbon\Carbon::parse($post->publicets_datums)->format('d.m.Y H:i') }}
    @if($post->kategorija) • {{ $post->kategorija->nosaukums }} @endif
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
</div>
@endsection
