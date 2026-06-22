<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $items = $request->user()->cartItems()->with('product.category')->get();

        $total = $items->sum(fn ($item) => $item->quantity * $item->product->price);

        return $this->success([
            'items' => CartItemResource::collection($items),
            'total' => number_format($total, 2, '.', ''),
        ], 'Cart retrieved.');
    }

    public function store(AddToCartRequest $request)
    {
        $existing = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $request->quantity]);
            $existing->load('product.category');
            return $this->success(new CartItemResource($existing), 'Cart updated.');
        }

        $cartItem = CartItem::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        $cartItem->load('product.category');

        return $this->success(new CartItemResource($cartItem), 'Product added to cart.', 201);
    }

    public function update(UpdateCartRequest $request, int $itemId)
    {
        $cartItem = CartItem::find($itemId);

        if (!$cartItem) {
            return $this->error('Cart item not found.', 404);
        }

        if ($cartItem->user_id !== $request->user()->id) {
            return $this->error('You do not own this cart item.', 403);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        $cartItem->load('product.category');

        return $this->success(new CartItemResource($cartItem), 'Cart item updated.');
    }

    public function destroy(Request $request, int $itemId)
    {
        $cartItem = CartItem::find($itemId);

        if (!$cartItem) {
            return $this->error('Cart item not found.', 404);
        }

        if ($cartItem->user_id !== $request->user()->id) {
            return $this->error('You do not own this cart item.', 403);
        }

        $cartItem->delete();

        return $this->success(null, 'Cart item removed.');
    }
}
