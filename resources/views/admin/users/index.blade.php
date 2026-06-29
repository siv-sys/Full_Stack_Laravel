@extends('admin.layouts.app')
@section('title', 'Users')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <span class="page-subtitle">Manage user accounts and roles</span>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
        Add User
    </a>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email...">
        <select name="role">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            Filter
        </button>
        @if(request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Clear</a>
        @endif
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td style="color: var(--gray-400); font-size: 0.8rem;">{{ $user->id }}</td>
                <td>
                    <a href="{{ route('admin.users.show', $user) }}" style="font-weight: 600; color: var(--gray-800); text-decoration: none;">
                        {{ $user->name }}
                    </a>
                </td>
                <td style="color: var(--gray-500);">{{ $user->email }}</td>
                <td>
                    <span class="badge {{ $user->is_admin ? 'badge-admin' : 'badge-customer' }}">
                        {{ $user->is_admin ? 'Admin' : 'Customer' }}
                    </span>
                </td>
                <td style="color: var(--gray-500);">{{ $user->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                            View
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-ghost btn-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                            Edit
                        </a>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="role" value="{{ $user->is_admin ? 'customer' : 'admin' }}">
                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Change role to {{ $user->is_admin ? 'Customer' : 'Admin' }}?')">
                                    {{ $user->is_admin ? 'Demote' : 'Promote' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="color: var(--danger); background: transparent; border: 1px solid var(--gray-300);" onclick="return confirm('Delete this user? This cannot be undone.')">Delete</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="table-empty">
                    <div class="table-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                    </div>
                    <div class="table-empty-text">No users found</div>
                    <div class="table-empty-sub">Try adjusting your search or filter criteria.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="pagination">
        @if($users->onFirstPage())
            <span class="disabled">&laquo;</span>
        @else
            <a href="{{ $users->previousPageUrl() }}">&laquo;</a>
        @endif

        @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
            @if($page == $users->currentPage())
                <span class="current">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}">&raquo;</a>
        @else
            <span class="disabled">&raquo;</span>
        @endif
    </div>
    @endif
</div>
@endsection
