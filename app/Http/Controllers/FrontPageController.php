<?php

namespace App\Http\Controllers;

use App\Courier;
use App\Helpers\MyHelper;
use App\Models\ErrorLog;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Slider;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\TransactionLog;
use App\Models\User;
use App\Page;
use App\ProductStock;
use App\Province;
use App\SettingWeb;
use App\Wishlist;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class FrontPageController extends Controller
{
    public function index()
    {
        $best_shop = User::where('role', 'SELLER')->with(['products'])->withCount(['transaction_items'])->orderBy('transaction_items_count', 'DESC')->first();
        $data = [
            'slider' => Slider::orderBy('urutan', 'ASC')->where('is_active', 1)->get(),
            'new_products' => Product::where('visible', 1)->latest()->take(12)->get(),
            'discount_products' => Product::where('visible', 1)->where('is_discount', 1)->take(12)->get(),
            'popular_products' => Product::where('visible', 1)->orderBy('views', 'DESC')->take(12)->get(),
            'best_shop' => $best_shop,
            'categories' => ProductCategory::where('thumbnail', '!=', NULL)->get()
        ];

        return view('welcome', $data);
    }

    public function show_product($slug)
    {
        $product = Product::where('visible', 1)->with(['product_category', 'user', 'product_galleries'])->where('slug', $slug)->first();
        if (!$product) {
            return abort(404, 'Halaman tidak ditemukan');
        }
        $related_products = Product::where('product_category_id', $product->product_category_id)
                                    ->take(3)
                                    ->get();

        $data = [
            'product' => $product,
            'related_products' => $related_products,
            'title' => $product->name,
            'popular_products' => Product::where('visible', 1)->orderBy('views', 'DESC')->take(6)->get(),
        ];

        $product->views += 1;
        $product->save();

        return view('front.product.show', $data);
    }

    public function my_cart()
    {
        $data = [
            'title' => 'Cart Saya',
            'items' => TransactionItem::with(['product'])->where('user_id', Auth::user()->id)->where('transaction_id', NULL)->paginate(1)
        ];

        return view('front.my_cart', $data);
    }

    public function my_wishlist()
    {
        $data = [
            'title' => 'Wishlist Saya',
            'items' => Wishlist::with(['product'])->where('user_id', Auth::user()->id)->paginate(1)
        ];

        return view('front.my_wishlist', $data);
    }

    public function checkout()
    {
        $couriers = Courier::all();
        $provinces = Province::all();
        $setting = SettingWeb::where('id', 1)->first();
        $data = [
            'title' => 'Checkout',
            'couriers' => $couriers,
            'provinces' => $provinces,
            'setting' => $setting,
            'items' => TransactionItem::with(['product'])->where('user_id', Auth::user()->id)->where('transaction_id', NULL)->get(),
            'user' => Auth::user(),
        ];

        return view('front.checkout', $data);
    }

    public function store_checkout(Request $request)
    {

        // Check Ipaymu setting
        $va           = config('setting.ipaymu_va');
        $secret       = config('setting.ipaymu_api');
        $url       = config('setting.ipaymu_url');

        if($va == '' || $secret == '' || $url == '') {
            return redirect()->back()->with('error', 'Aplikasi Belum Siap Digunakan | API not set');
        }

        if (config('setting.city_id') == NULL) {
            return redirect()->back()->with('error', 'Aplikasi Belum Siap Digunakan | Kota Pengirim (Aplikasi) Belum di set');
        }


        $request->validate([
            'customer_name' => ['required', 'max:255'],
            'address' => ['nullable'],
            'phone' => ['required'],
            'email' => ['required'],
            'notes' => ['nullable'],
        ]);

        // Create link payment IPayMu
        $client = new Client();
        // set the products from carts
        $transaction_items = TransactionItem::where('user_id', Auth::user()->id)->where('transaction_id', NULL)->get();

        // CEK JIKA CART KOSONG TOLAK CHECKOUT
        if ($transaction_items->count() == 0) {
            return redirect()->back()->with('error', 'Checkout tidak dapat dilakukan, cart anda masih kosong!');
        }

        if (!isset($request->courier)) {
            return redirect()->back()->with('error', 'Checkout tidak dapat dilakukan, pilih ongkirnya dahulu');
        }

        $total = 0;
        $weightTotal = 0;
        foreach($transaction_items as $item) {
            // check product stock to make sure the quantity of cart not exceed its current product stock
            $checkStock = $item->product;
            if ($checkStock->stock < $item->quantity) {
                return redirect()->back()->with('error',  __('messages.stock') . ' ' . __('messages.not') . ' ' . __('messages.availibility') . ' ' . __('messages.on_product') . $item->product->name . '. ' . __('messages.availibility') . ': ' . $checkStock->stock );
            }
            $total += $item->subtotal;
            $payLoad['product'][] = $item->product->name;
            $payLoad['qty'][] = $item->quantity;
            $payLoad['price'][] = $item->product->selected_price;
            $weightTotal += $item->product->weight * $item->quantity;
        }

        // Set shipping price to ipaymu payment
        $payLoad['product'][] = 'ONGKIR: ' . Str::upper($request->courier) . '('.$request->shipping_service_name.')';
        $payLoad['qty'][] = 1;

        // Harga ongkir re-fetch dari shipping service code ke rajaongkir lagi
        try {
            $ongkir = RajaOngkir::ongkosKirim([
                'origin' => config('setting.city_id'),
                'destination' => $request->city_to,
                'weight' => $weightTotal,
                'courier' => $request->courier
            ])->get();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Checkout tidak dapat dilakukan: ' . $e->getMessage());
        }

        $shippingOk = false;
        foreach($ongkir[0]['costs'] as $cost) {
            if ($cost['service'] == $request->shipping_service_code) {
                $shipping_service_price = $cost['cost'][0]['value'];
                $payLoad['price'][] = $shipping_service_price;
                $shippingOk = true;
            }
        }

        if (!$shippingOk) {
            return redirect()->back()->with('error', 'Checkout tidak dapat dilakukan, ongkir tidak valid');
        }

        $payLoad['returnUrl'] = route('ipaymu.return');
        $payLoad['notifyUrl'] = route('v1.ipaymu.notify');
        $payLoad['cancelUrl'] = route('ipaymu.cancel');
        $payLoad['buyerName'] = $request->customer_name;
        $payLoad['buyerEmail'] = $request->email;
        $payLoad['buyerPhone'] = $request->phone;
        // dd($payLoad);

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'signature' => MyHelper::ipaymu_signature($payLoad),
            'va' => $va,
            'timestamp' => Date('YmdHis')
        ];

        $Transaction = new Transaction();
        $Transaction->user_id = Auth::user()->id;
        $Transaction->customer_name = $request->customer_name;
        $Transaction->email = $request->email;
        $Transaction->phone = $request->phone;
        $Transaction->notes = $request->notes;
        $Transaction->address = $request->address;

        // Informasi Shipping
        $Transaction->shipping_courier_code = $request->courier;
        $Transaction->shipping_service_name = $request->shipping_service_name;
        $Transaction->shipping_service_code = $request->shipping_service_code;
        $Transaction->shipping_service_price = $shipping_service_price;
        $Transaction->shipping_city_from = config('setting.city_id');
        $Transaction->shipping_city_to = $request->city_to;




        $Transaction->status = 'PENDING';
        $Transaction->return_url = route('ipaymu.return');
        $Transaction->notify_url = route('v1.ipaymu.notify');
        $Transaction->cancel_url = route('ipaymu.cancel');

        // check if the price total is below 5000, then do not create a payment link
        if ($total > 5000) {
            try {
                $ipaymu_url = $url . '/api/v2/payment';
                $response = $client->request('POST', $ipaymu_url, [
                    'headers' => $headers,
                    'body' => json_encode($payLoad)
                ]);
            } catch (Exception $e) {
                ErrorLog::create([
                    'exception_class' => get_class($e),
                    'message' => $e->getMessage(),
                    'stack_trace' => json_encode($e->getTrace()),
                    'url' => url()->full(),
                    'user_id' => Auth::user()->id,
                ]);

                return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses checkout:' . $e->getMessage());
            }

            $decodedResponse = json_decode($response->getBody());
            // set the transaction to ipaymu, fill those column from the response
            $Transaction->payment_method = 'IPAYMU';
            $Transaction->subtotal = $total;
            $Transaction->sid = $decodedResponse->Data->SessionID;
            $Transaction->payment_url = $decodedResponse->Data->Url;
            $Transaction->status_code = 0;

            TransactionLog::create([
                'title' => 'IPayMu RedirectPayment',
                'response' => json_encode($decodedResponse)
            ]);
        } else {
            $decodedResponse = [
                'message' => 'Pesanan kurang dari 5000, maka tidak ada link pembayaran'
            ];
            $Transaction->payment_method = 'FREE';
            $Transaction->subtotal = $total;
            $Transaction->sid = NULL;
            $Transaction->payment_url = NULL;
            $Transaction->status_code = 1;
            $Transaction->notify_paid = 1;
            $Transaction->status = 'berhasil';

            TransactionLog::create([
                'title' => 'Free Transaction',
                'response' => json_encode($decodedResponse)
            ]);
        }


        $Transaction->save();

        // Set semua cart dengan transaction_id terbaru
        foreach($transaction_items as $item) {
            $item->transaction_id = $Transaction->id;
            $item->price = $item->product->selected_price;
            $item->save();

            // update stok produk, kurangi berdasarkan jumlah yang dibeli
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();

            // catat history stok barang ini bahwa telah terjual
            $productStock = new ProductStock();
            $productStock->product_id = $product->id;
            $productStock->qty = $item->quantity;
            $productStock->action = 'sold';
            $productStock->notes = 'Produk Terjual: ' . $Transaction->sid;
            $productStock->transaction_id = $Transaction->id;
            $productStock->save();
        }

        return redirect()->route('front.pesanan_saya')->with('success', 'Berhasil membuat pesanan');
    }

    public function my_account()
    {
        $data = [
            'title' => 'Akun Saya',
        ];
        return view('front.my_account', $data);
    }

    public function pesanan_saya(Request $request)
    {
        $keyword = $request->keyword;
        $orders = Transaction::with(['transaction_items'])->where('user_id', Auth::user()->id)->orderBy('id', 'DESC');
        if ($request->has('keyword')) {
            $orders->where(function($query) use($keyword) {
                $query->orWhere('sid', 'LIKE', '%'. $keyword .'%');
                $query->orWhere('status', 'LIKE', '%'. $keyword .'%');
            });
        }
        $data = [
            'title' => __('messages.my_orders'),
            'orders' => $orders->paginate(10),
            'keyword' => $keyword
        ];

        return view('front.pesanan_saya', $data);
    }

    public function detail_pesanan_saya($id)
    {
        $data = [
            'title' => 'Detail ' . __('messages.my_orders'),
            'setting' => SettingWeb::where('id', 1)->first(),
            'transaction' => Transaction::with(['transaction_items'])->where('user_id', Auth::user()->id)->where('id', $id)->first()
        ];

        return view('front.detail_pesanan_saya', $data);
    }

    public function detail_pesanan_saya_print($id)
    {
        $data = [
            'title' => 'Cetak Invoice ' . __('messages.my_orders'),
            'setting' => SettingWeb::where('id', 1)->first(),
            'transaction' => Transaction::with(['transaction_items'])->where('user_id', Auth::user()->id)->where('id', $id)->first()
        ];

        return view('front.detail_pesanan_saya_print', $data);
    }

    public function my_profile(Request $request)
    {
        if ($request->isMethod('put')) {
            $user_id = Auth::user()->id;
            $request->validate([
                'avatar' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
                'name' => ['required', 'max:255'],
                'address' => ['nullable'],
                'email' => ['required', 'email'],
                'phone' => ['nullable', 'digits_between:10,15'],
                'username' => ['required', 'unique:users,username,' . $user_id],
            ]);

            $User = User::findOrFail($user_id);
            $User->username = $request->username;
            $User->name = $request->name;
            $User->email = $request->email;
            $User->phone = $request->phone;
            $User->address = $request->address;
            if ($request->hasFile('avatar')) {
                if ($User->avatar != '') {
                    Storage::delete($User->avatar);
                }
                $filename = Str::random(32) . '.' . $request->file('avatar')->getClientOriginalExtension();
                $file_path = $request->file('avatar')->storeAs('public/uploads', $filename);
            }
            $User->avatar = isset($file_path) ? $file_path : $User->avatar;

            // Check if password is filled, then validate those 2 password\
            if($request->password != '') {
                // confirm both pass
                if($request->password != $request->password_confirmation) {
                    return redirect()->back()->with('error', 'Konfirmasi Password tidak cocok');
                }

                $User->password = Hash::make($request->password);
            }

            $User->save();

            return redirect()->back()->with('success', 'Profil berhasil diupdate');
        }

        $data = [
            'title' => 'Profil Saya',
            'user' => Auth::user()
        ];

        return view('front.my_profile', $data);
    }

    public function page_show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $data = [
            'title' => $page->judul,
            'page' => $page
        ];


        return view('front.page_detail', $data);
    }

    public function shopping()
    {
        $data = [
            'products' => Product::where('visible', 1)->latest()->paginate(8),
            'categories' => ProductCategory::has('products')->get(),
            'shops' => User::where('role', 'SELLER')->has('products')->take(6)->get(),
        ];

        return view('front.shopping', $data);
    }

    public function shopping_category($slug)
    {
        $productCategory = ProductCategory::where('slug', $slug)->first();
        $data = [
            'products' => Product::where('visible', 1)->latest()->where('product_category_id', $productCategory->id)->paginate(8),
            'categories' => ProductCategory::has('products')->get(),
            'product_category' => $productCategory,
            'shops' => User::where('role', 'SELLER')->has('products')->take(6)->get(),
        ];

        return view('front.shopping_category', $data);
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $data = [
            'products' => Product::where('visible', 1)->latest()->where(function ($query) use($keyword) {
                $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('description', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('tags', 'LIKE', '%' . $keyword . '%');
            })->paginate(8),
            'categories' => ProductCategory::has('products')->get(),
            'shops' => User::where('role', 'SELLER')->has('products')->take(6)->get(),
            'keyword' => $keyword
        ];

        return view('front.shopping_search', $data);
    }

    public function shopping_shop($username)
    {
        $shop = User::where('username', $username)->where('role', 'SELLER')->first();
        $data = [
            'products' => Product::where('visible', 1)->latest()->where('user_id', $shop->id)->paginate(8),
            'categories' => ProductCategory::has('products')->get(),
            'shop' => $shop,
            'shops' => User::where('role', 'SELLER')->has('products')->take(6)->get(),
        ];

        return view('front.shopping_shop', $data);
    }

    public function shopping_price_range(Request $request, $from = 0, $to = 1000000)
    {
        if (!is_numeric($from)) {
            return abort(404, 'URL Salah!');
        }
        if (!is_numeric($to)) {
            return abort(404, 'URL Salah!');
        }

        $products = Product::where('visible', 1)->get();

        $data = [
            'products' => $products->where('selected_price', '>=', $from)->where('selected_price', '<=', $to)->paginate(8),
            'categories' => ProductCategory::has('products')->get(),
            'shops' => User::where('role', 'SELLER')->has('products')->take(6)->get(),
            'from' => $from,
            'to' => $to,
        ];

        return view('front.shopping_price_range', $data);
    }

    public function cancel_pesanan(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->user_id != Auth::user()->id) {
            abort(403, 'Akses dilarang! bukan pesanan akun anda.');
        }

        $transaction->status = 'batal';
        $transaction->save();

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan');
    }

}
