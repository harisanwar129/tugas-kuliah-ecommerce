<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function getSubtotalAttribute()
    {
        if (!$this->product) {
            return 0;
        }
        $subtotal = $this->product->selected_price * $this->quantity;
        return $subtotal;
    }

    protected $appends = [
        'subtotal'
    ];
}
