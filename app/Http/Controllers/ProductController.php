<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Helpers\ResponseFormatter;
use App\Models\ErrorLog;
use App\Models\Product;
use App\Models\ProductCategory;
use App\ProductStock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    
    
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = Product::latest()->with(['user', 'product_category', 'product_galleries'])->select('products.*');
            if (Auth::user()->role == 'SELLER') {
                $query->where('products.user_id', Auth::user()->id);
            }

            return Datatables::of($query)
                        ->addIndexColumn()
                        ->editColumn('checklist', function($data) {
                            $id_product = $data->id;
                            return "<input type='checkbox' value='{$id_product}' id='check_{$id_product}' onchange='toggleProduct({$id_product})' />";
                        })
                        ->addColumn('thumbnail', function ($data) {
                            $route_gallery = route('dashboard.master.product_gallery.index', $data->id);
                            return '
                                <img src="' . MyHelper::get_uploaded_file_url($data->thumbnail) . '" alt="picture" width="140"> <br>
                                <a class="btn btn-sm btn-info waves-effect waves-light mt-1" href="'. $route_gallery .'"> <i class="mdi mdi-image-multiple mr-1"></i> <span>Galeri Produk ('. $data->product_galleries->count() .')</span> </a>
                                ';
                        })
                        ->addColumn('description', function ($data) {
                            return Str::limit($data->description, 20, '...');
                        })
                        ->editColumn('name', function($data) {
                            $str = Str::limit($data->name, 20, '...');
                            if($data->link_file != NULL) {
                                $str .= '<br><a href="'. $data->link_file .'">'.Str::limit($data->link_file, 20, '...') .'</a>';
                            }

                            $str .= '<br> <hr> Berat: '.MyHelper::rupiah($data->weight) .' Gram</a>';

                            return $str;
                        })
                        ->addColumn('price', function ($data) {
                            $price = '<b>Rp ' . MyHelper::rupiah($data->price) . '</b>';
                            if ($data->price_striked != NULL) {
                                $price = '<strike>Rp ' . MyHelper::rupiah($data->price) . '</strike><br> <span class="badge badge-danger badge-pill">Rp '. MyHelper::rupiah($data->price_striked ?? 0) .'</span>';
                            }
                            return $price;
                        })
                        ->editColumn('visible', function ($data) {
                            return MyHelper::get_visibility_status($data->visible) ;
                        })
                        ->addColumn('aksi', function ($data) {
                            $route_edit = route('dashboard.master.product.edit', $data->id);
                            $route_delete = route('dashboard.master.product.destroy', $data->id);
                            $route_stock = route('dashboard.master.product_stock.index', $data->id);
                            $aksi = '
                                <div class="d-flex justify-content-start">
                                    <a href=' . $route_edit . ' class="btn btn-sm waves-effect waves-light btn-warning mr-1"><i class="mdi mdi-wrench"></i></a>
                                    <form class="ml-1" action="' . $route_delete . '" method="post">
                                        <input type="hidden" name="_token" value="' . csrf_token() . '" />
                                        <input type="hidden" name="_method" value="DELETE" />
                                        
                                    </form>
                                    <a href="'. $route_stock .'" class="btn btn-sm waves-effect waves-light btn-info ml-2 buttonStockDetail">Stock</a>
                                </div>';
                            return $aksi;
                        })
                        ->rawColumns(['checklist', 'name', 'thumbnail', 'visible', 'aksi', 'price', 'price_striked'])
                        ->make(true);
        }

        $data = [
            'title' => 'List Produk',
            'datatable' => true
        ];

        return view('dashboard.product.index', $data);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            
        ]);
    }
    public function create()
    {
        $data = [
            'title' => 'Tambah Produk',
            'product_categories' => ProductCategory::all()
        ];

        return view('dashboard.product.create', $data);
    }

    public function store(Request $request)
    {
        // Kita cek slugnya jika sudah ada, maka tambahin random karakter agar dibuat unik
        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->first()) {
            $slug = $slug . '_' . Str::random(4);
        }

        $request->validate([
            'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'tags' => ['nullable'],
            'price' => ['required'],
            'weight' => ['required', 'min:1', 'max:20000', 'integer'],
            'price_striked' => ['nullable'],
            'product_category_id' => ['nullable'],
            'stock' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $filename = Str::random(32) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $file_path = $request->file('thumbnail')->storeAs('public/uploads', $filename);
        }

        $Product = new Product();
        $Product->user_id = Auth::user()->id;
        $Product->name = $request->name;
        $Product->slug = $slug;
        $Product->weight = $request->weight;
        $Product->description = $request->description;
        $Product->link_file = $request->link_file;
        $Product->tags = $request->tags;
        $Product->stock = $request->stock;
        $Product->product_category_id = $request->product_category_id;
        $Product->thumbnail = isset($file_path) ? $file_path : '';
        $Product->price = preg_replace('/[^0-9.]+/', '', $request->price);
        if ($request->has('discount')) {
            $Product->is_discount = 1;
            $Product->price_striked = preg_replace('/[^0-9.]+/', '', $request->price_striked ?? 0);
        }
        $Product->visible = $request->visible ?? 0;
        $Product->save();

        // store histori stok awal produk ini
        $productStock = new ProductStock();
        $productStock->product_id = $Product->id;
        $productStock->qty = $request->stock;
        $productStock->notes = 'Stok Awal';
        $productStock->action = 'in';
        $productStock->save();

        return redirect()->back()->with('success', 'Berhasil menambahkan produk');
    }

    public function destroy($id)
    {
        $Product = Product::findOrFail($id);
        if ($Product->user_id != Auth::user()->id) {
            return abort(403, 'Akses dilarang! Produk bukan milik akun anda');
        }

        // Jika sudah pernah ada transaksi maka ga boleh dihapus, cukup invisible kan saja
        if ($Product->transaction_items) {
            return abort(403, "Produk tidak bisa dihapus karena telah ada data transaksi, silahkan non-aktifkan status visibilitas produk.");
        }

        try {
            $Product = $Product;
            if (Storage::exists($Product->thumbnail)) {
                Storage::delete($Product->thumbnail);
            }
            $Product->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus data.');
        } catch (Exception $e) {
            ErrorLog::create([
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'stack_trace' => json_encode($e->getTrace()),
                'url' => url()->full(),
                'user_id' => Auth::user()->id,
            ]);

            return redirect()->back()->with([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function mass_destroy(Request $request)
    {
        if (isset($request->products)) {
            foreach($request->products as $product_id) {
                $Product = Product::with('sold_items')->where('id', $product_id)->first();
                // return ResponseFormatter::success($Product, 'dtest');

                // Jika sudah pernah ada transaksi maka ga boleh dihapus, cukup invisible kan saja
                if ($Product->sold_items->count() > 0) {
                    return ResponseFormatter::success(['has_sold_items' => true], 'Produk tidak bisa dihapus karena telah ada data transaksi, silahkan non-aktifkan status visibilitas produk.');
                    // return abort(403, "Produk tidak bisa dihapus karena telah ada data transaksi, silahkan non-aktifkan status visibilitas produk.");
                }

                try {
                    $Product = $Product;
                    if (Storage::exists($Product->thumbnail)) {
                        Storage::delete($Product->thumbnail);
                    }
                    $Product->delete();
                } catch (Exception $e) {
                    ErrorLog::create([
                        'exception_class' => get_class($e),
                        'message' => $e->getMessage(),
                        'stack_trace' => json_encode($e->getTrace()),
                        'url' => url()->full(),
                        'user_id' => Auth::user()->id,
                    ]);

                    return redirect()->back()->with([
                        'error' => $e->getMessage()
                    ]);
                }
            }
            return ResponseFormatter::success([], 'Berhasil menghapus data produk');
        }

    }

    public function edit($id)
    {
        // Kita cek user nya apakah barang dia atau bukan
        $Product = Product::findOrFail($id);
        if ($Product->user_id != Auth::user()->id) {
            return abort(403, 'Akses dilarang! Produk bukan milik akun anda');
        }

        $data = [
            'title' => 'Edit Produk',
            'product' => $Product,
            'product_categories' => ProductCategory::all()
        ];

        return view('dashboard.product.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // Kita cek slugnya jika sudah ada, maka tambahin random karakter agar dibuat unik
        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->first()) {
            $slug = $slug . '_' . Str::random(4);
        }

        $request->validate([
            'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'weight' => ['required', 'min:1', 'max:20000', 'integer'],
            'tags' => ['nullable'],
            'price' => ['required'],
            'price_striked' => ['nullable'],
            'product_category_id' => ['nullable'],
        ]);

        $Product = Product::findOrFail($id);
        $Product->name = $request->name;
        $Product->slug = $slug;
        $Product->description = $request->description;
        $Product->weight = $request->weight;
        $Product->link_file = $request->link_file;
        $Product->tags = $request->tags;
        $Product->product_category_id = $request->product_category_id;
        $Product->price = preg_replace('/[^0-9.]+/', '', $request->price);
        if ($request->has('discount')) {
            $Product->is_discount = 1;
            $Product->price_striked = preg_replace('/[^0-9.]+/', '', $request->price_striked ?? 0);
        } else {
            $Product->is_discount = 0;
        }

        if ($request->hasFile('thumbnail')) {
            if ($Product->thumbnail != '') {
                Storage::delete($Product->thumbnail);
            }
            $filename = Str::random(32) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $file_path = $request->file('thumbnail')->storeAs('public/uploads', $filename);
        }
        $Product->thumbnail = isset($file_path) ? $file_path : $Product->thumbnail;
        $Product->visible = $request->visible ?? 0;
        $Product->save();

        return redirect()->back()->with('success', 'Produk berhasil diupdate');
    }
}
