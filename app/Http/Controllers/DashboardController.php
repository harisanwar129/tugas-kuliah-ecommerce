<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionItemNoSubtotal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard Aplikasi',
        ];

        // jika Seller yang baru registrasi login, maka arahkan ke halaman setup toko untuk setup toko (only once)
        if (Auth::user()->role == 'SELLER' && Auth::user()->shop_name == NULL) {
            $data['title'] = 'Setup Toko Anda';
            $data['user'] = Auth::user();
            return view('dashboard.user.setup_toko', $data);
        }

        if (Auth::user()->role == 'MEMBER') {
            $data['title'] = 'Dashboard';
            $data['user'] = Auth::user();
            $my_transaction = Transaction::with(['transaction_items'])->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
            $data['pending'] = $my_transaction->whereIn('status', ['PENDING', 'pending'])->count();
            $data['berhasil'] = $my_transaction->where('status', 'berhasil')->count();
            $data['batal'] = $my_transaction->where('status', 'batal')->count();

            return view('front.my_account', $data);
        }



        if(Auth::user()->role == 'SELLER') {
            // jika seller, maka hitung hanya transaksi yang ke dia saja (yang ada produk dianya)
            $query = TransactionItemNoSubtotal::join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
                                    ->join('products', 'products.id', '=', 'transaction_items.product_id')
                                    ->join('users', 'users.id', '=', 'products.user_id')
                                    ->join('users as customer', 'customer.id', '=', 'transaction_items.user_id')
                                    ->select(
                                        'transactions.*',
                                        'products.is_discount',
                                        'products.price',
                                        'products.price_striked',
                                        'users.name',
                                        DB::raw('customer.name AS customer_name'))
                                    ->where('products.user_id', Auth::user()->id);

            $transactions = $query->groupBy('transaction_items.transaction_id')->get();

            $transactions_collection = collect($transactions);
            $data['transactions'] = $transactions;

            // Hitung pending_revenue berdasarkan produk dia saja yang terjual
            $pending_revenue_per_seller = 0;
            foreach($transactions_collection->where('status', 'PENDING') as $transaction) {
                $transaction_id = $transaction->id;
                // loop each transaction itesm that buying this seller product and count it if its product
                $transaction_items = TransactionItem::where('transaction_id', $transaction_id)->get();
                    foreach($transaction_items as $item) {
                        if ($item->product && ($item->product->user_id == Auth::user()->id)) {
                            $pending_revenue_per_seller += $item->subtotal;
                        }
                    }
            }

            // Hitung revenue berdasarkan produk dia saja yang terjual
            $revenue_per_seller = 0;
            foreach($transactions_collection->where('status', 'berhasil') as $transaction) {
                $transaction_id = $transaction->id;
                // loop each transaction itesm that buying this seller product and count it if its product
                $transaction_items = TransactionItem::where('transaction_id', $transaction_id)->get();
                    foreach($transaction_items as $item) {
                        if ($item->product->user_id == Auth::user()->id) {
                            $revenue_per_seller += $item->subtotal;
                        }
                    }
            }

            $data['transactions'] = $transactions;
            $data['revenue'] = $revenue_per_seller;
            $data['pending_revenue'] = $pending_revenue_per_seller;
            $data['product_sold_count'] = $query->where('transaction_items..transaction_id', '!=', NULL)->count();
            $data['top_sell_product'] = $query->orderBy('subtotal', 'DESC')->where('status', 'berhasil')->take(5)->get();
            $data['top_transaction'] = TransactionItemNoSubtotal::select(DB::raw('transactions.status, customer.name AS customer_name, products.id, products.name,  COUNT(*) as total'))
                                        ->join('products', 'products.id', '=', 'transaction_items.product_id')
                                        ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
                                        ->join('users as customer', 'customer.id', '=', 'transaction_items.user_id')
                                        ->groupBy('transaction_items.product_id')
                                        ->where('transaction_items.transaction_id', '!=', NULL)
                                        ->where('products.user_id', Auth::user()->id)
                                        ->where('status', 'berhasil')
                                        ->orderBy('total', 'DESC')
                                        ->take(3)
                                        ->get();

        } else {
            $transactions = collect(Transaction::all());
            $revenue = $transactions->where('status', 'berhasil')->sum('subtotal') + $transactions->where('status', 'berhasil')->sum('shipping_service_price');
            $pending_revenue = $transactions->whereIn('status', ['PENDING', 'pending'])->sum('subtotal') + $transactions->whereIn('status', ['PENDING', 'pending'])->sum('shipping_service_price');
            $product_sold = TransactionItem::where('transaction_id', '!=', NULL)->get();
            $product_sold_count = $product_sold->count();
            $top_transaction = Transaction::orderBy('subtotal', 'DESC')->where('status', 'berhasil')->take(5)->get();
            $top_sell_product = TransactionItem::select(DB::raw('products.id, products.name,  COUNT(*) as total'))
                                    ->join('products', 'products.id', '=', 'transaction_items.product_id')
                                    ->groupBy('transaction_items.product_id')
                                    ->where('transaction_items.transaction_id', '!=', NULL)
                                    ->orderBy('total', 'DESC')
                                    ->take(3)
                                    ->get();
            $data['transactions'] = $transactions;
            $data['revenue'] = $revenue;
            $data['pending_revenue'] = $pending_revenue;
            $data['product_sold_count'] = $product_sold_count;
            $data['top_sell_product'] = $top_sell_product;
            $data['top_transaction'] = $top_transaction;
        }

        return view('dashboard.index', $data);
    }

    public function my_shop()
    {
        $data = [
            'title' => 'Toko Saya',
            'user' => Auth::user()
        ];

        return view('dashboard.user.setup_toko', $data);
    }

    public function grafik_penjualan()
    {
        $data = [
            'title' => 'Grafik Penjualan',
        ];

        return view('dashboard.statistik.grafik_penjualan', $data);
    }
}
