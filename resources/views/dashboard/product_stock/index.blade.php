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
                        <h4 class="header-title">Nama Produk: <span class="text-primary">{{ $product->name }}</span></h4>
                        <h4 class="header-title">Stok Terakhir: <span class="text-primary">{{ $product->stock }}</span></h4>
                        <p>Gambar Utama Produk</p>
                        <img src="{{ MyHelper::get_uploaded_file_url($product->thumbnail) }}" alt="picture" width="120" class="mb-2">

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

            </div>
        </div>
    </div> <!-- end row -->

    <!-- Modal -->
    <div id="custom-modal" class="modal-demo">
        <button type="button" class="close" onclick="Custombox.modal.close();">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
        <h4 class="custom-modal-title">Tambah Data</h4>
        <div class="custom-modal-text text-muted">
            <form class="row myForm" method="POST"
                action="{{ route('dashboard.master.product_stock.store', $product->id) }}" enctype="multipart/form-data">
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
                <div class="form-group col-md-8">
                    <label>Jumlah Stok</label>
                    <input type="number" min="1" class="form-control" name="qty" value="1">
                </div>

                <div class="form-group col-md-12">
                    <label>Keterangan</label>
                    <textarea name="notes" id="" cols="30" rows="2" class="form-control"></textarea>
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
