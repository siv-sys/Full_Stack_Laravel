@extends('admin.layouts.app')
@section('title', 'Add User')

@section('content')
<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users._form')
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div>
@endsection
