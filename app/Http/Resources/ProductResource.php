<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image
                ? (str_starts_with($this->image, 'http') ? $this->image : asset(Storage::url($this->image)))
                : null,
            'average_rating' => $this->when(
                $this->reviews_avg_rating !== null,
                fn () => round($this->reviews_avg_rating, 1)
            ),
            'reviews_count' => $this->when(
                $this->reviews_count !== null,
                $this->reviews_count
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
