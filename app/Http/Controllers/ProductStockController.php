<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductStockController extends Controller
{
    public function index($product_id)
    {
        $product = Product::with(['product_stocks'])->where('id', $product_id)->first();
        $data = [
            'title' => 'Stok Produk',
            'product' => $product,
            'datatable' => false
        ];

        return view('dashboard.product_stock.index', $data);
    }

    public function store(Request $request, $product_id)
    {

        // dd($request->all());
        $request->validate([
            'qty' => ['required', 'min:1', 'integer'],
            'notes' => ['nullable']
        ]);

        // Write history stok
        $productStock = new ProductStock();
        $productStock->qty = $request->qty;
        $productStock->product_id = $product_id;
        $productStock->action = 'in'; // force to "in" only by request
        $productStock->notes = $request->notes;
        $productStock->save();

        // Add current stok di product
        $product = Product::find($product_id);
        $product->stock += $request->qty;
        $product->save();

        return redirect()->back()->with('success', 'Berhasil menambah data');
    }
}
