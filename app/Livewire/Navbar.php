<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;

class Navbar extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Get cart ID for the user
            $cart = Cart::where('user_id', $user->id)->first();

            if ($cart) {
                // Count products in cart_items table with this cart_id
                $this->cartCount = CartItem::where('cart_id', $cart->id)->count();
            }
        }
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
