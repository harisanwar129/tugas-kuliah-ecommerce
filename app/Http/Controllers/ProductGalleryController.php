<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use App\Models\Product;
use App\Models\ProductGallery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductGalleryController extends Controller
{
    public function index($product_id)
    {
        $product = Product::with(['product_galleries'])->where('id', $product_id)->first();
        $data = [
            'title' => 'Galeri Produk',
            'product' => $product,
            'datatable' => true
        ];

        return view('dashboard.product_gallery.index', $data);
    }

    public function store(Request $request, $product_id)
    {
        $request->validate([
            'thumbnail' => ['required', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $filename = Str::random(32) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $file_path = $request->file('thumbnail')->storeAs('public/uploads', $filename);
        }

        $productGallery = new ProductGallery();
        $productGallery->thumbnail = isset($file_path) ? $file_path : '';
        $productGallery->product_id = $product_id;
        $productGallery->save();

        return redirect()->back()->with('success', 'Berhasil menambahkan data galeri');
    }

    public function destroy($id)
    {
        try {
            $productGallery = ProductGallery::findOrFail($id);
            if (Storage::exists($productGallery->thumbnail)) {
                Storage::delete($productGallery->thumbnail);
            }
            $productGallery->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus data galeri.');
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
