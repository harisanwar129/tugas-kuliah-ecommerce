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
                                        <a href="{{ route('dashboard.index') }}"><i
                                                class="fa fa-dashboard"></i>
                                            Dashboard</a>
                                        <a href="{{ route('front.pesanan_saya') }}" class="active"><i class="fa fa-cart-arrow-down"></i>
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
                                        <div class="tab-pane fade show active" id="orders" role="tabpanel">
                                            <div class="myaccount-content">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5>{{ $title ?? config('app.name') }}</h5>
                                                    <form action="" method="GET">
                                                        <div class="d-flex justify-content-between">
                                                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Cari by no invoice,status" value="{{ $keyword }}">
                                                            <button style="submit" class="btn btn-sqr formSubmitter ml-1">Cari</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="myaccount-table table-responsive text-center">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>{{ __('messages.label_date') }}</th>
                                                                <th>No Invoice</th>
                                                                <th>Status Pembayaran</th>
                                                                <th>Total</th>
                                                                <th>{{ __('messages.label_action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($orders as $key=> $item)
                                                                <tr>
                                                                    <td>{{ $orders->firstItem()+$key }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->locale('id_ID')->isoFormat('D MMMM Y | H:mm') }}</td>
                                                                    <td>{{ $item->sid }}</td>
                                                                    <td>{!! $item->get_status_label() !!}</td>
                                                                    <td>Rp {{ MyHelper::rupiah($item->subtotal + $item->shipping_service_price) }}</td>
                                                                    <td><a href="{{ route('front.detail_pesanan_saya', $item->id) }}" class="btn btn-sqr">Detail</a>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6">Belum ada Transaksi</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>

                                        
                                                    <div class="pull-right">
                                                    {{ $orders->links() }}
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
