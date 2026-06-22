<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        $orders = $request->user()
            ->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'items' => OrderResource::collection($orders),
                'meta' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                ],
            ],
            'message' => 'Orders retrieved.',
        ]);
    }

    public function show(Request $request, int $id)
    {
        $order = $request->user()
            ->orders()
            ->with('items.product')
            ->find($id);

        if (!$order) {
            return $this->error('Order not found.', 403);
        }

        return $this->success(new OrderResource($order), 'Order retrieved.');
    }
}
