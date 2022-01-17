@extends('layouts.admin')

@push('css')
    <!-- Custom box css -->
    <link href="{{ asset('admin') }}/libs/custombox/custombox.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="header-title">Galeri Produk: <span class="text-primary">{{ $product->name }}</span></h4>
                        <p>Gambar Utama Produk</p>
                        <img src="{{ MyHelper::get_uploaded_file_url($product->thumbnail) }}" alt="picture" width="120" class="mb-2">
                        <p class="sub-header">
                            <code>Galeri produk pada tabel dibawah ini akan tampil pada halaman detail produk halaman depan sebagai slider </code>
                        </p>
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
                    </div>
                    <a href="#custom-modal" class="btn btn-primary btn-rounded waves-effect waves-light"
                        data-animation="slip" data-plugin="custommodal" data-overlaySpeed="100"
                        data-overlayColor="#36404a"><i class="mdi mdi-plus"></i> Tambah</a>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th width="40">Aksi</th>
                        </tr>
                    </thead>


                    <tbody>
                        @foreach ($product->product_galleries as $item)
                            <tr>
                                <td>
                                    <img src="{{ MyHelper::get_uploaded_file_url($item->thumbnail) }}" alt="picture"
                                        width="120">
                                </td>
                                <td>
                                    <div class="d-flex justify-content-start">
                                        <form action="{{ route('dashboard.master.product_gallery.destroy', $item->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm waves-effect waves-light btn-danger buttonDeletion"><i
                                                    class="mdi mdi-trash-can"></i></button>
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

    <!-- Modal -->
    <div id="custom-modal" class="modal-demo">
        <button type="button" class="close" onclick="Custombox.modal.close();">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
        <h4 class="custom-modal-title">Tambah Galeri</h4>
        <div class="custom-modal-text text-muted">
            <form class="row myForm" method="POST"
                action="{{ route('dashboard.master.product_gallery.store', $product->id) }}" enctype="multipart/form-data">
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
                <div class="form-group col-md-12">
                    <label>Thumbnail</label>
                    <input type="file" class="form-control" name="thumbnail">
                    <small class="text-muted">Disarankan ukuran: 600 x 600</small>
                </div>

                <div class="form-group col-md-12 text-right">
                    <button type="submit" class="btn btn-primary formSubmitter">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <!-- Modal-Effect -->
    <script src="{{ asset('admin') }}/libs/custombox/custombox.min.js"></script>
@endpush
