<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'List Page',
            'page' => Page::all(),
            'datatable' => true
        ];

        return view('dashboard.page.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Page',
        ];

        return view('dashboard.page.create', $data);
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
        if (Page::where('slug', $slug)->first()) {
            $slug = $slug . '_' . Str::random(4);
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:30'],
            'konten' => ['required'],
        ]);

        $Page = new Page();
        $Page->nama = htmlspecialchars($request->nama);
        $Page->slug = Str::slug($Page->nama, '_');
        $Page->konten = $request->konten;
        $Page->save();

        return redirect()->back()->with('success', 'Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $Page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $Page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $Page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $Page)
    {
        $data = [
            'title' => 'Edit Page',
            'page' => $Page,
        ];

        return view('dashboard.page.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $Page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $Page)
    {
        $slug = Str::slug($request->nama);
        if (Page::where('slug', $slug)->first()) {
            $slug = $slug . '_' . Str::random(4);
        }

        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $Page->nama = htmlspecialchars($request->nama);
        $Page->slug = Str::slug($Page->nama, '_');
        $Page->konten = $request->konten;
        $Page->save();

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $Page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $Page)
    {
        $Page->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data.');
    }
}
