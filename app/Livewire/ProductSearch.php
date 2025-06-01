<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class ProductSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sort = 'newest';
    public $perPage = 9;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sort' => ['except' => 'newest'],
    ];

    public function render()
    {
        $query = Product::query()
            ->with('category') // Eager load category
            ->when($this->search, function ($query) {
                return $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('description', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->category, function ($query) {
                return $query->where('category_id', $this->category);
            })
            ->when($this->sort === 'price-low', function ($query) {
                return $query->orderBy('price');
            })
            ->when($this->sort === 'price-high', function ($query) {
                return $query->orderByDesc('price');
            })
            ->when($this->sort === 'newest', function ($query) {
                return $query->latest();
            });

        $products = $query->paginate($this->perPage);
        $categories = Category::all();

        return view('livewire.product-search', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function loadMore()
    {
        $this->perPage += 9;
    }

    // Reset pagination when filters change
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }
}