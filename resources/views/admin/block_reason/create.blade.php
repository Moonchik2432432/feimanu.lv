@extends('layouts.app')

@section('title','Create reason')

@section('content')

<div class="container" style="max-width:600px;margin:40px auto;">

<h1>Pievienot iemeslu</h1>

<form method="POST" action="{{ route('admin.block_reasons.store') }}">

@csrf

<div style="margin-bottom:15px;">
<label>Nosaukums</label>
<input type="text" name="title" required style="width:100%;padding:8px;">
</div>

<div style="margin-bottom:15px;">
<label>Apraksts</label>
<textarea name="description" rows="4" style="width:100%;padding:8px;"></textarea>
</div>

<button type="submit">Saglabāt</button>

</form>

</div>

@endsection