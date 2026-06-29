@extends('admin.layouts.app')
@section('title', 'Orders')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <span class="page-subtitle">Track and manage customer orders</span>
    </div>
</div>

<div class="tab-pills">
    <a href="{{ route('admin.orders.index') }}" class="tab-pill {{ !request('status') ? 'active' : '' }}">
        All
        <span class="tab-pill-count">{{ $counts->total }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="tab-pill {{ request('status') === 'pending' ? 'active' : '' }}">
        Pending
        <span class="tab-pill-count">{{ $counts->pending }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="tab-pill {{ request('status') === 'completed' ? 'active' : '' }}">
        Completed
        <span class="tab-pill-count">{{ $counts->completed }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="tab-pill {{ request('status') === 'cancelled' ? 'active' : '' }}">
        Cancelled
        <span class="tab-pill-count">{{ $counts->cancelled }}</span>
    </a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Order</th>
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
                <td style="font-weight: 600;">#{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td style="font-weight: 600;">${{ number_format($order->total, 2) }}</td>
                <td>
                    <span class="badge badge-{{ $order->status }}">
                        <span class="badge-dot"></span>
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td style="color: var(--gray-500);">{{ $order->created_at->format('M d, Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                        View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="table-empty">
                    <div class="table-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                    </div>
                    <div class="table-empty-text">No orders found</div>
                    <div class="table-empty-sub">Orders will appear here once customers make purchases.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
    <div class="pagination">
        @if($orders->onFirstPage())
            <span class="disabled">&laquo;</span>
        @else
            <a href="{{ $orders->previousPageUrl() }}">&laquo;</a>
        @endif

        @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
            @if($page == $orders->currentPage())
                <span class="current">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}">&raquo;</a>
        @else
            <span class="disabled">&raquo;</span>
        @endif
    </div>
    @endif
</div>
@endsection
