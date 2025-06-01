<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        
        $orders = Order::with('items.product')->where('user_id', Auth::id())->get();
        return OrderResource::collection($orders);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'fake_payment_info' => 'required|string',
        ]);
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        DB::beginTransaction();
        try {
            $total = $cart->items->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total' => $total,
                'fake_payment_info' => $validated['fake_payment_info'],
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            DB::commit();

            return new OrderResource($order->load('items.product'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Order could not be placed'], 500);
        }
    }

    // Admin: view/update/delete orders
    // public function allOrders() // Only for admin
    // {

    //     if (Auth::check() && Auth::user()->user_type !== 'admin') {
    //         abort(403, 'Admins only');
    //     }

    //     $orders = Order::with('items.product', 'user')->get();
    //     return OrderResource::collection($orders);
    // }

    public function allOrders(Request $request) // Only for admin
    {

        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        $perPage = $request->get('per_page', 6);

        // $orders = Order::with('items.product', 'user')->get();
        $orders = Order::with('items.product', 'user')
                        ->orderBy('created_at', 'desc')
                        ->paginate($perPage);
        
        return response()->json($orders);
    }

    public function updateStatus(Request $request, $id)
    {

        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled',
        ]);
        $order->update(['status' => $request->status]);
        return new OrderResource($order);
    }

    public function destroy($id)
    {

        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        Order::destroy($id);
        return response()->noContent();
    }

    public function countOrders(Request $request) 
    {
        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        $status = $request->get('status', 'pending');
        return response()->json(['count' => Order::where('status', $status)->count()]);
    }

    public function confirmedRevenue() 
    {
        
        if (Auth::check() && Auth::user()->user_type !== 'admin') {
            abort(403, 'Admins only');
        }

        $revenue = Order::where('status', 'confirmed')->sum('total');
        return response()->json(['revenue' => $revenue]);
    }

    public function show($id)
    {

        // Load the order with user, items, and each item's product
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return new OrderResource($order);
    }
}
