<?php

namespace App\Http\Controllers\Api;

use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Http\Resources\WishlistResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::with('items.product')->firstOrCreate(['user_id' => Auth::id()]);
        return new WishlistResource($wishlist);
    }

    public function store(Request $request)
    {
        $wishlist = Wishlist::firstOrCreate(['user_id' => Auth::id()]);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $wishlist->items()->firstOrCreate(['product_id' => $validated['product_id']]);
        return new WishlistResource($wishlist->fresh('items.product'));
    }

    public function destroy(Request $request, $itemId)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->first();
        if ($wishlist) {
            $wishlist->items()->where('id', $itemId)->delete();
        }
        return response()->noContent();
    }
}
