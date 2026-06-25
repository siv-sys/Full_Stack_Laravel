<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;

    public function index(Request $request, int $productId)
    {
        $product = Product::findOrFail($productId);

        $perPage = min((int) $request->input('per_page', 12), 50);

        $reviews = $product->reviews()
            ->with('user:id,name')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'items' => ReviewResource::collection($reviews),
                'meta' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ],
            ],
            'message' => 'Reviews retrieved.',
        ]);
    }

    public function store(StoreReviewRequest $request, int $productId)
    {
        $product = Product::findOrFail($productId);

        $existing = Review::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return $this->error('You have already reviewed this product.', 409);
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $review->load('user');

        return $this->success(new ReviewResource($review), 'Review submitted.', 201);
    }
}
