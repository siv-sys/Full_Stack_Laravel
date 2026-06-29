@extends('admin.layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')
<a href="{{ route('admin.orders.index') }}" class="back-link">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
    Back to Orders
</a>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Order Details</h3>
        <span class="badge badge-{{ $order->status }}">
            <span class="badge-dot"></span>
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="info-grid" style="margin-bottom: 1.5rem;">
        <div class="info-item">
            <div class="info-label">Order ID</div>
            <div class="info-value">#{{ $order->id }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Customer</div>
            <div class="info-value">{{ $order->user->name }} ({{ $order->user->email }})</div>
        </div>
        <div class="info-item">
            <div class="info-label">Date</div>
            <div class="info-value">{{ $order->created_at->format('M d, Y \a\t H:i') }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Total</div>
            <div class="info-value" style="font-size: 1.1rem; font-weight: 700; color: var(--primary);">${{ number_format($order->total, 2) }}</div>
        </div>
    </div>

    <div class="status-box" style="margin-bottom: 1.5rem;">
        <label>Update Status:</label>
        <form id="status-form" method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" style="display: inline-flex; gap: 0.5rem; align-items: center;">
            @csrf
            @method('PATCH')
            <select name="status" id="status-select">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </form>
        @error('status')
            <div class="form-error">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Order Items</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="font-weight: 600;">{{ $item->product->name ?? 'Deleted product' }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>
                    <span class="badge badge-customer">x{{ $item->quantity }}</span>
                </td>
                <td style="text-align: right; font-weight: 600;">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: 700; font-size: 0.9rem; border-top: 2px solid var(--gray-200);">Total</td>
                <td style="text-align: right; font-weight: 700; font-size: 1.05rem; color: var(--primary); border-top: 2px solid var(--gray-200);">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
document.getElementById('status-form').addEventListener('submit', function (e) {
    var selected = document.getElementById('status-select').value;
    var message = 'Are you sure you want to update the order status to "' + selected + '"?';
    if (selected === 'cancelled') {
        message += '\n\nThis will restore stock for all items in this order.';
    }
    if (!confirm(message)) {
        e.preventDefault();
    }
});
</script>
@endsection
