<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductQuickView extends Component
{
    public $showModal = false;
    public $product;

    protected $listeners = ['showProductQuickView' => 'show'];

    public function show($productId)
    {
        $this->product = Product::with('category')->find($productId);
        $this->showModal = true;
    }

    public function close()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.product-quick-view');
    }
}

