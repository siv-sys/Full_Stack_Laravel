@extends('admin.layouts.app')
@section('title', 'Edit User')

@section('content')
<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        @include('admin.users._form')
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
@endsection
