<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Models\ErrorLog;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $productCategory = ProductCategory::latest()->get();
        $data = [
            'title' => 'List Kategori Produk',
            'product_categories' => $productCategory,
            'datatable' => true
        ];
        return view('dashboard.product_category.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori Produk',
        ];

        return view('dashboard.product_category.create', $data);
    }

    public function store(Request $request)
    {
        $slug = Str::slug($request->name);
        if (ProductCategory::where('slug', $slug)->first()) {
            return redirect()->back()->with('error', 'Slug sudah terdaftar, silahkan gunakan nama lain');
        }

        $request->validate([
            'thumbnail' => ['required', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'name' => ['required', 'max:15'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $filename = Str::random(32) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $file_path = $request->file('thumbnail')->storeAs('public/uploads', $filename);
        }


        $productCategory = new ProductCategory();
        $productCategory->name = $request->name;
        $productCategory->slug = $slug;
        $productCategory->thumbnail = isset($file_path) ? $file_path : '';
        $productCategory->save();

        return redirect()->back()->with('success', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kategori Produk',
            'productCategory' => ProductCategory::findOrFail($id),
        ];

        return view('dashboard.product_category.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'name' => ['required', 'max:100'],
            'slug' => ['required', 'unique:product_categories,slug,' . $id],
        ]);

        $ProductCategory = ProductCategory::findOrFail($id);
        $ProductCategory->name = $request->name;
        $ProductCategory->slug = $request->slug;

        if ($request->hasFile('thumbnail')) {
            if ($ProductCategory->thumbnail != '') {
                Storage::delete($ProductCategory->thumbnail);
            }
            $filename = Str::random(32) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $file_path = $request->file('thumbnail')->storeAs('public/uploads', $filename);
        }
        $ProductCategory->thumbnail = isset($file_path) ? $file_path : $ProductCategory->thumbnail;
        $ProductCategory->save();

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        try {
            $ProductCategory = ProductCategory::findOrFail($id);
            if (Storage::exists($ProductCategory->thumbnail)) {
                Storage::delete($ProductCategory->thumbnail);
            }
            $ProductCategory->delete();
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
}
