@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="header-title">Kategori Produk</h4>
                        <p class="sub-header">
                            Data kategori produk digunakan untuk mengelompokkan produk. <br><code>Grup Menu digunakan untuk mengelompokkan produk berdasarkan menu yang ada di halaman depan.</code>
                        </p>
                    </div>
                    <a href="{{ route('dashboard.master.product_category.create') }}" class="btn btn-primary btn-rounded waves-effect waves-light"><i class="mdi mdi-plus"></i> Tambah</a>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th width="20px">No</th>
                            <th width="10px">Nama Kategori</th>
                            <th width="20px">Slug</th>
                            <th width="150px">Gambar</th>
                            <th width="40px">Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($product_categories as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                   <td>{{ $item->slug }}</td>
                                <td>
                                    <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}" alt="picture" width="120">
                                </td>
                                <td>
                                    <div class="d-flex justify-content-start">
                                        <a href="{{ route('dashboard.master.product_category.edit', $item->id) }}" class="btn btn-sm waves-effect waves-light btn-warning mr-1"><i class="mdi mdi-wrench"></i></a>
                                        <form action="{{ route('dashboard.master.product_category.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm waves-effect waves-light btn-danger buttonDeletion"><i class="mdi mdi-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end row -->
@endsection
