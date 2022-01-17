@include('layouts.header_css')
<body onload="window.print()">
    <div class="container mt-3 pb-5">
        <div class="col-md-12">
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
                                <abbr title="Telp">Telp:</abbr>
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
                                                    {{ $item->product->name }}
                                                </a>
                                            </td>
                                            <td>{{ Str::limit($item->product->description, 40) }}</td>
                                            </td>
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
                            <p><b>Berat Produk Total:</b> {{ MyHelper::rupiah($total_weight) }} Gram</p>
                            <p><b>Subtotal:</b> Rp {{ MyHelper::rupiah($grand_total) }}</p>
                            @if ($transaction->shipping_courier_code != NULL)
                            <p><b>Ongkir ({{ Str::upper($transaction->shipping_courier_code) . ' | ' . $transaction->shipping_service_name }}):</b> Rp {{ MyHelper::rupiah($transaction->shipping_service_price) }}</p>
                            @else
                            <p><b>Ongkir: 0</b></p>
                            @endif
                            <h3>Rp {{ MyHelper::rupiah($grand_total + $transaction->shipping_service_price) }}</h3>
                            <hr>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="d-print-none">
                    <div class="float-right">
                        <div class="d-flex align-items-end">
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
</body>
