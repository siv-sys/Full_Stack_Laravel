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
                $productIds = $cartItems->pluck('product_id')->all();
                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $unavailable = [];
                foreach ($cartItems as $item) {
                    $product = $products->get($item->product_id);
                    if (!$product || $product->stock < $item->quantity) {
                        $unavailable[] = [
                            'product' => $product ? $product->name : "Product #{$item->product_id}",
                            'requested' => $item->quantity,
                            'available' => $product ? $product->stock : 0,
                        ];
                    }
                }

                if (!empty($unavailable)) {
                    $messages = array_map(
                        fn($u) => "{$u['product']} (requested: {$u['requested']}, available: {$u['available']})",
                        $unavailable
                    );
                    throw new \Exception('Insufficient stock for: ' . implode('; ', $messages));
                }

                $total = 0;

                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => 0,
                    'status' => 'pending',
                ]);

                foreach ($cartItems as $item) {
                    $product = $products->get($item->product_id);
                    $unitPrice = $product->price;

                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $unitPrice,
                    ]);

                    $product->decrement('stock', $item->quantity);

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
