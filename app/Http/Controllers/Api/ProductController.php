<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index() 
    {
        return ProductResource::collection(Product::with('category')->get());
    }

    public function adminIndex(Request $request)
    {
        $perPage = $request->get('per_page', 6); // Default to 10 if not provided

        $products = Product::with('category')
            ->orderBy('name')
            ->paginate($perPage);

        return response()->json($products);
    }


    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        // Only allow admins
        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|max:100|unique:products,sku',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);
        return new ProductResource($product);
    }


    public function update(Request $request, $id)
    {

        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        $product = Product::findOrFail($id);
        $product->update($request->all());
        return new ProductResource($product);
    }

    public function destroy($id)
    {

        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        Product::destroy($id);
        return response()->noContent();
    }

    public function countProducts() 
    {
        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }
        
        return response()->json(['count' => Product::count()]);
    }

    public function newArrivals()
    {
        $products = Product::with('category')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderByDesc('created_at')
            ->get();

        return ProductResource::collection($products);
    }
}
