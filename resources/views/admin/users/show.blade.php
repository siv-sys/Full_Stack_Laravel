@extends('admin.layouts.app')
@section('title', $user->name)

@section('content')
<a href="{{ route('admin.users.index') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
    Back to Users
</a>

<div class="card">
    <div class="card-header">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div class="sidebar-avatar" style="width: 48px; height: 48px; font-size: 1.1rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div>
                <h3 class="card-title" style="margin: 0;">{{ $user->name }}</h3>
                <div style="font-size: 0.8rem; color: var(--gray-500);">{{ $user->email }}</div>
            </div>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
            Edit
        </a>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Role</div>
            <div class="info-value">
                <span class="badge {{ $user->is_admin ? 'badge-admin' : 'badge-customer' }}">
                    {{ $user->is_admin ? 'Admin' : 'Customer' }}
                </span>
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Member Since</div>
            <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon stat-icon-amber">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Orders</div>
            <div class="stat-value">{{ $orderCount }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-green">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Total Spent</div>
            <div class="stat-value">${{ number_format($totalSpent, 2) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon-purple">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
        </div>
        <div class="stat-info">
            <div class="stat-label">Reviews</div>
            <div class="stat-value">{{ $reviewCount }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Order History</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($user->orders as $order)
            <tr>
                <td style="font-weight: 600;">#{{ $order->id }}</td>
                <td style="font-weight: 600;">${{ number_format($order->total, 2) }}</td>
                <td>
                    <span class="badge badge-{{ $order->status }}">
                        <span class="badge-dot"></span>
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td style="color: var(--gray-500);">{{ $order->created_at->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="table-empty">
                    <div class="table-empty-text">No orders yet</div>
                    <div class="table-empty-sub">This user hasn't placed any orders.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
