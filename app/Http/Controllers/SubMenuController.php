<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Models\ProductCategory;
use App\Page;
use App\SubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => ' List SubMenu',
            'sub_menu' => SubMenu::with(['menu'])->get(),
            'datatable' => true
        ];

        return view('dashboard.sub_menu.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => ' Tambah Sub Menu',
            'pages' => Page::all(),
            'categories' => ProductCategory::all(),
            'menu' => Menu::all(),
        ];

        return view('dashboard.sub_menu.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slug = Str::slug($request->nama);
        if (SubMenu::where('slug', $slug)->first()) {
            $slug = $slug . '_' . Str::random(4);
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string'],
            'menu_id' => ['nullable'],
        ]);

        $SubMenu = new SubMenu();
        $SubMenu->nama = htmlspecialchars($request->nama);
        if ($request->tipe_menu == 'page') {
            $SubMenu->page_id = $request->page_id;
            $SubMenu->link = NULL;
            $SubMenu->product_category_id = NULL;
        } elseif($request->tipe_menu == 'product_category') {
            $SubMenu->page_id = NULL;
            $SubMenu->link = NULL;
            $SubMenu->product_category_id = $request->product_category_id;
        } else {
            $SubMenu->page_id = NULL;
            $SubMenu->link = $request->link;
            $SubMenu->product_category_id = NULL;
        }
        $SubMenu->menu_id = $request->menu_id;
        $SubMenu->slug = $slug;
        $SubMenu->visible = isset($request->visible) ? $request->visible : 0;
        $SubMenu->urutan = isset($request->urutan) ? $request->urutan : 0;
        $SubMenu->save();

        return redirect()->back()->with('success', 'Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubMenu  $SubMenu
     * @return \Illuminate\Http\Response
     */
    public function show(SubMenu $SubMenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubMenu  $SubMenu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $SubMenu = SubMenu::findOrFail($id);
        $data = [
            'title' => 'Edit Sub Menu',
            'sub_menu' => $SubMenu,
            'pages' => Page::all(),
            'categories' => ProductCategory::all(),
            'menu' => Menu::all(),
        ];

        return view('dashboard.sub_menu.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubMenu  $SubMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $SubMenu = SubMenu::findOrFail($id);

        $slug = Str::slug($request->nama);
        if (SubMenu::where('slug', $slug)->first()) {
            $slug = $slug . '_' . Str::random(4);
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string'],
            'page_id' => ['nullable'],
            'menu_id' => ['nullable'],
        ]);

        $SubMenu->nama = htmlspecialchars($request->nama);
        $SubMenu->menu_id = $request->menu_id;
        $SubMenu->slug = $slug;
        if ($request->tipe_menu == 'page') {
            $SubMenu->page_id = $request->page_id;
            $SubMenu->link = NULL;
            $SubMenu->product_category_id = NULL;
        } elseif($request->tipe_menu == 'product_category') {
            $SubMenu->page_id = NULL;
            $SubMenu->link = NULL;
            $SubMenu->product_category_id = $request->product_category_id;
        } else {
            $SubMenu->page_id = NULL;
            $SubMenu->link = $request->link;
            $SubMenu->product_category_id = NULL;
        }
        $SubMenu->visible = isset($request->visible) ? $request->visible : 0;
        $SubMenu->urutan = isset($request->urutan) ? $request->urutan : 0;
        $SubMenu->save();

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubMenu  $SubMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $SubMenu = SubMenu::findOrFail($id);
        $SubMenu->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data.');
    }
}
