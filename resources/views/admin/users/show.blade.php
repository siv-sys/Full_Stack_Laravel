@extends('admin.layouts.app')
@section('title', $user->name)

@section('content')
<div class="card">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div><strong>Name:</strong> {{ $user->name }}</div>
        <div><strong>Email:</strong> {{ $user->email }}</div>
        <div>
            <strong>Role:</strong>
            <span class="badge" style="background: {{ $user->is_admin ? '#dbeafe' : '#f3f4f6' }}; color: {{ $user->is_admin ? '#1e40af' : '#374151' }};">
                {{ $user->is_admin ? 'Admin' : 'Customer' }}
            </span>
        </div>
        <div><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</div>
    </div>
</div>

<div class="grid grid-4">
    <div class="card stat-card">
        <div class="value">{{ $orderCount }}</div>
        <div class="label">Orders</div>
    </div>
    <div class="card stat-card">
        <div class="value">${{ number_format($totalSpent, 2) }}</div>
        <div class="label">Total Spent</div>
    </div>
    <div class="card stat-card">
        <div class="value">{{ $reviewCount }}</div>
        <div class="label">Reviews</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1rem;">Orders</h3>
    <table>
        <thead>
            <tr>
                <th>Order #</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($user->orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm" style="background: #e5e7eb; color: #374151;">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#6b7280;padding:2rem;">No orders yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Back to Users</a>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm" style="background: #e5e7eb; color: #374151; padding: 0.5rem 1rem;">Edit</a>
</div>
@endsection
