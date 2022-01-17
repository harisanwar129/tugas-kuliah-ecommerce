@extends('layouts.front')

@push('css')
    <style>
        @media print {
            body * {
                visibility: hidden !important;
            }

            #section-to-print,
            #section-to-print * {
                visibility: visible !important;
            }

            #section-to-print {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
            }
        }

    </style>
@endpush

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
                                        <a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i>
                                            Dashboard</a>
                                        <a href="{{ route('front.pesanan_saya') }}" class="active"><i
                                                class="fa fa-cart-arrow-down"></i>
                                                {{ __('messages.my_orders') }}</a>
                                        <a href="{{ route('front.my_profile') }}"><i class="fa fa-user"></i> {{ __('messages.my_profile') }}</a>
                                        <form action="{{ route('logout') }}" method="POST"
                                            style="display:flex !important; justify-content:flex-end">
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
                                                <div class="panel-body" id="section-to-print">
                                                    <div class="clearfix">
                                                        <div class="float-sm-left">
                                                            <img src="{{ config('setting.logo_rect_url') }}" alt="Logo Aplikasi"
                                                                width="200">
                                                            {{-- <h4 class="text-uppercase mt-0">{{ config('app.name') }}</h4> --}}
                                                        </div>
                                                        <div class="float-sm-right mt-4 mt-sm-0">
                                                            <h5>Invoice # {{ $transaction->trx_id ?? '' }}<br>
                                                                <small>{{ $transaction->sid }}</small>
                                                                <br>

                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="float-sm-left mt-4">
                                                                <address>
                                                                    <strong>{{ $transaction->user->name }}.</strong><br>
                                                                    {{ $transaction->user->address }}<br>
                                                                    {{ $transaction->user->email }}<br>
                                                                    <abbr title="Nomor Telepon">Telp:</abbr>
                                                                    {{ $transaction->user->phone }}
                                                                </address>
                                                            </div>
                                                            <div class="mt-4 text-sm-right">
                                                                <p><strong>{{ __('messages.label_date') }}: </strong>
                                                                    {{ \Carbon\Carbon::parse($transaction->created_at)->locale(Lang::locale())->isoFormat('dddd, D MMMM Y') }}
                                                                </p>
                                                                <p><strong>{{ __('messages.label_time') }}: </strong>
                                                                    {{ \Carbon\Carbon::parse($transaction->created_at)->locale(Lang::locale())->isoFormat('H:ss a') }}
                                                                </p>
                                                                <p><strong>Status: </strong> {!! $transaction->get_status_label() !!}
                                                                </p>
                                                                <p><strong>Trx ID: </strong> #{{ $transaction->trx_id }}</p>
                                                                <p><strong>Pengiriman: </strong> Dari {{ $setting->city->title . ' Ke ' . $transaction->city->title }}</p>
                                                            </div>
                                                        </div><!-- end col -->
                                                    </div>
                                                    <!-- end row -->

                                                    <div class="row mt-4">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-nowrap">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th width="150">{{ __('messages.label_product') }}</th>
                                                                            <th>{{ __('messages.label_desc') }}</th>
                                                                            <th>{{ __('messages.label_qty') }}</th>
                                                                            <th>{{ __('messages.label_unit_cost') }}</th>
                                                                            <th>Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @php
                                                                            $grand_total = 0;
                                                                            $total_weight = 0;
                                                                        @endphp
                                                                        @foreach ($transaction->transaction_items as $item)
                                                                            <tr>
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>
                                                                                    <a href="{{ route('product.show', $item->product->slug) }}">
                                                                                        {{ Str::limit($item->product->name, 20) }}
                                                                                    </a>
                                                                                </td>
                                                                                <td>{{ Str::limit($item->product->description, 40) }}</td>
                                                                                <td>{{ $item->quantity }}</td>
                                                                                <td>Rp
                                                                                    {{ MyHelper::rupiah($item->price) }}
                                                                                </td>
                                                                                <td>Rp
                                                                                    {{ MyHelper::rupiah($item->subtotal) }}
                                                                                </td>
                                                                            </tr>
                                                                            @php
                                                                                $grand_total += $item->subtotal;
                                                                                $total_weight += $item->product->weight * $item->quantity;
                                                                            @endphp
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-2">

                                                        </div>
                                                        <div class="col-sm-10">
                                                            <div class="text-right mt-4">
                                                                @if ($transaction->shipping_courier_code != NULL)
                                                                <p><b>Berat Produk Total:</b> {{ MyHelper::rupiah($total_weight) }} Gram</p>
                                                                <p><b>Subtotal:</b> Rp {{ MyHelper::rupiah($grand_total) }}</p>
                                                                <p><b>Ongkir ({{ Str::upper($transaction->shipping_courier_code) . ' | ' . $transaction->shipping_service_name }}):</b> Rp {{ MyHelper::rupiah($transaction->shipping_service_price) }}</p>
                                                                @else
                                                                <p><b>Ongkir: 0</b></p>
                                                                @endif
                                                                <h3>Rp {{ MyHelper::rupiah($grand_total + $transaction->shipping_service_price) }}</h3>
                                                                <hr>
                                                                @if ($transaction->payment_url && $transaction->status != 'batal')
                                                                    <a class="lead btn btn-sqr mb-2" target="_blank"
                                                                        href="{{ $transaction->payment_url }}">Link
                                                                        {{ __('messages.label_payment') }} <i class="fa fa-external-link"></i></a>
                                                                    <br>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="d-print-none">

                                                        @if ($transaction->status == 'berhasil' && $transaction->state == 'dikirim')
                                                            <form action="{{ route('dashboard.transaction.terima_pesanan', $transaction->id) }}" method="POST" class="mt-2 mb-2">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-success waves-effect waves-light buttonTerimaPesanan">Pesanan Diterima <i class="fa fa-bus"></i></button>
                                                            </form>
                                                        @endif
                                                        <h6>Status Pengiriman: {!! $transaction->get_status_pengiriman() !!}</h6>

                                                        <div class="float-right">
                                                            <div class="d-flex align-items-end">

                                                                @if ($transaction->status == 'PENDING')
                                                                    <form action="{{ route('front.cancel_pesanan', $transaction->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2 buttonCancel">
                                                                            <i class="fa fa-close"></i> {{ __('messages.label_cancel') }} {{ __('messages.label_orders') }}
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                                <a target="_blank" href="{{ route('front.detail_pesanan_saya.print', $transaction->id) }}"
                                                                    class="btn btn-dark waves-effect waves-light"><i
                                                                        class="fa fa-print"></i> {{ __('messages.label_print') }}</a>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
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

@push('js')
    <script>
        $(document).on('click', '.buttonCancel', function(e) {
            e.preventDefault();
            let form = $(this).parents('form');

            swal({
                title: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                text: "Pesanan yang dibatalkan tidak akan mendapatkan produk",
                icon: 'warning',
                buttons: {
                    confirm: {
                        text: 'Ya, Batalkan!',
                        className: 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        className: 'btn btn-danger'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    form.submit()
                    swal({
                        title: 'Mohon tunggu proses!',
                        text: 'mohon tunggu.',
                        icon: 'info',
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        }
                    });
                } else {
                    swal.close();
                }
            });
        });
    </script>
@endpush
