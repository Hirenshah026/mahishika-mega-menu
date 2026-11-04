<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    /**
     * GET /api/menu - the whole mega menu in one request.
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with([
                'subCategories' => fn ($q) => $q->where('is_active', true),
                'posts' => fn ($q) => $q->where('is_featured', true),
            ])
            ->get()
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'sub_categories' => $category->subCategories->map->only(['id', 'name', 'description', 'url'])->values(),
                'posts' => $category->posts->take(2)->map->only(['id', 'type', 'title', 'excerpt', 'image_url', 'url'])->values(),
            ]);

        return response()->json(['categories' => $categories]);
    }
}
