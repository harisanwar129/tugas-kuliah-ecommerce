<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;

class MobilePageController extends Controller
{
    public function index()
    {
        $best_shop = User::where('role', 'SELLER')->with(['products'])->withCount(['transaction_items'])->orderBy('transaction_items_count', 'DESC')->first();
        $data = [
            'slider' => Slider::orderBy('urutan', 'ASC')->where('is_active', 1)->get(),
            'new_products' => Product::where('visible', 1)->latest()->take(12)->get(),
            'discount_products' => Product::where('visible', 1)->where('is_discount', 1)->take(12)->get(),
            'popular_products' => Product::where('visible', 1)->orderBy('views', 'DESC')->take(12)->get(),
            'best_shop' => $best_shop
        ];

        return view('mobile.index', $data);
    }
}
