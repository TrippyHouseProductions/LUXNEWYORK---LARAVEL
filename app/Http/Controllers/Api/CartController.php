<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->firstOrCreate(['user_id' => Auth::id()]);
        return new CartResource($cart);
    }

    public function store(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        // Check if item exists
        $item = $cart->items()->where('product_id', $validated['product_id'])->first();
        if ($item) {
            $item->quantity += $validated['quantity'];
            $item->save();
        } else {
            $cart->items()->create($validated);
        }
        return new CartResource($cart->fresh('items.product'));
    }


    public function update(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $item = $cart->items()->where('id', $itemId)->first();

        if (!$item) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        // If quantity is 0 or less, remove the item
        if ($validated['quantity'] <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $validated['quantity']]);
        }

        return new CartResource($cart->fresh('items.product'));
    }


    public function destroy(Request $request, $itemId)
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->where('id', $itemId)->delete();
        }
        return response()->noContent();
    }

    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
        }
        return response()->noContent();
    }
}
