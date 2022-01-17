<?php

namespace App\Http\Controllers\API;

use App\Helpers\MyHelper;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionLog;
use App\Wishlist;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;;

class FrontAPIController extends Controller
{
    public function fetch_detail_product(Request $request)
    {

        $product = Product::with(['product_galleries'])->where('id', $request->product_id)->first();
        if ($product) {
            return ResponseFormatter::success($product, 'Produk berhasil diambil');
        } else {
            return ResponseFormatter::error(null, 'Produk gagal diambil');
        }
    }

    public function add_to_cart(Request $request)
    {

        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable']
        ]);

        $request->merge(['quantity' => $request->quantity ?? 1]);

        if($request->user_id != NULL || $request->user_id != '') {
            // check only for member can add to cart
            $user = Auth::loginUsingId($request->user_id);
            if ($user->role != 'MEMBER') {
                return ResponseFormatter::success(['is_success' => 'only_member'], 'Hanya boleh dilakukan oleh akun dengan role MEMBER');
            }

            // cek stok
            $checkStock = Product::find($request->product_id);
            if ($checkStock->stock >= $request->quantity) {
                // cek jika sudah ada barang yang sama, maka tambah qty by 1
                $productExist = TransactionItem::where('user_id', Auth::loginUsingId($request->user_id)->id)->where('transaction_id', NULL)->where('product_id', $request->product_id)->first();
                if ($productExist) {
                    $productExist->quantity += 1;
                    $productExist->save();
                } else {
                    // Proses tambah ke cart
                    $transitem = new TransactionItem();
                    $transitem->product_id = $request->product_id;
                    $transitem->user_id = $request->user_id;
                    $transitem->price = $checkStock->selected_price;
                    $transitem->quantity = $request->quantity;
                    $transitem->save();
                }

                return ResponseFormatter::success(['is_success' => true], 'Berhasil ditambahkan ke cart');
            } else {
                // stock not avail
                return ResponseFormatter::success(['is_success' => true, 'stock_empty' => true], __('messages.not') . ' ' . __('messages.availibility'));

            }

        } else {
            return ResponseFormatter::success(['is_success' => false], 'Silahkan <a href="'. route('register') .'">Registrasi</a> dahulu');
        }

        // $user_id = Auth::user()->id;
    }

    public function add_to_wishlist(Request $request)
    {

        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);


        if($request->user_id != NULL || $request->user_id != '') {
            // check only for member can add to cart
            $user = Auth::loginUsingId($request->user_id);
            if ($user->role != 'MEMBER') {
                return ResponseFormatter::success(['is_success' => 'only_member'], 'Hanya boleh dilakukan oleh akun dengan role MEMBER');
            }


            // cek jika sudah ada barang yang sama, maka tambah qty by 1
            $productExist = Wishlist::where('user_id', Auth::loginUsingId($request->user_id)->id)->where('product_id', $request->product_id)->first();
            if ($productExist) {
                return ResponseFormatter::success(['is_success' => true, 'wishlist_existed' => true], 'Produk sudah ada di wishlist anda');
            } else {
                // Proses tambah ke wishlist
                $transitem = new Wishlist();
                $transitem->product_id = $request->product_id;
                $transitem->user_id = $request->user_id;
                $transitem->save();
            }

            return ResponseFormatter::success(['is_success' => true], 'Berhasil ditambahkan ke wishlist');

        } else {
            return ResponseFormatter::success(['is_success' => false], 'Silahkan <a href="'. route('register') .'">Registrasi</a> dahulu');
        }
    }

    public function update_cart_qty(Request $request)
    {
        $request->validate([
            'cart_id' => ['required', 'exists:transaction_items,id'],
            'new_qty' => ['required'],
            'user_id' => ['required']
        ]);

        $transitem = TransactionItem::findOrFail($request->cart_id);
        $transitem->quantity = $request->new_qty;
        $transitem->save();

        return ResponseFormatter::success([
            'is_success' => true,
            'transaction_item' => $transitem,
            'new_subtotal' => MyHelper::rupiah($transitem->subtotal)
        ], 'Data berhasil disimpan');
    }

    public function recalculate_total(Request $request)
    {
        $transaction_items = TransactionItem::with(['product'])->where('user_id', $request->user_id)->where('transaction_id', NULL)->get();
        $total = 0;
        foreach($transaction_items as $item) {
            $total += $item->subtotal;
        }
        return ResponseFormatter::success([
            'total' => MyHelper::rupiah($total)
        ], 'Kalkulasi Total berhasil dilakukan');
    }

    public function reload_cart_items(Request $request)
    {
        $transaction_items = TransactionItem::with(['product'])->where('user_id', $request->user_id)->where('transaction_id', NULL)->get();
        $total = 0;
        foreach($transaction_items as $item) {
            $total += $item->subtotal;
        }
        return ResponseFormatter::success([
            'items' => $transaction_items,
        ], 'Reload cart item berhasil dilakukan');
    }

    public function delete_cart_item(Request $request)
    {
        $request->validate([
            'cart_id' => ['required', 'exists:transaction_items,id'],
            'user_id' => ['required']
        ]);

        $transitem = TransactionItem::findOrFail($request->cart_id);
        $transitem->delete();

        return ResponseFormatter::success([
            'is_success' => true,
        ], 'Item berhasil dihapus');
    }

    public function delete_wishlist_item(Request $request)
    {
        $request->validate([
            'wishlist_id' => ['required', 'exists:wishlists,id'],
            'user_id' => ['required']
        ]);

        $wishlistItem = Wishlist::findOrFail($request->wishlist_id);
        $wishlistItem->delete();

        return ResponseFormatter::success([
            'is_success' => true,
        ], 'Wishlist berhasil dihapus');
    }

    public function check_transaction(Request $request)
    {
        $transaction_id =  $request->transaction_id;
        $transaction = Transaction::where('id', $transaction_id)->first();

        if ($transaction->trx_id != NULL) {
            // Create link payment IPayMu
            $client = new Client();
            $payLoad['transactionId'] = $transaction->trx_id;

            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'signature' => MyHelper::ipaymu_signature($payLoad),
                'va' => config('setting.ipaymu_va'),
                'timestamp' => Date('YmdHis')
            ];

            $response = $client->request('POST', config('setting.ipaymu_url') . '/api/v2/transaction', [
                'headers' => $headers,
                'body' => json_encode($payLoad)
            ]);

            $decodedResponse = json_decode($response->getBody());
            Log::info("IPAYMU Check Transaction");
            Log::info(json_encode($decodedResponse));
            TransactionLog::create([
                'title' => 'IPayMu Check Transaction',
                'response' => json_encode($decodedResponse)
            ]);

            // Update status transaksi
            $transaction->status = Str::lower($decodedResponse->Data->StatusDesc);
            $transaction->save();

            return ResponseFormatter::success($decodedResponse, 'Cek Status Transaksi berhasil dilakukan');
        } else {
            return ResponseFormatter::success($transaction, "Trx ID tidak ditemukan, silahkan lakukan pembayaran terlebih dahulu");
        }
    }

}
