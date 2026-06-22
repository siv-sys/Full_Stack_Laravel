<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToWishlistRequest;
use App\Http\Resources\WishlistResource;
use App\Models\Wishlist;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $wishlists = $request->user()->wishlists()->with('product.category')->get();

        return $this->success(WishlistResource::collection($wishlists), 'Wishlist retrieved.');
    }

    public function store(AddToWishlistRequest $request)
    {
        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return $this->error('Product already in wishlist.', 409);
        }

        $wishlist = Wishlist::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
        ]);

        $wishlist->load('product.category');

        return $this->success(new WishlistResource($wishlist), 'Product added to wishlist.', 201);
    }

    public function destroy(Request $request, int $productId)
    {
        $wishlist = Wishlist::where('product_id', $productId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$wishlist) {
            $existsForOther = Wishlist::where('product_id', $productId)->exists();

            if ($existsForOther) {
                return $this->error('You do not own this wishlist item.', 403);
            }

            return $this->error('Product not found in wishlist.', 404);
        }

        $wishlist->delete();

        return $this->success(null, 'Product removed from wishlist.');
    }
}
