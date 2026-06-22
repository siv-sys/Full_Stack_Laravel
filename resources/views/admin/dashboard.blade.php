@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-4">
    <div class="card stat-card">
        <div class="value">{{ $stats['users'] }}</div>
        <div class="label">Total Users</div>
    </div>
    <div class="card stat-card">
        <div class="value">{{ $stats['products'] }}</div>
        <div class="label">Products</div>
    </div>
    <div class="card stat-card">
        <div class="value">{{ $stats['orders'] }}</div>
        <div class="label">Orders</div>
    </div>
    <div class="card stat-card">
        <div class="value">${{ number_format($stats['total_sales'], 2) }}</div>
        <div class="label">Total Sales</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1rem;">Recent Orders</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stats['recent_orders'] as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#6b7280;">No orders yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
