@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <form class="row myForm" method="POST" action="{{ route('dashboard.master.product_category.store') }}"
                    enctype="multipart/form-data">
                   
                    @csrf
                    <div class="form-group col-md-8">
                        <label>Nama Kategori Produk</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" class="form-control-front">
                          @if ($errors->has('name'))
                                        @foreach ($errors->get('name') as $msg)
                                            <small class="text-danger">{{ $msg }}</small>
                                        @endforeach
                                    @endif
                        <small>slug kategori akan menjadi: <code id="slugPreview"></code></small>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Thumbnail</label>
                        <input type="file" class="form-control" name="thumbnail">
                        <small class="text-muted">Disarankan ukuran: 1360 x 760</small>
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
            $('#name').on('keyup', function() {
                $('#slugPreview').html('{{ url('') }}/<b><u>' + string_to_slug($(this).val()) + '</u></b>');
            })
        })
    </script>
@endpush
