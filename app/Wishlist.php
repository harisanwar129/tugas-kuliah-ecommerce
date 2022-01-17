<?php

namespace App;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
