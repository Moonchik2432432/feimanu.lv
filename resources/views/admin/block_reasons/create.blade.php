@extends('layouts.app')

@section('title','Create reason')

@section('content')

<div class="container" style="max-width:600px;margin:40px auto;">

    <h1>Pievienot iemeslu</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.block_reasons.store') }}">
        @csrf

        <div style="margin-bottom:15px;">
            <label>Nosaukums</label>
            <input type="text" name="title" value="{{ old('title') }}" required style="width:100%;padding:8px;">
        </div>

        <div style="margin-bottom:15px;">
            <label>Apraksts</label>
            <textarea name="description" rows="4" style="width:100%;padding:8px;">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="submit" style="padding:9px 14px;">
                Saglabāt
            </button>

            <a href="{{ route('admin.block_reasons') }}"
               style="padding:9px 14px; background:#eee; text-decoration:none; color:#000; display:inline-block;">
                Atpakaļ
            </a>
        </div>

    </form>

</div>

@endsection