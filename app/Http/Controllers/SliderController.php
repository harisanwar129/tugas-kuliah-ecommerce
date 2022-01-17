<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use App\Models\Slider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index()
    {
        $Slider = Slider::latest()->get();
        $data = [
            'title' => 'List Slider',
            'slider' => $Slider,
            'datatable' => true
        ];
        return view('dashboard.slider.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Slider',
        ];

        return view('dashboard.slider.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'thumbnail' => ['required', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'title' => ['required', 'max:100'],
            'subtitle' => ['nullable', 'max:100'],
            'button_text' => ['nullable'],
            'link' => ['nullable'],
            'urutan' => ['nullable'],
            'is_active' => ['nullable'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $filename = Str::random(32) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $file_path = $request->file('thumbnail')->storeAs('public/uploads', $filename);

        }

        $Slider = new Slider();
        $Slider->title = $request->title;
        $Slider->urutan = $request->urutan ?? 1;
        $Slider->is_active = $request->is_active ?? 0;
        $Slider->subtitle = $request->subtitle;
        $Slider->button_text = $request->button_text;
        $Slider->link = $request->link;
        $Slider->thumbnail = isset($file_path) ? $file_path : '';
        $Slider->save();

        return redirect()->back()->with('success', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Slider',
            'slider' => Slider::findOrFail($id),
        ];

        return view('dashboard.slider.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'title' => ['required', 'max:100'],
            'subtitle' => ['nullable', 'max:100'],
            'button_text' => ['nullable'],
            'link' => ['nullable'],
            'urutan' => ['nullable'],
            'is_active' => ['nullable'],
        ]);

        $Slider = Slider::findOrFail($id);
        $Slider->title = $request->title;
        $Slider->urutan = $request->urutan ?? 1;
        $Slider->is_active = $request->is_active ?? 0;
        $Slider->subtitle = $request->subtitle;
        $Slider->button_text = $request->button_text;
        $Slider->link = $request->link;

        if ($request->hasFile('thumbnail')) {
            if ($Slider->thumbnail != '') {
                Storage::delete($Slider->thumbnail);
            }
            $filename = Str::random(32) . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $file_path = $request->file('thumbnail')->storeAs('public/uploads', $filename);
        }
        $Slider->thumbnail = isset($file_path) ? $file_path : $Slider->thumbnail;
        $Slider->save();

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        try {
            $Slider = Slider::findOrFail($id);
            if (Storage::exists($Slider->thumbnail)) {
                Storage::delete($Slider->thumbnail);
            }
            $Slider->delete();
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
