<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $data = [
            'title' =>  'List Menu',
            'menu' => Menu::with(['sub_menus'])->orderBy('urutan', 'ASC')->get(),
            'datatable' => true
        ];

        return view('dashboard.menu.index', $data);
    }

    public function create()
    {
        $data = [
            'title' =>'Tambah Menu',
        ];

        return view('dashboard.menu.create', $data);
    }

    public function store(Request $request)
    {
        // Kita cek slugnya jika sudah ada, maka tambahin random karakter agar dibuat unik
        $slug = Str::slug($request->nama);
        if (Menu::where('slug', $slug)->first()) {
            $slug = $slug . '_' . Str::random(4);
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:15'],
            'link' => ['required', 'string','max:100'],
        ]);

        $Menu = new Menu();
        $Menu->nama = htmlspecialchars($request->nama);
        $Menu->slug = Str::slug($request->nama);
        $Menu->link = $request->link;
        $Menu->position = $request->position;
        $Menu->visible = isset($request->visible) ? $request->visible : 0;
        $Menu->urutan = isset($request->urutan) ? $request->urutan : 0;
        $Menu->save();

        return redirect()->back()->with('success', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $Menu = Menu::findOrFail($id);
        $data = [
            'title' => 'Edit Menu',
            'menu' => $Menu,
        ];

        return view('dashboard.menu.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // Kita cek slugnya jika sudah ada, maka tambahin random karakter agar dibuat unik
        $slug = Str::slug($request->nama);
        if (Menu::where('slug', $slug)->first()) {
            $slug = $slug . '_' . Str::random(4);
        }
        $Menu = Menu::findOrFail($id);
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'link' => ['required', 'string'],
        ]);

        $Menu->nama = htmlspecialchars($request->nama);
        $Menu->link = $request->link;
        $Menu->slug = Str::slug($request->nama);
        $Menu->position = $request->position;
        $Menu->visible = isset($request->visible) ? $request->visible : 0;
        $Menu->urutan = isset($request->urutan) ? $request->urutan : 0;
        $Menu->save();

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $Menu = Menu::findOrFail($id);
        $Menu->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data.');
    }
}
