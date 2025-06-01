<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function adminIndex(Request $request)
    {
        $perPage = $request->get('per_page', 6);

        $categories = Category::withCount('products')
            ->orderBy('name')
            ->paginate($perPage); // Supports pagination

        return response()->json($categories);
    }

    public function show($id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'name' => 'required',
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        $category = Category::create($validated);
        return new CategoryResource($category);
    }

    // public function update(Request $request, $id)
    // {
    //     $category = Category::findOrFail($id);
    //     $category->update($request->all());
    //     return new CategoryResource($category);
    // }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            // add other fields as needed, e.g. 'is_active' => 'boolean'
        ]);

        $category->update($validated);

        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return response()->noContent();
    }
}
