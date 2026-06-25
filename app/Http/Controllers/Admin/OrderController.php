<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status') && in_array($request->status, ['pending', 'completed', 'cancelled'])) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        $counts = Order::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
        ")->first();

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $newStatus = $request->status;

        if ($order->status === $newStatus) {
            return back()->with('success', 'Order status is already ' . $newStatus . '.');
        }

        DB::transaction(function () use ($order, $newStatus) {
            if ($newStatus === 'cancelled' && $order->status !== 'cancelled') {
                $order->load('items');
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)
                        ->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => $newStatus]);
        });

        return back()->with('success', 'Order status updated to ' . $newStatus . '.');
    }
}
