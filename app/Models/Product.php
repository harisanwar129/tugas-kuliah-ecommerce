<?php

namespace App\Models;

use App\ProductStock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;
    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class)->withDefault(['name' => '-']);
    }

    public function transaction_items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function sold_items()
    {
        return $this->hasMany(TransactionItem::class)->whereNotNull('transaction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product_galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }


    public function product_stocks()
    {
        return $this->hasMany(ProductStock::class)->orderBy('id', 'ASC');
    }

    public function getThumbnailCompleteUrlAttribute()
    {
        return url(Storage::url($this->thumbnail ?? asset('img/default.png')));
    }

    public function getDetailUrlAttribute()
    {
        return route('product.show', $this->slug);
    }

    public function getSelectedPriceAttribute()
    {
        $price = $this->is_discount == 1 ? $this->price_striked : $this->price;
        return $price;
    }

    public function getShortNameAttribute()
    {
        return Str::limit($this->name, 16);
    }

    protected $appends = [
        'thumbnail_complete_url',
        'detail_url',
        'short_name',
        'selected_price'
    ];

}
