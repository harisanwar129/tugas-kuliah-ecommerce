@extends('layouts.front')



@section('content')
    <!-- entry-header-area-start -->
    <div class="entry-header-area mt-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title">
                        <h2>{{ $title ?? config('app.name') }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->
    <!-- my account wrapper start -->
    <div class="my-account-wrapper mb-70">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper">
                            <!-- My Account Tab Menu Start -->
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="myaccount-tab-menu nav" role="tablist">
                                        <a href="{{ route('dashboard.index') }}" class="active"><i
                                                class="fa fa-dashboard"></i>
                                            Dashboard</a>
                                        <a href="{{ route('front.pesanan_saya') }}"><i class="fa fa-cart-arrow-down"></i>
                                            {{ __('messages.my_orders') }}</a>
                                        <a href="{{ route('front.my_profile') }}"><i class="fa fa-user"></i> {{ __('messages.my_profile') }}</a>
                                        <form action="{{ route('logout') }}" method="POST" style="display:flex !important; justify-content:flex-end">
                                            @csrf
                                            <button type="submit" class="buttonLogout"><i class="fa fa-sign-out"></i> Logout</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- My Account Tab Menu End -->

                                <!-- My Account Tab Content Start -->
                                <div class="col-lg-9 col-md-8">
                                    <div class="tab-content" id="myaccountContent">
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>Dashboard</h5>
                                                <div class="welcome">
                                                    <p>Hai, <strong>{{ $user->name }}</strong></p>
                                                </div>
                                                <p class="mb-0">{{ __('messages.welcome_buyer') }}</p>

                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="card card-body">
                                                            <h5 class="card-title text-warning">Transaksi Pending</h5>
                                                            <h4 class="card-text">{{ $pending }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card card-body">
                                                            <h5 class="card-title text-success">Transaksi Berhasil</h5>
                                                            <h4 class="card-text">{{ $berhasil }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="card card-body">
                                                            <h5 class="card-title text-danger">Transaksi Batal</h5>
                                                            <h4 class="card-text">{{ $batal }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Single Tab Content End -->
                                    </div>
                                </div> <!-- My Account Tab Content End -->
                            </div>
                        </div> <!-- My Account Page End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- my account wrapper end -->
@endsection
