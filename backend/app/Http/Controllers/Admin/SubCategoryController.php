<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->orderBy('category_id')->orderBy('sort_order')->get();

        return view('admin.sub-categories.index', compact('subCategories'));
    }

    public function create()
    {
        return view('admin.sub-categories.form', [
            'subCategory' => new SubCategory(['is_active' => true, 'url' => '#']),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        SubCategory::create($this->validated($request));

        return redirect()->route('admin.sub-categories.index')->with('status', 'Sub-category created.');
    }

    public function edit(SubCategory $subCategory)
    {
        return view('admin.sub-categories.form', [
            'subCategory' => $subCategory,
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $subCategory->update($this->validated($request));

        return redirect()->route('admin.sub-categories.index')->with('status', 'Sub-category updated.');
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return redirect()->route('admin.sub-categories.index')->with('status', 'Sub-category deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:160'],
            'url' => ['required', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        return $data + ['is_active' => $request->boolean('is_active')];
    }
}
