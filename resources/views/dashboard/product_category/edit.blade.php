@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" method="POST" action="{{ route('dashboard.master.product_category.update', $productCategory->id) }}"
                    enctype="multipart/form-data">
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
                    @method('PUT')
                    <input type="hidden" name="slug" id="slug" value="{{ $productCategory->slug }}">
                    <div class="form-group col-md-8">
                        <label>Nama Kategori Produk</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $productCategory->name }}">
                        <small>slug kategori akan menjadi: <code id="slugPreview">{!! url('') . '/<b><u>'. $productCategory->slug .'</u></b>' !!}</code></small>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Thumbnail</label><br>
                        <img src="{{ MyHelper::get_uploaded_file_url($productCategory->thumbnail) }}" alt="picture" width="150" class="mb-1">
                        <input type="file" class="form-control" name="thumbnail">
                        <small class="text-muted">Disarankan ukuran: 1360 x 760</small>
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
            $('#name').on('keyup', function() {
                $('#slug').val(string_to_slug($(this).val()));
                $('#slugPreview').html('{{ url('') }}/<b><u>' + string_to_slug($(this).val()) + '</u></b>');
            })
        })
    </script>
@endpush
