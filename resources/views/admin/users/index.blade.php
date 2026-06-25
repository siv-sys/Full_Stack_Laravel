@extends('admin.layouts.app')
@section('title', 'Users')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 0.5rem; align-items: center;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem;">
        <select name="role" style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem;">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        @if(request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm" style="background: #6b7280; color: #fff;">Clear</a>
        @endif
    </form>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
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
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge" style="background: {{ $user->is_admin ? '#dbeafe' : '#f3f4f6' }}; color: {{ $user->is_admin ? '#1e40af' : '#374151' }};">
                        {{ $user->is_admin ? 'Admin' : 'Customer' }}
                    </span>
                </td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td style="display: flex; gap: 0.35rem; align-items: center;">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm" style="background: #e5e7eb; color: #374151;">View</a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="role" value="{{ $user->is_admin ? 'customer' : 'admin' }}">
                            <button type="submit" class="btn btn-sm" style="background: #f59e0b; color: #fff;" onclick="return confirm('Change role to {{ $user->is_admin ? 'Customer' : 'Admin' }}?')">
                                {{ $user->is_admin ? 'Demote' : 'Promote' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user? This cannot be undone.')">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#6b7280;">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="pagination">
        @if($users->onFirstPage())
            <span>&laquo;</span>
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
            <span>&raquo;</span>
        @endif
    </div>
    @endif
</div>
@endsection
