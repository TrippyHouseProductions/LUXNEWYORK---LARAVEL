<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- THIS IS REQUIRED TO SEED YOUR DB AND THIS IS USED BY DATA FACTORIES

// NOTE this is the product class
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'image', 'category_id', 'created_at', 'sku', 'stock',
    ];
    public function category() { return $this->belongsTo(Category::class); }
}

