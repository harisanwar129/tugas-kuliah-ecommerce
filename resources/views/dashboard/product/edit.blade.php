@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" id="myForm" method="POST"
                    action="{{ route('dashboard.master.product.update', $product->id) }}" enctype="multipart/form-data">
                   
                    @csrf
                    @method('PUT')
                    <div class="form-group col-md-6">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $product->name }}" >
                         <div>
                                   @if ($errors->has('name'))
                                        @foreach ($errors->get('name') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                    </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Harga Normal</label>
                        <div class="input-group mb-1 mr-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" name="price"  value="{{ $product->price }}"
                                class="form-control rupiah" placeholder="Harga">
                        </div>
                         <div>
                                   @if ($errors->has('price'))
                                        @foreach ($errors->get('price') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                                    </div>
                        <small>
                            <input id="discount" name="discount" value="1" type="checkbox" {{ $product->is_discount == 0 ? '' : 'checked' }}>
                            <label for="discount" class="text-muted font-weight-normal">
                                Aktifkan Harga Diskon
                            </label>
                        </small>
                    </div>
                    <div class="form-group col-md-3 div_diskon {{ $product->is_discount == 0 ? 'd-none' : 'd-block' }}">
                        <label>Harga Diskon</label>
                        <div class="input-group mr-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" name="price_striked" value="{{ $product->price_striked }}"
                                class="form-control rupiah" placeholder="Harga">
                        </div>
                        <small class="text-muted">
                            Jika diisi, maka harga normal akan dicoret
                        </small>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Deskripsi Produk</label>
                        <textarea name="description" id="description" class="form-control" cols="30"
                            rows="4">{{ $product->description }}</textarea>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Berat Produk</label>
                        <div class="input-group mb-3 mr-3">
                            <input type="number" name="weight" min="1" max="10000" class="form-control" id="weight" placeholder="Satuan gram" value="{{ $product->weight }}">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Gram</span>
                            </div>
                            <small class="text-muted">Barang akan tetap terhitung 1KG dalam payment gateway</small>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Kategori Produk</label>
                        <select name="product_category_id" id="product_category_id" class="form-control">
                            <option value="">-</option>
                            @foreach ($product_categories as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $product->product_category_id ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Gambar Produk</label><br>
                        <img src="{{ MyHelper::get_uploaded_file_url($product->thumbnail) }}" alt="picture" width="150" class="mb-1">
                        <input type="file" class="form-control" name="thumbnail">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Status</label>
                        <div class="checkbox checkbox-success checkbox-circle">
                            <input id="visible" type="checkbox" {{ $product->visible == 1 ? 'checked' : '' }} value="1"
                                name="visible">
                            <label for="visible">
                                Visible
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-md-12 text-right">
                     <input  class="btn btn-primary" type="reset" value="reset">
                        <button type="submit" class="btn btn-success formSubmitter">Simpan</button>
                    </div>
                </form>
            </div>
        </div><!-- end col -->
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#discount').change(function() {
                if ($(this).is(':checked')) {
                    $('.div_diskon').removeClass('d-none').addClass('d-block');
                } else {
                    $('.div_diskon').removeClass('d-block').addClass('d-none');
                };
            });

        })
    </script>
@endpush
