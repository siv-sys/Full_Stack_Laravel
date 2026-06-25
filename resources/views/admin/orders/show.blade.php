@extends('admin.layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')
<div class="card">
    <h3 style="margin-bottom: 1rem;">Order Summary</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div><strong>Order ID:</strong> #{{ $order->id }}</div>
        <div>
            <strong>Status:</strong>
            <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>
        <div><strong>Customer:</strong> {{ $order->user->name }} ({{ $order->user->email }})</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</div>
        <div><strong>Total:</strong> ${{ number_format($order->total, 2) }}</div>
    </div>

    <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f9fafb; border-radius: 6px;">
        <strong>Update Status:</strong>
        <form id="status-form" method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" style="display: inline-flex; gap: 0.5rem; align-items: center; margin-left: 0.5rem;">
            @csrf
            @method('PATCH')
            <select name="status" id="status-select" style="padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem;">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </form>
        @error('status')
            <div style="color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem;">{{ $message }}</div>
        @enderror
    </div>

    <h3 style="margin-bottom: 1rem;">Order Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->name ?? 'Deleted product' }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; font-weight: 700;">Total:</td>
                <td style="font-weight: 700;">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<a href="{{ route('admin.orders.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Back to Orders</a>

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
