<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
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

        Product::create($data);
        Cache::increment('products:version');

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($data['name'] !== $product->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
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
        Cache::increment('products:version');

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && !str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
            Storage::disk('public')->delete('products/thumbs/' . basename($product->image));
        }

        $product->delete();
        Cache::increment('products:version');

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
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
}
