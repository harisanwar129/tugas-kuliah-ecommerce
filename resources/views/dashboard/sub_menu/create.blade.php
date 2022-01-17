@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" id="myForm" method="POST"
                    action="{{ route('dashboard.master.submenu.store') }}" enctype="multipart/form-data">
                    @if ($errors->any())
                        <div class="col-md-12">
                            <ul>
                                @foreach ($errors->all() as $item)
                                    <li class="text-danger">
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Parent Menu <span class="text-danger">*</span></label>
                            <select name="menu_id" id="menu_id" class="form-control">
                                @foreach ($menu as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tipe Menu <span class="text-danger">*</span></label>
                            <select name="tipe_menu" id="tipe_menu" class="form-control">
                                <option value="link">Link</option>
                                <option value="page">Halaman</option>
                                <option value="product_category">Kategori Produk</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Nama Sub Menu <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                        </div>
                    </div>
                    <div class="col-md-8 div_link">
                        <div class="form-group">
                            <label>Link <span class="text-danger">*</span></label>
                            <input type="text" name="link" class="form-control" value="{{ old('link') }}">
                        </div>
                    </div>
                    <div class="col-md-8 div_page d-none">
                        <div class="form-group">
                            <label>Kaitkan Halaman <span class="text-danger">*</span></label>
                            <select name="page_id" id="page_id" class="form-control">
                                @foreach ($pages as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 div_prodcat d-none">
                        <div class="form-group">
                            <label>Kaitkan Kategori Produk <span class="text-danger">*</span></label>
                            <select name="product_category_id" id="product_category_id" class="form-control">
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Urutan <span class="text-danger">*</span></label>
                            <input type="number" name="urutan" class="form-control" value="{{ old('urutan') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tampil ?</label>
                            <p class="demo">
                                <input type="checkbox" name="visible" value="1" checked data-toggle="toggle"
                                    data-onstyle="success" data-style="btn-round">
                            </p>
                        </div>
                    </div>

                    <div class="form-group col-md-12 text-right">
                        <button type="submit" class="btn btn-primary formSubmitter">Simpan</button>
                    </div>
                </form>
            </div>
        </div><!-- end col -->
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#tipe_menu').change(function() {
                var tipe_menu = $(this).val();
                if (tipe_menu == 'page') {
                    $('.div_page').removeClass('d-none').addClass('block');
                    $('.div_link').removeClass('block').addClass('d-none');
                    $('.div_prodcat').removeClass('block').addClass('d-none');
                } else if(tipe_menu == 'product_category') {
                    $('.div_page').removeClass('block').addClass('d-none');
                    $('.div_link').removeClass('block').addClass('d-none');
                    $('.div_prodcat').removeClass('d-none').addClass('block');
                } else {
                    $('.div_page').removeClass('block').addClass('d-none');
                    $('.div_link').addClass('block').removeClass('d-none');
                    $('.div_prodcat').removeClass('block').addClass('d-none');
                }
            })
        })
    </script>
@endpush
