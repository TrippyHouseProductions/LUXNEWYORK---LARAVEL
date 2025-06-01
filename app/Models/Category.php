<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- THIS IS REQUIRED TO SEED YOUR DB AND THIS IS USED BY DATA FACTORIES

// NOTE this is the category class
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    public function products() { return $this->hasMany(Product::class); }
}

