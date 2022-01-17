<?php

namespace App;

use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function get_cart_total_price()
    {
        $transaction_items = TransactionItem::where('user_id', Auth::user()->id)->where('transaction_id', NULL)->get();
        $total = 0;
        foreach($transaction_items as $item) {
            $total += $item->subtotal;
        }

        return $total;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'avatar', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
