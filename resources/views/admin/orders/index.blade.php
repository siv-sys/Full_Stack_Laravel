@extends('admin.layouts.app')
@section('title', 'Orders')

@section('content')
<div class="card">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>${{ number_format($order->total, 2) }}</td>
                <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-primary btn-sm">View</a></td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;color:#6b7280;">No orders yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $orders->links('pagination::simple-default') }}
    </div>
</div>
@endsection
