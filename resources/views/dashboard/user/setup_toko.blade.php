@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div class="text-center">
                    <h3 class="mb-4">{{ $title ?? config('app.name') }}</h3>
                    @if (Auth::user()->shop_logo != '' || Auth::user()->shop_logo != NULL)
                    <img src="{{ MyHelper::get_uploaded_file_url($user->shop_logo) }}" alt="picture" width="150" class="mb-3">
                    @endif
                    <p class="text-muted">
                        Mohon lengkapi identitas toko anda dengan data sebenar-benarnya sehingga toko anda dapat diverifikasi valid dan produk anda muncul di beranda aplikasi.
                    </p>
                </div>

                <form class="row myForm" method="POST" action="{{ route('dashboard.master.user.update_toko') }}"
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
                    <div class="form-group col-md-8">
                        <label>Nama Toko Anda</label>
                        <input type="text" class="form-control" name="shop_name" id="shop_name" value="{{ $user->shop_name }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Logo Toko</label>
                        <input type="file" class="form-control" name="shop_logo">
                        <small class="text-muted">Support: PNG,JPG</small>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Deskripsi Toko Anda</label>
                        <textarea name="shop_desc" id="shop_desc" cols="30" rows="4" class="form-control">{{ $user->shop_desc }}</textarea>
                        <small class="text-muted">Deskripsikan toko anda disini</small>
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
                $('#slugPreview').html('{{ url('') }}/<b><u>' + string_to_slug($(this).val()) + '</u></b>');
            })
        })
    </script>
@endpush
