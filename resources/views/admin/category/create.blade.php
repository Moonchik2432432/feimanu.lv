@extends('layouts.app')

@section('title', 'Pievienot kategoriju')

@section('content')
<div class="container" style="max-width:650px; margin:40px auto;">
    <h1>Pievienot kategoriju</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.category.store') }}">
        @csrf

        <div style="margin:10px 0;">
            <label>Nosaukums</label><br>
            <input type="text" name="nosaukums" value="{{ old('nosaukums') }}" style="width:100%; padding:8px;">
        </div>

        <button type="submit" style="padding:10px 14px;">Save</button>
        <a href="{{ route('admin.category') }}" style="margin-left:10px;">Back</a>
    </form>
</div>
@endsection