<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $categories = Cache::remember('categories:all', 3600, function () {
            return Category::orderBy('name')->get();
        });

        return $this->success(CategoryResource::collection($categories), 'Categories retrieved.');
    }
}
