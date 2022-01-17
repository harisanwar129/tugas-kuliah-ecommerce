<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionItemNoSubtotal;
use App\SettingWeb;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function histori_penjualan(Request $request)
    {
        if ($request->ajax()) {
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
                                    ->groupBy('transaction_items.transaction_id');

            if (Auth::user()->role == 'SELLER') {
                $query->where('products.user_id', Auth::user()->id);
            }

            return DataTables::of($query->get())
                        ->editColumn('subtotal', function ($data) {
                            if (Auth::user()->role == 'ADMIN') {
                                $subtotal = '<b>Rp ' . MyHelper::rupiah($data->subtotal + $data->shipping_service_price) . '</b>';
                                return $subtotal;

                            } else {
                                // instead of get it directly from column, we need to loop per items to check if its seller product or not
                                $total = 0;
                                $transaction_items = TransactionItem::where('transaction_id', $data->id)->get();
                                foreach($transaction_items as $item) {
                                    if ($item->product && ($item->product->user_id == Auth::user()->id)) {
                                        $total += $item->subtotal;
                                    }
                                }
                                return '<b>Rp ' . MyHelper::rupiah($total + $data->shipping_service_price) . '</b>';
                            }

                        })
                        ->editColumn('selected_price', function ($data) {
                            return MyHelper::get_selected_price($data);
                            $selected_price = MyHelper::get_selected_price($data->is_discount, $data);
                            return $selected_price;
                        })
                        ->editColumn('status', function ($data) {
                            return MyHelper::get_status_label($data->status);
                        })
                        ->editColumn('tanggal', function($data) {
                            $is_new = '';
                            if ($data->is_read == 0) {
                                $is_new = '<br><span class="badge badge-pill badge-danger">Baru</span>';
                            }
                            return Carbon::parse($data->created_at)->locale('id_ID')->isoFormat('D MMM Y | H:s A') . $is_new;
                        })
                        ->addColumn('aksi', function ($data) {
                            $route_show = route('dashboard.transaction.show', $data->id);
                            $aksi = '
                                <div class="d-flex justify-content-start">
                                    <a href=' . $route_show . ' class="btn btn-sm waves-effect waves-light btn-info mr-1"><i class="mdi mdi-information"></i> Detail</a>
                                </div>';
                            return $aksi;
                        })
                        ->rawColumns(['aksi', 'subtotal', 'tanggal', 'status'])
                        ->make(true);
        }

        $data = [
            'title' => 'Histori Penjualan',
            'datatable' => true
        ];

        return view('dashboard.transaction.histori_penjualan', $data);
    }

    public function show(Request $request, $id)
    {
        $transaction = Transaction::with(['user', 'transaction_items'])->find($id);
        $transaction->is_read = 1;

        // check jika ini di buka dari notifikasi pembayaran baru maka set notify_paid menjadi 0 agar tidak muncul di notifikasi pembayaran baru
        if ($request->get('notify_paid_seen')) {
            $transaction->notify_paid = 0;
        }

        $transaction->save();

        $data = [
            'title' => 'Detail Transaksi',
            'transaction' => $transaction,
            'setting' => $setting = SettingWeb::where('id', 1)->with(['city'])->first()
        ];

        return view('dashboard.transaction.show_v3', $data);
    }

    public function kirim_pesanan($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->state = 'dikirim';
        $transaction->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dikirim');
    }

    public function terima_pesanan($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->state = 'diterima';
        $transaction->save();

        return redirect()->back()->with('success', 'Status Pesanan berhasil diubah menjadi diterima');
    }
}
