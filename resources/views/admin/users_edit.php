@extends('layouts.app')

@section('title', 'Edit user')

@section('content')
<div class="container" style="max-width:650px; margin:40px auto;">
    <h1>Edit: {{ $user->name }}</h1>

    @if($errors->any())
        <div style="padding:10px; background:#ffecec; border:1px solid #ffbcbc; margin:15px 0;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div style="margin:10px 0;">
            <label>Name</label><br>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width:100%; padding:8px;">
        </div>

        <div style="margin:10px 0;">
            <label>Email</label><br>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width:100%; padding:8px;">
        </div>

        <div style="margin:10px 0;">
            <label>Role</label><br>
            <select name="role" style="width:100%; padding:8px;">
                <option value="user"  @selected(old('role', $user->role) === 'user')>user</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>admin</option>
            </select>
        </div>

        <button type="submit" style="padding:10px 14px;">Save</button>
        <a href="{{ route('admin.users') }}" style="margin-left:10px;">Back</a>
    </form>
</div>
@endsection