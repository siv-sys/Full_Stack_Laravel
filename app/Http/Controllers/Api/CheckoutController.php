<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $user = $request->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return $this->error('Cart is empty.', 422);
        }

        try {
            $order = DB::transaction(function () use ($user, $cartItems) {
                $total = 0;

                foreach ($cartItems as $item) {
                    if ($item->product->stock < $item->quantity) {
                        throw new \Exception("Insufficient stock for {$item->product->name}. Available: {$item->product->stock}");
                    }
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => 0,
                    'status' => 'pending',
                ]);

                foreach ($cartItems as $item) {
                    $unitPrice = $item->product->price;

                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $unitPrice,
                    ]);

                    Product::where('id', $item->product_id)
                        ->decrement('stock', $item->quantity);

                    $total += $item->quantity * $unitPrice;
                }

                $order->update(['total' => $total]);

                $user->cartItems()->delete();

                return $order;
            });

            $order->load('items.product');

            return $this->success(new OrderResource($order), 'Order placed successfully.', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
