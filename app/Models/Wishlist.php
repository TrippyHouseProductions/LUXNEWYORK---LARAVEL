<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- THIS IS REQUIRED TO SEED YOUR DB AND THIS IS USED BY DATA FACTORIES

// NOTE this is the wishlist class
class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];
    public function items() { return $this->hasMany(WishlistItem::class); }
    public function user() { return $this->belongsTo(User::class); }
}
