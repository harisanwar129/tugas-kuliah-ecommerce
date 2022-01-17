@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
                <div>
                    <h4 class="header-title">Ingin membuat akun Seller dan Member?</h4>
                    <p class="sub-header">
                        Form dibawah ini digunakan untuk membuat akun Administrator, jika untuk Seller dan Member silahkan akses halaman <a href="{{ route('register') }}" target="_blank">Registrasi</a> <code>(Logout dahulu)</code>.
                    </p>
                </div>

                <form class="row myForm" method="POST" action="{{ route('dashboard.master.user.store') }}"
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
                    <div class="form-group col-md-4">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Nomor HP</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Avatar</label>
                        <input type="file" class="form-control" name="avatar">
                        <small class="text-muted">Support: PNG,JPG</small>
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

@endpush
