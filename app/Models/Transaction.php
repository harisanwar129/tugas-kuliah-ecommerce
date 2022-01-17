<?php

namespace App\Models;

use App\City;
use App\Helpers\MyHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    public function transaction_items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'shipping_city_to', 'city_id')->withDefault(['title' => $this->address]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function get_status_label()
    {
        $status = $this->status;
        return MyHelper::get_status_label($status);
    }

    public function get_status_pengiriman()
    {
        $state = $this->state;
        return MyHelper::get_state_pengiriman($state);
    }
}
