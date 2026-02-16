@extends('layouts.app')

@section('title', $post->nosaukums)

@section('content')
<div class="container">
  <h1>{{ $post->nosaukums }}</h1>
  <div style="color:#666;margin:10px 0;">
    {{ \Carbon\Carbon::parse($post->publicets_datums)->format('d.m.Y H:i') }}
    @if($post->kategorija) • {{ $post->kategorija->nosaukums }} @endif
  </div>
  <div>{!! nl2br(e($post->saturs)) !!}</div>
</div>
@endsection
