<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function get_penjualan_harian(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'integer'],
            'bulan' => ['nullable', 'numeric'],
            'tahun' => ['nullable', 'numeric'],
            'jumlah_hari' => ['nullable', 'numeric'],
        ]);

        $sales = [];
        $days = (isset($request->jumlah_hari)) ? $request->jumlah_hari : Carbon::create($request->tahun, $request->bulan, 1)->daysInMonth;
        // return $days;
        for ($i = 1; $i <= $days; $i++) {
            $transaksi_berhasil = Transaction::whereDay('created_at', '=', $i)->whereMonth('created_at', '=', $request->bulan)->whereYear('created_at', '=', $request->tahun)->where('status', 'berhasil')->sum('subtotal');
            $transaksi_pending = Transaction::whereDay('created_at', '=', $i)->whereMonth('created_at', '=', $request->bulan)->whereYear('created_at', '=', $request->tahun)->where('status', 'PENDING')->sum('subtotal');
            $sales[] = [
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'transaksi_berhasil' => $transaksi_berhasil,
                'transaksi_pending' => $transaksi_pending,
            ];
        }

        return $sales;
    }

    public function get_top_sales_product(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'integer'],
            'jumlah_produk' => ['nullable', 'integer'],
        ]);

        $top_sell_product = DB::table('transaction_items')->select(DB::raw('products.id, products.name,  COUNT(*) as total'))
        ->join('products', 'products.id', '=', 'transaction_items.product_id')
        ->groupBy('transaction_items.product_id')
        ->where('transaction_items.transaction_id', '!=', NULL)
        ->orderBy('total', 'DESC')
        ->take($request->jumlah_produk)
        ->get();

        return response()->json($top_sell_product);
    }
}
