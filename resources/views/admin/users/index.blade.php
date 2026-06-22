@extends('admin.layouts.app')
@section('title', 'Users')

@section('content')
<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->is_admin ? 'Admin' : 'Customer' }}</td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#6b7280;">No users.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $users->links('pagination::simple-default') }}
    </div>
</div>
@endsection
