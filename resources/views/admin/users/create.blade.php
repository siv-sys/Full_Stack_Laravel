@extends('admin.layouts.app')
@section('title', 'Add User')

@section('content')
<a href="{{ route('admin.users.index') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
    Back to Users
</a>

<div class="card" style="max-width: 640px;">
    <div class="card-header">
        <h3 class="card-title">New User</h3>
    </div>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        @include('admin.users._form')
        <div style="display: flex; gap: 0.75rem; padding-top: 0.5rem;">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                Create User
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
