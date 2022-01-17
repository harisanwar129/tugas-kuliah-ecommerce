<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductGallery extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getThumbnailCompleteUrlAttribute()
    {
        return url(Storage::url($this->thumbnail ?? asset('img/default.png')));
    }

    protected $appends = [
        'thumbnail_complete_url'
    ];
}
