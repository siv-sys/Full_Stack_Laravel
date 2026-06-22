@extends('admin.layouts.app')
@section('title', 'Order #' . $order->id)

@section('content')
<div class="card">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <strong>Customer:</strong> {{ $order->user->name }} ({{ $order->user->email }})
        </div>
        <div>
            <strong>Status:</strong> <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
        </div>
        <div>
            <strong>Total:</strong> ${{ number_format($order->total, 2) }}
        </div>
        <div>
            <strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}
        </div>
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
                <td>{{ $item->product->name }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right; font-weight:700;">Total:</td>
                <td style="font-weight:700;">${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<a href="{{ route('admin.orders.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Back to Orders</a>
@endsection
