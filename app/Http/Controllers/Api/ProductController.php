<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 12), 50);
        $version = Cache::get('products:version', 0);
        $cacheKey = "products:v{$version}:" . md5($request->getQueryString() ?? '');

        $paginated = Cache::remember($cacheKey, 60, function () use ($request, $perPage) {
            $query = Product::with('category')
                ->withAvg('reviews', 'rating')
                ->withCount('reviews');

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->whereFullText(['name', 'description'], $search, ['mode' => 'boolean']);
            }

            if ($request->filled('category')) {
                $query->where('category_id', $request->input('category'));
            }

            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->input('min_price'));
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->input('max_price'));
            }

            $sort = $request->input('sort', 'latest');
            match ($sort) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                default => $query->orderBy('created_at', 'desc'),
            };

            return $query->paginate($perPage)->withQueryString();
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => ProductResource::collection($paginated),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ],
            ],
            'message' => 'Products retrieved.',
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
            $this->generateThumbnail($data['image']);
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        }

        unset($data['image_url']);

        $product = Product::create($data);
        $product->load('category');

        Cache::increment('products:version');

        return $this->success(new ProductResource($product), 'Product created.', 201);
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->error('Product not found.', 404);
        }

        $data = $request->validated();

        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $id);
        }

        if ($request->hasFile('image')) {
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
                Storage::disk('public')->delete('products/thumbs/' . basename($product->image));
            }
            $data['image'] = $request->file('image')->store('products', 'public');
            $this->generateThumbnail($data['image']);
        } elseif ($request->filled('image_url')) {
            if ($product->image && !str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
                Storage::disk('public')->delete('products/thumbs/' . basename($product->image));
            }
            $data['image'] = $request->input('image_url');
        }

        unset($data['image_url']);

        $product->update($data);
        $product->load('category');

        Cache::increment('products:version');

        return $this->success(new ProductResource($product), 'Product updated.');
    }

    public function destroy(int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->error('Product not found.', 404);
        }

        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
            Storage::disk('public')->delete('products/thumbs/' . basename($product->image));
        }

        $product->delete();

        Cache::increment('products:version');

        return $this->success(null, 'Product deleted.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }

    private function generateThumbnail(string $imagePath): void
    {
        if (!extension_loaded('gd')) {
            return;
        }

        try {
            $disk = Storage::disk('public');
            $disk->makeDirectory('products/thumbs');

            $fullPath = $disk->path($imagePath);
            $thumbPath = $disk->path('products/thumbs/' . basename($imagePath));

            $info = getimagesize($fullPath);
            if (!$info) {
                return;
            }

            [$width, $height, $type] = $info;
            if ($width <= 400) {
                copy($fullPath, $thumbPath);
                return;
            }

            $newWidth = 400;
            $newHeight = (int) ($height * ($newWidth / $width));

            $source = match ($type) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($fullPath),
                IMAGETYPE_PNG => imagecreatefrompng($fullPath),
                IMAGETYPE_WEBP => imagecreatefromwebp($fullPath),
                default => null,
            };

            if (!$source) {
                return;
            }

            $thumb = imagecreatetruecolor($newWidth, $newHeight);

            if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP) {
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
            }

            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            match ($type) {
                IMAGETYPE_JPEG => imagejpeg($thumb, $thumbPath, 80),
                IMAGETYPE_PNG => imagepng($thumb, $thumbPath, 6),
                IMAGETYPE_WEBP => imagewebp($thumb, $thumbPath, 80),
                default => null,
            };

            imagedestroy($source);
            imagedestroy($thumb);
        } catch (\Throwable) {
        }
    }

    public function show(string $idOrSlug)
    {
        $product = Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where(fn ($q) => $q->where('id', $idOrSlug)->orWhere('slug', $idOrSlug))
            ->firstOrFail();

        return $this->success(new ProductResource($product), 'Product retrieved.');
    }
}
