<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItemNoSubtotal extends Model
{

    protected $table = 'transaction_items';
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
