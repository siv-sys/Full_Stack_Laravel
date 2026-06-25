@extends('admin.layouts.app')
@section('title', 'Orders')

@section('content')
<div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
    <a href="{{ route('admin.orders.index') }}"
       class="btn btn-sm"
       style="background: {{ !request('status') ? '#2563eb' : '#e5e7eb' }}; color: {{ !request('status') ? '#fff' : '#374151' }};">
        All ({{ $counts->total }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
       class="btn btn-sm"
       style="background: {{ request('status') === 'pending' ? '#f59e0b' : '#e5e7eb' }}; color: {{ request('status') === 'pending' ? '#fff' : '#374151' }};">
        Pending ({{ $counts->pending }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}"
       class="btn btn-sm"
       style="background: {{ request('status') === 'completed' ? '#10b981' : '#e5e7eb' }}; color: {{ request('status') === 'completed' ? '#fff' : '#374151' }};">
        Completed ({{ $counts->completed }})
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
       class="btn btn-sm"
       style="background: {{ request('status') === 'cancelled' ? '#ef4444' : '#e5e7eb' }}; color: {{ request('status') === 'cancelled' ? '#fff' : '#374151' }};">
        Cancelled ({{ $counts->cancelled }})
    </a>
</div>

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
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #6b7280; padding: 2rem;">
                    No orders found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
    <div class="pagination">
        @if($orders->onFirstPage())
            <span>&laquo;</span>
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
            <span>&raquo;</span>
        @endif
    </div>
    @endif
</div>
@endsection
